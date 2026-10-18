<?php

namespace Modules\SST\Http\Controllers\Aprendiz\TiposEventos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Modules\SST\Entities\TipoEvento;
use Modules\SST\Entities\LugarFormacion;
use Modules\SST\Entities\RespuestaEvento;

class RiesgosController extends Controller
{
    /**
     * Muestra el catálogo y reportes de Riesgos para el Aprendiz SST
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login', ['redirect' => route('SST.aprendiz.tipos_eventos.riesgos.index')]);
        }

        /** @var User $user */
        $user = Auth::user();

        if ($user->roles->contains('slug', 'sst.aprendiz') || $user->roles->contains('slug', 'sst.funcionario') || $user->roles->contains('slug', 'sst.admin') || $user->hasSuperAdmin()) {
            
            // Consultar reportes de riesgos registrados
            $riesgos = RespuestaEvento::where(function($q) {
                    $q->where('nombre', 'LIKE', '%Riesgo%')
                      ->orWhere('nombre', 'LIKE', '%riesgo%');
                })
                ->orderBy('id_respuesta', 'desc')
                ->get();

            $lugares = LugarFormacion::where('estado', 'activo')
                ->orderBy('nombre', 'asc')
                ->get();

            $catalogoRiesgos = TipoEvento::where(function($q) {
                    $q->where('tipo_categoria', 'riesgo')
                      ->orWhere('tipo_categoria', 'Riesgo')
                      ->orWhereRaw('LOWER(tipo_categoria) = ?', ['riesgo']);
                })
                ->where('estado', 'activo')
                ->orderBy('nombre', 'asc')
                ->get();

            return view('sst::aprendiz.tipos_eventos.Riesgos', compact('riesgos', 'lugares', 'catalogoRiesgos'));
        }

        abort(403, 'Acceso denegado: No tienes permisos para consultar este recurso en el módulo SST.');
    }

    /**
     * Registra un nuevo reporte de riesgo y guarda hasta 3 evidencias fotográficas
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo_riesgo' => 'nullable|string|max:150',
            'fecha_hora' => 'nullable',
            'lugar_formacion' => 'nullable|string|max:150',
            'gravedad' => 'nullable|string|max:50',
            'persona_afectada' => 'nullable|string|max:255',
            'personas_involucradas' => 'nullable|string|max:255',
            'instructor_responsable' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'evidencias' => 'nullable|array|max:3',
            'evidencias.*' => 'nullable|file|max:10240',
            'evidencia' => 'nullable|file|max:10240',
        ]);

        $tipoRiesgo = !empty($validated['tipo_riesgo']) ? $validated['tipo_riesgo'] : 'Riesgo Laboral';
        $personaAfectada = !empty($validated['persona_afectada']) ? $validated['persona_afectada'] : (!empty($validated['personas_involucradas']) ? $validated['personas_involucradas'] : 'Personal Afectado');
        $personas = !empty($validated['personas_involucradas']) ? $validated['personas_involucradas'] : '';
        $lugar = !empty($validated['lugar_formacion']) ? $validated['lugar_formacion'] : 'Instalaciones del Centro';
        $gravedad = !empty($validated['gravedad']) ? $validated['gravedad'] : 'Medio';
        $fecha = !empty($validated['fecha_hora']) ? $validated['fecha_hora'] : now()->format('Y-m-d H:i');
        $desc = !empty($validated['descripcion']) ? $validated['descripcion'] : 'Sin observaciones adicionales.';

        // Manejo de hasta 3 Evidencias Fotográficas
        $evidenciasUrls = [];
        $destination = public_path('uploads/evidencias_sst');
        if (!file_exists($destination)) {
            mkdir($destination, 0777, true);
        }

        if ($request->hasFile('evidencias')) {
            $files = array_slice($request->file('evidencias'), 0, 3);
            foreach ($files as $file) {
                if ($file && $file->isValid()) {
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move($destination, $filename);
                    $evidenciasUrls[] = asset('uploads/evidencias_sst/' . $filename);
                }
            }
        } elseif ($request->hasFile('evidencia')) {
            $file = $request->file('evidencia');
            if ($file && $file->isValid()) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($destination, $filename);
                $evidenciasUrls[] = asset('uploads/evidencias_sst/' . $filename);
            }
        }

        $evidenciaPrincipal = !empty($evidenciasUrls) ? $evidenciasUrls[0] : null;

        $reportData = [
            'tipo_riesgo' => $tipoRiesgo,
            'fecha_hora' => $fecha,
            'lugar_formacion' => $lugar,
            'gravedad' => $gravedad,
            'persona_afectada' => $personaAfectada,
            'personas_involucradas' => $personas,
            'instructor_responsable' => $validated['instructor_responsable'] ?? '',
            'descripcion' => $desc,
            'evidencia' => $evidenciaPrincipal,
            'evidencias' => $evidenciasUrls,
        ];

        RespuestaEvento::create([
            'nombre' => "Riesgo: {$tipoRiesgo} ({$personaAfectada})",
            'descripcion' => json_encode($reportData, JSON_UNESCAPED_UNICODE),
            'estado' => 'activo'
        ]);

        return redirect()->route('SST.aprendiz.tipos_eventos.riesgos.index')
            ->with('success', '¡Reporte de riesgo registrado con éxito! Se guardaron ' . count($evidenciasUrls) . ' imagen(es) de evidencia.');
    }

    /**
     * Actualiza un reporte de riesgo existente (admite hasta 3 imágenes)
     */
    public function update(Request $request, $id)
    {
        $evento = RespuestaEvento::findOrFail($id);

        $validated = $request->validate([
            'tipo_riesgo' => 'nullable|string|max:150',
            'fecha_hora' => 'nullable',
            'lugar_formacion' => 'nullable|string|max:150',
            'gravedad' => 'nullable|string|max:50',
            'persona_afectada' => 'nullable|string|max:255',
            'personas_involucradas' => 'nullable|string|max:255',
            'instructor_responsable' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'evidencias' => 'nullable|array|max:3',
            'evidencias.*' => 'nullable|file|max:10240',
            'evidencia' => 'nullable|file|max:10240',
        ]);

        $existingData = json_decode($evento->descripcion, true);
        $existingEvidencias = [];
        if (is_array($existingData)) {
            if (!empty($existingData['evidencias']) && is_array($existingData['evidencias'])) {
                $existingEvidencias = $existingData['evidencias'];
            } elseif (!empty($existingData['evidencia'])) {
                $existingEvidencias = [$existingData['evidencia']];
            }
        }

        $fotosConservadas = $request->input('fotos_conservadas', null);
        
        if ($fotosConservadas !== null) {
            $evidenciasUrls = is_array($fotosConservadas) ? $fotosConservadas : [];
        } else {
            $evidenciasUrls = $existingEvidencias;
        }

        $destination = public_path('uploads/evidencias_sst');
        if (!file_exists($destination)) {
            mkdir($destination, 0777, true);
        }

        if ($request->hasFile('evidencias')) {
            $newFiles = $request->file('evidencias');
            $cuposDisponibles = max(0, 3 - count($evidenciasUrls));
            $filesToAdd = array_slice($newFiles, 0, $cuposDisponibles);

            foreach ($filesToAdd as $file) {
                if ($file && $file->isValid()) {
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move($destination, $filename);
                    $evidenciasUrls[] = asset('uploads/evidencias_sst/' . $filename);
                }
            }
        } elseif ($request->hasFile('evidencia')) {
            $file = $request->file('evidencia');
            if ($file && $file->isValid() && count($evidenciasUrls) < 3) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($destination, $filename);
                $evidenciasUrls[] = asset('uploads/evidencias_sst/' . $filename);
            }
        }

        $evidenciasUrls = array_slice($evidenciasUrls, 0, 3);

        $tipoRiesgo = !empty($validated['tipo_riesgo']) ? $validated['tipo_riesgo'] : 'Riesgo Laboral';
        $personaAfectada = !empty($validated['persona_afectada']) ? $validated['persona_afectada'] : (!empty($validated['personas_involucradas']) ? $validated['personas_involucradas'] : 'Personal Afectado');
        $personas = !empty($validated['personas_involucradas']) ? $validated['personas_involucradas'] : '';
        $lugar = !empty($validated['lugar_formacion']) ? $validated['lugar_formacion'] : 'Instalaciones del Centro';
        $gravedad = !empty($validated['gravedad']) ? $validated['gravedad'] : 'Medio';
        $fecha = !empty($validated['fecha_hora']) ? $validated['fecha_hora'] : now()->format('Y-m-d H:i');
        $desc = !empty($validated['descripcion']) ? $validated['descripcion'] : 'Sin observaciones adicionales.';

        $evidenciaPrincipal = !empty($evidenciasUrls) ? $evidenciasUrls[0] : null;

        $reportData = [
            'tipo_riesgo' => $tipoRiesgo,
            'fecha_hora' => $fecha,
            'lugar_formacion' => $lugar,
            'gravedad' => $gravedad,
            'persona_afectada' => $personaAfectada,
            'personas_involucradas' => $personas,
            'instructor_responsable' => $validated['instructor_responsable'] ?? '',
            'descripcion' => $desc,
            'evidencia' => $evidenciaPrincipal,
            'evidencias' => $evidenciasUrls,
        ];

        $evento->update([
            'nombre' => "Riesgo: {$tipoRiesgo} ({$personaAfectada})",
            'descripcion' => json_encode($reportData, JSON_UNESCAPED_UNICODE),
        ]);

        return redirect()->route('SST.aprendiz.tipos_eventos.riesgos.index')
            ->with('success', '¡Reporte de riesgo actualizado correctamente!');
    }

    /**
     * Elimina un reporte de riesgo y sus evidencias fotográficas
     */
    public function destroy($id)
    {
        $evento = RespuestaEvento::findOrFail($id);

        $data = json_decode($evento->descripcion, true);
        if (is_array($data)) {
            $filesToDelete = [];
            if (!empty($data['evidencias']) && is_array($data['evidencias'])) {
                $filesToDelete = $data['evidencias'];
            } elseif (!empty($data['evidencia'])) {
                $filesToDelete = [$data['evidencia']];
            }

            foreach ($filesToDelete as $url) {
                $path = str_replace(asset(''), public_path(''), $url);
                if (file_exists($path)) {
                    @unlink($path);
                }
            }
        }

        $evento->delete();

        return redirect()->route('SST.aprendiz.tipos_eventos.riesgos.index')
            ->with('success', '¡Reporte de riesgo eliminado correctamente!');
    }
}

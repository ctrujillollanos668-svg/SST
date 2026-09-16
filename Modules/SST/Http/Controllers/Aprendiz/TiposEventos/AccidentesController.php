<?php

namespace Modules\SST\Http\Controllers\Aprendiz\TiposEventos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Modules\SST\Entities\TipoEvento;
use Modules\SST\Entities\LugarFormacion;
use Modules\SST\Entities\RespuestaEvento;

class AccidentesController extends Controller
{
    /**
     * Muestra el catálogo de Accidentes para el Aprendiz SST
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login', ['redirect' => route('SST.aprendiz.tipos_eventos.accidentes.index')]);
        }

        /** @var User $user */
        $user = Auth::user();

        if ($user->roles->contains('slug', 'sst.aprendiz') || $user->roles->contains('slug', 'sst.funcionario') || $user->roles->contains('slug', 'sst.admin') || $user->hasSuperAdmin()) {
            
            // Consultar todos los reportes de accidentes registrados
            $accidentes = RespuestaEvento::where(function($q) {
                    $q->where('nombre', 'LIKE', '%Accidente%')
                      ->orWhere('nombre', 'LIKE', '%accidente%');
                })
                ->orderBy('id_respuesta', 'desc')
                ->get();

            if ($accidentes->isEmpty()) {
                $accidentes = RespuestaEvento::orderBy('id_respuesta', 'desc')->get();
            }

            $lugares = LugarFormacion::where('estado', 'activo')
                ->orderBy('nombre', 'asc')
                ->get();

            $catalogoAccidentes = TipoEvento::where('tipo_categoria', 'accidente')
                ->where('estado', 'activo')
                ->orderBy('nombre', 'asc')
                ->get();

            return view('sst::aprendiz.tipos_eventos.Accidentes', compact('accidentes', 'lugares', 'catalogoAccidentes'));
        }

        abort(403, 'Acceso denegado: No tienes permisos para consultar este recurso en el módulo SST.');
    }

    /**
     * Registra un nuevo reporte de accidente y guarda hasta 3 evidencias fotográficas
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo_accidente' => 'nullable|string|max:150',
            'fecha_hora' => 'nullable',
            'lugar_formacion' => 'nullable|string|max:150',
            'gravedad' => 'nullable|string|max:50',
            'personas_involucradas' => 'nullable|string|max:255',
            'instructor_responsable' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'evidencias' => 'nullable|array|max:3',
            'evidencias.*' => 'nullable|file|max:10240',
            'evidencia' => 'nullable|file|max:10240',
        ]);

        $tipoAccidente = !empty($validated['tipo_accidente']) ? $validated['tipo_accidente'] : 'Accidente de Trabajo';
        $personas = !empty($validated['personas_involucradas']) ? $validated['personas_involucradas'] : 'Aprendiz';
        $lugar = !empty($validated['lugar_formacion']) ? $validated['lugar_formacion'] : 'Instalaciones del Centro';
        $gravedad = !empty($validated['gravedad']) ? $validated['gravedad'] : 'Leve';
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

        // Estructura completa de datos para mostrar en las tablas
        $reportData = [
            'tipo_accidente' => $tipoAccidente,
            'fecha_hora' => $fecha,
            'lugar_formacion' => $lugar,
            'gravedad' => $gravedad,
            'personas_involucradas' => $personas,
            'instructor_responsable' => $validated['instructor_responsable'] ?? '',
            'descripcion' => $desc,
            'evidencia' => $evidenciaPrincipal,
            'evidencias' => $evidenciasUrls,
        ];

        RespuestaEvento::create([
            'nombre' => "Accidente: {$tipoAccidente} ({$personas})",
            'descripcion' => json_encode($reportData, JSON_UNESCAPED_UNICODE),
            'estado' => 'activo'
        ]);

        return redirect()->route('SST.aprendiz.tipos_eventos.accidentes.index')
            ->with('success', '¡Reporte de accidente registrado con éxito! Se guardaron ' . count($evidenciasUrls) . ' imagen(es) de evidencia.');
    }

    /**
     * Actualiza un reporte de accidente existente (admite hasta 3 imágenes)
     */
    public function update(Request $request, $id)
    {
        $evento = RespuestaEvento::findOrFail($id);

        $validated = $request->validate([
            'tipo_accidente' => 'nullable|string|max:150',
            'fecha_hora' => 'nullable',
            'lugar_formacion' => 'nullable|string|max:150',
            'gravedad' => 'nullable|string|max:50',
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
        
        // Si el formulario envió la lista explícita de fotos a conservar
        if ($fotosConservadas !== null) {
            $evidenciasUrls = is_array($fotosConservadas) ? $fotosConservadas : [];
        } else {
            $evidenciasUrls = $existingEvidencias;
        }

        $destination = public_path('uploads/evidencias_sst');
        if (!file_exists($destination)) {
            mkdir($destination, 0777, true);
        }

        // Subir y añadir nuevas fotos hasta completar el cupo de 3
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

        $tipoAccidente = !empty($validated['tipo_accidente']) ? $validated['tipo_accidente'] : 'Accidente de Trabajo';
        $personas = !empty($validated['personas_involucradas']) ? $validated['personas_involucradas'] : 'Aprendiz';
        $lugar = !empty($validated['lugar_formacion']) ? $validated['lugar_formacion'] : 'Instalaciones del Centro';
        $gravedad = !empty($validated['gravedad']) ? $validated['gravedad'] : 'Leve';
        $fecha = !empty($validated['fecha_hora']) ? $validated['fecha_hora'] : now()->format('Y-m-d H:i');
        $desc = !empty($validated['descripcion']) ? $validated['descripcion'] : 'Sin observaciones adicionales.';

        $evidenciaPrincipal = !empty($evidenciasUrls) ? $evidenciasUrls[0] : null;

        $reportData = [
            'tipo_accidente' => $tipoAccidente,
            'fecha_hora' => $fecha,
            'lugar_formacion' => $lugar,
            'gravedad' => $gravedad,
            'personas_involucradas' => $personas,
            'instructor_responsable' => $validated['instructor_responsable'] ?? '',
            'descripcion' => $desc,
            'evidencia' => $evidenciaPrincipal,
            'evidencias' => $evidenciasUrls,
        ];

        $evento->update([
            'nombre' => "Accidente: {$tipoAccidente} ({$personas})",
            'descripcion' => json_encode($reportData, JSON_UNESCAPED_UNICODE),
        ]);

        return redirect()->route('SST.aprendiz.tipos_eventos.accidentes.index')
            ->with('success', '¡Reporte de accidente actualizado correctamente!');
    }

    /**
     * Elimina un reporte de accidente y sus evidencias fotográficas
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

        return redirect()->route('SST.aprendiz.tipos_eventos.accidentes.index')
            ->with('success', '¡Reporte de accidente eliminado correctamente!');
    }
}

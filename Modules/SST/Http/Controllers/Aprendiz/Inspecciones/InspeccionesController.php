<?php

namespace Modules\SST\Http\Controllers\Aprendiz\Inspecciones;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SST\Entities\InspeccionSST;
use Modules\SST\Entities\LugarFormacion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InspeccionesController extends Controller
{
    /**
     * Muestra el formulario y el listado de inspecciones SST del aprendiz.
     */
    public function realizar()
    {
        // Consultar únicamente los lugares registrados por el administrador
        $lugares = LugarFormacion::where('estado', 'activo')->orderBy('nombre', 'asc')->get();
        if ($lugares->isEmpty()) {
            $lugares = LugarFormacion::orderBy('nombre', 'asc')->get();
        }

        $inspecciones = InspeccionSST::with(['lugar', 'user'])->orderBy('id_inspeccion', 'desc')->get();

        return view('sst::aprendiz.inspecciones.Realizar_inspeccion', compact('lugares', 'inspecciones'));
    }

    /**
     * Guarda la inspección en la base de datos (tabla inspecciones_sst de Admin).
     */
    public function store(Request $request)
    {
        $request->validate([
            'lugar_id' => 'required|exists:lugares_formacion,id_lugar',
            'responsable' => 'required|string|max:100',
            'lider_unidad' => 'nullable|string|max:100',
            'fecha_inspeccion' => 'nullable|date',
            'fecha_seguimiento' => 'nullable|date',
            'descripcion' => 'nullable|string',
            'fotos.*' => 'nullable|image|max:5120'
        ], [
            'lugar_id.required' => 'Debe seleccionar un lugar de formación registrado.',
            'lugar_id.exists' => 'El lugar de formación seleccionado no es válido.',
            'responsable.required' => 'El nombre del responsable es obligatorio.'
        ]);

        $lugarId = $request->input('lugar_id');
        $lugarObj = LugarFormacion::find($lugarId);

        // 2. Determinar tipo de inspección / reporte
        $tipoInspeccion = $request->input('tipo_inspeccion');
        if (empty($tipoInspeccion)) {
            $checkActos = $request->has('check_actos') || $request->input('check_actos') == '1';
            $checkCond = $request->has('check_condiciones') || $request->input('check_condiciones') == '1';

            if ($checkActos && $checkCond) {
                $tipoInspeccion = 'Acto y Condición Insegura';
            } elseif ($checkActos) {
                $tipoInspeccion = 'Acto Inseguro';
            } else {
                $tipoInspeccion = 'Condición Insegura';
            }
        }

        // 3. Procesar fotografías de evidencia
        $rutasFotos = [];
        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $foto) {
                $path = $foto->store('inspecciones', 'public');
                $rutasFotos[] = asset('storage/' . $path);
            }
        }

        // Si se enviaron imágenes como base64
        if ($request->has('fotos_base64')) {
            $base64List = json_decode($request->input('fotos_base64'), true);
            if (is_array($base64List)) {
                foreach ($base64List as $b64) {
                    if (str_starts_with($b64, 'data:image')) {
                        @list($type, $data) = explode(';', $b64);
                        @list(, $data) = explode(',', $data);
                        if (!empty($data)) {
                            $decoded = base64_decode($data);
                            $filename = 'inspecciones/insp_' . uniqid() . '.jpg';
                            Storage::disk('public')->put($filename, $decoded);
                            $rutasFotos[] = asset('storage/' . $filename);
                        }
                    } elseif (filter_var($b64, FILTER_VALIDATE_URL)) {
                        $rutasFotos[] = $b64;
                    }
                }
            }
        }

        // 4. Estado y fecha de seguimiento
        $isSeguimiento = $request->has('seguimiento') || $request->input('estado') === 'seguimiento' || $request->input('estado') === 'pendiente';
        $estado = $isSeguimiento ? 'pendiente' : 'completada';
        $fechaSeguimiento = $isSeguimiento ? $request->input('fecha_seguimiento') : null;

        // 5. Empaquetar observaciones y metadatos estructurados
        $metadata = [
            'responsable' => $request->input('responsable'),
            'lugar_nombre' => $lugarObj ? $lugarObj->nombre : 'Ambiente SENA',
            'lider_unidad' => $request->input('lider_unidad') ?? 'Coordinación SST',
            'fecha_seguimiento' => $fechaSeguimiento,
            'descripcion' => $request->input('descripcion') ?? '',
            'seguimiento' => $isSeguimiento,
            'evidencias' => $rutasFotos
        ];

        // 6. Crear en la tabla real de la base de datos (inspecciones_sst)
        $inspeccion = InspeccionSST::create([
            'lugar_id' => $lugarId,
            'user_id' => Auth::id() ?? 1,
            'tipo_inspeccion' => $tipoInspeccion,
            'fecha_inspeccion' => $request->input('fecha_inspeccion') ?? now()->format('Y-m-d'),
            'hora_inspeccion' => now()->format('H:i:s'),
            'estado' => $estado,
            'observaciones' => json_encode($metadata, JSON_UNESCAPED_UNICODE)
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Inspección registrada exitosamente en la base de datos.',
                'data' => $inspeccion,
                'metadata' => $metadata
            ]);
        }

        return redirect()->route('SST.aprendiz.inspecciones.realizar')
            ->with('success', 'Inspección guardada exitosamente en el sistema.');
    }

    /**
     * Muestra el historial de inspecciones para el aprendiz.
     */
    public function historial()
    {
        $inspecciones = InspeccionSST::with(['lugar', 'user'])->orderBy('fecha_inspeccion', 'desc')->paginate(15);
        return view('sst::aprendiz.inspecciones.Historial', compact('inspecciones'));
    }

    /**
     * Actualiza una inspección en la base de datos.
     */
    public function update(Request $request, $id)
    {
        $inspeccion = InspeccionSST::findOrFail($id);

        $request->validate([
            'lugar_id' => 'required|exists:lugares_formacion,id_lugar',
            'responsable' => 'required|string|max:100',
            'lider_unidad' => 'nullable|string|max:100',
            'fecha_inspeccion' => 'nullable|date',
            'fecha_seguimiento' => 'nullable|date',
            'descripcion' => 'nullable|string',
            'fotos.*' => 'nullable|image|max:5120'
        ], [
            'lugar_id.required' => 'Debe seleccionar un lugar de formación registrado.',
            'lugar_id.exists' => 'El lugar de formación seleccionado no es válido.',
            'responsable.required' => 'El nombre del responsable es obligatorio.'
        ]);

        $lugarId = $request->input('lugar_id');
        $lugarObj = LugarFormacion::find($lugarId);

        // Determinar tipo de inspección
        $tipoInspeccion = $request->input('tipo_inspeccion');
        if (empty($tipoInspeccion)) {
            $checkActos = $request->has('check_actos') || $request->input('check_actos') == '1';
            $checkCond = $request->has('check_condiciones') || $request->input('check_condiciones') == '1';

            if ($checkActos && $checkCond) {
                $tipoInspeccion = 'Acto y Condición Insegura';
            } elseif ($checkActos) {
                $tipoInspeccion = 'Acto Inseguro';
            } else {
                $tipoInspeccion = 'Condición Insegura';
            }
        }

        // Obtener fotos previas
        $metaExistente = json_decode($inspeccion->observaciones, true) ?? [];
        $rutasFotos = $metaExistente['evidencias'] ?? [];

        // Si se subieron nuevas fotos vía archivo
        if ($request->hasFile('fotos')) {
            $rutasNuevas = [];
            foreach ($request->file('fotos') as $foto) {
                $path = $foto->store('inspecciones', 'public');
                $rutasNuevas[] = asset('storage/' . $path);
            }
            if (!empty($rutasNuevas)) {
                $rutasFotos = $rutasNuevas;
            }
        }

        // Si se enviaron fotos vía base64 o URLs
        if ($request->has('fotos_base64')) {
            $base64List = json_decode($request->input('fotos_base64'), true);
            if (is_array($base64List)) {
                $rutasActualizadas = [];
                foreach ($base64List as $b64) {
                    if (str_starts_with($b64, 'data:image')) {
                        @list($type, $data) = explode(';', $b64);
                        @list(, $data) = explode(',', $data);
                        if (!empty($data)) {
                            $decoded = base64_decode($data);
                            $filename = 'inspecciones/insp_' . uniqid() . '.jpg';
                            Storage::disk('public')->put($filename, $decoded);
                            $rutasActualizadas[] = asset('storage/' . $filename);
                        }
                    } elseif (filter_var($b64, FILTER_VALIDATE_URL)) {
                        $rutasActualizadas[] = $b64;
                    }
                }
                $rutasFotos = $rutasActualizadas;
            }
        }

        $isSeguimiento = $request->has('seguimiento') || $request->input('estado') === 'seguimiento' || $request->input('estado') === 'pendiente';
        $estado = $isSeguimiento ? 'pendiente' : 'completada';
        $fechaSeguimiento = $isSeguimiento ? $request->input('fecha_seguimiento') : null;

        $metadata = [
            'responsable' => $request->input('responsable'),
            'lugar_nombre' => $lugarObj ? $lugarObj->nombre : 'Ambiente SENA',
            'lider_unidad' => $request->input('lider_unidad') ?? 'Coordinación SST',
            'fecha_seguimiento' => $fechaSeguimiento,
            'descripcion' => $request->input('descripcion') ?? '',
            'seguimiento' => $isSeguimiento,
            'evidencias' => array_slice($rutasFotos, 0, 3)
        ];

        $inspeccion->update([
            'lugar_id' => $lugarId,
            'tipo_inspeccion' => $tipoInspeccion,
            'fecha_inspeccion' => $request->input('fecha_inspeccion') ?? $inspeccion->fecha_inspeccion,
            'estado' => $estado,
            'observaciones' => json_encode($metadata, JSON_UNESCAPED_UNICODE)
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Inspección actualizada exitosamente.',
                'data' => $inspeccion,
                'metadata' => $metadata
            ]);
        }

        return redirect()->route('SST.aprendiz.inspecciones.realizar')
            ->with('success', 'Inspección actualizada exitosamente en el sistema.');
    }

    /**
     * Elimina una inspección de la base de datos.
     */
    public function destroy($id)
    {
        $inspeccion = InspeccionSST::findOrFail($id);
        $inspeccion->delete();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Inspección eliminada exitosamente.']);
        }

        return redirect()->route('SST.aprendiz.inspecciones.realizar')
            ->with('success', 'Inspección eliminada exitosamente.');
    }
}

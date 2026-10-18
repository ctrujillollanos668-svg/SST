<?php

namespace Modules\SST\Http\Controllers\Admin\Cronograma;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SST\Entities\ActividadSST;
use Modules\SST\Entities\LugarFormacion;
use Modules\SST\Entities\TipoActividad;

class CalendarioController extends Controller
{
    /**
     * Display the calendar and activities schedule.
     */
    public function index(Request $request)
    {
        // 1. Obtener todas las actividades registradas en Actividades SST (Pausas Activas, Charlas, Sensibilización, etc.)
        $actividadesSst = ActividadSST::orderBy('fecha', 'asc')
            ->orderBy('hora_inicio', 'asc')
            ->get();

        // 2. Mapear a una estructura uniforme
        $actividades = $actividadesSst->map(function ($act) {
            return (object) [
                'id_actividad' => $act->id_actividad,
                'source' => 'actividad_sst',
                'nombre' => $act->titulo ?? $act->actividad_a_realizar ?? 'Actividad SST',
                'fecha' => $act->fecha,
                'hora' => $act->hora_inicio ? substr($act->hora_inicio, 0, 5) : '08:00',
                'hora_fin' => $act->hora_fin ? substr($act->hora_fin, 0, 5) : null,
                'tipo_actividad' => $act->tipo_actividad ?? 'Pausa Activa',
                'id_lugar' => null,
                'lugar_nombre' => $act->lugar ?? 'No especificado',
                'responsable' => $act->responsable ?? $act->instructor ?? 'No asignado',
                'estado' => $act->estado ?? 'Programada',
                'descripcion' => $act->descripcion ?? $act->observaciones ?? '',
                'ficha_programa' => $act->ficha_programa ?? null
            ];
        });



        // Ordenar actividades por fecha y hora
        $actividades = $actividades->sortBy([
            ['fecha', 'asc'],
            ['hora', 'asc']
        ])->values();

        $lugares = LugarFormacion::where('estado', 'activo')->orderBy('nombre', 'asc')->get();
        $tiposActividades = TipoActividad::where('estado', 'activo')->orderBy('nombre', 'asc')->get();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $actividades,
                'lugares' => $lugares,
                'tiposActividades' => $tiposActividades
            ]);
        }

        return view('sst::admin.cronograma.Calendario', compact('actividades', 'lugares', 'tiposActividades'));
    }

    /**
     * Store a newly created quick Activity Type.
     */
    public function storeTipoActividad(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100|unique:tipos_actividades_sst,nombre',
            'icono' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50'
        ]);

        $tipo = TipoActividad::create([
            'nombre' => trim($validated['nombre']),
            'icono' => $validated['icono'] ?? 'fa-calendar-check',
            'color' => $validated['color'] ?? 'blue',
            'estado' => 'activo'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tipo de actividad agregado con éxito.',
            'data' => $tipo
        ]);
    }

    /**
     * Delete an Activity Type.
     */
    public function destroyTipoActividad(Request $request, $id)
    {
        $tipo = TipoActividad::findOrFail($id);
        $nombre = $tipo->nombre;
        $tipo->delete();

        return response()->json([
            'success' => true,
            'message' => "Tipo de actividad '{$nombre}' eliminado con éxito.",
            'data' => [
                'id_tipo_actividad' => $id,
                'nombre' => $nombre
            ]
        ]);
    }

    /**
     * Store a newly created calendar activity (crea en ActividadSST).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:150',
            'fecha' => 'required|date',
            'hora' => 'nullable|string',
            'responsable' => 'required|string|max:100',
            'tipo_actividad' => 'nullable|string|max:100',
            'id_lugar' => 'nullable|integer',
            'lugar' => 'nullable|string|max:150',
            'estado' => 'nullable|in:Programada,Realizada,Completada,Cancelada,En Ejecución',
            'descripcion' => 'nullable|string',
        ]);

        $horaInicio = !empty($validated['hora']) ? date('H:i:s', strtotime($validated['hora'])) : '09:00:00';
        $lugarNombre = $validated['lugar'] ?? null;
        if (!$lugarNombre && !empty($validated['id_lugar'])) {
            $lug = LugarFormacion::find($validated['id_lugar']);
            if ($lug) $lugarNombre = $lug->nombre;
        }

        $estado = $validated['estado'] ?? 'Programada';
        if ($estado === 'Completada') $estado = 'Realizada';

        $actividad = ActividadSST::create([
            'tipo_actividad' => $validated['tipo_actividad'] ?? 'Pausa Activa',
            'titulo' => $validated['nombre'],
            'actividad_a_realizar' => $validated['nombre'],
            'fecha' => $validated['fecha'],
            'hora_inicio' => $horaInicio,
            'responsable' => $validated['responsable'],
            'lugar' => $lugarNombre ?? 'No especificado',
            'estado' => $estado,
            'descripcion' => $validated['descripcion'] ?? null,
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Actividad guardada con éxito.',
                'data' => [
                    'id_actividad' => $actividad->id_actividad,
                    'nombre' => $actividad->titulo,
                    'fecha' => $actividad->fecha,
                    'hora' => substr($actividad->hora_inicio, 0, 5),
                    'tipo_actividad' => $actividad->tipo_actividad,
                    'lugar_nombre' => $actividad->lugar,
                    'responsable' => $actividad->responsable,
                    'estado' => $actividad->estado,
                    'descripcion' => $actividad->descripcion
                ]
            ]);
        }

        return redirect()->route('SST.admin.cronograma.calendario')
            ->with('success', 'Actividad agendada con éxito.');
    }

    /**
     * Update the specified calendar activity.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nombre' => 'nullable|string|max:150',
            'fecha' => 'nullable|date',
            'hora' => 'nullable|string',
            'responsable' => 'nullable|string|max:100',
            'tipo_actividad' => 'nullable|string|max:100',
            'id_lugar' => 'nullable',
            'lugar' => 'nullable|string|max:150',
            'estado' => 'nullable|string',
            'descripcion' => 'nullable|string',
            'source' => 'nullable|string'
        ]);

        $actSST = ActividadSST::find($id);
        if ($actSST && ($request->get('source') !== 'cronograma')) {
            if (!empty($validated['nombre'])) {
                $actSST->titulo = $validated['nombre'];
                $actSST->actividad_a_realizar = $validated['nombre'];
            }
            if (!empty($validated['fecha'])) {
                $actSST->fecha = $validated['fecha'];
            }
            if (!empty($validated['hora'])) {
                $actSST->hora_inicio = date('H:i:s', strtotime($validated['hora']));
            }
            if (!empty($validated['responsable'])) {
                $actSST->responsable = $validated['responsable'];
            }
            if (!empty($validated['tipo_actividad'])) {
                $actSST->tipo_actividad = $validated['tipo_actividad'];
            }
            if (!empty($validated['estado'])) {
                $st = $validated['estado'];
                if ($st === 'Completada') $st = 'Realizada';
                $actSST->estado = $st;
            }
            if (isset($validated['descripcion'])) {
                $actSST->descripcion = $validated['descripcion'];
            }
            if (!empty($validated['lugar'])) {
                $actSST->lugar = $validated['lugar'];
            } elseif (!empty($validated['id_lugar'])) {
                $lugarObj = LugarFormacion::find($validated['id_lugar']);
                if ($lugarObj) {
                    $actSST->lugar = $lugarObj->nombre;
                }
            }

            $actSST->save();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Actividad de SST actualizada con éxito.',
                    'data' => [
                        'id_actividad' => $actSST->id_actividad,
                        'nombre' => $actSST->titulo,
                        'fecha' => $actSST->fecha,
                        'hora' => substr($actSST->hora_inicio, 0, 5),
                        'tipo_actividad' => $actSST->tipo_actividad,
                        'lugar_nombre' => $actSST->lugar,
                        'responsable' => $actSST->responsable,
                        'estado' => $actSST->estado,
                        'descripcion' => $actSST->descripcion
                    ]
                ]);
            }

            return redirect()->route('SST.admin.cronograma.calendario')
                ->with('success', 'Actividad actualizada con éxito.');
        }

        return response()->json(['success' => false, 'message' => 'Actividad no encontrada'], 404);
    }

    /**
     * Remove the specified activity from storage.
     */
    public function destroy(Request $request, $id)
    {
        $actSST = ActividadSST::find($id);
        if ($actSST) {
            $actSST->delete();
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Actividad de SST eliminada con éxito.'
                ]);
            }
            return redirect()->route('SST.admin.cronograma.calendario')
                ->with('success', 'Actividad eliminada con éxito.');
        }

        return response()->json(['success' => false, 'message' => 'Actividad no encontrada'], 404);
    }
}


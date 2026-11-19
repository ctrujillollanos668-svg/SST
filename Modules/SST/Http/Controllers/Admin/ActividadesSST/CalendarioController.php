<?php

namespace Modules\SST\Http\Controllers\Admin\ActividadesSST;

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

        return view('sst::admin.actividades_sst.Calendario', compact('actividades', 'lugares', 'tiposActividades'));
    }

    /**
     * Store a newly created quick Activity Type.
     */
    public function storeTipoActividad(Request $request)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:100', 'unique:tipos_actividades_sst,nombre', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/u'],
            'icono' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50'
        ], [
            'nombre.required' => 'El nombre del tipo es obligatorio.',
            'nombre.regex' => 'El nombre del tipo solo debe contener letras y espacios.'
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
            'nombre' => ['required', 'string', 'max:150', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/u'],
            'fecha' => 'required|date',
            'hora' => 'nullable|string',
            'responsable' => ['required', 'string', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/u'],
            'tipo_actividad' => 'nullable|string|max:100',
            'id_lugar' => 'nullable|integer',
            'lugar' => 'nullable|string|max:150',
            'estado' => 'nullable|in:Programada,Realizada,Completada,Cancelada,En Ejecución',
            'descripcion' => 'nullable|string|max:500',
        ], [
            'nombre.required' => 'El nombre de la actividad es obligatorio.',
            'nombre.regex' => 'El nombre de la actividad solo debe contener letras y espacios.',
            'responsable.required' => 'El responsable es obligatorio.',
            'responsable.regex' => 'El responsable solo debe contener letras y espacios.',
        ]);

        $horaInicio = !empty($validated['hora']) ? date('H:i:s', strtotime($validated['hora'])) : '09:00:00';
        $lugarNombre = $validated['lugar'] ?? null;
        if (!$lugarNombre && !empty($validated['id_lugar'])) {
            $lug = LugarFormacion::find($validated['id_lugar']);
            if ($lug) $lugarNombre = $lug->nombre;
        }

        $estado = $validated['estado'] ?? 'Programada';
        if ($estado === 'Completada') $estado = 'Realizada';

        $tipoActividad = $validated['tipo_actividad'] ?? 'Pausa Activa';
        $allowedTipos = ['Pausa Activa', 'Charla', 'Sensibilización'];
        if (!in_array($tipoActividad, $allowedTipos)) {
            if (stripos($tipoActividad, 'charla') !== false) {
                $tipoActividad = 'Charla';
            } elseif (stripos($tipoActividad, 'sensibil') !== false) {
                $tipoActividad = 'Sensibilización';
            } else {
                $tipoActividad = 'Pausa Activa';
            }
        }

        $actividad = ActividadSST::create([
            'tipo_actividad' => $tipoActividad,
            'titulo' => $validated['nombre'],
            'actividad_a_realizar' => $validated['nombre'],
            'fecha' => $validated['fecha'],
            'hora_inicio' => $horaInicio,
            'responsable' => $validated['responsable'],
            'lugar' => $lugarNombre ?? 'No especificado',
            'estado' => $estado,
            'descripcion' => $validated['descripcion'] ?? null,
        ]);

        // Notificar por correo real sobre la nueva actividad programada
        \Modules\SST\Services\SSTNotificationMailService::enviarNotificacionAprendiz(
            null,
            'Comunidad Aprendiz SST',
            [
                'caso_nombre' => "Nueva Actividad: {$actividad->titulo} ({$actividad->tipo_actividad})",
                'estado' => 'Programada',
                'es_finalizado' => false,
                'atendido_por' => $actividad->responsable ?? 'Administrador SST',
                'fecha' => date('d/m/Y', strtotime($actividad->fecha)) . " - " . substr($actividad->hora_inicio, 0, 5),
                'medidas_tomadas' => "Se ha agendado una nueva jornada de {$actividad->tipo_actividad} en {$actividad->lugar}. " . ($actividad->descripcion ?? ''),
            ]
        );

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
            'nombre' => ['nullable', 'string', 'max:150', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/u'],
            'fecha' => 'nullable|date',
            'hora' => 'nullable|string',
            'responsable' => ['nullable', 'string', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/u'],
            'tipo_actividad' => 'nullable|string|max:100',
            'id_lugar' => 'nullable',
            'lugar' => 'nullable|string|max:150',
            'estado' => 'nullable|string',
            'descripcion' => 'nullable|string|max:500',
            'source' => 'nullable|string'
        ], [
            'nombre.regex' => 'El nombre de la actividad solo debe contener letras y espacios.',
            'responsable.regex' => 'El responsable solo debe contener letras y espacios.'
        ]);

        $actSST = ActividadSST::find($id);
        if ($actSST && ($request->get('source') !== 'cronograma')) {
            $hoy = now('America/Bogota')->format('Y-m-d');
            $nuevaFecha = !empty($validated['fecha']) ? $validated['fecha'] : $actSST->fecha;

            // Si la actividad pertenecía a una fecha pasada y no se está reprogramando a hoy o al futuro:
            if ($actSST->fecha < $hoy && $nuevaFecha < $hoy) {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Esta actividad pertenece a una fecha pasada y está protegida contra modificaciones.'
                    ], 422);
                }
                return redirect()->route('SST.admin.cronograma.calendario')
                    ->with('error', 'Esta actividad pertenece a una fecha pasada y está protegida contra modificaciones.');
            }

            // Si la actividad es actual o futura, pero intentan cambiarla a una fecha pasada:
            if ($actSST->fecha >= $hoy && $nuevaFecha < $hoy) {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No se puede reprogramar una actividad hacia una fecha pasada.'
                    ], 422);
                }
                return redirect()->route('SST.admin.cronograma.calendario')
                    ->with('error', 'No se puede reprogramar una actividad hacia una fecha pasada.');
            }

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
                $tipoAct = $validated['tipo_actividad'];
                if (stripos($tipoAct, 'charla') !== false) {
                    $tipoAct = 'Charla';
                } elseif (stripos($tipoAct, 'sensibil') !== false) {
                    $tipoAct = 'Sensibilización';
                } else {
                    $tipoAct = 'Pausa Activa';
                }
                $actSST->tipo_actividad = $tipoAct;
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
            $hoy = now('America/Bogota')->format('Y-m-d');
            if ($actSST->fecha < $hoy) {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No se pueden eliminar actividades pertenecientes a fechas pasadas.'
                    ], 422);
                }
                return redirect()->route('SST.admin.cronograma.calendario')
                    ->with('error', 'No se pueden eliminar actividades pertenecientes a fechas pasadas.');
            }

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


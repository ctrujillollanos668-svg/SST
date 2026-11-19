<?php

namespace Modules\SST\Http\Controllers\Aprendiz\ActividadesSST;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SST\Entities\ActividadSST;
use Modules\SST\Entities\LugarFormacion;
use Modules\SST\Entities\TipoActividad;
use Modules\SICA\Entities\Course;

class ActividadesSSTController extends Controller
{
    /**
     * Muestra el Cronograma SST (Calendario y Actividades Programadas) para el Aprendiz.
     */
    public function index(Request $request)
    {
        // 1. Obtener solo las actividades pendientes de asistencia para el Aprendiz.
        // Apenas el aprendiz registre la asistencia (estado 'Realizada' o con asistentes registrados), 
        // la actividad desaparece automáticamente de este módulo y queda archivada en Control de Asistencias.
        $actividadesSst = ActividadSST::where(function ($q) {
                $q->whereNull('estado')
                  ->orWhere(function ($sub) {
                      $sub->where('estado', '!=', 'Realizada')
                          ->where('estado', '!=', 'Cancelada');
                  });
            })
            ->where(function ($q) {
                $q->whereNull('numero_asistentes')
                  ->orWhere('numero_asistentes', '<=', 0);
            })
            ->orderBy('fecha', 'asc')
            ->orderBy('hora_inicio', 'asc')
            ->get();

        // 2. Mapear a una estructura uniforme para el Calendario interactivo
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
                'instructor' => $act->instructor ?? null,
                'estado' => $act->estado ?? 'Programada',
                'descripcion' => $act->descripcion ?? $act->observaciones ?? '',
                'ficha_programa' => $act->ficha_programa ?? null,
                'participantes' => $act->participantes ?? null,
                'numero_asistentes' => $act->numero_asistentes ?? 0,
                'observaciones' => $act->observaciones ?? null
            ];
        });

        // Ordenar actividades por fecha y hora
        $actividades = $actividades->sortBy([
            ['fecha', 'asc'],
            ['hora', 'asc']
        ])->values();

        $lugares = LugarFormacion::where('estado', 'activo')->orderBy('nombre', 'asc')->get();
        $tiposActividades = TipoActividad::where('estado', 'activo')->orderBy('nombre', 'asc')->get();
        $cursos = Course::with('program')->where('status', 'Activo')->orderBy('code', 'asc')->get();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $actividades,
                'lugares' => $lugares,
                'tiposActividades' => $tiposActividades
            ]);
        }

        return view('sst::aprendiz.actividades_sst.Actividades_sst', compact('actividades', 'lugares', 'tiposActividades', 'cursos'));
    }

    /**
     * Registra o actualiza la asistencia de cualquier Actividad SST.
     */
    public function asistencia(Request $request, $id)
    {
        $actividad = ActividadSST::findOrFail($id);

        $validated = $request->validate([
            'ficha_programa' => ['nullable', 'string', 'max:20', 'regex:/^[0-9]+$/'],
            'instructor' => ['nullable', 'string', 'max:150', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/u'],
            'responsable' => ['nullable', 'string', 'max:150', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/u'],
            'numero_asistentes' => 'nullable|integer|min:0',
            'participantes' => 'nullable|string',
            'observaciones' => 'nullable|string|max:500',
            'estado' => 'required|in:Programada,Realizada,Cancelada'
        ], [
            'ficha_programa.regex' => 'El código de la ficha solo debe contener números sin espacios ni caracteres especiales.',
            'instructor.regex' => 'El nombre del instructor o instructora solo debe contener letras y espacios.',
            'responsable.regex' => 'El nombre del responsable de SST solo debe contener letras y espacios.',
        ]);

        $validated['numero_asistentes'] = !empty($validated['numero_asistentes']) ? (int) $validated['numero_asistentes'] : 0;
        
        // Al registrar la asistencia, se marca como 'Realizada' para que desaparezca de este módulo
        $validated['estado'] = 'Realizada';

        $actividad->update($validated);

        // Notificar por correo real al Administrador SST
        \Modules\SST\Services\SSTNotificationMailService::enviarNotificacionAdmin([
            'tipo_evento' => 'Asistencia Registrada: ' . ($actividad->titulo ?? $actividad->tipo_actividad),
            'categoria' => 'actividad',
            'afectado' => "Ficha: " . ($validated['ficha_programa'] ?? 'General') . " ({$validated['numero_asistentes']} asistentes)",
            'lugar' => $actividad->lugar ?? 'Ambiente SENA',
            'fecha' => now()->format('d/m/Y - h:i A'),
            'gravedad' => 'Informativa',
            'descripcion' => "Se registró la planilla de asistencia con {$validated['numero_asistentes']} participantes. Responsable: " . ($validated['responsable'] ?? ($validated['instructor'] ?? 'Aprendiz')),
        ]);

        return redirect()->route('SST.aprendiz.actividades_sst.index')
            ->with('success', '¡Asistencia registrada exitosamente para la actividad: ' . ($actividad->titulo ?? $actividad->tipo_actividad) . '! Ha sido completada y trasladada al módulo de Asistencia.');
    }

    /**
     * Retorna el listado de aprendices matriculados en una ficha/curso específico para control de asistencia.
     */
    public function getAprendicesPorFicha(Request $request)
    {
        $fichaParam = trim($request->get('ficha', ''));
        if (!$fichaParam) {
            return response()->json([
                'success' => false,
                'message' => 'Ficha no especificada',
                'aprendices' => []
            ]);
        }

        // Extraer el código numérico si viene en formato "2847386 - PROGRAMA"
        $codigo = $fichaParam;
        if (preg_match('/^(\d+)/', $fichaParam, $matches)) {
            $codigo = $matches[1];
        }

        $course = Course::with(['apprentices.person', 'program'])
            ->where('code', $codigo)
            ->orWhere('id', $codigo)
            ->first();

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró la ficha en el sistema SICA',
                'aprendices' => []
            ]);
        }

        $aprendices = $course->apprentices->map(function ($a) {
            $p = $a->person;
            return [
                'id' => $a->id,
                'document_number' => $p->document_number ?? 'S/N',
                'full_name' => trim(($p->first_name ?? '') . ' ' . ($p->first_last_name ?? '') . ' ' . ($p->second_last_name ?? '')),
                'status' => $a->apprentice_status ?? 'En formación',
            ];
        })->filter(function ($a) {
            return !empty($a['full_name']);
        })->sortBy('full_name')->values();

        return response()->json([
            'success' => true,
            'course' => [
                'id' => $course->id,
                'code' => $course->code,
                'program' => $course->program->name ?? '',
            ],
            'total' => $aprendices->count(),
            'aprendices' => $aprendices
        ]);
    }
}

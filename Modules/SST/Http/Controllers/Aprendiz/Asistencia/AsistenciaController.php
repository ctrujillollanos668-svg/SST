<?php

namespace Modules\SST\Http\Controllers\Aprendiz\Asistencia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SST\Entities\ActividadSST;
use Modules\SICA\Entities\Course;

class AsistenciaController extends Controller
{
    /**
     * Muestra el panel centralizado y consolidado de Control de Asistencias SST.
     */
    public function index(Request $request)
    {
        $query = ActividadSST::query();

        // Filtro por Estado: por defecto mostramos Realizadas (con asistencia completada), pero permitimos ver todas
        if ($request->filled('estado') && $request->estado !== 'todos') {
            $query->where('estado', $request->estado);
        } else {
            // Si no se especifica, priorizamos mostrar las que tienen asistencia registrada o realizadas
            if (!$request->has('estado')) {
                $query->where('estado', 'Realizada');
            }
        }

        // Filtro por Tipo de Actividad
        if ($request->filled('tipo_actividad') && $request->tipo_actividad !== 'todos') {
            $query->where('tipo_actividad', $request->tipo_actividad);
        }

        // Filtro por Ficha
        if ($request->filled('ficha') && $request->ficha !== 'todos') {
            $query->where('ficha_programa', 'like', "%{$request->ficha}%");
        }

        // Filtro de Búsqueda de texto libre
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhere('lugar', 'like', "%{$search}%")
                  ->orWhere('instructor', 'like', "%{$search}%")
                  ->orWhere('responsable', 'like', "%{$search}%")
                  ->orWhere('ficha_programa', 'like', "%{$search}%")
                  ->orWhere('participantes', 'like', "%{$search}%");
            });
        }

        // Filtro por Fecha
        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha', '<=', $request->fecha_hasta);
        }

        $asistencias = $query->orderBy('fecha', 'desc')
                             ->orderBy('hora_inicio', 'desc')
                             ->paginate(10)
                             ->withQueryString();

        // Estadísticas y Métricas Generales del Módulo de Asistencia
        $stats = [
            'total_realizadas' => ActividadSST::where('estado', 'Realizada')->count(),
            'total_asistentes' => (int) ActividadSST::where('estado', 'Realizada')->sum('numero_asistentes'),
            'charlas_count' => ActividadSST::where('tipo_actividad', 'Charla')->where('estado', 'Realizada')->count(),
            'pausas_count' => ActividadSST::where('tipo_actividad', 'Pausa Activa')->where('estado', 'Realizada')->count(),
            'sensibilizacion_count' => ActividadSST::where('tipo_actividad', 'Sensibilización')->where('estado', 'Realizada')->count(),
            'fichas_impactadas' => ActividadSST::where('estado', 'Realizada')->whereNotNull('ficha_programa')->where('ficha_programa', '!=', '')->distinct('ficha_programa')->count('ficha_programa'),
        ];

        // Cursos activos de SICA para el filtro de ficha
        $cursos = Course::with('program')->where('status', 'Activo')->orderBy('code', 'asc')->get();

        return view('sst::aprendiz.asistencia.Control_asistencias', compact('asistencias', 'stats', 'cursos'));
    }

    /**
     * Muestra la planilla en formato JSON o detalle.
     */
    public function show($id)
    {
        $actividad = ActividadSST::findOrFail($id);
        return response()->json([
            'success' => true,
            'actividad' => $actividad
        ]);
    }
}

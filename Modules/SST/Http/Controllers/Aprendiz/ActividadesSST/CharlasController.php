<?php

namespace Modules\SST\Http\Controllers\Aprendiz\ActividadesSST;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SST\Entities\ActividadSST;

use Modules\SICA\Entities\Course;

class CharlasController extends Controller
{
    /**
     * Muestra el listado de Charlas de Seguridad para el Aprendiz.
     */
    public function index(Request $request)
    {
        $query = ActividadSST::where('tipo_actividad', 'Charla');

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhere('lugar', 'like', "%{$search}%")
                  ->orWhere('instructor', 'like', "%{$search}%")
                  ->orWhere('ficha_programa', 'like', "%{$search}%");
            });
        }

        if ($request->filled('estado') && $request->estado !== 'todos') {
            $query->where('estado', $request->estado);
        }

        $actividades = $query->orderBy('fecha', 'desc')->orderBy('hora_inicio', 'asc')->paginate(10)->withQueryString();

        $stats = [
            'total' => ActividadSST::where('tipo_actividad', 'Charla')->count(),
            'programadas' => ActividadSST::where('tipo_actividad', 'Charla')->where('estado', 'Programada')->count(),
            'realizadas' => ActividadSST::where('tipo_actividad', 'Charla')->where('estado', 'Realizada')->count(),
        ];

        $cursos = Course::with('program')->where('status', 'Activo')->orderBy('code', 'asc')->get();

        return view('sst::aprendiz.actividades_sst.Charlas', compact('actividades', 'stats', 'cursos'));
    }

    /**
     * Registra o actualiza la asistencia a la charla.
     */
    public function asistencia(Request $request, $id)
    {
        $actividad = ActividadSST::where('tipo_actividad', 'Charla')->findOrFail($id);

        $validated = $request->validate([
            'ficha_programa' => 'nullable|string|max:150',
            'instructor' => 'nullable|string|max:150',
            'responsable' => 'nullable|string|max:150',
            'numero_asistentes' => 'nullable|integer|min:0',
            'participantes' => 'nullable|string',
            'observaciones' => 'nullable|string',
            'estado' => 'required|in:Programada,Realizada,Cancelada'
        ]);

        $validated['numero_asistentes'] = !empty($validated['numero_asistentes']) ? (int) $validated['numero_asistentes'] : 0;

        $actividad->update($validated);

        return redirect()->route('SST.aprendiz.actividades_sst.charlas.index')
            ->with('success', '¡Asistencia registrada exitosamente para la charla preventiva!');
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

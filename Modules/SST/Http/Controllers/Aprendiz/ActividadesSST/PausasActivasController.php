<?php

namespace Modules\SST\Http\Controllers\Aprendiz\ActividadesSST;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SST\Entities\ActividadSST;
use Modules\SST\Entities\LugarFormacion;
use Modules\SICA\Entities\Course;

class PausasActivasController extends Controller
{
    /**
     * Muestra el listado de Pausas Activas para el Aprendiz.
     */
    public function index(Request $request)
    {
        $query = ActividadSST::where('tipo_actividad', 'Pausa Activa');

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
            'total' => ActividadSST::where('tipo_actividad', 'Pausa Activa')->count(),
            'programadas' => ActividadSST::where('tipo_actividad', 'Pausa Activa')->where('estado', 'Programada')->count(),
            'realizadas' => ActividadSST::where('tipo_actividad', 'Pausa Activa')->where('estado', 'Realizada')->count(),
        ];

        $cursos = Course::with('program')->where('status', 'Activo')->orderBy('code', 'asc')->get();

        return view('sst::aprendiz.actividades_sst.Pausas_activas', compact('actividades', 'stats', 'cursos'));
    }

    /**
     * Registra o actualiza la asistencia a la pausa activa.
     */
    public function asistencia(Request $request, $id)
    {
        $actividad = ActividadSST::where('tipo_actividad', 'Pausa Activa')->findOrFail($id);

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

        $actividad->update($validated);

        return redirect()->route('SST.aprendiz.actividades_sst.pausas_activas.index')
            ->with('success', '¡Asistencia registrada exitosamente para la pausa activa!');
    }
}

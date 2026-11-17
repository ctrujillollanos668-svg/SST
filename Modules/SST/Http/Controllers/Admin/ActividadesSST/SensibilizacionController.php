<?php

namespace Modules\SST\Http\Controllers\Admin\ActividadesSST;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SST\Entities\ActividadSST;
use Modules\SST\Entities\LugarFormacion;
use Modules\SICA\Entities\Course;

class SensibilizacionController extends Controller
{
    /**
     * Muestra el listado de Jornadas y Campañas de Sensibilización.
     */
    public function index(Request $request)
    {
        $query = ActividadSST::where('tipo_actividad', 'Sensibilización');

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

        $actividades = $query->orderBy('fecha', 'desc')->orderBy('hora_inicio', 'desc')->paginate(10)->withQueryString();

        $stats = [
            'total' => ActividadSST::where('tipo_actividad', 'Sensibilización')->count(),
            'programadas' => ActividadSST::where('tipo_actividad', 'Sensibilización')->where('estado', 'Programada')->count(),
            'realizadas' => ActividadSST::where('tipo_actividad', 'Sensibilización')->where('estado', 'Realizada')->count(),
            'canceladas' => ActividadSST::where('tipo_actividad', 'Sensibilización')->where('estado', 'Cancelada')->count()
        ];

        $lugares = LugarFormacion::orderBy('nombre', 'asc')->get();
        $cursos = Course::with('program')->where('status', 'Activo')->orderBy('code', 'asc')->get();

        return view('sst::admin.actividades_sst.Sensibilizacion', compact('actividades', 'stats', 'lugares', 'cursos'));
    }

    /**
     * Guarda una nueva jornada de sensibilización.
     */
    public function store(Request $request)
    {
        $rules = [
            'titulo' => ['required', 'string', 'max:150', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/u'],
            'descripcion' => 'nullable|string|max:500',
            'actividad_a_realizar' => 'nullable|string',
            'fecha' => 'required|date',
            'hora_inicio' => 'required',
            'hora_fin' => 'nullable',
            'lugar' => 'required|string|max:150',
            'instructor' => ['nullable', 'string', 'max:150', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/u'],
            'responsable' => ['nullable', 'string', 'max:150', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/u'],
            'ficha_programa' => ['nullable', 'string', 'max:20', 'regex:/^[0-9]+$/'],
            'participantes' => 'nullable|string|max:255',
            'numero_asistentes' => 'nullable|integer|min:0',
            'observaciones' => 'nullable|string|max:500',
            'estado' => 'required|in:Programada,Realizada,Cancelada'
        ];

        $messages = [
            'titulo.required' => 'El campo Tema de la Jornada / Dinámica es obligatorio.',
            'titulo.regex' => 'El campo Tema de la Jornada / Dinámica solo debe contener letras y espacios (sin números ni caracteres especiales).',
            'instructor.regex' => 'El campo Instructor o Capacitador solo debe contener letras y espacios.',
            'responsable.regex' => 'El campo Responsable SST solo debe contener letras y espacios.',
            'ficha_programa.regex' => 'El campo Ficha o Programa solo debe contener números.',
            'fecha.required' => 'La fecha es obligatoria.',
            'hora_inicio.required' => 'La hora de inicio es obligatoria.',
            'lugar.required' => 'Debe seleccionar un ambiente o unidad.',
            'estado.required' => 'Debe seleccionar un estado válido.',
        ];

        $validated = $request->validate($rules, $messages);

        $validated['tipo_actividad'] = 'Sensibilización';
        $validated['numero_asistentes'] = $validated['numero_asistentes'] ?? 0;
        $validated['actividad_a_realizar'] = $validated['actividad_a_realizar'] ?? $validated['titulo'];

        ActividadSST::create($validated);

        return redirect()->route('SST.admin.sensibilizacion.index')
            ->with('success', '¡Jornada de sensibilización programada con éxito!');
    }

    /**
     * Actualiza una jornada de sensibilización.
     */
    public function update(Request $request, $id)
    {
        $actividad = ActividadSST::where('tipo_actividad', 'Sensibilización')->findOrFail($id);

        $rules = [
            'titulo' => ['required', 'string', 'max:150', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/u'],
            'descripcion' => 'nullable|string|max:500',
            'actividad_a_realizar' => 'nullable|string',
            'fecha' => 'required|date',
            'hora_inicio' => 'required',
            'hora_fin' => 'nullable',
            'lugar' => 'required|string|max:150',
            'instructor' => ['nullable', 'string', 'max:150', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/u'],
            'responsable' => ['nullable', 'string', 'max:150', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/u'],
            'ficha_programa' => ['nullable', 'string', 'max:20', 'regex:/^[0-9]+$/'],
            'participantes' => 'nullable|string|max:255',
            'numero_asistentes' => 'nullable|integer|min:0',
            'observaciones' => 'nullable|string|max:500',
            'estado' => 'required|in:Programada,Realizada,Cancelada'
        ];

        $messages = [
            'titulo.required' => 'El campo Tema de la Jornada / Dinámica es obligatorio.',
            'titulo.regex' => 'El campo Tema de la Jornada / Dinámica solo debe contener letras y espacios (sin números ni caracteres especiales).',
            'instructor.regex' => 'El campo Instructor o Capacitador solo debe contener letras y espacios.',
            'responsable.regex' => 'El campo Responsable SST solo debe contener letras y espacios.',
            'ficha_programa.regex' => 'El campo Ficha o Programa solo debe contener números.',
            'fecha.required' => 'La fecha es obligatoria.',
            'hora_inicio.required' => 'La hora de inicio es obligatoria.',
            'lugar.required' => 'Debe seleccionar un ambiente o unidad.',
            'estado.required' => 'Debe seleccionar un estado válido.',
        ];

        $validated = $request->validate($rules, $messages);

        if (empty($validated['actividad_a_realizar']) && !empty($validated['titulo'])) {
            $validated['actividad_a_realizar'] = $validated['titulo'];
        }

        $actividad->update($validated);

        return redirect()->route('SST.admin.sensibilizacion.index')
            ->with('success', '¡Jornada de sensibilización actualizada correctamente!');
    }

    /**
     * Elimina una jornada de sensibilización.
     */
    public function destroy($id)
    {
        $actividad = ActividadSST::where('tipo_actividad', 'Sensibilización')->findOrFail($id);
        $actividad->delete();

        return redirect()->route('SST.admin.sensibilizacion.index')
            ->with('success', '¡La jornada de sensibilización fue eliminada correctamente!');
    }
}

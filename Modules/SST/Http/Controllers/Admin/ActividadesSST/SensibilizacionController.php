<?php

namespace Modules\SST\Http\Controllers\Admin\ActividadesSST;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SST\Entities\ActividadSST;
use Modules\SST\Entities\LugarFormacion;

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

        $actividades = $query->orderBy('fecha', 'desc')->orderBy('hora_inicio', 'desc')->paginate(12)->withQueryString();

        $stats = [
            'total' => ActividadSST::where('tipo_actividad', 'Sensibilización')->count(),
            'programadas' => ActividadSST::where('tipo_actividad', 'Sensibilización')->where('estado', 'Programada')->count(),
            'realizadas' => ActividadSST::where('tipo_actividad', 'Sensibilización')->where('estado', 'Realizada')->count(),
            'canceladas' => ActividadSST::where('tipo_actividad', 'Sensibilización')->where('estado', 'Cancelada')->count()
        ];

        $lugares = LugarFormacion::orderBy('nombre', 'asc')->get();

        return view('sst::admin.actividades_sst.Sensibilizacion', compact('actividades', 'stats', 'lugares'));
    }

    /**
     * Guarda una nueva jornada de sensibilización.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'actividad_a_realizar' => 'nullable|string',
            'fecha' => 'required|date',
            'hora_inicio' => 'required',
            'hora_fin' => 'nullable',
            'lugar' => 'required|string|max:150',
            'instructor' => 'nullable|string|max:150',
            'responsable' => 'nullable|string|max:150',
            'ficha_programa' => 'nullable|string|max:150',
            'participantes' => 'nullable|string|max:255',
            'numero_asistentes' => 'nullable|integer|min:0',
            'observaciones' => 'nullable|string',
            'estado' => 'required|in:Programada,Realizada,Cancelada'
        ]);

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

        $validated = $request->validate([
            'titulo' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'actividad_a_realizar' => 'nullable|string',
            'fecha' => 'required|date',
            'hora_inicio' => 'required',
            'hora_fin' => 'nullable',
            'lugar' => 'required|string|max:150',
            'instructor' => 'nullable|string|max:150',
            'responsable' => 'nullable|string|max:150',
            'ficha_programa' => 'nullable|string|max:150',
            'participantes' => 'nullable|string|max:255',
            'numero_asistentes' => 'nullable|integer|min:0',
            'observaciones' => 'nullable|string',
            'estado' => 'required|in:Programada,Realizada,Cancelada'
        ]);

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

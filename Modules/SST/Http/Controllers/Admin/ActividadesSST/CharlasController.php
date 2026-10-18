<?php

namespace Modules\SST\Http\Controllers\Admin\ActividadesSST;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SST\Entities\ActividadSST;
use Modules\SST\Entities\LugarFormacion;

class CharlasController extends Controller
{
    /**
     * Muestra el listado de Charlas de Seguridad.
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

        $actividades = $query->orderBy('fecha', 'desc')->orderBy('hora_inicio', 'desc')->paginate(10)->withQueryString();

        $stats = [
            'total' => ActividadSST::where('tipo_actividad', 'Charla')->count(),
            'programadas' => ActividadSST::where('tipo_actividad', 'Charla')->where('estado', 'Programada')->count(),
            'realizadas' => ActividadSST::where('tipo_actividad', 'Charla')->where('estado', 'Realizada')->count(),
            'canceladas' => ActividadSST::where('tipo_actividad', 'Charla')->where('estado', 'Cancelada')->count()
        ];

        $lugares = LugarFormacion::orderBy('nombre', 'asc')->get();

        return view('sst::admin.actividades_sst.Charlas', compact('actividades', 'stats', 'lugares'));
    }

    /**
     * Guarda una nueva charla programada.
     */
    public function store(Request $request)
    {
        $minFecha = now('America/Bogota')->subDays(3)->format('Y-m-d');
        $minFechaFormat = now('America/Bogota')->subDays(3)->format('d/m/Y');

        $validated = $request->validate([
            'titulo' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'actividad_a_realizar' => 'nullable|string',
            'fecha' => 'required|date|after_or_equal:' . $minFecha,
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
        ], [
            'fecha.after_or_equal' => 'Solo se pueden programar actividades a partir de 3 días anteriores a hoy (' . $minFechaFormat . ').'
        ]);

        $validated['tipo_actividad'] = 'Charla';
        $validated['numero_asistentes'] = $validated['numero_asistentes'] ?? 0;
        $validated['actividad_a_realizar'] = $validated['actividad_a_realizar'] ?? $validated['titulo'];

        ActividadSST::create($validated);

        return redirect()->route('SST.admin.charlas.index')
            ->with('success', '¡Charla de seguridad programada exitosamente!');
    }

    /**
     * Actualiza una charla existente.
     */
    public function update(Request $request, $id)
    {
        $actividad = ActividadSST::where('tipo_actividad', 'Charla')->findOrFail($id);

        $minFecha = now('America/Bogota')->subDays(3)->format('Y-m-d');
        $minFechaFormat = now('America/Bogota')->subDays(3)->format('d/m/Y');

        $validated = $request->validate([
            'titulo' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'actividad_a_realizar' => 'nullable|string',
            'fecha' => 'required|date|after_or_equal:' . $minFecha,
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
        ], [
            'fecha.after_or_equal' => 'Solo se pueden seleccionar fechas a partir de 3 días anteriores a hoy (' . $minFechaFormat . ').'
        ]);

        if (empty($validated['actividad_a_realizar']) && !empty($validated['titulo'])) {
            $validated['actividad_a_realizar'] = $validated['titulo'];
        }

        $actividad->update($validated);

        return redirect()->route('SST.admin.charlas.index')
            ->with('success', '¡Charla de seguridad actualizada correctamente!');
    }

    /**
     * Elimina una charla.
     */
    public function destroy($id)
    {
        $actividad = ActividadSST::where('tipo_actividad', 'Charla')->findOrFail($id);
        $actividad->delete();

        return redirect()->route('SST.admin.charlas.index')
            ->with('success', '¡La charla fue eliminada satisfactoriamente!');
    }
}

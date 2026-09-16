<?php

namespace Modules\SST\Http\Controllers\Admin\Cronograma;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SST\Entities\CronogramaActividad;
use Modules\SST\Entities\LugarFormacion;
use Modules\SST\Entities\TipoActividad;

class CalendarioController extends Controller
{
    /**
     * Display the calendar and activities schedule.
     */
    public function index(Request $request)
    {
        $actividades = CronogramaActividad::with('lugar')
            ->orderBy('fecha', 'asc')
            ->orderBy('hora', 'asc')
            ->get();

        $lugares = LugarFormacion::where('estado', 'activo')->get();
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
     * Store a newly created calendar activity.
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
            'estado' => 'nullable|in:Programada,En Ejecución,Completada,Cancelada',
            'descripcion' => 'nullable|string',
            'id_fase' => 'nullable|integer'
        ]);

        $validated['hora'] = $validated['hora'] ? date('H:i:s', strtotime($validated['hora'])) : '09:00:00';
        $validated['tipo_actividad'] = $validated['tipo_actividad'] ?? 'Capacitación';
        $validated['estado'] = $validated['estado'] ?? 'Programada';

        $actividad = CronogramaActividad::create($validated);
        $actividad->load('lugar');

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Actividad agendada con éxito en el cronograma.',
                'data' => $actividad
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
        $actividad = CronogramaActividad::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:150',
            'fecha' => 'required|date',
            'hora' => 'nullable|string',
            'responsable' => 'required|string|max:100',
            'tipo_actividad' => 'nullable|string|max:100',
            'id_lugar' => 'nullable|integer',
            'estado' => 'nullable|in:Programada,En Ejecución,Completada,Cancelada',
            'descripcion' => 'nullable|string',
            'id_fase' => 'nullable|integer'
        ]);

        if (!empty($validated['hora'])) {
            $validated['hora'] = date('H:i:s', strtotime($validated['hora']));
        }

        $actividad->update($validated);
        $actividad->load('lugar');

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Actividad actualizada con éxito en el cronograma.',
                'data' => $actividad
            ]);
        }

        return redirect()->route('SST.admin.cronograma.calendario')
            ->with('success', 'Actividad actualizada con éxito.');
    }

    /**
     * Remove the specified activity from storage.
     */
    public function destroy(Request $request, $id)
    {
        $actividad = CronogramaActividad::findOrFail($id);
        $actividad->delete();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Actividad eliminada del cronograma con éxito.'
            ]);
        }

        return redirect()->route('SST.admin.cronograma.calendario')
            ->with('success', 'Actividad eliminada con éxito.');
    }
}

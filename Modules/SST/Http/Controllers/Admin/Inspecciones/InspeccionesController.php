<?php

namespace Modules\SST\Http\Controllers\Admin\Inspecciones;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SST\Entities\InspeccionSST;
use Modules\SST\Entities\LugarFormacion;
use Illuminate\Support\Facades\Auth;

class InspeccionesController extends Controller
{
    /**
     * Muestra el formulario para realizar una nueva inspección SST.
     */
    public function realizar()
    {
        $lugares = LugarFormacion::orderBy('nombre', 'asc')->get();
        return view('sst::admin.inspecciones.Realizar_inspeccion', compact('lugares'));
    }

    /**
     * Guarda una nueva inspección en la base de datos.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'lugar_id' => 'nullable|exists:lugares_formacion,id_lugar',
            'tipo_inspeccion' => 'required|string|max:100',
            'fecha_inspeccion' => 'required|date',
            'hora_inspeccion' => 'nullable',
            'estado' => 'nullable|in:pendiente,en_proceso,completada,con_hallazgos',
            'observaciones' => 'nullable|string'
        ]);

        $validated['user_id'] = Auth::id() ?? 1;
        $validated['estado'] = $validated['estado'] ?? 'completada';

        $inspeccion = InspeccionSST::create($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Inspección registrada exitosamente.',
                'data' => $inspeccion
            ]);
        }

        return redirect()->route('SST.admin.inspecciones.historial')
            ->with('success', 'Inspección registrada exitosamente.');
    }

    /**
     * Muestra el historial y listado de inspecciones de seguridad registradas.
     */
    public function historial()
    {
        $inspecciones = InspeccionSST::with(['lugar', 'user'])->orderBy('fecha_inspeccion', 'desc')->paginate(15);
        return view('sst::admin.inspecciones.Historial', compact('inspecciones'));
    }
}

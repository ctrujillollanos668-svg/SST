<?php

namespace Modules\SST\Http\Controllers\Admin\Inspecciones;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SST\Entities\InspeccionSST;
use Modules\SST\Entities\LugarFormacion;
use Illuminate\Support\Facades\Auth;

class HistorialController extends Controller
{
    /**
     * Muestra el formulario para realizar una nueva inspección SST (redirige a historial para el admin).
     */
    public function realizar()
    {
        return redirect()->route('SST.admin.inspecciones.historial');
    }

    /**
     * Muestra el historial y listado de inspecciones de seguridad registradas por aprendices.
     */
    public function historial()
    {
        $inspecciones = InspeccionSST::with(['lugar', 'user'])->orderBy('id_inspeccion', 'desc')->get();
        return view('sst::admin.inspecciones.Historial', compact('inspecciones'));
    }

    /**
     * Permite al Administrador SST atender o gestionar una inspección reportada.
     */
    public function atender(Request $request, $id)
    {
        $inspeccion = InspeccionSST::findOrFail($id);

        $validated = $request->validate([
            'estado' => 'required|in:pendiente,en_proceso,completada,con_hallazgos',
            'observacion_admin' => 'nullable|string',
        ]);

        $meta = json_decode($inspeccion->observaciones, true);
        if (!is_array($meta)) {
            $meta = ['descripcion' => $inspeccion->observaciones];
        }

        $adminName = 'Administrador SST';
        if (Auth::check()) {
            $adminName = Auth::user()->name ?? (Auth::user()->nombres ?? 'Administrador SST');
        }

        $meta['revision_admin'] = [
            'revisado_por' => $adminName,
            'fecha_revision' => now()->format('d/m/Y - h:i A'),
            'observacion' => $validated['observacion_admin'] ?? '',
        ];

        $inspeccion->estado = $validated['estado'];
        $inspeccion->observaciones = json_encode($meta, JSON_UNESCAPED_UNICODE);
        $inspeccion->save();

        return redirect()->route('SST.admin.inspecciones.historial')
            ->with('success', '¡Inspección gestionada exitosamente! Se actualizó el estado y el seguimiento.');
    }
}

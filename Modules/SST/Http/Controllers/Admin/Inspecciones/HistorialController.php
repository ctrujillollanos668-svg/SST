<?php

namespace Modules\SST\Http\Controllers\Admin\Inspecciones;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SST\Entities\InspeccionSST;
use Modules\SST\Entities\HallazgoInspeccion;
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
    public function historial(Request $request)
    {
        $query = InspeccionSST::with(['lugar', 'user', 'hallazgos'])->orderBy('id_inspeccion', 'desc');

        if ($request->filled('fecha')) {
            $query->whereDate('fecha_inspeccion', $request->fecha);
        }

        if ($request->filled('estado') && $request->estado !== 'todos') {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function($q) use ($s) {
                $q->where('tipo_inspeccion', 'like', "%{$s}%")
                  ->orWhere('observaciones', 'like', "%{$s}%")
                  ->orWhereHas('lugar', function($ql) use ($s) {
                      $ql->where('nombre', 'like', "%{$s}%");
                  })
                  ->orWhereHas('user', function($qu) use ($s) {
                      $qu->where('nickname', 'like', "%{$s}%")
                         ->orWhere('email', 'like', "%{$s}%");
                  })
                  ->orWhereHas('hallazgos', function($qh) use ($s) {
                      $qh->where('descripcion_hallazgo', 'like', "%{$s}%")
                         ->orWhere('responsable', 'like', "%{$s}%");
                  });
            });
        }

        $inspecciones = $query->paginate(10)->withQueryString();
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
            'observacion_admin' => 'nullable|string|max:1000',
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

        // Sincronizar estado en hallazgos_inspecciones (Buenas prácticas de ERP)
        $estadoHallazgo = in_array($validated['estado'], ['completada']) ? 'cerrado' : ($validated['estado'] === 'con_hallazgos' ? 'abierto' : 'en_proceso');
        $hallazgo = HallazgoInspeccion::firstOrNew(['id_inspeccion' => $inspeccion->id_inspeccion]);
        $hallazgo->estado = $estadoHallazgo;
        if (!empty($validated['observacion_admin'])) {
            $hallazgo->accion_correctiva = $validated['observacion_admin'];
        }
        $hallazgo->save();

        // Notificar por correo real al Aprendiz sobre la atención de la inspección
        $esFinalizado = in_array($validated['estado'], ['completada']);
        \Modules\SST\Services\SSTNotificationMailService::enviarNotificacionAprendiz(
            null,
            $inspeccion->responsable ?? 'Aprendiz SST',
            [
                'caso_nombre' => "Inspección: {$inspeccion->tipo_inspeccion}",
                'estado' => $esFinalizado ? 'atendido' : 'en_proceso',
                'es_finalizado' => $esFinalizado,
                'atendido_por' => auth()->check() ? (auth()->user()->full_name ?? auth()->user()->name ?? 'Administrador SST') : 'Administrador SST',
                'fecha' => now()->format('d/m/Y - h:i A'),
                'medidas_tomadas' => $validated['observacion_admin'] ?? "Inspección actualizada al estado: {$validated['estado']}.",
            ]
        );

        return redirect()->route('SST.admin.inspecciones.historial')
            ->with('success', '¡Inspección gestionada exitosamente! Se actualizó el estado y el seguimiento.');
    }
}

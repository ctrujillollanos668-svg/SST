<?php

namespace Modules\SST\Http\Controllers\Admin\Informacion_basica;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SST\Entities\RespuestaEvento;

class RespuestaEventoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $respuestas = RespuestaEvento::orderBy('id_respuesta', 'desc')->get();

        $accidentes = $respuestas->filter(function($r) {
            return stripos($r->nombre, 'accidente') !== false;
        });

        $incidentes = $respuestas->filter(function($r) {
            return stripos($r->nombre, 'incidente') !== false;
        });

        // Si hay respuestas que no tienen la palabra explícita, agruparlas en accidentes por defecto
        if ($accidentes->isEmpty() && !$respuestas->isEmpty() && $incidentes->isEmpty()) {
            $accidentes = $respuestas;
        }

        return view('sst::admin.informacion_basica.Respuesta_evento', compact('respuestas', 'accidentes', 'incidentes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'estado' => 'nullable|in:activo,inactivo,Activo,Inactivo',
        ]);

        $validated['estado'] = strtolower($validated['estado'] ?? 'activo');

        RespuestaEvento::create($validated);

        return redirect()->route('SST.admin.informacion_basica.respuesta_eventos.index')
            ->with('success', 'Tipo de Respuesta registrado correctamente.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $respuesta = RespuestaEvento::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'estado' => 'nullable|in:activo,inactivo,Activo,Inactivo',
        ]);

        $validated['estado'] = strtolower($validated['estado'] ?? 'activo');

        $respuesta->update($validated);

        return redirect()->route('SST.admin.informacion_basica.respuesta_eventos.index')
            ->with('success', 'Tipo de Respuesta actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $respuesta = RespuestaEvento::findOrFail($id);
        $respuesta->delete();

        return redirect()->route('SST.admin.informacion_basica.respuesta_eventos.index')
            ->with('success', 'Tipo de Respuesta eliminado correctamente.');
    }

    /**
     * Registrar la atención y respuesta del Administrador SST al reporte.
     */
    public function atender(Request $request, $id)
    {
        $respuesta = RespuestaEvento::findOrFail($id);

        $validated = $request->validate([
            'protocolo' => 'required|string',
            'medidas_tomadas' => 'required|string',
            'estado' => 'nullable|string',
        ]);

        $data = json_decode($respuesta->descripcion, true);
        if (!is_array($data)) {
            $data = [
                'descripcion_original' => $respuesta->descripcion,
            ];
        }

        $adminName = 'Administrador SST';
        if (auth()->check()) {
            $adminName = auth()->user()->name ?? (auth()->user()->nombres ?? 'Administrador SST');
        }

        $data['respuesta_admin'] = [
            'atendido_por' => $adminName,
            'protocolo' => $validated['protocolo'],
            'medidas_tomadas' => $validated['medidas_tomadas'],
            'fecha_atencion' => now()->format('d/m/Y - h:i A'),
            'estado' => $validated['estado'] ?? 'atendido',
        ];

        $respuesta->descripcion = json_encode($data, JSON_UNESCAPED_UNICODE);
        $respuesta->estado = $validated['estado'] ?? 'atendido';
        $respuesta->save();

        return redirect()->route('SST.admin.informacion_basica.respuesta_eventos.index')
            ->with('success', '¡Atención y respuesta registrada exitosamente! El aprendiz ya puede visualizar las medidas tomadas.');
    }
}

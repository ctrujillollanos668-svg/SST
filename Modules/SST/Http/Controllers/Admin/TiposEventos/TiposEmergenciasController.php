<?php

namespace Modules\SST\Http\Controllers\Admin\TiposEventos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SST\Entities\TipoEvento;

class TiposEmergenciasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $emergencias = TipoEvento::where('tipo_categoria', 'tipo_emergencia')
            ->orderBy('id_tipo_evento', 'desc')
            ->get();

        return view('sst::admin.tipos_eventos.Tipos_de_emergencias', compact('emergencias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'modulo_pertenece' => 'nullable|string|max:150',
            'notificaciones' => 'nullable|boolean',
            'estado' => 'nullable|in:activo,inactivo',
        ]);

        $validated['tipo_categoria'] = 'tipo_emergencia';
        $validated['estado'] = $validated['estado'] ?? 'activo';
        $validated['notificaciones'] = $request->has('notificaciones');

        TipoEvento::create($validated);

        return redirect()->route('SST.admin.tipos_eventos.tipos_emergencias.index')
            ->with('success', 'Tipo de Emergencia registrado correctamente.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $emergencia = TipoEvento::where('tipo_categoria', 'tipo_emergencia')->findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'modulo_pertenece' => 'nullable|string|max:150',
            'notificaciones' => 'nullable|boolean',
            'estado' => 'nullable|in:activo,inactivo',
        ]);

        $validated['notificaciones'] = $request->has('notificaciones');

        $emergencia->update($validated);

        return redirect()->route('SST.admin.tipos_eventos.tipos_emergencias.index')
            ->with('success', 'Tipo de Emergencia actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $emergencia = TipoEvento::where('tipo_categoria', 'tipo_emergencia')->findOrFail($id);
        $emergencia->delete();

        return redirect()->route('SST.admin.tipos_eventos.tipos_emergencias.index')
            ->with('success', 'Tipo de Emergencia eliminado correctamente.');
    }
}

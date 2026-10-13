<?php

namespace Modules\SST\Http\Controllers\Admin\TiposEventos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SST\Entities\TipoEvento;

class CondicionesInsegurasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $condiciones = TipoEvento::where('tipo_categoria', 'condicion_insegura')
            ->orderBy('id_tipo_evento', 'desc')
            ->get();

        return view('sst::admin.tipos_eventos.Condiciones_inseguras', compact('condiciones'));
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

        $validated['tipo_categoria'] = 'condicion_insegura';
        $validated['estado'] = $validated['estado'] ?? 'activo';
        $validated['notificaciones'] = $request->has('notificaciones');

        TipoEvento::create($validated);

        return redirect()->route('SST.admin.tipos_eventos.condiciones_inseguras.index')
            ->with('success', 'Condición Insegura registrada correctamente.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $condicion = TipoEvento::where('tipo_categoria', 'condicion_insegura')->findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'modulo_pertenece' => 'nullable|string|max:150',
            'notificaciones' => 'nullable|boolean',
            'estado' => 'nullable|in:activo,inactivo',
        ]);

        $validated['notificaciones'] = $request->has('notificaciones');

        $condicion->update($validated);

        return redirect()->route('SST.admin.tipos_eventos.condiciones_inseguras.index')
            ->with('success', 'Condición Insegura actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $condicion = TipoEvento::where('tipo_categoria', 'condicion_insegura')->findOrFail($id);
        $condicion->delete();

        return redirect()->route('SST.admin.tipos_eventos.condiciones_inseguras.index')
            ->with('success', 'Condición Insegura eliminada correctamente.');
    }
}

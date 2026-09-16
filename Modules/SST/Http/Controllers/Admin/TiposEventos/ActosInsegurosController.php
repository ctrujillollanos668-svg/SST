<?php

namespace Modules\SST\Http\Controllers\Admin\TiposEventos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SST\Entities\TipoEvento;

class ActosInsegurosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $actos = TipoEvento::where('tipo_categoria', 'acto_inseguro')
            ->orderBy('id_tipo_evento', 'desc')
            ->get();

        return view('sst::admin.tipos_eventos.Actos_inseguros', compact('actos'));
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

        $validated['tipo_categoria'] = 'acto_inseguro';
        $validated['estado'] = $validated['estado'] ?? 'activo';
        $validated['notificaciones'] = $request->has('notificaciones');

        TipoEvento::create($validated);

        return redirect()->route('SST.admin.tipos_eventos.actos_inseguros.index')
            ->with('success', 'Acto Inseguro registrado correctamente.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $acto = TipoEvento::where('tipo_categoria', 'acto_inseguro')->findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'modulo_pertenece' => 'nullable|string|max:150',
            'notificaciones' => 'nullable|boolean',
            'estado' => 'nullable|in:activo,inactivo',
        ]);

        $validated['notificaciones'] = $request->has('notificaciones');

        $acto->update($validated);

        return redirect()->route('SST.admin.tipos_eventos.actos_inseguros.index')
            ->with('success', 'Acto Inseguro actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $acto = TipoEvento::where('tipo_categoria', 'acto_inseguro')->findOrFail($id);
        $acto->delete();

        return redirect()->route('SST.admin.tipos_eventos.actos_inseguros.index')
            ->with('success', 'Acto Inseguro eliminado correctamente.');
    }
}

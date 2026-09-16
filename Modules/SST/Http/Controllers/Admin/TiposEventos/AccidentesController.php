<?php

namespace Modules\SST\Http\Controllers\Admin\TiposEventos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SST\Entities\TipoEvento;

class AccidentesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $accidentes = TipoEvento::where('tipo_categoria', 'accidente')
            ->orderBy('id_tipo_evento', 'desc')
            ->get();

        return view('sst::admin.tipos_eventos.Accidentes', compact('accidentes'));
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

        $validated['tipo_categoria'] = 'accidente';
        $validated['estado'] = $validated['estado'] ?? 'activo';
        $validated['notificaciones'] = $request->has('notificaciones');

        TipoEvento::create($validated);

        return redirect()->route('SST.admin.tipos_eventos.accidentes.index')
            ->with('success', 'Tipo de Accidente registrado correctamente.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $accidente = TipoEvento::where('tipo_categoria', 'accidente')->findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'modulo_pertenece' => 'nullable|string|max:150',
            'notificaciones' => 'nullable|boolean',
            'estado' => 'nullable|in:activo,inactivo',
        ]);

        $validated['notificaciones'] = $request->has('notificaciones');

        $accidente->update($validated);

        return redirect()->route('SST.admin.tipos_eventos.accidentes.index')
            ->with('success', 'Tipo de Accidente actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $accidente = TipoEvento::where('tipo_categoria', 'accidente')->findOrFail($id);
        $accidente->delete();

        return redirect()->route('SST.admin.tipos_eventos.accidentes.index')
            ->with('success', 'Tipo de Accidente eliminado correctamente.');
    }
}

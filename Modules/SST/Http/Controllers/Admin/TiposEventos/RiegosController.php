<?php

namespace Modules\SST\Http\Controllers\Admin\TiposEventos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SST\Entities\TipoEvento;

class RiegosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $riesgos = TipoEvento::where('tipo_categoria', 'riesgo')
            ->orderBy('id_tipo_evento', 'desc')
            ->get();

        return view('sst::admin.tipos_eventos.Riegos', compact('riesgos'));
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

        $validated['tipo_categoria'] = 'riesgo';
        $validated['estado'] = $validated['estado'] ?? 'activo';
        $validated['notificaciones'] = $request->has('notificaciones');

        TipoEvento::create($validated);

        return redirect()->route('SST.admin.tipos_eventos.riesgos.index')
            ->with('success', 'Riesgo registrado correctamente.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $riesgo = TipoEvento::where('tipo_categoria', 'riesgo')->findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'modulo_pertenece' => 'nullable|string|max:150',
            'notificaciones' => 'nullable|boolean',
            'estado' => 'nullable|in:activo,inactivo',
        ]);

        $validated['notificaciones'] = $request->has('notificaciones');

        $riesgo->update($validated);

        return redirect()->route('SST.admin.tipos_eventos.riesgos.index')
            ->with('success', 'Riesgo actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $riesgo = TipoEvento::where('tipo_categoria', 'riesgo')->findOrFail($id);
        $riesgo->delete();

        return redirect()->route('SST.admin.tipos_eventos.riesgos.index')
            ->with('success', 'Riesgo eliminado correctamente.');
    }
}

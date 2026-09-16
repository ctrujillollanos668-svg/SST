<?php

namespace Modules\SST\Http\Controllers\Admin\TiposEventos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SST\Entities\TipoEvento;

class LesionesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lesiones = TipoEvento::where('tipo_categoria', 'lesion')
            ->orderBy('id_tipo_evento', 'desc')
            ->get();

        return view('sst::admin.tipos_eventos.Lesiones', compact('lesiones'));
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

        $validated['tipo_categoria'] = 'lesion';
        $validated['estado'] = $validated['estado'] ?? 'activo';
        $validated['notificaciones'] = $request->has('notificaciones');

        TipoEvento::create($validated);

        return redirect()->route('SST.admin.tipos_eventos.lesiones.index')
            ->with('success', 'Tipo de Lesión registrado correctamente.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $lesion = TipoEvento::where('tipo_categoria', 'lesion')->findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'modulo_pertenece' => 'nullable|string|max:150',
            'notificaciones' => 'nullable|boolean',
            'estado' => 'nullable|in:activo,inactivo',
        ]);

        $validated['notificaciones'] = $request->has('notificaciones');

        $lesion->update($validated);

        return redirect()->route('SST.admin.tipos_eventos.lesiones.index')
            ->with('success', 'Tipo de Lesión actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $lesion = TipoEvento::where('tipo_categoria', 'lesion')->findOrFail($id);
        $lesion->delete();

        return redirect()->route('SST.admin.tipos_eventos.lesiones.index')
            ->with('success', 'Tipo de Lesión eliminado correctamente.');
    }
}

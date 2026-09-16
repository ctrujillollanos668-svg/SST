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
        return view('sst::admin.informacion_basica.Respuesta_evento', compact('respuestas'));
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
}

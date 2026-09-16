<?php

namespace Modules\SST\Http\Controllers\Admin\Informacion_basica;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SST\Entities\LugarFormacion;

class LugarInformacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lugares = LugarFormacion::orderBy('id_lugar', 'desc')->get();
        return view('sst::admin.informacion_basica.Lugar_informacion', compact('lugares'));
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

        LugarFormacion::create($validated);

        return redirect()->route('SST.admin.informacion_basica.lugar_informacion.index')
            ->with('success', 'Lugar de Formación registrado correctamente.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $lugar = LugarFormacion::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'estado' => 'nullable|in:activo,inactivo,Activo,Inactivo',
        ]);

        $validated['estado'] = strtolower($validated['estado'] ?? 'activo');

        $lugar->update($validated);

        return redirect()->route('SST.admin.informacion_basica.lugar_informacion.index')
            ->with('success', 'Lugar de Formación actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $lugar = LugarFormacion::findOrFail($id);
        $lugar->delete();

        return redirect()->route('SST.admin.informacion_basica.lugar_informacion.index')
            ->with('success', 'Lugar de Formación eliminado correctamente.');
    }
}

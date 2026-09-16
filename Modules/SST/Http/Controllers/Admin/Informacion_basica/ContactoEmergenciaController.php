<?php

namespace Modules\SST\Http\Controllers\Admin\Informacion_basica;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SST\Entities\ContactoEmergencia;

class ContactoEmergenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contactos = ContactoEmergencia::orderBy('id_contacto', 'desc')->get();
        return view('sst::admin.informacion_basica.Contacto_emergencia', compact('contactos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:50',
            'telefono' => 'required|string|max:15',
            'descripcion' => 'nullable|string',
            'estado' => 'nullable|in:activo,inactivo',
        ]);

        $validated['estado'] = $validated['estado'] ?? 'activo';

        ContactoEmergencia::create($validated);

        return redirect()->route('SST.admin.informacion_basica.contacto_emergencia.index')
            ->with('success', 'Contacto de Emergencia registrado correctamente.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $contacto = ContactoEmergencia::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:50',
            'telefono' => 'required|string|max:15',
            'descripcion' => 'nullable|string',
            'estado' => 'nullable|in:activo,inactivo',
        ]);

        $contacto->update($validated);

        return redirect()->route('SST.admin.informacion_basica.contacto_emergencia.index')
            ->with('success', 'Contacto de Emergencia actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $contacto = ContactoEmergencia::findOrFail($id);
        $contacto->delete();

        return redirect()->route('SST.admin.informacion_basica.contacto_emergencia.index')
            ->with('success', 'Contacto de Emergencia eliminado correctamente.');
    }
}

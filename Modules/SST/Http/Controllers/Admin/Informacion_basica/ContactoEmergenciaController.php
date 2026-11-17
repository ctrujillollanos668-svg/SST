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
            'nombre' => ['required', 'string', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/u'],
            'telefono' => ['required', 'string', 'max:20', 'regex:/^[0-9]+$/'],
            'descripcion' => 'nullable|string|max:500',
            'estado' => 'nullable|in:activo,inactivo',
        ], [
            'nombre.required' => 'El nombre de la entidad o persona es obligatorio.',
            'nombre.regex' => 'El nombre solo debe contener letras y espacios.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.regex' => 'El teléfono solo debe contener números.'
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
            'nombre' => ['required', 'string', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/u'],
            'telefono' => ['required', 'string', 'max:20', 'regex:/^[0-9]+$/'],
            'descripcion' => 'nullable|string|max:500',
            'estado' => 'nullable|in:activo,inactivo',
        ], [
            'nombre.required' => 'El nombre de la entidad o persona es obligatorio.',
            'nombre.regex' => 'El nombre solo debe contener letras y espacios.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.regex' => 'El teléfono solo debe contener números.'
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

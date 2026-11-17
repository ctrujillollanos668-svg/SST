<?php

namespace Modules\SST\Http\Controllers\Admin\TiposEventos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SST\Entities\TipoEvento;

class IncidentesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $incidentes = TipoEvento::where('tipo_categoria', 'incidente')
            ->orderBy('id_tipo_evento', 'desc')
            ->get();

        return view('sst::admin.tipos_eventos.Incidentes', compact('incidentes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/u'],
            'descripcion' => 'nullable|string|max:500',
            'modulo_pertenece' => 'nullable|string|max:150',
            'notificaciones' => 'nullable|boolean',
            'estado' => 'nullable|in:activo,inactivo',
        ], [
            'nombre.regex' => 'El nombre solo debe contener letras y espacios.',
        ]);

        $validated['tipo_categoria'] = 'incidente';
        $validated['estado'] = $validated['estado'] ?? 'activo';
        $validated['notificaciones'] = $request->has('notificaciones');

        TipoEvento::create($validated);

        return redirect()->route('SST.admin.tipos_eventos.incidentes.index')
            ->with('success', 'Incidente registrado correctamente.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $incidente = TipoEvento::where('tipo_categoria', 'incidente')->findOrFail($id);

        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/u'],
            'descripcion' => 'nullable|string|max:500',
            'modulo_pertenece' => 'nullable|string|max:150',
            'notificaciones' => 'nullable|boolean',
            'estado' => 'nullable|in:activo,inactivo',
        ], [
            'nombre.regex' => 'El nombre solo debe contener letras y espacios.',
        ]);

        $validated['notificaciones'] = $request->has('notificaciones');

        $incidente->update($validated);

        return redirect()->route('SST.admin.tipos_eventos.incidentes.index')
            ->with('success', 'Incidente actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $incidente = TipoEvento::where('tipo_categoria', 'incidente')->findOrFail($id);
        $incidente->delete();

        return redirect()->route('SST.admin.tipos_eventos.incidentes.index')
            ->with('success', 'Incidente eliminado correctamente.');
    }
}

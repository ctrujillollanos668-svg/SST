<?php

namespace Modules\SST\Http\Controllers\Admin\Definiciones;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SST\Entities\DefinicionSST;

class DefinicionesController extends Controller
{
    /**
     * Muestra las definiciones de Accidentes
     */
    public function accidentes()
    {
        $definiciones = DefinicionSST::where('tipo_categoria', 'accidente')
            ->orderBy('nombre', 'asc')
            ->get();

        return view('sst::admin.definiciones.Accidentes', [
            'definiciones' => $definiciones,
            'categoria' => 'accidente',
            'titulo' => 'Accidentes',
            'icono' => 'fa-burst',
            'color' => 'orange',
        ]);
    }

    /**
     * Muestra las definiciones de Incidentes
     */
    public function incidentes()
    {
        $definiciones = DefinicionSST::where('tipo_categoria', 'incidente')
            ->orderBy('nombre', 'asc')
            ->get();

        return view('sst::admin.definiciones.Incidentes', [
            'definiciones' => $definiciones,
            'categoria' => 'incidente',
            'titulo' => 'Incidentes',
            'icono' => 'fa-triangle-exclamation',
            'color' => 'amber',
        ]);
    }

    /**
     * Muestra las definiciones de Riesgos
     */
    public function riesgos()
    {
        $definiciones = DefinicionSST::where('tipo_categoria', 'riesgo')
            ->orderBy('nombre', 'asc')
            ->get();

        return view('sst::admin.definiciones.Riesgos', [
            'definiciones' => $definiciones,
            'categoria' => 'riesgo',
            'titulo' => 'Riesgos',
            'icono' => 'fa-biohazard',
            'color' => 'blue',
        ]);
    }

    /**
     * Muestra las definiciones de Actos Inseguros
     */
    public function actosInseguros()
    {
        $definiciones = DefinicionSST::where('tipo_categoria', 'acto_inseguro')
            ->orderBy('nombre', 'asc')
            ->get();

        return view('sst::admin.definiciones.Actos_inseguros', [
            'definiciones' => $definiciones,
            'categoria' => 'acto_inseguro',
            'titulo' => 'Actos Inseguros',
            'icono' => 'fa-lightbulb',
            'color' => 'yellow',
        ]);
    }

    /**
     * Muestra las definiciones de Lesiones
     */
    public function lesiones()
    {
        $definiciones = DefinicionSST::where('tipo_categoria', 'lesion')
            ->orderBy('nombre', 'asc')
            ->get();

        return view('sst::admin.definiciones.Lesiones', [
            'definiciones' => $definiciones,
            'categoria' => 'lesion',
            'titulo' => 'Lesiones',
            'icono' => 'fa-crutch',
            'color' => 'purple',
        ]);
    }

    /**
     * Muestra las definiciones de Condiciones Inseguras
     */
    public function condicionesInseguras()
    {
        $definiciones = DefinicionSST::where('tipo_categoria', 'condicion_insegura')
            ->orderBy('nombre', 'asc')
            ->get();

        return view('sst::admin.definiciones.Condiciones_inseguras', [
            'definiciones' => $definiciones,
            'categoria' => 'condicion_insegura',
            'titulo' => 'Condiciones Inseguras',
            'icono' => 'fa-triangle-exclamation',
            'color' => 'teal',
        ]);
    }

    /**
     * Muestra las definiciones de Condiciones de Emergencias
     */
    public function tiposEmergencias()
    {
        $definiciones = DefinicionSST::where('tipo_categoria', 'tipo_emergencia')
            ->orderBy('nombre', 'asc')
            ->get();

        return view('sst::admin.definiciones.Tipos_de_emergencias', [
            'definiciones' => $definiciones,
            'categoria' => 'tipo_emergencia',
            'titulo' => 'Condiciones de Emergencias',
            'icono' => 'fa-hospital',
            'color' => 'rose',
        ]);
    }

    /**
     * Guarda una nueva definición en la tabla definiciones_sst
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo_categoria' => 'required|string|in:accidente,incidente,riesgo,acto_inseguro,condicion_insegura,lesion,tipo_emergencia',
            'nombre' => 'required|string|max:150',
            'descripcion' => 'required|string',
            'como_prevenir' => 'nullable|string',
            'tipo_gravedad' => 'nullable|string|max:50',
            'tipo_otro' => 'nullable|string|max:150',
            'modulo_pertenece' => 'nullable|string|max:150',
            'estado' => 'required|in:activo,inactivo',
        ]);

        if ($request->tipo_gravedad === 'Otro' && $request->filled('tipo_otro')) {
            $validated['tipo_otro'] = $request->tipo_otro;
        } elseif ($request->tipo_gravedad !== 'Otro') {
            $validated['tipo_otro'] = null;
        }

        DefinicionSST::create($validated);

        return redirect()->back()->with('success', 'Definición registrada exitosamente.');
    }

    /**
     * Actualiza una definición existente
     */
    public function update(Request $request, $id)
    {
        $definicion = DefinicionSST::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:150',
            'descripcion' => 'required|string',
            'como_prevenir' => 'nullable|string',
            'tipo_gravedad' => 'nullable|string|max:50',
            'tipo_otro' => 'nullable|string|max:150',
            'modulo_pertenece' => 'nullable|string|max:150',
            'estado' => 'required|in:activo,inactivo',
        ]);

        if ($request->tipo_gravedad === 'Otro' && $request->filled('tipo_otro')) {
            $validated['tipo_otro'] = $request->tipo_otro;
        } elseif ($request->tipo_gravedad !== 'Otro') {
            $validated['tipo_otro'] = null;
        }

        $definicion->update($validated);

        return redirect()->back()->with('success', 'Definición actualizada correctamente.');
    }

    /**
     * Alterna el estado activo / inactivo
     */
    public function toggleEstado($id)
    {
        $definicion = DefinicionSST::findOrFail($id);
        $definicion->estado = $definicion->estado === 'activo' ? 'inactivo' : 'activo';
        $definicion->save();

        $msg = $definicion->estado === 'activo' 
            ? 'Definición activada (visible para los aprendices).' 
            : 'Definición desactivada (oculta para los aprendices).';

        return redirect()->back()->with('success', $msg);
    }

    /**
     * Elimina una definición
     */
    public function destroy($id)
    {
        $definicion = DefinicionSST::findOrFail($id);
        $definicion->delete();

        return redirect()->back()->with('success', 'Definición eliminada correctamente.');
    }
}

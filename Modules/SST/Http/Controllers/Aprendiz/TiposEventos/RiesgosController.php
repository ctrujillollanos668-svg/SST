<?php

namespace Modules\SST\Http\Controllers\Aprendiz\TiposEventos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Modules\SST\Entities\TipoEvento;

class RiesgosController extends Controller
{
    /**
     * Muestra el catálogo de Riesgos para el Aprendiz SST
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login', ['redirect' => route('SST.aprendiz.tipos_eventos.riesgos.index')]);
        }

        /** @var User $user */
        $user = Auth::user();

        if ($user->roles->contains('slug', 'sst.aprendiz') || $user->roles->contains('slug', 'sst.funcionario') || $user->roles->contains('slug', 'sst.admin') || $user->hasSuperAdmin()) {
            $riesgos = TipoEvento::where('tipo_categoria', 'riesgo')
                ->where('estado', 'activo')
                ->orderBy('nombre', 'asc')
                ->get();

            return view('sst::aprendiz.tipos_eventos.Riegos', compact('riesgos'));
        }

        abort(403, 'Acceso denegado: No tienes permisos para consultar este recurso en el módulo SST.');
    }

    /**
     * Registra un nuevo riesgo desde el panel de Aprendiz
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'modulo_pertenece' => 'nullable|string|max:150',
        ]);

        $validated['tipo_categoria'] = 'riesgo';
        $validated['estado'] = 'activo';
        $validated['notificaciones'] = true;

        TipoEvento::create($validated);

        return redirect()->route('SST.aprendiz.tipos_eventos.riesgos.index')
            ->with('success', 'Riesgo registrado correctamente.');
    }
}

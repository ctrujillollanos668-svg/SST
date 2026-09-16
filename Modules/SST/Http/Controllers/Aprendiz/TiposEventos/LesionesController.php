<?php

namespace Modules\SST\Http\Controllers\Aprendiz\TiposEventos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Modules\SST\Entities\TipoEvento;

class LesionesController extends Controller
{
    /**
     * Muestra el catálogo de Lesiones para el Aprendiz SST
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login', ['redirect' => route('SST.aprendiz.tipos_eventos.lesiones.index')]);
        }

        /** @var User $user */
        $user = Auth::user();

        if ($user->roles->contains('slug', 'sst.aprendiz') || $user->roles->contains('slug', 'sst.funcionario') || $user->roles->contains('slug', 'sst.admin') || $user->hasSuperAdmin()) {
            $lesiones = TipoEvento::where('tipo_categoria', 'lesion')
                ->where('estado', 'activo')
                ->orderBy('nombre', 'asc')
                ->get();

            return view('sst::aprendiz.tipos_eventos.Lesiones', compact('lesiones'));
        }

        abort(403, 'Acceso denegado: No tienes permisos para consultar este recurso en el módulo SST.');
    }

    /**
     * Registra una nueva lesión desde el panel de Aprendiz
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'modulo_pertenece' => 'nullable|string|max:150',
        ]);

        $validated['tipo_categoria'] = 'lesion';
        $validated['estado'] = 'activo';
        $validated['notificaciones'] = true;

        TipoEvento::create($validated);

        return redirect()->route('SST.aprendiz.tipos_eventos.lesiones.index')
            ->with('success', 'Lesión registrada correctamente.');
    }
}

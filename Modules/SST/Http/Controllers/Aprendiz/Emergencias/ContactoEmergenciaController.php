<?php

namespace Modules\SST\Http\Controllers\Aprendiz\Emergencias;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Modules\SST\Entities\ContactoEmergencia;

class ContactoEmergenciaController extends Controller
{
    /**
     * Vista de Contactos de Emergencia para el Aprendiz SST
     * Muestra los contactos activos registrados por el administrador.
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login', ['redirect' => route('SST.aprendiz.emergencias.contactos')]);
        }

        /** @var User $user */
        $user = Auth::user();

        if ($user->roles->contains('slug', 'sst.aprendiz') || $user->roles->contains('slug', 'sst.funcionario') || $user->roles->contains('slug', 'sst.admin') || $user->hasSuperAdmin()) {

            $contactos = ContactoEmergencia::where('estado', 'activo')
                ->orderBy('nombre', 'asc')
                ->get();

            return view('sst::aprendiz.emergencias.Contacto_emergencias', compact('contactos'));
        }

        abort(403, 'Acceso denegado: No tienes permisos para consultar este recurso en el módulo SST.');
    }
}

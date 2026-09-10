<?php

namespace Modules\SST\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
class SSTController extends Controller
{
    /**
     * Página de Bienvenida / Landing SST
     */
    public function welcome()
    {
        return view('sst::welcome');
    }

    /**
     * Entrada inteligente: redirige al Dashboard según el Rol del usuario
     */
    public function dashboard()
    {
        // 1. Si no ha iniciado sesión, lo manda a loguearse
        if (!Auth::check()) {
            return redirect()->route('login', ['redirect' => route('SST.dashboard')]);
        }

        /** @var User $user */
        $user = Auth::user();

        // 2. Si tiene el rol de Aprendiz (o Funcionario por compatibilidad)
        if ($user->roles->contains('slug', 'sst.aprendiz') || $user->roles->contains('slug', 'sst.funcionario')) {
            return redirect()->route('SST.aprendiz.dashboard');
        }

        // 3. Si tiene el rol de Administrador o es Superadmin
        if ($user->roles->contains('slug', 'sst.admin') || $user->hasSuperAdmin()) {
            return redirect()->route('SST.admin.dashboard');
        }

        // 4. Si no tiene rol asignado en SST
        return redirect()->route('SST.welcome')->with('error', 'Tu usuario no tiene roles asignados en el módulo SST.');
    }

    /**
     * Dashboard / Panel del Administrador SST
     */
    public function adminDashboard()
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user && ($user->roles->contains('slug', 'sst.admin') || $user->hasSuperAdmin())) {
            return view('sst::admin.dashboard');
        }

        return redirect()->route('SST.welcome')->with('error', 'No tienes permisos de Administrador en SST.');
    }

    /**
     * Dashboard / Panel del Aprendiz SST
     */
    public function aprendizDashboard()
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user && ($user->roles->contains('slug', 'sst.aprendiz') || $user->roles->contains('slug', 'sst.funcionario') || $user->roles->contains('slug', 'sst.admin') || $user->hasSuperAdmin())) {
            return view('sst::aprendiz.dashboard');
        }

        return redirect()->route('SST.welcome')->with('error', 'No tienes permisos de Aprendiz en SST.');
    }
}

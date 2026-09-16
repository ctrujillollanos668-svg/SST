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
            return redirect()->route('SST.aprendiz.definiciones');
        }

        // 3. Si tiene el rol de Administrador o es Superadmin
        if ($user->roles->contains('slug', 'sst.admin') || $user->hasSuperAdmin()) {
            return redirect()->route('SST.admin.dashboard');
        }

        // 4. Si no tiene rol asignado en SST
        abort(403, 'Acceso denegado: Tu usuario no tiene roles asignados en el módulo SST.');
    }
}

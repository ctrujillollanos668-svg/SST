<?php

namespace Modules\SST\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AdminController extends Controller
{
    /**
     * Dashboard / Panel del Administrador SST
     */
    public function dashboard()
    {
        if (!Auth::check()) {
            return redirect()->route('login', ['redirect' => route('SST.admin.dashboard')]);
        }

        /** @var User $user */
        $user = Auth::user();

        if ($user->roles->contains('slug', 'sst.admin') || $user->hasSuperAdmin()) {
            return view('sst::admin.dashboard');
        }

        abort(403, 'Acceso denegado: No tienes permisos de Administrador en el módulo SST.');
    }
}

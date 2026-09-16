<?php

namespace Modules\SST\Http\Controllers\Aprendiz;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AprendizController extends Controller
{
    /**
     * Dashboard / Panel del Aprendiz SST
     */
    public function dashboard()
    {
        if (!Auth::check()) {
            return redirect()->route('login', ['redirect' => route('SST.aprendiz.dashboard')]);
        }

        /** @var User $user */
        $user = Auth::user();

        if ($user->roles->contains('slug', 'sst.aprendiz') || $user->roles->contains('slug', 'sst.funcionario') || $user->roles->contains('slug', 'sst.admin') || $user->hasSuperAdmin()) {
            return redirect()->route('SST.aprendiz.definiciones');
        }

        abort(403, 'Acceso denegado: No tienes permisos de Aprendiz en el módulo SST.');
    }
}

<?php

namespace Modules\SST\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SSTAprendizMiddleware
{
    /**
     * Handle an incoming request for SST Aprendiz area.
     */
    public function handle(Request $request, Closure $next)
    {
        // 1. Verificar si está autenticado
        if (!Auth::check()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No autenticado. Por favor inicia sesión.'
                ], 401);
            }
            return redirect()->route('login', ['redirect' => $request->fullUrl()]);
        }

        $user = Auth::user();

        // 2. Verificar si tiene rol de Aprendiz, Administrador SST o es SuperAdmin
        if ($user->roles && (
            $user->roles->contains('slug', 'sst.aprendiz') || 
            $user->roles->contains('slug', 'sst.funcionario') || 
            $user->roles->contains('slug', 'sst.admin') || 
            $user->hasSuperAdmin()
        )) {
            return $next($request);
        }

        // 3. Si no tiene ninguno de los roles requeridos
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Acceso denegado: Se requiere rol en el módulo SST.'
            ], 403);
        }

        abort(403, 'Acceso denegado: No tienes permisos para acceder a esta sección en el módulo SST.');
    }
}

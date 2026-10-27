<?php

namespace Modules\SST\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SSTAdminMiddleware
{
    /**
     * Handle an incoming request for SST Admin area.
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

        // 2. Verificar si tiene rol de administrador SST o es SuperAdmin
        if ($user->roles && ($user->roles->contains('slug', 'sst.admin') || $user->hasSuperAdmin())) {
            return $next($request);
        }

        // 3. Si no tiene el rol, denegar acceso (403)
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Acceso denegado: Se requiere rol de Administrador SST.'
            ], 403);
        }

        abort(403, 'Acceso denegado: No tienes permisos de Administrador en el módulo SST.');
    }
}

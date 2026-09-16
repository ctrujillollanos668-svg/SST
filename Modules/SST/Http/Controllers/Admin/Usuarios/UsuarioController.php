<?php

namespace Modules\SST\Http\Controllers\Admin\Usuarios;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Modules\SICA\Entities\Person;
use Modules\SICA\Entities\Role;

class UsuarioController extends Controller
{
    /**
     * Muestra el listado de usuarios reales y sus roles en SST.
     */
    public function index(Request $request)
    {
        $query = User::with(['person', 'roles']);

        if ($request->filled('role')) {
            $roleFilter = $request->role;
            $query->whereHas('roles', function ($q) use ($roleFilter) {
                $q->where('name', 'like', "%{$roleFilter}%")
                  ->orWhere('slug', 'like', "%{$roleFilter}%");
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nickname', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('person', function ($qp) use ($search) {
                      $qp->where('first_name', 'like', "%{$search}%")
                         ->orWhere('first_last_name', 'like', "%{$search}%")
                         ->orWhere('document_number', 'like', "%{$search}%");
                  });
            });
        }

        $usuarios = $query->orderBy('nickname', 'asc')->paginate(15)->withQueryString();
        $allUsers = User::with('person')->orderBy('nickname', 'asc')->get();
        $roles = Role::orderBy('name', 'asc')->get();

        $totalUsuarios = User::count();
        $conRolSST = User::whereHas('roles')->count();
        $totalRoles = Role::count();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $usuarios,
                'roles' => $roles
            ]);
        }

        return view('sst::admin.usuarios.Registrar_usuario', compact('usuarios', 'allUsers', 'roles', 'totalUsuarios', 'conRolSST', 'totalRoles'));
    }

    /**
     * Asigna un rol a un usuario en el sistema.
     */
    public function assignRole(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id'
        ]);

        $user = User::findOrFail($validated['user_id']);
        $user->roles()->syncWithoutDetaching([$validated['role_id']]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Rol asignado con éxito en el sistema.',
                'data' => $user->load('roles', 'person')
            ]);
        }

        return redirect()->route('SST.admin.usuarios.index')
            ->with('success', 'Rol asignado con éxito al usuario.');
    }

    /**
     * Remueve un rol asignado al usuario.
     */
    public function removeRole(Request $request, $userId, $roleId)
    {
        $user = User::findOrFail($userId);
        $user->roles()->detach($roleId);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Rol removido correctamente.'
            ]);
        }

        return redirect()->route('SST.admin.usuarios.index')
            ->with('success', 'Rol removido correctamente.');
    }
}

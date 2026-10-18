<?php

namespace Modules\SST\Http\Controllers\Admin\Usuarios;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Modules\SICA\Entities\Person;
use Modules\SICA\Entities\Role;

class RegistrarUsuarioController extends Controller
{
    /**
     * Muestra el listado de usuarios reales y sus roles en SST.
     */
    public function index(Request $request)
    {
        // Filtrar exclusivamente usuarios que tengan asignado el rol de Aprendiz SST
        $query = User::with(['person', 'roles'])
            ->whereHas('roles', function ($q) {
                $q->where('slug', 'sst.aprendiz');
            });

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

        $usuarios = $query->orderBy('nickname', 'asc')->paginate(10)->withQueryString();
        $allUsers = User::with('person')->orderBy('nickname', 'asc')->get();
        $roles = Role::where('slug', 'sst.aprendiz')
            ->orWhere('slug', 'like', 'sst.%')
            ->orWhere('app_id', 30)
            ->orderBy('name', 'asc')
            ->get();

        $totalUsuarios = User::whereHas('roles', function ($q) {
            $q->where('slug', 'sst.aprendiz');
        })->count();
        $conRolSST = $totalUsuarios;
        $totalRoles = $roles->count();

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

    /**
     * Actualiza las credenciales y datos de relevo de un usuario en SST.
     */
    public function updateCredentials(Request $request, $id)
    {
        $user = User::with('person', 'roles')->findOrFail($id);

        $validated = $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
            'nickname' => 'nullable|string|max:50',
            'first_name' => 'nullable|string|max:100',
            'first_last_name' => 'nullable|string|max:100',
            'document_number' => 'nullable|string|max:30',
            'password' => 'nullable|string|min:6|confirmed',
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico ingresado no es válido.',
            'email.unique' => 'Este correo ya pertenece a otro usuario registrado.',
            'password.min' => 'La nueva contraseña debe tener como mínimo 6 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
        ]);

        // 1. Actualizar datos del Usuario en Users
        $user->email = $validated['email'];
        if (!empty($validated['nickname'])) {
            $user->nickname = $validated['nickname'];
            $user->name = $validated['nickname'];
        }

        if (!empty($validated['password'])) {
            $user->password = \Illuminate\Support\Facades\Hash::make($validated['password']);
        }
        $user->save();

        // 2. Actualizar o Vincular Persona si corresponde
        if ($user->person) {
            $person = $user->person;
            if (!empty($validated['first_name'])) {
                $person->first_name = mb_strtoupper($validated['first_name']);
            }
            if (!empty($validated['first_last_name'])) {
                $person->first_last_name = mb_strtoupper($validated['first_last_name']);
            }
            if (!empty($validated['document_number'])) {
                $person->document_number = $validated['document_number'];
            }
            $person->save();
        }

        // 3. Si se solicita asegurar el rol sst.aprendiz
        if ($request->has('assign_sst_aprendiz') && $request->boolean('assign_sst_aprendiz')) {
            $rolAprendiz = Role::where('slug', 'sst.aprendiz')->first();
            if ($rolAprendiz) {
                $user->roles()->syncWithoutDetaching([$rolAprendiz->id]);
            }
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Credenciales actualizadas exitosamente.',
                'data' => $user->load('person', 'roles')
            ]);
        }

        return redirect()->route('SST.admin.usuarios.index')
            ->with('success', "Credenciales de {$user->full_name} actualizadas correctamente para el relevo de fase.");
    }

    /**
     * Permite al aprendiz en sesión cambiar su propia contraseña.
     */
    public function updateMyPassword(Request $request)
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        if (!$user) {
            abort(403, 'Sesión no iniciada.');
        }

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ], [
            'current_password.required' => 'Debes ingresar tu contraseña actual.',
            'new_password.required' => 'Debes ingresar la nueva contraseña.',
            'new_password.min' => 'La nueva contraseña debe tener mínimo 6 caracteres.',
            'new_password.confirmed' => 'La confirmación de la contraseña no coincide.',
        ]);

        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual no es correcta.'])
                         ->with('error', 'La contraseña actual no coincide.');
        }

        $user->password = \Illuminate\Support\Facades\Hash::make($request->new_password);
        $user->save();

        return back()->with('success', '¡Tu contraseña ha sido actualizada con éxito!');
    }
}

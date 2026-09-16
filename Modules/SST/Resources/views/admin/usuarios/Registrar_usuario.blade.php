@extends('sst::components.layouts.admin')

@section('title', 'Usuarios y Roles • SST')

@section('content')
<style>
    @keyframes modalPopIn {
        0% { opacity: 0; transform: scale(0.95) translateY(8px); }
        100% { opacity: 1; transform: scale(1) translateY(0); }
    }
    .animate-modal-pop {
        animation: modalPopIn 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>

<div class="space-y-6 max-w-7xl mx-auto">

    <!-- Mensaje Flash -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200/80 text-emerald-800 flex items-center justify-between shadow-xs animate-in fade-in duration-200">
            <div class="flex items-center gap-3">
                <span class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold">
                    <i class="fa-solid fa-check text-sm"></i>
                </span>
                <span class="text-xs sm:text-sm font-semibold">{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 p-1 cursor-pointer">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
    @endif

    <!-- 1. Encabezado de la Sección -->
    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4 transition-all duration-200">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center border border-orange-200/80 shadow-xs">
                    <i class="fa-solid fa-users text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Gestión de Usuarios y Roles SST</h1>
                    <p class="text-sm text-slate-500 font-medium mt-0.5">Asignación de roles, permisos, brigadistas y administradores del SG-SST.</p>
                </div>
            </div>
        </div>

        <button onclick="openRegisterModal()" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-orange-50 hover:bg-orange-100/80 active:bg-orange-200/70 text-orange-700 text-sm font-bold shadow-xs transition-all duration-200 cursor-pointer group border border-orange-200/90 hover:border-orange-300">
            <i class="fa-solid fa-plus text-xs group-hover:rotate-90 transition-transform duration-300"></i>
            <span>Vincular / Asignar Rol</span>
        </button>
    </div>

    <!-- 2. Tarjetas de Resumen Dinámicas -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center font-bold text-lg shrink-0">
                <i class="fa-solid fa-users"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Usuarios</p>
                <h4 class="text-xl font-extrabold text-slate-800">{{ $totalUsuarios ?? 0 }}</h4>
            </div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg shrink-0">
                <i class="fa-solid fa-user-shield"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Con Roles Asignados</p>
                <h4 class="text-xl font-extrabold text-slate-800">{{ $conRolSST ?? 0 }}</h4>
            </div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg shrink-0">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Roles Disponibles</p>
                <h4 class="text-xl font-extrabold text-slate-800">{{ $totalRoles ?? 0 }}</h4>
            </div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-lg shrink-0">
                <i class="fa-solid fa-server"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Estado Conexión</p>
                <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600 mt-1">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Base de Datos
                </span>
            </div>
        </div>
    </div>

    <!-- 3. Filtros y Búsqueda -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="relative w-full md:w-96">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
            <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Buscar por nombre, correo o documento..." 
                class="w-full pl-9 pr-4 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-medium transition">
        </div>

        <div class="flex items-center gap-2 w-full md:w-auto">
            <label class="text-xs font-bold text-slate-500 whitespace-nowrap"><i class="fa-solid fa-filter text-orange-500 mr-1"></i> Filtrar rol:</label>
            <select id="roleFilter" onchange="filterTable()" class="px-3.5 py-2 text-xs font-bold rounded-xl bg-slate-50 border border-slate-200 text-slate-700 focus:outline-none focus:border-orange-500 cursor-pointer">
                <option value="">Todos los roles</option>
                @foreach($roles as $role)
                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- 4. Tabla de Usuarios Conectada a Base de Datos -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="usuariosTable">
                <thead>
                    <tr class="bg-slate-50/90 border-b border-slate-200/80 text-slate-500 text-[11px] font-bold uppercase tracking-wider">
                        <th class="py-4 px-6">USUARIO / FUNCIONARIO</th>
                        <th class="py-4 px-6">CORREO INSTITUCIONAL</th>
                        <th class="py-4 px-6 text-center">DOCUMENTO</th>
                        <th class="py-4 px-6 text-center">ROL(ES) ASIGNADOS</th>
                        <th class="py-4 px-6 text-center">ESTADO</th>
                        <th class="py-4 px-6 text-center">ACCIONES</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 text-xs font-medium">
                    @forelse($usuarios as $user)
                        @php
                            $initials = $user->initials ?? 'US';
                            $fullName = $user->full_name;
                            $doc = $user->person ? $user->person->document_number : 'S/D';
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors user-row">
                            <td class="py-4 px-6 font-bold text-slate-900 flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-orange-600 text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-xs">
                                    {{ $initials }}
                                </div>
                                <div class="truncate max-w-xs">
                                    <p class="font-bold text-slate-900 leading-tight">{{ $fullName }}</p>
                                    <span class="text-[10px] text-slate-400">{{ $user->nickname ?? $user->name }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-slate-600 font-mono text-[11px]">{{ $user->email }}</td>
                            <td class="py-4 px-6 text-center font-mono font-semibold">{{ $doc }}</td>
                            <td class="py-4 px-6 text-center">
                                <div class="flex flex-wrap items-center justify-center gap-1.5 role-container">
                                    @forelse($user->roles as $r)
                                        <span class="inline-flex items-center gap-1.5 pl-2.5 pr-1.5 py-0.5 rounded-full text-[10px] font-bold bg-orange-50 text-orange-800 border border-orange-200 shadow-2xs group">
                                            <i class="fa-solid fa-shield-halved text-[8px] text-orange-600"></i>
                                            <span>{{ $r->name }}</span>
                                            <button type="button" onclick="confirmRemoveRole({{ $user->id }}, {{ $r->id }}, '{{ addslashes($r->name) }}', '{{ addslashes($fullName) }}')" class="w-3.5 h-3.5 rounded-full bg-orange-200/70 hover:bg-rose-500 hover:text-white flex items-center justify-center transition cursor-pointer text-[8px]" title="Remover rol {{ $r->name }}">
                                                <i class="fa-solid fa-xmark"></i>
                                            </button>
                                        </span>
                                    @empty
                                        <span class="text-[11px] text-slate-400 italic">Sin rol asignado</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Activo
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center whitespace-nowrap">
                                <button onclick="openAssignModalForUser({{ $user->id }}, '{{ addslashes($fullName) }}')" 
                                    class="px-2.5 py-1 text-[11px] font-bold text-orange-700 bg-orange-50 hover:bg-orange-100 border border-orange-200 rounded-lg transition cursor-pointer flex items-center gap-1 mx-auto">
                                    <i class="fa-solid fa-plus text-[9px]"></i>
                                    <span>Asignar Rol</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-slate-400">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-2">
                                    <i class="fa-solid fa-users-slash text-lg"></i>
                                </div>
                                <p class="text-sm font-semibold">No se encontraron usuarios registrados.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($usuarios) && $usuarios->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $usuarios->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Modal Vincular Usuario / Asignar Rol Real -->
<div id="modalRegister" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-100 animate-modal-pop">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
            <h3 class="font-extrabold text-slate-900 text-base flex items-center gap-2">
                <i class="fa-solid fa-user-plus text-orange-600"></i>
                <span id="modalTitle">Asignar Rol en SST</span>
            </h3>
            <button onclick="closeRegisterModal()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition cursor-pointer">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>

        <form action="{{ route('SST.admin.usuarios.assign_role') }}" method="POST" class="space-y-4">
            @csrf
            
            <!-- Selector de Usuario -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Usuario / Funcionario *</label>
                <select name="user_id" id="modalUserId" required class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 text-slate-800 focus:outline-none focus:border-orange-500 font-semibold cursor-pointer">
                    <option value="">-- Selecciona un usuario --</option>
                    @foreach($allUsers ?? $usuarios as $u)
                        <option value="{{ $u->id }}">{{ $u->full_name }} ({{ $u->email }})</option>
                    @endforeach
                </select>
            </div>

            <!-- Selector de Rol -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Rol a Asignar en SST *</label>
                <select name="role_id" required class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 text-slate-800 focus:outline-none focus:border-orange-500 font-semibold cursor-pointer">
                    <option value="">-- Selecciona un rol --</option>
                    @foreach($roles as $r)
                        <option value="{{ $r->id }}">{{ $r->name }} ({{ $r->slug }})</option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeRegisterModal()" class="px-4 py-2 text-xs font-bold text-slate-600 rounded-xl hover:bg-slate-100 transition cursor-pointer">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-orange-600 hover:bg-orange-700 active:bg-orange-800 rounded-xl shadow-xs transition cursor-pointer flex items-center gap-1.5">
                    <i class="fa-solid fa-check text-xs"></i>
                    <span>Guardar Asignación</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Formulario Oculto para Eliminar Roles -->
<form id="formRemoveRole" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

<script>
    function openRegisterModal() {
        document.getElementById('modalTitle').innerText = 'Asignar Rol en SST';
        document.getElementById('modalUserId').value = '';
        document.getElementById('modalRegister').classList.remove('hidden');
    }

    function openAssignModalForUser(userId, userName) {
        document.getElementById('modalTitle').innerText = `Asignar Rol a ${userName}`;
        const select = document.getElementById('modalUserId');
        if (select) select.value = userId;
        document.getElementById('modalRegister').classList.remove('hidden');
    }

    function closeRegisterModal() {
        document.getElementById('modalRegister').classList.add('hidden');
    }

    function confirmRemoveRole(userId, roleId, roleName, userName) {
        if (confirm(`¿Estás seguro de remover el rol "${roleName}" del usuario "${userName}"?`)) {
            const form = document.getElementById('formRemoveRole');
            form.action = `{{ url('Sst/admin/usuarios/remove-role') }}/${userId}/${roleId}`;
            form.submit();
        }
    }

    function filterTable() {
        const query = document.getElementById('searchInput').value.toLowerCase();
        const roleQuery = document.getElementById('roleFilter').value.toLowerCase();
        const rows = document.querySelectorAll('#usuariosTable tbody tr.user-row');

        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            const roleContainer = row.querySelector('.role-container');
            const roleText = roleContainer ? roleContainer.innerText.toLowerCase() : '';

            const matchesText = text.includes(query);
            const matchesRole = !roleQuery || roleText.includes(roleQuery);

            row.style.display = (matchesText && matchesRole) ? '' : 'none';
        });
    }
</script>
@endsection

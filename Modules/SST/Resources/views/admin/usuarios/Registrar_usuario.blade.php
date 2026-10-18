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

    <!-- Mensajes Flash y Errores -->
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
    @if(session('error') || $errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200/80 text-rose-800 flex items-center justify-between shadow-xs animate-in fade-in duration-200">
            <div class="flex items-center gap-3">
                <span class="w-8 h-8 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center font-bold">
                    <i class="fa-solid fa-circle-exclamation text-sm"></i>
                </span>
                <div class="text-xs sm:text-sm font-semibold">
                    {{ session('error') ?? 'Ocurrieron errores al validar la información:' }}
                    @if($errors->any())
                        <ul class="list-disc list-inside text-xs mt-1 text-rose-700 font-medium">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
            <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700 p-1 cursor-pointer">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
    @endif

    <!-- 1. Encabezado de la Sección -->
    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4 transition-all duration-200">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center border border-orange-200/80 shadow-xs">
                    <i class="fa-solid fa-user-graduate text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Gestión del Aprendiz SST</h1>
                    <p class="text-sm text-slate-500 font-medium mt-0.5">Control de credenciales, acceso y relevo de fase del Aprendiz en SG-SST.</p>
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
                <i class="fa-solid fa-user-graduate"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Aprendiz SST</p>
                <h4 class="text-xl font-extrabold text-slate-800">{{ $totalUsuarios ?? 0 }} Activo</h4>
            </div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg shrink-0">
                <i class="fa-solid fa-user-shield"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Rol Asignado</p>
                <h4 class="text-sm font-extrabold text-slate-800">Aprendiz SST</h4>
            </div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg shrink-0">
                <i class="fa-solid fa-arrows-rotate"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Relevo de Fase</p>
                <h4 class="text-sm font-extrabold text-blue-600">Habilitado</h4>
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
            <span class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-orange-50 border border-orange-200/80 text-orange-700 text-xs font-bold shadow-2xs">
                <i class="fa-solid fa-user-graduate text-xs"></i>
                <span>Listando únicamente: Aprendiz SST</span>
            </span>
        </div>
    </div>

    <!-- 4. Tabla de Usuarios Conectada a Base de Datos -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="usuariosTable">
                <thead>
                    <tr class="bg-slate-50/90 border-b border-slate-200/80 text-slate-500 text-[11px] font-bold uppercase tracking-wider">
                        <th class="py-4 px-6">USUARIO / APRENDIZ SST</th>
                        <th class="py-4 px-6">CORREO INSTITUCIONAL</th>
                        <th class="py-4 px-6 text-center">DOCUMENTO</th>
                        <th class="py-4 px-6 text-center">ROL ASIGNADO</th>
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
                                <div class="flex items-center justify-center gap-2">
                                    @php
                                        $credData = [
                                            'id' => $user->id,
                                            'fullName' => $fullName,
                                            'nickname' => $user->nickname ?? $user->name ?? '',
                                            'firstName' => $user->person->first_name ?? '',
                                            'firstLastName' => $user->person->first_last_name ?? '',
                                            'document' => $doc,
                                            'email' => $user->email,
                                            'hasSstAprendiz' => $user->roles->contains('slug', 'sst.aprendiz')
                                        ];
                                    @endphp
                                    <button type="button" 
                                        data-user="{{ base64_encode(json_encode($credData)) }}"
                                        onclick="openCredentialsModal(this)" 
                                        class="px-2.5 py-1.5 text-[11px] font-bold text-slate-700 bg-slate-100 hover:bg-orange-50 hover:text-orange-700 border border-slate-200 hover:border-orange-200 rounded-xl transition cursor-pointer flex items-center gap-1.5 shadow-2xs"
                                        title="Editar correo, contraseña o traspasar el puesto al nuevo aprendiz">
                                        <i class="fa-solid fa-key text-[10px] text-amber-500"></i>
                                        <span>Credenciales / Relevo</span>
                                    </button>

                                    <button type="button" 
                                        onclick="openAssignModalForUser({{ $user->id }}, '{{ addslashes($fullName) }}')" 
                                        class="px-2.5 py-1.5 text-[11px] font-bold text-orange-700 bg-orange-50 hover:bg-orange-100 border border-orange-200 rounded-xl transition cursor-pointer flex items-center gap-1 shadow-2xs"
                                        title="Asignar rol en SST">
                                        <i class="fa-solid fa-plus text-[9px]"></i>
                                        <span>Rol</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-slate-400">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-2">
                                    <i class="fa-solid fa-user-slash text-lg"></i>
                                </div>
                                <p class="text-sm font-semibold">No se encontró ningún aprendiz asignado a SST actualmente.</p>
                                <p class="text-xs text-slate-400 mt-1">Puedes vincular un aprendiz usando el botón "+ Vincular / Asignar Rol".</p>
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

<!-- Modal 2: Relevo de Puesto y Gestión de Credenciales SST -->
<div id="modalCredentials" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-100 animate-modal-pop max-h-[92vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-id-card-clip"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base leading-tight">Relevo de Puesto y Credenciales</h3>
                    <p class="text-[11px] text-slate-500">Actualizar credenciales de acceso para el relevo de fase formativa</p>
                </div>
            </div>
            <button onclick="closeCredentialsModal()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition cursor-pointer">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>

        <div class="mb-4 p-3.5 rounded-xl bg-amber-50 border border-amber-200/80 text-amber-900 text-xs flex items-start gap-2.5 leading-relaxed">
            <i class="fa-solid fa-lightbulb text-amber-600 mt-0.5 text-sm shrink-0"></i>
            <div>
                <strong class="font-bold block text-amber-950 mb-0.5">Relevo de Fase del Aprendiz SST:</strong>
                <span>Al terminar la etapa de Yuliana (o del encargado actual), puedes colocar aquí los nombres, correo institucional y la nueva contraseña del nuevo aprendiz que asume el puesto en SENA Empresa.</span>
            </div>
        </div>

        <form id="formCredentials" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Nombres y Apellidos -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nombres del Aprendiz / Titular</label>
                    <input type="text" name="first_name" id="cred_first_name" placeholder="Ej: CARLOS ANDRES" class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-semibold uppercase">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Apellidos del Aprendiz</label>
                    <input type="text" name="first_last_name" id="cred_first_last_name" placeholder="Ej: PEREZ GOMEZ" class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-semibold uppercase">
                </div>
            </div>

            <!-- Documento y Usuario (Nickname) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Documento de Identidad (CC/TI)</label>
                    <input type="text" name="document_number" id="cred_document_number" placeholder="Ej: 1075283921" class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-semibold font-mono">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nombre de Usuario (Nickname)</label>
                    <input type="text" name="nickname" id="cred_nickname" placeholder="Ej: CarlosPerez" class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-semibold">
                </div>
            </div>

            <!-- Correo de Acceso -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1 flex items-center justify-between">
                    <span>Correo Electrónico de Inicio de Sesión *</span>
                    <span class="text-[10px] text-orange-600 font-semibold">Credencial de Entrada</span>
                </label>
                <div class="relative">
                    <i class="fa-solid fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="email" name="email" id="cred_email" required placeholder="correo.aprendiz@sena.edu.co" class="w-full pl-9 pr-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-semibold font-mono text-slate-800">
                </div>
            </div>

            <!-- Nueva Contraseña -->
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="block text-xs font-bold text-slate-700">Nueva Contraseña de Acceso</label>
                    <button type="button" onclick="generateRandomPassword()" class="text-[11px] font-bold text-orange-600 hover:text-orange-700 flex items-center gap-1 cursor-pointer">
                        <i class="fa-solid fa-wand-magic-sparkles text-[10px]"></i> Generar clave segura
                    </button>
                </div>
                <div class="relative">
                    <i class="fa-solid fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="password" name="password" id="cred_password_input" placeholder="Ingresa mínimo 6 caracteres..." class="w-full pl-9 pr-10 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-semibold text-slate-800 font-mono">
                    <button type="button" onclick="togglePasswordVisibility('cred_password_input', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 cursor-pointer p-1" title="Ver contraseña">
                        <i class="fa-solid fa-eye text-xs"></i>
                    </button>
                </div>
                <span class="text-[10px] text-slate-400 block mt-1">Dejar en blanco si no deseas cambiar la contraseña actual del usuario.</span>
            </div>

            <!-- Confirmar Contraseña -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Confirmar Nueva Contraseña</label>
                <div class="relative">
                    <i class="fa-solid fa-lock-open absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="password" name="password_confirmation" id="cred_password_confirmation" placeholder="Repite la nueva contraseña..." class="w-full pl-9 pr-10 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-semibold text-slate-800 font-mono">
                    <button type="button" onclick="togglePasswordVisibility('cred_password_confirmation', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 cursor-pointer p-1" title="Ver contraseña">
                        <i class="fa-solid fa-eye text-xs"></i>
                    </button>
                </div>
            </div>

            <!-- Asegurar Rol Aprendiz SST -->
            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/80 flex items-center justify-between">
                <div class="pr-2">
                    <span class="text-xs font-bold text-slate-800 block">Rol Aprendiz SST</span>
                    <span class="text-[10px] text-slate-500">Asegura que el usuario tenga acceso al módulo Aprendiz SST (sst.aprendiz)</span>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="assign_sst_aprendiz" value="1" id="cred_assign_sst_aprendiz" class="sr-only peer" checked>
                    <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-orange-600"></div>
                </label>
            </div>

            <!-- Botones -->
            <div class="flex justify-end gap-2.5 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeCredentialsModal()" class="px-4 py-2 text-xs font-bold text-slate-600 rounded-xl hover:bg-slate-100 transition cursor-pointer">
                    Cancelar
                </button>
                <button type="submit" class="px-5 py-2.5 text-xs font-bold text-white bg-orange-600 hover:bg-orange-700 active:bg-orange-800 rounded-xl shadow-xs transition cursor-pointer flex items-center gap-1.5">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    <span>Guardar Credenciales y Relevo</span>
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

    function openCredentialsModal(btnOrData) {
        let data = btnOrData;
        if (btnOrData instanceof HTMLElement) {
            try {
                data = JSON.parse(atob(btnOrData.dataset.user));
            } catch (e) {
                console.error('Error decodificando datos del usuario', e);
                return;
            }
        }
        const form = document.getElementById('formCredentials');
        form.action = "{{ url('Sst/admin/usuarios/update-credentials') }}/" + data.id;

        document.getElementById('cred_first_name').value = data.firstName || '';
        document.getElementById('cred_first_last_name').value = data.firstLastName || '';
        document.getElementById('cred_document_number').value = (data.document && data.document !== 'S/D') ? data.document : '';
        document.getElementById('cred_nickname').value = data.nickname || '';
        document.getElementById('cred_email').value = data.email || '';
        document.getElementById('cred_password_input').value = '';
        document.getElementById('cred_password_confirmation').value = '';
        
        const chkSst = document.getElementById('cred_assign_sst_aprendiz');
        if (chkSst) {
            chkSst.checked = data.hasSstAprendiz ? true : false;
        }

        document.getElementById('modalCredentials').classList.remove('hidden');
    }

    function closeCredentialsModal() {
        document.getElementById('modalCredentials').classList.add('hidden');
    }

    function togglePasswordVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        if (!input) return;
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            if (icon) icon.className = 'fa-solid fa-eye-slash text-xs';
        } else {
            input.type = 'password';
            if (icon) icon.className = 'fa-solid fa-eye text-xs';
        }
    }

    function generateRandomPassword() {
        const num = Math.floor(1000 + Math.random() * 9000);
        const pass = 'Sena' + num + '*';
        const passInput = document.getElementById('cred_password_input');
        const confirmInput = document.getElementById('cred_password_confirmation');
        if (passInput) {
            passInput.value = pass;
            passInput.type = 'text';
        }
        if (confirmInput) {
            confirmInput.value = pass;
            confirmInput.type = 'text';
        }
        alert('Contraseña sugerida generada: ' + pass + '\n(Asegúrate de copiarla para entregársela al nuevo aprendiz)');
    }

    function confirmRemoveRole(userId, roleId, roleName, userName) {
        if (confirm(`¿Estás seguro de remover el rol "${roleName}" del usuario "${userName}"?`)) {
            const form = document.getElementById('formRemoveRole');
            form.action = `{{ url('Sst/admin/usuarios/remove-role') }}/${userId}/${roleId}`;
            form.submit();
        }
    }

    function filterTable() {
        const query = (document.getElementById('searchInput')?.value || '').toLowerCase();
        const rows = document.querySelectorAll('#usuariosTable tbody tr.user-row');

        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            const matchesText = text.includes(query);
            row.style.display = matchesText ? '' : 'none';
        });
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeRegisterModal();
            closeCredentialsModal();
        }
    });
</script>
@endsection

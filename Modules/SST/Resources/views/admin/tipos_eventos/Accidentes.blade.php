@extends('sst::components.layouts.admin')

@section('title', 'Tipos de Eventos - Accidentes • SST')

@section('content')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<div class="space-y-6 max-w-7xl mx-auto">

    <!-- Mensaje Flash de Éxito -->
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
    <div class="bg-white p-6 sm:p-7 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4 transition-all duration-200">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center border border-orange-200/80 shadow-xs">
                <i class="fa-solid fa-triangle-exclamation text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Tipos de Accidentes</h1>
                <p class="text-sm text-slate-500 font-medium mt-0.5">Configuración y administración de catálogos en base de datos.</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button onclick="openRegisterModal()" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-orange-50 hover:bg-orange-100/80 active:bg-orange-200/70 text-orange-700 text-sm font-bold shadow-xs transition-all duration-200 cursor-pointer group border border-orange-200/90 hover:border-orange-300">
                <i class="fa-solid fa-plus text-xs group-hover:rotate-90 transition-transform duration-300"></i>
                <span>Registrar Tipo de Accidente</span>
            </button>
        </div>
    </div>

    <!-- 2. Tabla Principal de Catálogo -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse table-fixed">
                <thead>
                    <tr class="bg-slate-50/90 border-b border-slate-200/80 text-slate-500 text-[11px] font-bold uppercase tracking-wider">
                        <th class="py-4 px-6 w-1/4">NOMBRE</th>
                        <th class="py-4 px-6 w-1/2">DESCRIPCIÓN</th>
                        <th class="py-4 px-6 text-center w-32">ESTADO</th>
                        <th class="py-4 px-6 text-center w-36">ACCIONES</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 text-sm font-medium">
                    @forelse($accidentes as $accidente)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-4 px-6 font-bold text-slate-900 break-words">
                                {{ $accidente->nombre }}
                            </td>
                            <td class="py-4 px-6 text-slate-600 max-w-md">
                                @if($accidente->descripcion)
                                    <p class="line-clamp-2 cursor-pointer hover:text-orange-600 transition-colors" onclick="showFullDesc(event, {{ json_encode($accidente->descripcion) }})" title="Haz clic para ver la descripción completa">
                                        {{ $accidente->descripcion }}
                                    </p>
                                @else
                                    <span class="text-slate-400 italic text-xs">Sin descripción</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center whitespace-nowrap">
                                @if($accidente->estado === 'activo')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Activo
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        Inactivo
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick="openEditModal({{ $accidente->id_tipo_evento }}, {{ json_encode($accidente->nombre) }}, {{ json_encode($accidente->descripcion ?? '') }}, {{ json_encode($accidente->estado) }})" class="w-9 h-9 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-600 border border-amber-200/80 flex items-center justify-center transition-all duration-150 cursor-pointer shadow-xs hover:scale-105" title="Editar">
                                        <i class="fa-regular fa-pen-to-square text-sm"></i>
                                    </button>
                                    <button onclick="openDeleteModal({{ $accidente->id_tipo_evento }}, {{ json_encode($accidente->nombre) }})" class="w-9 h-9 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200/80 flex items-center justify-center transition-all duration-150 cursor-pointer shadow-xs hover:scale-105" title="Eliminar">
                                        <i class="fa-regular fa-trash-can text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 px-6 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400 space-y-2">
                                    <i class="fa-solid fa-folder-open text-3xl text-slate-300"></i>
                                    <p class="text-sm font-semibold text-slate-600">No hay tipos de accidentes registrados aún.</p>
                                    <p class="text-xs text-slate-400">Haz clic en "Registrar Tipo de Accidente" para agregar el primero a la base de datos.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- POPOVER FLOTANTE PARA DESCRIPCIÓN          -->
<!-- ========================================== -->
<div id="descPopover" class="fixed z-50 hidden bg-white border border-slate-200 text-slate-800 p-4 rounded-2xl shadow-xl max-w-md text-xs leading-relaxed animate-in fade-in zoom-in duration-150">
    <div class="flex items-center justify-between gap-3 pb-2 mb-2 border-b border-slate-100">
        <span class="font-bold text-slate-900 flex items-center gap-1.5">
            <i class="fa-solid fa-align-left text-orange-500"></i> Descripción Completa
        </span>
        <button onclick="closeDescPopover()" class="w-6 h-6 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition cursor-pointer">
            <i class="fa-solid fa-xmark text-xs"></i>
        </button>
    </div>
    <p id="descPopoverText" class="text-slate-600 text-xs leading-relaxed"></p>
</div>

<!-- ========================================== -->
<!-- MODALES INTERACTIVOS (CONEXIÓN BD)          -->
<!-- ========================================== -->

<!-- Modal: Registrar -->
<div id="modalRegister" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center hidden p-4">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 w-full max-w-lg overflow-hidden animate-in fade-in zoom-in duration-200">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-plus text-orange-500"></i>
                <span>Registrar Tipo de Accidente</span>
            </h3>
            <button onclick="closeRegisterModal()" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition cursor-pointer">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form action="{{ route('SST.admin.tipos_eventos.accidentes.store') }}" method="POST">
            @csrf
            <div class="p-6 space-y-4 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5">Nombre del Tipo de Accidente *</label>
                    <input type="text" name="nombre" required placeholder="Ej. Caída de diferente nivel / Trabajo en alturas" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 transition text-sm">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5">Descripción</label>
                    <textarea name="descripcion" rows="3" placeholder="Describa el alcance de este tipo de accidente..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 transition text-sm"></textarea>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5">Estado</label>
                    <select name="estado" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 transition text-sm bg-white">
                        <option value="activo" selected>Activo</option>
                        <option value="inactivo">Inactivo</option>
                    </select>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex justify-end gap-2">
                <button type="button" onclick="closeRegisterModal()" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-200/70 transition cursor-pointer">Cancelar</button>
                <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-orange-50 hover:bg-orange-100 active:bg-orange-200/70 text-orange-700 border border-orange-200/90 hover:border-orange-300 shadow-xs transition cursor-pointer">Guardar Registro</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Editar -->
<div id="modalEdit" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center hidden p-4">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 w-full max-w-lg overflow-hidden animate-in fade-in zoom-in duration-200">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                <i class="fa-regular fa-pen-to-square text-orange-600"></i>
                <span>Editar Tipo de Accidente</span>
            </h3>
            <button onclick="closeEditModal()" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition cursor-pointer">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form id="editAccidenteForm" action="" method="POST">
            @csrf
            @method('PUT')
            <div class="p-6 space-y-4 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5">Nombre del Tipo de Accidente *</label>
                    <input type="text" id="editNombreInput" name="nombre" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 transition text-sm">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5">Descripción</label>
                    <textarea id="editDescInput" name="descripcion" rows="3" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 transition text-sm"></textarea>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5">Estado</label>
                    <select id="editEstadoSelect" name="estado" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 transition text-sm bg-white">
                        <option value="activo">Activo</option>
                        <option value="inactivo">Inactivo</option>
                    </select>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex justify-end gap-2">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-200/70 transition cursor-pointer">Cancelar</button>
                <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-orange-50 hover:bg-orange-100 active:bg-orange-200/70 text-orange-700 border border-orange-200/90 hover:border-orange-300 shadow-xs transition cursor-pointer">Actualizar Registro</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Eliminar -->
<div id="modalDelete" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center hidden p-4">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 w-full max-w-md overflow-hidden animate-in fade-in zoom-in duration-200">
        <div class="p-6 text-center">
            <div class="w-14 h-14 bg-rose-50 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-4 border border-rose-100">
                <i class="fa-solid fa-triangle-exclamation text-xl"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-900">¿Eliminar Tipo de Accidente?</h3>
            <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                Estás a punto de eliminar el registro <span id="deleteItemName" class="font-bold text-slate-800"></span>. Esta acción no se puede deshacer.
            </p>
            <form id="deleteAccidenteForm" action="" method="POST" class="mt-6 flex items-center justify-center gap-3">
                @csrf
                @method('DELETE')
                <button type="button" onclick="closeDeleteModal()" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition cursor-pointer">
                    Cancelar
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-rose-600 hover:bg-rose-700 text-white shadow-xs transition cursor-pointer">
                    Sí, Eliminar
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function showFullDesc(event, text) {
        event.stopPropagation();
        const popover = document.getElementById('descPopover');
        const popoverText = document.getElementById('descPopoverText');
        popoverText.innerText = text;

        const rect = event.currentTarget.getBoundingClientRect();
        let top = rect.bottom + 8;
        let left = rect.left;

        const popoverWidth = 380;
        if (left + popoverWidth > window.innerWidth - 20) {
            left = Math.max(10, window.innerWidth - popoverWidth - 20);
        }

        if (top + 160 > window.innerHeight) {
            top = Math.max(10, rect.top - 160);
        }

        popover.style.top = top + 'px';
        popover.style.left = left + 'px';
        popover.classList.remove('hidden');
    }

    function closeDescPopover() {
        document.getElementById('descPopover').classList.add('hidden');
    }

    document.addEventListener('click', function (e) {
        const popover = document.getElementById('descPopover');
        if (popover && !popover.contains(e.target)) {
            popover.classList.add('hidden');
        }
    });

    function openRegisterModal() {
        document.getElementById('modalRegister').classList.remove('hidden');
    }
    function closeRegisterModal() {
        document.getElementById('modalRegister').classList.add('hidden');
    }

    function openEditModal(id, nombre, descripcion, estado) {
        const form = document.getElementById('editAccidenteForm');
        form.action = "{{ url('Sst/admin/tipos-eventos/accidentes') }}/" + id;
        document.getElementById('editNombreInput').value = nombre;
        document.getElementById('editDescInput').value = descripcion;
        document.getElementById('editEstadoSelect').value = estado || 'activo';
        document.getElementById('modalEdit').classList.remove('hidden');
    }
    function closeEditModal() {
        document.getElementById('modalEdit').classList.add('hidden');
    }

    function openDeleteModal(id, nombre) {
        const form = document.getElementById('deleteAccidenteForm');
        form.action = "{{ url('Sst/admin/tipos-eventos/accidentes') }}/" + id;
        document.getElementById('deleteItemName').innerText = `"${nombre}"`;
        document.getElementById('modalDelete').classList.remove('hidden');
    }
    function closeDeleteModal() {
        document.getElementById('modalDelete').classList.add('hidden');
    }
</script>
@endsection

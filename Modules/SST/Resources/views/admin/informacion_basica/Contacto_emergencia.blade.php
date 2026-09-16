@extends('sst::components.layouts.admin')

@section('title', 'Información Básica - Contactos de Emergencia • SST')

@section('content')
<style>
    @keyframes modalPopIn {
        0% { opacity: 0; transform: scale(0.9) translateY(10px); }
        100% { opacity: 1; transform: scale(1) translateY(0); }
    }
    .animate-modal-pop {
        animation: modalPopIn 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
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

    <!-- 1. Encabezado de la Sección y Breadcrumbs -->
    <div class="flex flex-col gap-2">
        <div class="flex items-center gap-2 text-xs font-semibold text-slate-400">
            <span class="text-slate-500">Información Básica</span>
            <i class="fa-solid fa-chevron-right text-[9px]"></i>
            <span class="text-orange-600 font-bold">Contacto de Emergencia</span>
        </div>

        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4 transition-all duration-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center border border-orange-200/80 shadow-xs">
                    <i class="fa-solid fa-phone-volume text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Contactos de Emergencia</h1>
                    <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">Configuración y administración de catálogos en base de datos.</p>
                </div>
            </div>

            <button onclick="openRegisterModal()" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-orange-50 hover:bg-orange-100/80 active:bg-orange-200/70 text-orange-700 text-sm font-bold shadow-xs transition-all duration-200 cursor-pointer group border border-orange-200/90 hover:border-orange-300 shrink-0">
                <i class="fa-solid fa-plus text-xs group-hover:rotate-90 transition-transform duration-300"></i>
                <span>Registrar Contacto de Emergencia</span>
            </button>
        </div>
    </div>

    <!-- 2. Filtros y Búsqueda -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="relative w-full md:w-80">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
            <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Buscar por entidad o teléfono..." class="w-full pl-9 pr-4 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 focus:bg-white transition font-medium">
        </div>
    </div>

    <!-- 3. Tabla Principal de Contactos de Emergencia -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="contactsTable">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-slate-500 text-[11px] font-bold uppercase tracking-wider">
                        <th class="py-4 px-6 w-1/3">NOMBRE / ENTIDAD</th>
                        <th class="py-4 px-6 w-1/4">TELÉFONO</th>
                        <th class="py-4 px-6 w-1/4">DESCRIPCIÓN</th>
                        <th class="py-4 px-6 text-center w-28">ESTADO</th>
                        <th class="py-4 px-6 text-center w-36">ACCIONES</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 text-sm font-medium">
                    @forelse($contactos as $contacto)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-4 px-6 font-bold text-slate-900 break-words">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center font-bold shrink-0 border border-orange-100">
                                        <i class="fa-solid fa-phone text-xs"></i>
                                    </div>
                                    <span class="font-bold text-slate-900">{{ $contacto->nombre }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 font-semibold text-slate-700 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-extrabold text-slate-800">{{ $contacto->telefono }}</span>
                                    <button onclick="copyNumber({{ json_encode($contacto->telefono) }})" class="text-slate-400 hover:text-orange-600 transition" title="Copiar número">
                                        <i class="fa-regular fa-copy text-xs"></i>
                                    </button>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-slate-600 text-xs">
                                {{ $contacto->descripcion ?: 'Sin descripción' }}
                            </td>
                            <td class="py-4 px-6 text-center whitespace-nowrap">
                                @if($contacto->estado === 'activo')
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
                                <div class="flex items-center justify-center gap-1.5">
                                    <button onclick="openEditModal({{ $contacto->id_contacto }}, {{ json_encode($contacto->nombre) }}, {{ json_encode($contacto->telefono) }}, {{ json_encode($contacto->descripcion ?? '') }}, {{ json_encode($contacto->estado) }})" class="w-8 h-8 rounded-xl flex items-center justify-center text-orange-600 bg-orange-50 hover:bg-orange-500 hover:text-white border border-orange-200/80 shadow-2xs transition-all cursor-pointer" title="Editar">
                                        <i class="fa-regular fa-pen-to-square text-xs"></i>
                                    </button>
                                    <button onclick="openDeleteModal({{ $contacto->id_contacto }}, {{ json_encode($contacto->nombre) }})" class="w-8 h-8 rounded-xl flex items-center justify-center text-rose-600 bg-rose-50 hover:bg-rose-500 hover:text-white border border-rose-200/80 shadow-2xs transition-all cursor-pointer" title="Eliminar">
                                        <i class="fa-regular fa-trash-can text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 px-6 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400 space-y-2">
                                    <i class="fa-solid fa-folder-open text-3xl text-slate-300"></i>
                                    <p class="text-sm font-semibold text-slate-600">No hay contactos de emergencia registrados aún.</p>
                                    <p class="text-xs text-slate-400">Haz clic en "Registrar Contacto de Emergencia" para agregar el primero a la base de datos.</p>
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
<!-- MODALES INTERACTIVOS (CONEXIÓN BD)          -->
<!-- ========================================== -->

<!-- Modal: Registrar Contacto de Emergencia -->
<div id="registerModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-100 space-y-5 animate-modal-pop">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center font-bold border border-orange-200/80 shadow-xs">
                    <i class="fa-solid fa-phone-volume text-lg"></i>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Registrar Contacto de Emergencia</h3>
                    <p class="text-xs text-slate-400">Complete los datos de la entidad o contacto.</p>
                </div>
            </div>
            <button onclick="closeRegisterModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition cursor-pointer">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <form action="{{ route('SST.admin.informacion_basica.contacto_emergencia.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nombre Entidad / Persona *</label>
                <input type="text" name="nombre" required placeholder="Ej: Bomberos Voluntarios / Cruz Roja" class="w-full px-3.5 py-2.5 text-xs font-semibold rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 transition bg-slate-50/30">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Teléfono *</label>
                    <input type="text" name="telefono" required placeholder="Ej: 119 / 3158901234" class="w-full px-3.5 py-2.5 text-xs font-semibold rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 transition bg-slate-50/30">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Estado</label>
                    <select name="estado" class="w-full px-3.5 py-2.5 text-xs font-bold rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 bg-white cursor-pointer">
                        <option value="activo" selected>Activo</option>
                        <option value="inactivo">Inactivo</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Descripción</label>
                <textarea name="descripcion" rows="2" placeholder="Observaciones o indicaciones adicionales..." class="w-full px-3.5 py-2.5 text-xs font-semibold rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 transition bg-slate-50/30"></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeRegisterModal()" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-100 transition cursor-pointer">Cancelar</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-orange-50 hover:bg-orange-100 active:bg-orange-200/70 text-orange-700 border border-orange-200/90 hover:border-orange-300 font-bold text-xs shadow-xs transition cursor-pointer">Guardar Registro</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Editar Contacto de Emergencia -->
<div id="editModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-100 space-y-5 animate-modal-pop">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center font-bold border border-orange-200/80 shadow-xs">
                    <i class="fa-regular fa-pen-to-square"></i>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Editar Contacto de Emergencia</h3>
                    <p class="text-xs text-slate-400">Modifique los datos del directorio.</p>
                </div>
            </div>
            <button onclick="closeEditModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition cursor-pointer">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <form id="editContactoForm" action="" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nombre Entidad / Persona *</label>
                <input type="text" id="editNombre" name="nombre" required class="w-full px-3.5 py-2.5 text-xs font-semibold rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 transition bg-slate-50/30">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Teléfono *</label>
                    <input type="text" id="editTelefono" name="telefono" required class="w-full px-3.5 py-2.5 text-xs font-semibold rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 transition bg-slate-50/30">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Estado</label>
                    <select id="editEstado" name="estado" class="w-full px-3.5 py-2.5 text-xs font-bold rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 bg-white cursor-pointer">
                        <option value="activo">Activo</option>
                        <option value="inactivo">Inactivo</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Descripción</label>
                <textarea id="editDescripcion" name="descripcion" rows="2" class="w-full px-3.5 py-2.5 text-xs font-semibold rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 transition bg-slate-50/30"></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-100 transition cursor-pointer">Cancelar</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-orange-50 hover:bg-orange-100 active:bg-orange-200/70 text-orange-700 border border-orange-200/90 hover:border-orange-300 font-bold text-xs shadow-xs transition cursor-pointer">Actualizar Registro</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Confirmar Eliminación -->
<div id="deleteModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl border border-slate-100 text-center space-y-4 animate-modal-pop">
        <div class="w-14 h-14 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center mx-auto text-2xl border border-rose-100">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <div>
            <h3 class="text-lg font-extrabold text-slate-900">¿Eliminar Contacto de Emergencia?</h3>
            <p class="text-xs text-slate-500 mt-1">Está a punto de borrar el contacto <strong id="deleteTargetName" class="text-slate-800"></strong>.</p>
        </div>
        <form id="deleteContactoForm" action="" method="POST" class="flex items-center justify-center gap-3 pt-2">
            @csrf
            @method('DELETE')
            <button type="button" onclick="closeDeleteModal()" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition cursor-pointer">Cancelar</button>
            <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 shadow-md transition cursor-pointer">Sí, Eliminar</button>
        </form>
    </div>
</div>

<script>
    function openRegisterModal() { 
        document.getElementById('registerModal').classList.remove('hidden'); 
    }
    function closeRegisterModal() { 
        document.getElementById('registerModal').classList.add('hidden'); 
    }

    function openEditModal(id, nombre, telefono, descripcion, estado) {
        const form = document.getElementById('editContactoForm');
        form.action = "{{ url('Sst/admin/informacion-basica/contacto-emergencia') }}/" + id;
        document.getElementById('editNombre').value = nombre;
        document.getElementById('editTelefono').value = telefono;
        document.getElementById('editDescripcion').value = descripcion;
        document.getElementById('editEstado').value = estado || 'activo';
        document.getElementById('editModal').classList.remove('hidden');
    }
    function closeEditModal() { 
        document.getElementById('editModal').classList.add('hidden'); 
    }

    function openDeleteModal(id, nombre) {
        const form = document.getElementById('deleteContactoForm');
        form.action = "{{ url('Sst/admin/informacion-basica/contacto-emergencia') }}/" + id;
        document.getElementById('deleteTargetName').innerText = `"${nombre}"`;
        document.getElementById('deleteModal').classList.remove('hidden');
    }
    function closeDeleteModal() { 
        document.getElementById('deleteModal').classList.add('hidden'); 
    }

    function copyNumber(num) {
        navigator.clipboard.writeText(num);
        alert(`Número ${num} copiado al portapapeles`);
    }

    function filterTable() {
        const search = document.getElementById('searchInput').value.toLowerCase();
        const rows = document.querySelectorAll('#contactsTable tbody tr');

        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            if (text.includes(search)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>
@endsection

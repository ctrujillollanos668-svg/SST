@extends('sst::components.layouts.admin')

@section('title', 'Tipos de Eventos - Lesiones • SST')

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

    <!-- 1. Encabezado de la Sección -->
    <div class="bg-white p-6 sm:p-7 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4 transition-all duration-200">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center border border-orange-200/80 shadow-xs">
                <i class="fa-solid fa-bandage text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Tipos de Lesiones</h1>
                <p class="text-sm text-slate-500 font-medium mt-0.5">Configuración y administración de catálogos y registros generales.</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button onclick="openRegisterModal()" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-orange-600 hover:bg-orange-700 active:bg-orange-800 text-white text-sm font-bold shadow-xs transition-all duration-200 cursor-pointer group border border-orange-500/80">
                <i class="fa-solid fa-plus text-xs group-hover:rotate-90 transition-transform duration-300"></i>
                <span>Registrar Tipo de Lesiones</span>
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
                    <!-- Fila 1 -->
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="py-4 px-6 font-bold text-slate-900 break-words">
                            Herida cortante / Incisa
                        </td>
                        <td class="py-4 px-6 text-slate-600 max-w-md">
                            <p class="line-clamp-2 cursor-pointer hover:text-orange-600 transition-colors" onclick="showFullDesc(event, 'Laceración o discontinuidad de la superficie epidérmica producida por objetos, virutas o herramientas con filos cortantes que requiere curación primaria o sutura médica.')" title="Haz clic para ver la descripción completa">
                                Laceración o discontinuidad de la superficie epidérmica producida por objetos, virutas o herramientas con filos cortantes que requiere curación primaria o sutura médica.
                            </p>
                        </td>
                        <td class="py-4 px-6 text-center whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Activo
                            </span>
                        </td>
                        <td class="py-4 px-6 text-center whitespace-nowrap">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="openEditModal('Herida cortante / Incisa', 'Laceración o discontinuidad de la superficie epidérmica producida por objetos, virutas o herramientas con filos cortantes que requiere curación primaria o sutura médica.')" class="w-9 h-9 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-600 border border-amber-200/80 flex items-center justify-center transition-all duration-150 cursor-pointer shadow-xs hover:scale-105" title="Editar">
                                    <i class="fa-regular fa-pen-to-square text-sm"></i>
                                </button>
                                <button onclick="openDeleteModal('Herida cortante / Incisa')" class="w-9 h-9 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200/80 flex items-center justify-center transition-all duration-150 cursor-pointer shadow-xs hover:scale-105" title="Eliminar">
                                    <i class="fa-regular fa-trash-can text-sm"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Fila 2 -->
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="py-4 px-6 font-bold text-slate-900 break-words">
                            Fractura ósea
                        </td>
                        <td class="py-4 px-6 text-slate-600 max-w-md">
                            <p class="line-clamp-2 cursor-pointer hover:text-orange-600 transition-colors" onclick="showFullDesc(event, 'Ruptura parcial o total de la continuidad de estructuras óseas en extremidades o torso a causa de golpes severos, caídas a desnivel o atrapamientos mecánicos.')" title="Haz clic para ver la descripción completa">
                                Ruptura parcial o total de la continuidad de estructuras óseas en extremidades o torso a causa de golpes severos, caídas a desnivel o atrapamientos mecánicos.
                            </p>
                        </td>
                        <td class="py-4 px-6 text-center whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Activo
                            </span>
                        </td>
                        <td class="py-4 px-6 text-center whitespace-nowrap">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="openEditModal('Fractura ósea', 'Ruptura parcial o total de la continuidad de estructuras óseas en extremidades o torso a causa de golpes severos, caídas a desnivel o atrapamientos mecánicos.')" class="w-9 h-9 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-600 border border-amber-200/80 flex items-center justify-center transition-all duration-150 cursor-pointer shadow-xs hover:scale-105" title="Editar">
                                    <i class="fa-regular fa-pen-to-square text-sm"></i>
                                </button>
                                <button onclick="openDeleteModal('Fractura ósea')" class="w-9 h-9 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200/80 flex items-center justify-center transition-all duration-150 cursor-pointer shadow-xs hover:scale-105" title="Eliminar">
                                    <i class="fa-regular fa-trash-can text-sm"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Fila 3 -->
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="py-4 px-6 font-bold text-slate-900 break-words">
                            Quemadura térmica o química
                        </td>
                        <td class="py-4 px-6 text-slate-600 max-w-md">
                            <p class="line-clamp-2 cursor-pointer hover:text-orange-600 transition-colors" onclick="showFullDesc(event, 'Lesión tisular epidérmica o profunda provocada por exposición accidental a superficies incandescentes, fuego directo, salpicaduras o vapores químicos corrosivos.')" title="Haz clic para ver la descripción completa">
                                Lesión tisular epidérmica o profunda provocada por exposición accidental a superficies incandescentes, fuego directo, salpicaduras o vapores químicos corrosivos.
                            </p>
                        </td>
                        <td class="py-4 px-6 text-center whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Activo
                            </span>
                        </td>
                        <td class="py-4 px-6 text-center whitespace-nowrap">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="openEditModal('Quemadura térmica o química', 'Lesión tisular epidérmica o profunda provocada por exposición accidental a superficies incandescentes, fuego directo, salpicaduras o vapores químicos corrosivos.')" class="w-9 h-9 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-600 border border-amber-200/80 flex items-center justify-center transition-all duration-150 cursor-pointer shadow-xs hover:scale-105" title="Editar">
                                    <i class="fa-regular fa-pen-to-square text-sm"></i>
                                </button>
                                <button onclick="openDeleteModal('Quemadura térmica o química')" class="w-9 h-9 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200/80 flex items-center justify-center transition-all duration-150 cursor-pointer shadow-xs hover:scale-105" title="Eliminar">
                                    <i class="fa-regular fa-trash-can text-sm"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Fila 4 -->
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="py-4 px-6 font-bold text-slate-900 break-words">
                            Esguince o luxación
                        </td>
                        <td class="py-4 px-6 text-slate-600 max-w-md">
                            <p class="line-clamp-2 cursor-pointer hover:text-orange-600 transition-colors" onclick="showFullDesc(event, 'Distensión o estiramiento traumático excesivo de ligamentos articulares en tobillos, muñecas o rodillas por movimientos bruscos, tropezones o cargas pesadas.')" title="Haz clic para ver la descripción completa">
                                Distensión o estiramiento traumático excesivo de ligamentos articulares en tobillos, muñecas o rodillas por movimientos bruscos, tropezones o cargas pesadas.
                            </p>
                        </td>
                        <td class="py-4 px-6 text-center whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Activo
                            </span>
                        </td>
                        <td class="py-4 px-6 text-center whitespace-nowrap">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="openEditModal('Esguince o luxación', 'Distensión o estiramiento traumático excesivo de ligamentos articulares en tobillos, muñecas o rodillas por movimientos bruscos, tropezones o cargas pesadas.')" class="w-9 h-9 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-600 border border-amber-200/80 flex items-center justify-center transition-all duration-150 cursor-pointer shadow-xs hover:scale-105" title="Editar">
                                    <i class="fa-regular fa-pen-to-square text-sm"></i>
                                </button>
                                <button onclick="openDeleteModal('Esguince o luxación')" class="w-9 h-9 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200/80 flex items-center justify-center transition-all duration-150 cursor-pointer shadow-xs hover:scale-105" title="Eliminar">
                                    <i class="fa-regular fa-trash-can text-sm"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
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
<!-- MODALES INTERACTIVOS                       -->
<!-- ========================================== -->

<!-- Modal: Registrar -->
<div id="modalRegister" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center hidden p-4">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 w-full max-w-lg overflow-hidden animate-in fade-in zoom-in duration-200">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-plus text-orange-500"></i>
                <span>Registrar Tipo de Lesión</span>
            </h3>
            <button onclick="closeRegisterModal()" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition cursor-pointer">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form onsubmit="handleModalSubmit(event, 'Tipo de lesión registrado con éxito')">
            <div class="p-6 space-y-4 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5">Nombre de la Lesión *</label>
                    <input type="text" required placeholder="Ej. Amputación traumática" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 transition text-sm">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5">Descripción *</label>
                    <textarea rows="3" required placeholder="Describa las características de este tipo de lesión..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 transition text-sm"></textarea>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5">Estado</label>
                    <select class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 transition text-sm bg-white">
                        <option value="activo">Activo</option>
                        <option value="inactivo">Inactivo</option>
                    </select>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex justify-end gap-2">
                <button type="button" onclick="closeRegisterModal()" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-200/70 transition cursor-pointer">Cancelar</button>
                <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-orange-600 hover:bg-orange-700 text-white shadow-xs transition cursor-pointer">Guardar Registro</button>
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
                <span>Editar Tipo de Lesión</span>
            </h3>
            <button onclick="closeEditModal()" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition cursor-pointer">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form onsubmit="handleModalSubmit(event, 'Tipo de lesión actualizado con éxito')">
            <div class="p-6 space-y-4 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5">Nombre de la Lesión *</label>
                    <input type="text" id="editNombreInput" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 transition text-sm">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5">Descripción *</label>
                    <textarea id="editDescInput" rows="3" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 transition text-sm"></textarea>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5">Estado</label>
                    <select class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 transition text-sm bg-white">
                        <option value="activo" selected>Activo</option>
                        <option value="inactivo">Inactivo</option>
                    </select>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex justify-end gap-2">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-200/70 transition cursor-pointer">Cancelar</button>
                <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-orange-600 hover:bg-orange-700 text-white shadow-xs transition cursor-pointer">Actualizar Registro</button>
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
            <h3 class="text-lg font-bold text-slate-900">¿Eliminar Tipo de Lesión?</h3>
            <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                Estás a punto de eliminar el registro <span id="deleteItemName" class="font-bold text-slate-800"></span>. Esta acción no se puede deshacer.
            </p>
            <div class="mt-6 flex items-center justify-center gap-3">
                <button onclick="closeDeleteModal()" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition cursor-pointer">
                    Cancelar
                </button>
                <button onclick="handleModalSubmit(event, 'Registro eliminado del catálogo')" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-rose-600 hover:bg-rose-700 text-white shadow-xs transition cursor-pointer">
                    Sí, Eliminar
                </button>
            </div>
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

    function openEditModal(nombre, descripcion) {
        document.getElementById('editNombreInput').value = nombre;
        document.getElementById('editDescInput').value = descripcion;
        document.getElementById('modalEdit').classList.remove('hidden');
    }
    function closeEditModal() {
        document.getElementById('modalEdit').classList.add('hidden');
    }

    function openDeleteModal(nombre) {
        document.getElementById('deleteItemName').innerText = `"${nombre}"`;
        document.getElementById('modalDelete').classList.remove('hidden');
    }
    function closeDeleteModal() {
        document.getElementById('modalDelete').classList.add('hidden');
    }

    function handleModalSubmit(event, message) {
        event.preventDefault();
        closeRegisterModal();
        closeEditModal();
        closeDeleteModal();
        alert(message);
    }
</script>
@endsection

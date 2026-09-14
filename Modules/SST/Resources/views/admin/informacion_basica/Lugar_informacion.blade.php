@extends('sst::components.layouts.admin')

@section('title', 'Información Básica - Lugares de Formación • SST')

@section('content')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    @keyframes modalPopIn {
        0% { opacity: 0; transform: scale(0.9) translateY(10px); }
        100% { opacity: 1; transform: scale(1) translateY(0); }
    }
    .animate-modal-pop {
        animation: modalPopIn 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>

<div class="space-y-6 max-w-7xl mx-auto">

    <!-- 1. Encabezado de la Sección -->
    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4 transition-all duration-200">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center border border-orange-200/80 shadow-xs">
                    <i class="fa-solid fa-chalkboard-user text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Lugares de Formación</h1>
                    <p class="text-sm text-slate-500 font-medium mt-0.5">Configuración y administración de catálogos y registros generales.</p>
                </div>
            </div>
        </div>

        <button onclick="openRegisterModal()" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-orange-50 hover:bg-orange-100/80 active:bg-orange-200/70 text-orange-700 text-sm font-bold shadow-xs transition-all duration-200 cursor-pointer group border border-orange-200/90 hover:border-orange-300">
            <i class="fa-solid fa-plus text-xs group-hover:rotate-90 transition-transform duration-300"></i>
            <span>Registrar Lugares de Formación</span>
        </button>
    </div>

    <!-- 3. Barra de Búsqueda y Filtros -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="relative w-full md:w-80">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
            <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Buscar ambiente de formación o descripción..." class="w-full pl-9 pr-4 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 focus:bg-white transition font-medium">
        </div>

        <div class="flex items-center gap-2 w-full md:w-auto">
            <select id="statusFilter" onchange="filterTable()" class="px-3.5 py-2 text-xs font-bold rounded-xl bg-slate-50 border border-slate-200 text-slate-600 focus:outline-none focus:border-orange-500 cursor-pointer">
                <option value="">Todos los estados</option>
                <option value="Activo">Activo</option>
                <option value="Inactivo">Inactivo</option>
            </select>
        </div>
    </div>

    <!-- 4. Tabla Principal de Lugares de Formación -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="placesTable">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-slate-500 text-[11px] font-bold uppercase tracking-wider">
                        <th class="py-4 px-6 w-1/3">NOMBRE</th>
                        <th class="py-4 px-6 w-1/2">DESCRIPCIÓN</th>
                        <th class="py-4 px-6 text-center w-32">ESTADO</th>
                        <th class="py-4 px-6 text-center w-48">ACCIONES</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 text-sm font-medium">

                    <!-- Fila 1: Taller Mecánico -->
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="py-4 px-6 font-bold text-slate-900 break-words">
                            Taller Mecánico
                        </td>
                        <td class="py-4 px-6 text-slate-600">
                            <p class="line-clamp-2 text-xs leading-relaxed cursor-pointer hover:text-amber-600 transition-colors" onclick="showFullDesc(event, 'Área de formación en mecanismos y máquinas industriales. Equipado con tornos, fresadoras y estación de soldadura con extintores de polvo químico seco.')" title="Haz clic para ver la descripción completa">
                                Área de formación en mecanismos y máquinas industriales. Equipado con tornos, fresadoras y estación de soldadura con extintores de polvo químico seco.
                            </p>
                        </td>
                        <td class="py-4 px-6 text-center whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-200/80">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Activo
                            </span>
                        </td>
                        <td class="py-4 px-6 text-center whitespace-nowrap">
                            <div class="flex items-center justify-center gap-1.5">
                                <button onclick="openEditModal('Taller Mecánico', 'Área de formación en mecanismos y máquinas industriales. Equipado con tornos, fresadoras y estación de soldadura con extintores de polvo químico seco.')" class="w-8 h-8 rounded-xl flex items-center justify-center text-orange-600 bg-orange-50 hover:bg-orange-500 hover:text-white border border-orange-200/80 shadow-2xs transition-all cursor-pointer" title="Editar">
                                    <i class="fa-regular fa-pen-to-square text-xs"></i>
                                </button>
                                <button onclick="openDeleteModal('Taller Mecánico')" class="w-8 h-8 rounded-xl flex items-center justify-center text-rose-600 bg-rose-50 hover:bg-rose-500 hover:text-white border border-rose-200/80 shadow-2xs transition-all cursor-pointer" title="Eliminar">
                                    <i class="fa-regular fa-trash-can text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Fila 2: Laboratorio Agrícola -->
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="py-4 px-6 font-bold text-slate-900 break-words">
                            Laboratorio Agrícola
                        </td>
                        <td class="py-4 px-6 text-slate-600">
                            <p class="line-clamp-2 text-xs leading-relaxed cursor-pointer hover:text-amber-600 transition-colors" onclick="showFullDesc(event, 'Espacio para prácticas de cultivo y manejo de productos agrícolas. Cuenta con módulos de hidroponía, control fitosanitario y kits de primeros auxilios.')" title="Haz clic para ver la descripción completa">
                                Espacio para prácticas de cultivo y manejo de productos agrícolas. Cuenta con módulos de hidroponía, control fitosanitario y kits de primeros auxilios.
                            </p>
                        </td>
                        <td class="py-4 px-6 text-center whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-200/80">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Activo
                            </span>
                        </td>
                        <td class="py-4 px-6 text-center whitespace-nowrap">
                            <div class="flex items-center justify-center gap-1.5">
                                <button onclick="openEditModal('Laboratorio Agrícola', 'Espacio para prácticas de cultivo y manejo de productos agrícolas. Cuenta con módulos de hidroponía, control fitosanitario y kits de primeros auxilios.')" class="w-8 h-8 rounded-xl flex items-center justify-center text-orange-600 bg-orange-50 hover:bg-orange-500 hover:text-white border border-orange-200/80 shadow-2xs transition-all cursor-pointer" title="Editar">
                                    <i class="fa-regular fa-pen-to-square text-xs"></i>
                                </button>
                                <button onclick="openDeleteModal('Laboratorio Agrícola')" class="w-8 h-8 rounded-xl flex items-center justify-center text-rose-600 bg-rose-50 hover:bg-rose-500 hover:text-white border border-rose-200/80 shadow-2xs transition-all cursor-pointer" title="Eliminar">
                                    <i class="fa-regular fa-trash-can text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Fila 3: Aula Multimedios de Seguridad -->
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="py-4 px-6 font-bold text-slate-900 break-words">
                            Aula Multimedios de Seguridad
                        </td>
                        <td class="py-4 px-6 text-slate-600">
                            <p class="line-clamp-2 text-xs leading-relaxed cursor-pointer hover:text-amber-600 transition-colors" onclick="showFullDesc(event, 'Ambiente computarizado para inducciones virtuales en SST, simuladores de riesgo laboral y capacitaciones interactivas de brigadistas.')" title="Haz clic para ver la descripción completa">
                                Ambiente computarizado para inducciones virtuales en SST, simuladores de riesgo laboral y capacitaciones interactivas de brigadistas.
                            </p>
                        </td>
                        <td class="py-4 px-6 text-center whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-200/80">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Activo
                            </span>
                        </td>
                        <td class="py-4 px-6 text-center whitespace-nowrap">
                            <div class="flex items-center justify-center gap-1.5">
                                <button onclick="openEditModal('Aula Multimedios de Seguridad', 'Ambiente computarizado para inducciones virtuales en SST, simuladores de riesgo laboral y capacitaciones interactivas de brigadistas.')" class="w-8 h-8 rounded-xl flex items-center justify-center text-orange-600 bg-orange-50 hover:bg-orange-500 hover:text-white border border-orange-200/80 shadow-2xs transition-all cursor-pointer" title="Editar">
                                    <i class="fa-regular fa-pen-to-square text-xs"></i>
                                </button>
                                <button onclick="openDeleteModal('Aula Multimedios de Seguridad')" class="w-8 h-8 rounded-xl flex items-center justify-center text-rose-600 bg-rose-50 hover:bg-rose-500 hover:text-white border border-rose-200/80 shadow-2xs transition-all cursor-pointer" title="Eliminar">
                                    <i class="fa-regular fa-trash-can text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Fila 4: Invernadero Agroindustrial -->
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="py-4 px-6 font-bold text-slate-900 break-words">
                            Invernadero Agroindustrial
                        </td>
                        <td class="py-4 px-6 text-slate-600">
                            <p class="line-clamp-2 text-xs leading-relaxed cursor-pointer hover:text-amber-600 transition-colors" onclick="showFullDesc(event, 'Espacio controlado para la formación en siembra, riego automatizado y evaluación de normas de bioseguridad en cultivos protegidos.')" title="Haz clic para ver la descripción completa">
                                Espacio controlado para la formación en siembra, riego automatizado y evaluación de normas de bioseguridad en cultivos protegidos.
                            </p>
                        </td>
                        <td class="py-4 px-6 text-center whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-200/80">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Activo
                            </span>
                        </td>
                        <td class="py-4 px-6 text-center whitespace-nowrap">
                            <div class="flex items-center justify-center gap-1.5">
                                <button onclick="openEditModal('Invernadero Agroindustrial', 'Espacio controlado para la formación en siembra, riego automatizado y evaluación de normas de bioseguridad en cultivos protegidos.')" class="w-8 h-8 rounded-xl flex items-center justify-center text-orange-600 bg-orange-50 hover:bg-orange-500 hover:text-white border border-orange-200/80 shadow-2xs transition-all cursor-pointer" title="Editar">
                                    <i class="fa-regular fa-pen-to-square text-xs"></i>
                                </button>
                                <button onclick="openDeleteModal('Invernadero Agroindustrial')" class="w-8 h-8 rounded-xl flex items-center justify-center text-rose-600 bg-rose-50 hover:bg-rose-500 hover:text-white border border-rose-200/80 shadow-2xs transition-all cursor-pointer" title="Eliminar">
                                    <i class="fa-regular fa-trash-can text-xs"></i>
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
<!-- MODALES Y NOTIFICACIÓN TOAST              -->
<!-- ========================================== -->

<!-- Modal: Registrar Lugar de Formación -->
<div id="registerModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-100 space-y-5 animate-modal-pop">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center font-bold border border-orange-200">
                    <i class="fa-solid fa-chalkboard-user"></i>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Registrar Lugar de Formación</h3>
                    <p class="text-xs text-slate-400">Complete los datos del ambiente o taller de aprendizaje.</p>
                </div>
            </div>
            <button onclick="closeRegisterModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition cursor-pointer">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <form onsubmit="handleFormSubmit(event, 'Lugar de Formación registrado con éxito')" class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nombre del Ambiente de Formación *</label>
                <input type="text" id="regNombre" required placeholder="Ej. Taller Mecánico" class="w-full px-3.5 py-2.5 text-xs font-semibold rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 transition bg-slate-50/30">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Descripción de Actividades & Equipamiento *</label>
                <textarea id="regDesc" rows="3" required placeholder="Describa las prácticas a realizar, máquinas y medidas de seguridad..." class="w-full px-3.5 py-2.5 text-xs font-medium rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 transition bg-slate-50/30 resize-none"></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Estado</label>
                <select class="w-full px-3.5 py-2.5 text-xs font-bold rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 bg-white cursor-pointer">
                    <option value="Activo">Activo</option>
                    <option value="Inactivo">Inactivo</option>
                </select>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeRegisterModal()" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-100 transition cursor-pointer">Cancelar</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-orange-50 hover:bg-orange-100 active:bg-orange-200/70 text-orange-700 border border-orange-200/90 hover:border-orange-300 font-bold text-xs shadow-xs transition cursor-pointer">Guardar Registro</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Editar Lugar de Formación -->
<div id="editModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-100 space-y-5 animate-modal-pop">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center font-bold border border-orange-200/80 shadow-xs">
                    <i class="fa-regular fa-pen-to-square"></i>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Editar Lugar de Formación</h3>
                    <p class="text-xs text-slate-400">Modifique los datos del ambiente seleccionado.</p>
                </div>
            </div>
            <button onclick="closeEditModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition cursor-pointer">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <form onsubmit="handleFormSubmit(event, 'Lugar de Formación actualizado con éxito')" class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nombre del Ambiente *</label>
                <input type="text" id="editNombre" required class="w-full px-3.5 py-2.5 text-xs font-semibold rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 transition bg-slate-50/30">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Descripción *</label>
                <textarea id="editDesc" rows="3" required class="w-full px-3.5 py-2.5 text-xs font-medium rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 transition bg-slate-50/30 resize-none"></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Estado</label>
                <select class="w-full px-3.5 py-2.5 text-xs font-bold rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 bg-white cursor-pointer">
                    <option value="Activo">Activo</option>
                    <option value="Inactivo">Inactivo</option>
                </select>
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
            <h3 class="text-lg font-extrabold text-slate-900">¿Eliminar Lugar de Formación?</h3>
            <p class="text-xs text-slate-500 mt-1">Está a punto de borrar el registro <strong id="deleteTargetName" class="text-slate-800"></strong>.</p>
        </div>
        <div class="flex items-center justify-center gap-3 pt-2">
            <button onclick="closeDeleteModal()" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition cursor-pointer">Cancelar</button>
            <button onclick="confirmDelete()" class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 shadow-md transition cursor-pointer">Sí, Eliminar</button>
        </div>
    </div>
</div>

<!-- NOTIFICACIÓN TOAST FLOTANTE (MENSAJE DE ÉXITO) -->
<div id="toastSuccessNotification" class="fixed top-5 right-5 z-50 transform translate-x-full opacity-0 transition-all duration-300 pointer-events-none">
    <div class="bg-white rounded-2xl p-4 shadow-xl border border-slate-100 border-l-4 border-l-emerald-500 flex items-center gap-3.5 max-w-md pointer-events-auto">
        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold shrink-0 border border-emerald-100">
            <i class="fa-solid fa-circle-check text-lg"></i>
        </div>
        <div class="flex-1 pr-2">
            <h4 class="text-xs font-extrabold text-slate-900">¡Acción Exitosa!</h4>
            <p id="toastMessageText" class="text-xs text-slate-500 font-medium mt-0.5 leading-snug">Operación realizada correctamente.</p>
        </div>
        <button onclick="hideToastNotification()" class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-400 hover:text-slate-600 flex items-center justify-center transition cursor-pointer">
            <i class="fa-solid fa-xmark text-xs"></i>
        </button>
    </div>
</div>

<script>
    function openRegisterModal() { 
        document.getElementById('registerModal').classList.remove('hidden'); 
    }
    function closeRegisterModal() { 
        document.getElementById('registerModal').classList.add('hidden'); 
    }

    function openEditModal(nombre, desc) {
        document.getElementById('editNombre').value = nombre;
        document.getElementById('editDesc').value = desc;
        document.getElementById('editModal').classList.remove('hidden');
    }
    function closeEditModal() { 
        document.getElementById('editModal').classList.add('hidden'); 
    }

    let targetToDelete = '';
    function openDeleteModal(nombre) {
        targetToDelete = nombre;
        document.getElementById('deleteTargetName').innerText = `"${nombre}"`;
        document.getElementById('deleteModal').classList.remove('hidden');
    }
    function closeDeleteModal() { 
        document.getElementById('deleteModal').classList.add('hidden'); 
    }
    
    function confirmDelete() {
        closeDeleteModal();
        showToastNotification(`Lugar de Formación "${targetToDelete}" eliminado correctamente.`);
    }

    function handleFormSubmit(e, message) {
        e.preventDefault();
        closeRegisterModal();
        closeEditModal();
        showToastNotification(message);
    }
</script>

<!-- Popover / Modal para Descripción Completa (Estilo Blanco Elegante) -->
<div id="descPopover" class="fixed z-50 hidden bg-white text-slate-800 p-4 rounded-2xl shadow-2xl max-w-sm text-xs leading-relaxed border border-slate-200/90 animate-in fade-in zoom-in duration-150">
    <div class="flex items-center justify-between font-extrabold text-[11px] text-orange-700 mb-2 border-b border-slate-100 pb-2">
        <span class="flex items-center gap-1.5">
            <span class="w-2 h-2 rounded-full bg-orange-500"></span>
            <span>DESCRIPCIÓN COMPLETA</span>
        </span>
        <button onclick="closeDescPopover()" class="w-6 h-6 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-400 hover:text-slate-600 flex items-center justify-center transition cursor-pointer">
            <i class="fa-solid fa-xmark text-xs"></i>
        </button>
    </div>
    <p id="descPopoverText" class="text-slate-600 font-medium pt-1 break-words leading-relaxed"></p>
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
        const pop = document.getElementById('descPopover');
        if (pop) pop.classList.add('hidden');
    }

    document.addEventListener('click', function (e) {
        const popover = document.getElementById('descPopover');
        if (popover && !popover.contains(e.target)) {
            popover.classList.add('hidden');
        }
    });

    function openRegisterModal() { 
        document.getElementById('registerModal').classList.remove('hidden'); 
    }
    function closeRegisterModal() { 
        document.getElementById('registerModal').classList.add('hidden'); 
    }

    function openEditModal(nombre, desc) {
        document.getElementById('editNombre').value = nombre;
        document.getElementById('editDesc').value = desc;
        document.getElementById('editModal').classList.remove('hidden');
    }
    function closeEditModal() { 
        document.getElementById('editModal').classList.add('hidden'); 
    }

    function openDeleteModal(nombre) {
        document.getElementById('deleteTargetName').innerText = `"${nombre}"`;
        document.getElementById('deleteModal').classList.remove('hidden');
    }
    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }

    function confirmDelete() {
        closeDeleteModal();
        showToastNotification('Lugar de Formación eliminado con éxito');
    }

    function handleFormSubmit(event, message) {
        event.preventDefault();
        closeRegisterModal();
        closeEditModal();
        showToastNotification(message);
    }

    let toastTimeout;
    function showToastNotification(message) {
        const toast = document.getElementById('toastSuccessNotification');
        document.getElementById('toastMessageText').innerText = message;
        
        toast.classList.remove('translate-x-full', 'opacity-0', 'pointer-events-none');
        toast.classList.add('translate-x-0', 'opacity-100');

        clearTimeout(toastTimeout);
        toastTimeout = setTimeout(() => {
            hideToastNotification();
        }, 4000);
    }

    function hideToastNotification() {
        const toast = document.getElementById('toastSuccessNotification');
        if (toast) {
            toast.classList.remove('translate-x-0', 'opacity-100');
            toast.classList.add('translate-x-full', 'opacity-0', 'pointer-events-none');
        }
    }

    function filterTable() {
        const search = document.getElementById('searchInput').value.toLowerCase();
        const status = document.getElementById('statusFilter').value.toLowerCase();
        const rows = document.querySelectorAll('#placesTable tbody tr');

        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            const matchesSearch = text.includes(search);
            const matchesStatus = status === '' || text.includes(status);

            if (matchesSearch && matchesStatus) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>
@endsection

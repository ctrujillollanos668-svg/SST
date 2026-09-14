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
                    <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">Configuración y administración de catálogos y registros generales.</p>
                </div>
            </div>

            <button onclick="openRegisterModal()" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-orange-50 hover:bg-orange-100/80 active:bg-orange-200/70 text-orange-700 text-sm font-bold shadow-xs transition-all duration-200 cursor-pointer group border border-orange-200/90 hover:border-orange-300 shrink-0">
                <i class="fa-solid fa-plus text-xs group-hover:rotate-90 transition-transform duration-300"></i>
                <span>Registrar Contactos de Emergencia</span>
            </button>
        </div>
    </div>

    <!-- 2. Filtros y Búsqueda -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="relative w-full md:w-80">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
            <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Buscar por entidad o teléfono..." class="w-full pl-9 pr-4 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 focus:bg-white transition font-medium">
        </div>

        <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto pb-1 md:pb-0">
            <button onclick="filterCategory('all', this)" class="cat-filter-btn px-3.5 py-1.5 rounded-xl text-xs font-bold bg-orange-50 text-orange-700 border border-orange-200 shadow-xs transition cursor-pointer">Todos</button>
            <button onclick="filterCategory('Fuerza Pública', this)" class="cat-filter-btn px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-slate-100 text-slate-600 hover:bg-slate-200 transition cursor-pointer">Fuerza Pública</button>
            <button onclick="filterCategory('ARL / Salud', this)" class="cat-filter-btn px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-slate-100 text-slate-600 hover:bg-slate-200 transition cursor-pointer">ARL / Salud</button>
            <button onclick="filterCategory('Brigada Interna', this)" class="cat-filter-btn px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-slate-100 text-slate-600 hover:bg-slate-200 transition cursor-pointer">Brigada Interna</button>
        </div>
    </div>

    <!-- 3. Tabla Principal de Contactos de Emergencia -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="contactsTable">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-slate-500 text-[11px] font-bold uppercase tracking-wider">
                        <th class="py-4 px-6 w-2/5">NOMBRE</th>
                        <th class="py-4 px-6 w-1/4">TELÉFONO</th>
                        <th class="py-4 px-6 text-center w-1/5">DISPONIBILIDAD</th>
                        <th class="py-4 px-6 text-center w-36">ACCIONES</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 text-sm font-medium">

                    <!-- Fila 1: Bomberos La Angostura -->
                    <tr class="hover:bg-slate-50/60 transition-colors" data-category="Fuerza Pública">
                        <td class="py-4 px-6 font-bold text-slate-900 break-words">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold shrink-0 border border-rose-100">
                                    <i class="fa-solid fa-fire-extinguisher text-xs"></i>
                                </div>
                                <div>
                                    <span class="block font-bold text-slate-900">Bomberos La Angostura</span>
                                    <span class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">Fuerza Pública</span>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6 font-semibold text-slate-700 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-extrabold text-slate-800">119</span>
                                <button onclick="copyNumber('119')" class="text-slate-400 hover:text-amber-600 transition" title="Copiar número">
                                    <i class="fa-regular fa-copy text-xs"></i>
                                </button>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-center whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-600 border border-indigo-200/80">
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span> 24/7
                            </span>
                        </td>
                        <td class="py-4 px-6 text-center whitespace-nowrap">
                            <div class="flex items-center justify-center gap-1.5">
                                <button onclick="openEditModal('Bomberos La Angostura', 'Fuerza Pública', '119', '', '24/7')" class="w-8 h-8 rounded-xl flex items-center justify-center text-orange-600 bg-orange-50 hover:bg-orange-500 hover:text-white border border-orange-200/80 shadow-2xs transition-all cursor-pointer" title="Editar">
                                    <i class="fa-regular fa-pen-to-square text-xs"></i>
                                </button>
                                <button onclick="openDeleteModal('Bomberos La Angostura')" class="w-8 h-8 rounded-xl flex items-center justify-center text-rose-600 bg-rose-50 hover:bg-rose-500 hover:text-white border border-rose-200/80 shadow-2xs transition-all cursor-pointer" title="Eliminar">
                                    <i class="fa-regular fa-trash-can text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Fila 2: Cruz Roja -->
                    <tr class="hover:bg-slate-50/60 transition-colors" data-category="ARL / Salud">
                        <td class="py-4 px-6 font-bold text-slate-900 break-words">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-red-50 text-red-600 flex items-center justify-center font-bold shrink-0 border border-red-100">
                                    <i class="fa-solid fa-square-plus text-xs"></i>
                                </div>
                                <div>
                                    <span class="block font-bold text-slate-900">Cruz Roja</span>
                                    <span class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">ARL / Salud</span>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6 font-semibold text-slate-700 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-extrabold text-slate-800">132</span>
                                <button onclick="copyNumber('132')" class="text-slate-400 hover:text-amber-600 transition" title="Copiar número">
                                    <i class="fa-regular fa-copy text-xs"></i>
                                </button>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-center whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-600 border border-indigo-200/80">
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span> 24/7
                            </span>
                        </td>
                        <td class="py-4 px-6 text-center whitespace-nowrap">
                            <div class="flex items-center justify-center gap-1.5">
                                <button onclick="openEditModal('Cruz Roja', 'ARL / Salud', '132', '', '24/7')" class="w-8 h-8 rounded-xl flex items-center justify-center text-orange-600 bg-orange-50 hover:bg-orange-500 hover:text-white border border-orange-200/80 shadow-2xs transition-all cursor-pointer" title="Editar">
                                    <i class="fa-regular fa-pen-to-square text-xs"></i>
                                </button>
                                <button onclick="openDeleteModal('Cruz Roja')" class="w-8 h-8 rounded-xl flex items-center justify-center text-rose-600 bg-rose-50 hover:bg-rose-500 hover:text-white border border-rose-200/80 shadow-2xs transition-all cursor-pointer" title="Eliminar">
                                    <i class="fa-regular fa-trash-can text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Fila 3: ARL Sura - Línea Nacional -->
                    <tr class="hover:bg-slate-50/60 transition-colors" data-category="ARL / Salud">
                        <td class="py-4 px-6 font-bold text-slate-900 break-words">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center font-bold shrink-0 border border-sky-100">
                                    <i class="fa-solid fa-user-doctor text-xs"></i>
                                </div>
                                <div>
                                    <span class="block font-bold text-slate-900">ARL Sura - Línea Nacional</span>
                                    <span class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">ARL / Salud</span>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6 font-semibold text-slate-700 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-extrabold text-slate-800">01 8000 511 411</span>
                                <button onclick="copyNumber('018000511411')" class="text-slate-400 hover:text-amber-600 transition" title="Copiar número">
                                    <i class="fa-regular fa-copy text-xs"></i>
                                </button>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-center whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-600 border border-indigo-200/80">
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span> 24/7
                            </span>
                        </td>
                        <td class="py-4 px-6 text-center whitespace-nowrap">
                            <div class="flex items-center justify-center gap-1.5">
                                <button onclick="openEditModal('ARL Sura - Línea Nacional', 'ARL / Salud', '01 8000 511 411', '#888', '24/7')" class="w-8 h-8 rounded-xl flex items-center justify-center text-orange-600 bg-orange-50 hover:bg-orange-500 hover:text-white border border-orange-200/80 shadow-2xs transition-all cursor-pointer" title="Editar">
                                    <i class="fa-regular fa-pen-to-square text-xs"></i>
                                </button>
                                <button onclick="openDeleteModal('ARL Sura - Línea Nacional')" class="w-8 h-8 rounded-xl flex items-center justify-center text-rose-600 bg-rose-50 hover:bg-rose-500 hover:text-white border border-rose-200/80 shadow-2xs transition-all cursor-pointer" title="Eliminar">
                                    <i class="fa-regular fa-trash-can text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Fila 4: Brigada Interna SENA SST -->
                    <tr class="hover:bg-slate-50/60 transition-colors" data-category="Brigada Interna">
                        <td class="py-4 px-6 font-bold text-slate-900 break-words">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold shrink-0 border border-amber-200">
                                    <i class="fa-solid fa-user-shield text-xs"></i>
                                </div>
                                <div>
                                    <span class="block font-bold text-slate-900">Brigada Interna SENA SST</span>
                                    <span class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">Brigada Interna</span>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6 font-semibold text-slate-700 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-extrabold text-slate-800">315 890 1234</span>
                                <button onclick="copyNumber('3158901234')" class="text-slate-400 hover:text-amber-600 transition" title="Copiar número">
                                    <i class="fa-regular fa-copy text-xs"></i>
                                </button>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-center whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200/80">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Horario Hábil
                            </span>
                        </td>
                        <td class="py-4 px-6 text-center whitespace-nowrap">
                            <div class="flex items-center justify-center gap-1.5">
                                <button onclick="openEditModal('Brigada Interna SENA SST', 'Brigada Interna', '315 890 1234', 'Ext. 405', 'Horario Hábil')" class="w-8 h-8 rounded-xl flex items-center justify-center text-orange-600 bg-orange-50 hover:bg-orange-500 hover:text-white border border-orange-200/80 shadow-2xs transition-all cursor-pointer" title="Editar">
                                    <i class="fa-regular fa-pen-to-square text-xs"></i>
                                </button>
                                <button onclick="openDeleteModal('Brigada Interna SENA SST')" class="w-8 h-8 rounded-xl flex items-center justify-center text-rose-600 bg-rose-50 hover:bg-rose-500 hover:text-white border border-rose-200/80 shadow-2xs transition-all cursor-pointer" title="Eliminar">
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
                    <p class="text-xs text-slate-400">Complete los datos de la entidad o brigadista.</p>
                </div>
            </div>
            <button onclick="closeRegisterModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition cursor-pointer">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <form onsubmit="handleFormSubmit(event, 'Contacto de Emergencia registrado con éxito')" class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nombre Entidad / Persona *</label>
                <input type="text" required placeholder="Ej: Bomberos Voluntarios" class="w-full px-3.5 py-2.5 text-xs font-semibold rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 transition bg-slate-50/30">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Categoría *</label>
                    <select class="w-full px-3.5 py-2.5 text-xs font-bold rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 bg-white cursor-pointer">
                        <option value="Fuerza Pública">Fuerza Pública</option>
                        <option value="ARL / Salud">ARL / Salud</option>
                        <option value="Brigada Interna">Brigada Interna</option>
                        <option value="Autoridad Ambiental">Autoridad Ambiental</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Disponibilidad *</label>
                    <select class="w-full px-3.5 py-2.5 text-xs font-bold rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 bg-white cursor-pointer">
                        <option value="24/7">24/7</option>
                        <option value="Horario Hábil">Horario Hábil</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Teléfono Principal *</label>
                    <input type="text" required placeholder="Ej: 119" class="w-full px-3.5 py-2.5 text-xs font-semibold rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 transition bg-slate-50/30">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Teléfono Secundario / Ext</label>
                    <input type="text" placeholder="Ej: Ext. 102" class="w-full px-3.5 py-2.5 text-xs font-semibold rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 transition bg-slate-50/30">
                </div>
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

        <form onsubmit="handleFormSubmit(event, 'Contacto actualizado con éxito')" class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nombre Entidad *</label>
                <input type="text" id="editNombre" required class="w-full px-3.5 py-2.5 text-xs font-semibold rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 transition bg-slate-50/30">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Categoría *</label>
                    <select id="editCategoria" class="w-full px-3.5 py-2.5 text-xs font-bold rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 bg-white cursor-pointer">
                        <option value="Fuerza Pública">Fuerza Pública</option>
                        <option value="ARL / Salud">ARL / Salud</option>
                        <option value="Brigada Interna">Brigada Interna</option>
                        <option value="Autoridad Ambiental">Autoridad Ambiental</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Disponibilidad *</label>
                    <select id="editDisp" class="w-full px-3.5 py-2.5 text-xs font-bold rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 bg-white cursor-pointer">
                        <option value="24/7">24/7</option>
                        <option value="Horario Hábil">Horario Hábil</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Teléfono Principal *</label>
                    <input type="text" id="editTel1" required class="w-full px-3.5 py-2.5 text-xs font-semibold rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 transition bg-slate-50/30">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Teléfono Secundario</label>
                    <input type="text" id="editTel2" class="w-full px-3.5 py-2.5 text-xs font-semibold rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 transition bg-slate-50/30">
                </div>
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

    function openEditModal(nombre, cat, tel1, tel2, disp) {
        document.getElementById('editNombre').value = nombre;
        document.getElementById('editCategoria').value = cat;
        document.getElementById('editTel1').value = tel1;
        document.getElementById('editTel2').value = tel2;
        document.getElementById('editDisp').value = disp;
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
        showToastNotification(`Contacto "${targetToDelete}" eliminado con éxito`);
    }

    function handleFormSubmit(e, message) {
        e.preventDefault();
        closeRegisterModal();
        closeEditModal();
        showToastNotification(message);
    }

    function copyNumber(num) {
        navigator.clipboard.writeText(num);
        showToastNotification(`Número ${num} copiado al portapapeles`);
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

    let currentCategoryFilter = 'all';

    function filterCategory(cat, btnElement) {
        currentCategoryFilter = cat;
        
        const buttons = document.querySelectorAll('.cat-filter-btn');
        buttons.forEach(btn => {
            btn.className = "cat-filter-btn px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-slate-100 text-slate-600 hover:bg-slate-200 transition cursor-pointer";
        });
        if(btnElement) {
            btnElement.className = "cat-filter-btn px-3.5 py-1.5 rounded-xl text-xs font-bold bg-orange-50 text-orange-700 border border-orange-200/90 shadow-xs transition cursor-pointer";
        }

        filterTable();
    }

    function filterTable() {
        const search = document.getElementById('searchInput').value.toLowerCase();
        const rows = document.querySelectorAll('#contactsTable tbody tr');

        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            const category = row.getAttribute('data-category');
            
            const matchesSearch = text.includes(search);
            const matchesCategory = (currentCategoryFilter === 'all' || category === currentCategoryFilter);

            if (matchesSearch && matchesCategory) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>
@endsection

@extends('sst::components.layouts.admin')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto pb-12">

    <!-- 1. Cabecera Principal -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center border border-orange-200/80 shadow-xs">
                <i class="fa-solid fa-clock-rotate-left text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Historial de Inspecciones SST</h1>
                <p class="text-sm text-slate-500 font-medium mt-0.5">Control de registros, actas y seguimiento de condiciones de seguridad.</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('SST.admin.inspecciones.realizar') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-orange-50 hover:bg-orange-100/80 active:bg-orange-200/70 text-orange-700 text-sm font-bold shadow-xs transition-all duration-200 cursor-pointer group border border-orange-200/90 hover:border-orange-300">
                <i class="fa-solid fa-plus text-xs group-hover:rotate-90 transition-transform duration-300"></i>
                <span>+ Nueva Inspección</span>
            </a>
        </div>
    </div>

    <!-- 2. Tarjetas de Métricas KPI -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <!-- KPI 1: Total -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Inspecciones</p>
                <h3 class="text-3xl font-extrabold text-slate-800">385</h3>
                <p class="text-[11px] font-semibold text-slate-500">Registradas en el año</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center border border-orange-200/60 shadow-xs">
                <i class="fa-solid fa-clipboard-list text-xl"></i>
            </div>
        </div>

        <!-- KPI 2: Conformes -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-bold uppercase tracking-wider text-emerald-600">Cumplidas (Conformes)</p>
                <h3 class="text-3xl font-extrabold text-emerald-600">312</h3>
                <p class="text-[11px] font-semibold text-emerald-600/80">81.0% de cumplimiento</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-200/60 shadow-xs">
                <i class="fa-solid fa-circle-check text-xl"></i>
            </div>
        </div>

        <!-- KPI 3: Críticas / Hallazgos -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-bold uppercase tracking-wider text-rose-500">Críticas (Con Hallazgos)</p>
                <h3 class="text-3xl font-extrabold text-rose-600">18</h3>
                <p class="text-[11px] font-semibold text-rose-500/80">Requieren intervención</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center border border-rose-200/60 shadow-xs">
                <i class="fa-solid fa-triangle-exclamation text-xl"></i>
            </div>
        </div>
    </div>

    <!-- 3. Barra de Búsqueda y Filtros -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="relative flex-1 max-w-md">
            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <i class="fa-solid fa-magnifying-glass text-xs"></i>
            </span>
            <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Buscar por código, área, inspector..." class="w-full pl-10 pr-4 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 transition text-xs">
        </div>

        <div class="flex items-center gap-2.5">
            <select id="typeFilter" onchange="filterTable()" class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-medium text-slate-700 bg-white focus:outline-none focus:border-orange-500">
                <option value="">Todos los Tipos</option>
                <option value="Extintores">Extintores</option>
                <option value="Botiquines">Botiquines</option>
                <option value="EPP">Uso de EPP</option>
                <option value="Locativa">Locativa y Orden</option>
                <option value="Herramientas">Herramientas</option>
            </select>

            <select id="statusFilter" onchange="filterTable()" class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-medium text-slate-700 bg-white focus:outline-none focus:border-orange-500">
                <option value="">Todos los Estados</option>
                <option value="CUMPLIDO">Cumplido</option>
                <option value="EN SEGUIMIENTO">En Seguimiento</option>
                <option value="CRÍTICO">Crítico</option>
            </select>
        </div>
    </div>

    <!-- 4. Tabla de Registros -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="historyTable">
                <thead>
                    <tr class="bg-slate-50/90 border-b border-slate-200/80 text-slate-500 text-[11px] font-bold uppercase tracking-wider">
                        <th class="py-4 px-6 w-36">CÓDIGO</th>
                        <th class="py-4 px-6">TIPO DE INSPECCIÓN</th>
                        <th class="py-4 px-6">UBICACIÓN / ÁREA</th>
                        <th class="py-4 px-6">INSPECTOR</th>
                        <th class="py-4 px-6 w-32">FECHA</th>
                        <th class="py-4 px-6 text-center w-36">ESTADO</th>
                        <th class="py-4 px-6 text-center w-36">ACCIONES</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    
                    <!-- Fila 1 -->
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="py-4 px-6 font-bold text-slate-800">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 font-mono text-[11px]">
                                INS-2026-0041
                            </span>
                        </td>
                        <td class="py-4 px-6 font-bold text-slate-800">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                                <span>Extintores</span>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-slate-600 font-medium">Bloque A - Piso 2 (Taller Mecánica)</td>
                        <td class="py-4 px-6 text-slate-700 font-semibold">Andrés Felipe Vargas</td>
                        <td class="py-4 px-6 text-slate-500 font-medium">20 Jun 2026</td>
                        <td class="py-4 px-6 text-center">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                CUMPLIDO
                            </span>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <button onclick="openDetailModal('INS-2026-0041', 'Extintores', 'Bloque A - Piso 2 (Taller Mecánica)', 'Andrés Felipe Vargas', '20 Jun 2026', 'CUMPLIDO', 'Todos los extintores (3 de PQS y 1 de CO2) se encuentran en zona verde de presión, con precintos intactos y señalización despejada a 1.50m.')" class="w-9 h-9 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-600 border border-slate-200 flex items-center justify-center transition cursor-pointer shadow-xs hover:scale-105" title="Ver Detalle">
                                    <i class="fa-regular fa-eye text-sm"></i>
                                </button>
                                <button onclick="openDeleteModal('INS-2026-0041')" class="w-9 h-9 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200/80 flex items-center justify-center transition cursor-pointer shadow-xs hover:scale-105" title="Eliminar">
                                    <i class="fa-regular fa-trash-can text-sm"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Fila 2 -->
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="py-4 px-6 font-bold text-slate-800">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 font-mono text-[11px]">
                                INS-2026-0040
                            </span>
                        </td>
                        <td class="py-4 px-6 font-bold text-slate-800">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                <span>EPP</span>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-slate-600 font-medium">Almacén General y Bodega</td>
                        <td class="py-4 px-6 text-slate-700 font-semibold">María Paula López</td>
                        <td class="py-4 px-6 text-slate-500 font-medium">19 Jun 2026</td>
                        <td class="py-4 px-6 text-center">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                EN SEGUIMIENTO
                            </span>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <button onclick="openDetailModal('INS-2026-0040', 'EPP', 'Almacén General y Bodega', 'María Paula López', '19 Jun 2026', 'EN SEGUIMIENTO', 'Dos operarios realizaban manipulación de carga pesada sin guantes de vaqueta reglamentarios. Se entregó dotación provisional.')" class="w-9 h-9 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-600 border border-slate-200 flex items-center justify-center transition cursor-pointer shadow-xs hover:scale-105" title="Ver Detalle">
                                    <i class="fa-regular fa-eye text-sm"></i>
                                </button>
                                <button onclick="openDeleteModal('INS-2026-0040')" class="w-9 h-9 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200/80 flex items-center justify-center transition cursor-pointer shadow-xs hover:scale-105" title="Eliminar">
                                    <i class="fa-regular fa-trash-can text-sm"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Fila 3 -->
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="py-4 px-6 font-bold text-slate-800">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 font-mono text-[11px]">
                                INS-2026-0039
                            </span>
                        </td>
                        <td class="py-4 px-6 font-bold text-slate-800">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                <span>Locativa</span>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-slate-600 font-medium">Taller de Soldadura y Corte</td>
                        <td class="py-4 px-6 text-slate-700 font-semibold">Carlos Alberto Ruiz</td>
                        <td class="py-4 px-6 text-slate-500 font-medium">18 Jun 2026</td>
                        <td class="py-4 px-6 text-center">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                CRÍTICO
                            </span>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <button onclick="openDetailModal('INS-2026-0039', 'Locativa', 'Taller de Soldadura y Corte', 'Carlos Alberto Ruiz', '18 Jun 2026', 'CRÍTICO', 'Derrame activo de aceite hidráulico en pasillo de alto tránsito sin demarcación ni aserrín absorbente. Cable trifásico con aislante desgastado expuesto al paso.')" class="w-9 h-9 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-600 border border-slate-200 flex items-center justify-center transition cursor-pointer shadow-xs hover:scale-105" title="Ver Detalle">
                                    <i class="fa-regular fa-eye text-sm"></i>
                                </button>
                                <button onclick="openDeleteModal('INS-2026-0039')" class="w-9 h-9 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200/80 flex items-center justify-center transition cursor-pointer shadow-xs hover:scale-105" title="Eliminar">
                                    <i class="fa-regular fa-trash-can text-sm"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Fila 4 -->
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="py-4 px-6 font-bold text-slate-800">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 font-mono text-[11px]">
                                INS-2026-0038
                            </span>
                        </td>
                        <td class="py-4 px-6 font-bold text-slate-800">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span>Botiquines</span>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-slate-600 font-medium">Oficinas Administrativas</td>
                        <td class="py-4 px-6 text-slate-700 font-semibold">Sofía Díaz Moreno</td>
                        <td class="py-4 px-6 text-slate-500 font-medium">17 Jun 2026</td>
                        <td class="py-4 px-6 text-center">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                CUMPLIDO
                            </span>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <button onclick="openDetailModal('INS-2026-0038', 'Botiquines', 'Oficinas Administrativas', 'Sofía Díaz Moreno', '17 Jun 2026', 'CUMPLIDO', 'Botiquín tipo A verificado con inventario completo, gasas estériles y solución salina al 100% dentro de fechas de vencimiento.')" class="w-9 h-9 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-600 border border-slate-200 flex items-center justify-center transition cursor-pointer shadow-xs hover:scale-105" title="Ver Detalle">
                                    <i class="fa-regular fa-eye text-sm"></i>
                                </button>
                                <button onclick="openDeleteModal('INS-2026-0038')" class="w-9 h-9 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200/80 flex items-center justify-center transition cursor-pointer shadow-xs hover:scale-105" title="Eliminar">
                                    <i class="fa-regular fa-trash-can text-sm"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal: Detalle de la Inspección -->
    <div id="modalDetail" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center hidden p-4">
        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 w-full max-w-xl overflow-hidden animate-in fade-in zoom-in duration-200">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center border border-orange-200/60">
                        <i class="fa-solid fa-file-lines text-sm"></i>
                    </span>
                    <h3 class="text-base font-bold text-slate-800" id="detailCode">Detalle de Inspección</h3>
                </div>
                <button onclick="closeDetailModal()" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition cursor-pointer">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <div class="p-6 space-y-4 text-xs">
                <div class="grid grid-cols-2 gap-3 bg-slate-50 p-4 rounded-xl border border-slate-100">
                    <div>
                        <span class="text-slate-400 font-bold block mb-0.5">TIPO DE INSPECCIÓN</span>
                        <span class="text-slate-800 font-bold text-sm" id="detailType">-</span>
                    </div>
                    <div>
                        <span class="text-slate-400 font-bold block mb-0.5">ESTADO</span>
                        <span class="text-slate-800 font-bold text-sm" id="detailStatus">-</span>
                    </div>
                    <div>
                        <span class="text-slate-400 font-bold block mb-0.5">UBICACIÓN</span>
                        <span class="text-slate-700 font-semibold" id="detailLocation">-</span>
                    </div>
                    <div>
                        <span class="text-slate-400 font-bold block mb-0.5">INSPECTOR & FECHA</span>
                        <span class="text-slate-700 font-semibold" id="detailInspector">-</span>
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1.5">Hallazgos y Observaciones Registradas</label>
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 text-slate-700 leading-relaxed font-medium" id="detailNotes">
                        -
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex justify-end gap-2">
                <button onclick="closeDetailModal()" class="px-5 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition cursor-pointer">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    <!-- Modal: Eliminar -->
    <div id="modalDelete" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center hidden p-4">
        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 w-full max-w-md overflow-hidden animate-in fade-in zoom-in duration-200 p-6 text-center">
            <div class="w-16 h-16 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mx-auto mb-4 border border-rose-200/60 shadow-xs">
                <i class="fa-regular fa-trash-can text-2xl"></i>
            </div>
            <h3 class="text-lg font-extrabold text-slate-800">¿Eliminar registro?</h3>
            <p class="text-xs text-slate-500 font-medium mt-1 mb-6">
                Esta acción eliminará el registro de auditoría <span id="deleteItemName" class="font-bold text-slate-700"></span>.
            </p>
            <div class="flex items-center justify-center gap-3">
                <button onclick="closeDeleteModal()" class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition cursor-pointer">
                    Cancelar
                </button>
                <button onclick="confirmDelete()" class="px-5 py-2 rounded-xl text-xs font-bold bg-rose-50 hover:bg-rose-100 active:bg-rose-200 text-rose-600 border border-rose-200 shadow-xs transition cursor-pointer">
                    Sí, eliminar
                </button>
            </div>
        </div>
    </div>

</div>

<!-- Scripts de Interacción -->
<script>
    function openDetailModal(code, type, location, inspector, date, status, notes) {
        document.getElementById('detailCode').textContent = `Inspección ${code}`;
        document.getElementById('detailType').textContent = type;
        document.getElementById('detailStatus').textContent = status;
        document.getElementById('detailLocation').textContent = location;
        document.getElementById('detailInspector').textContent = `${inspector} (${date})`;
        document.getElementById('detailNotes').textContent = notes;
        document.getElementById('modalDetail').classList.remove('hidden');
    }

    function closeDetailModal() {
        document.getElementById('modalDetail').classList.add('hidden');
    }

    let itemToDelete = null;

    function openDeleteModal(name) {
        itemToDelete = name;
        document.getElementById('deleteItemName').textContent = name;
        document.getElementById('modalDelete').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('modalDelete').classList.add('hidden');
        itemToDelete = null;
    }

    function confirmDelete() {
        closeDeleteModal();
        alert(`Registro ${itemToDelete} eliminado con éxito.`);
    }

    function filterTable() {
        const query = document.getElementById('searchInput').value.toLowerCase();
        const typeFilter = document.getElementById('typeFilter').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value.toLowerCase();

        const rows = document.querySelectorAll('#historyTable tbody tr');
        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            const matchesQuery = text.includes(query);
            const matchesType = !typeFilter || text.includes(typeFilter);
            const matchesStatus = !statusFilter || text.includes(statusFilter);

            row.style.display = (matchesQuery && matchesType && matchesStatus) ? '' : 'none';
        });
    }
</script>
@endsection

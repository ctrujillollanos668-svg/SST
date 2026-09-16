@extends('sst::components.layouts.admin')

@section('title', 'Indicadores de Gestión • SST')

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
                    <i class="fa-solid fa-chart-line text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Indicadores de Gestión SG-SST</h1>
                    <p class="text-sm text-slate-500 font-medium mt-0.5">Monitoreo de estructura, proceso y resultado según Decreto 1072 y Res. 0312.</p>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200/80 text-slate-700 text-xs font-bold transition">
                <i class="fa-solid fa-print text-xs"></i> Imprimir Reporte
            </button>
            <button onclick="openNewIndicatorModal()" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-orange-50 hover:bg-orange-100/80 active:bg-orange-200/70 text-orange-700 text-xs font-bold transition shadow-xs border border-orange-200/90 hover:border-orange-300">
                <i class="fa-solid fa-plus text-xs"></i> Nuevo Indicador
            </button>
        </div>
    </div>

    <!-- 2. KPIs Principales en Tarjetas -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- KPI 1 -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-calendar-check"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase text-slate-400">Días sin Accidentes</p>
                <p class="text-2xl font-black text-slate-800">142</p>
                <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-1 mt-0.5">
                    <i class="fa-solid fa-arrow-trend-up"></i> Meta continua
                </span>
            </div>
        </div>

        <!-- KPI 2 -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-list-check"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase text-slate-400">Cumplimiento Plan Anual</p>
                <p class="text-2xl font-black text-slate-800">87.5%</p>
                <span class="text-[10px] font-bold text-orange-600 flex items-center gap-1 mt-0.5">
                    Meta: ≥ 85%
                </span>
            </div>
        </div>

        <!-- KPI 3 -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-user-graduate"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase text-slate-400">Cobertura Capacitaciones</p>
                <p class="text-2xl font-black text-slate-800">92.0%</p>
                <span class="text-[10px] font-bold text-blue-600 flex items-center gap-1 mt-0.5">
                    238 participantes
                </span>
            </div>
        </div>

        <!-- KPI 4 -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase text-slate-400">Total Indicadores en BD</p>
                <p class="text-2xl font-black text-slate-800">{{ count($indicadores) }}</p>
                <span class="text-[10px] font-bold text-purple-600 flex items-center gap-1 mt-0.5">
                    <i class="fa-solid fa-server"></i> Registrados en MySQL
                </span>
            </div>
        </div>
    </div>

    <!-- 3. Tabla Detallada de Indicadores desde Base de Datos -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h2 class="font-extrabold text-slate-800 text-sm">Ficha Técnica de Indicadores en Base de Datos</h2>
            <span class="text-xs text-slate-400 font-medium">Vigencia 2026</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/90 border-b border-slate-200/80 text-slate-500 text-[11px] font-bold uppercase tracking-wider">
                        <th class="py-4 px-6">NOMBRE DEL INDICADOR</th>
                        <th class="py-4 px-6">TIPO</th>
                        <th class="py-4 px-6">FÓRMULA DE CÁLCULO</th>
                        <th class="py-4 px-6 text-center">META</th>
                        <th class="py-4 px-6 text-center">RESULTADO ACTUAL</th>
                        <th class="py-4 px-6 text-center">CUMPLIMIENTO</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 text-xs font-medium">
                    @forelse($indicadores as $ind)
                        @php
                            $isCumplido = str_contains(strtolower($ind->cumplimiento), 'cumplid');
                            $tipoBadge = match($ind->tipo) {
                                'resultado' => 'bg-slate-100 text-slate-700',
                                'proceso' => 'bg-amber-50 text-amber-800 border border-amber-200',
                                'estructura' => 'bg-blue-50 text-blue-800 border border-blue-200',
                                default => 'bg-slate-100 text-slate-700'
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-4 px-6 font-bold text-slate-900">{{ $ind->nombre }}</td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-0.5 rounded-full font-bold text-[10px] capitalize {{ $tipoBadge }}">
                                    {{ $ind->tipo }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-slate-500">{{ $ind->formula ?? 'No especificada' }}</td>
                            <td class="py-4 px-6 text-center font-bold">{{ $ind->meta ?? 'N/A' }}</td>
                            <td class="py-4 px-6 text-center font-extrabold {{ $isCumplido ? 'text-emerald-600' : 'text-orange-600' }}">
                                {{ $ind->resultado_actual ?? '0%' }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if($isCumplido)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <i class="fa-solid fa-check text-[10px]"></i> {{ $ind->cumplimiento }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-orange-50 text-orange-700 border border-orange-200">
                                        <i class="fa-solid fa-clock text-[10px]"></i> {{ $ind->cumplimiento }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-slate-400 font-semibold">
                                No se encontraron indicadores en la base de datos.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Modal Nuevo Indicador -->
<div id="modalNewIndicator" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-100 animate-modal-pop">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
            <h3 class="font-extrabold text-slate-900 text-base flex items-center gap-2">
                <i class="fa-solid fa-chart-line text-orange-600"></i>
                <span>Nuevo Indicador de Gestión</span>
            </h3>
            <button onclick="closeNewIndicatorModal()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition cursor-pointer">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>

        <form action="{{ route('SST.admin.indicadores.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nombre del Indicador *</label>
                <input type="text" name="nombre" required placeholder="Ej: Tasa de Accidentalidad Laboral" class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-semibold">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tipo *</label>
                    <select name="tipo" required class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-semibold">
                        <option value="proceso">Proceso</option>
                        <option value="resultado">Resultado</option>
                        <option value="estructura">Estructura</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Meta *</label>
                    <input type="text" name="meta" required placeholder="Ej: ≥ 85% o 0 casos" class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-semibold">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Fórmula de Cálculo</label>
                <input type="text" name="formula" placeholder="Ej: (Eventos atendidos / Eventos totales) * 100" class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-medium">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Resultado Actual</label>
                    <input type="text" name="resultado_actual" placeholder="Ej: 90%" class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-semibold">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Estado de Cumplimiento</label>
                    <select name="cumplimiento" class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-semibold">
                        <option value="Cumplido">Cumplido</option>
                        <option value="En seguimiento">En seguimiento</option>
                        <option value="No cumplido">No cumplido</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeNewIndicatorModal()" class="px-4 py-2 text-xs font-bold text-slate-600 rounded-xl hover:bg-slate-100 transition cursor-pointer">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-orange-600 hover:bg-orange-700 active:bg-orange-800 rounded-xl shadow-xs transition cursor-pointer flex items-center gap-1.5">
                    <i class="fa-solid fa-check text-xs"></i>
                    <span>Guardar Indicador</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openNewIndicatorModal() {
        document.getElementById('modalNewIndicator').classList.remove('hidden');
    }
    function closeNewIndicatorModal() {
        document.getElementById('modalNewIndicator').classList.add('hidden');
    }
</script>
@endsection

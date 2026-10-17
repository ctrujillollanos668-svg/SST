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
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200/80 text-slate-700 text-xs font-bold transition cursor-pointer">
                <i class="fa-solid fa-print text-xs"></i> Imprimir Reporte
            </button>
        </div>
    </div>



    <!-- 3. DASHBOARD GRANDE: 3 GRÁFICAS DE INDICADORES SST (ACTIVIDADES, EVENTOS, INSPECCIONES) -->
    <div class="space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h2 class="text-lg font-black text-slate-900 tracking-tight flex items-center gap-2">
                    <span class="w-2.5 h-6 rounded-full bg-orange-500 inline-block"></span>
                    <span>Dashboard Gráfico de Gestión SST</span>
                </h2>
                <p class="text-xs text-slate-500 font-medium">Análisis visual en tiempo real de actividades, tipos de eventos e inspecciones de seguridad.</p>
            </div>
            <div class="flex items-center gap-2 text-xs font-bold text-slate-600 bg-white px-3 py-1.5 rounded-xl border border-slate-200/80 shadow-2xs">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Datos sincronizados en vivo</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            <!-- GRÁFICA 1: ACTIVIDADES SST -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 flex flex-col justify-between space-y-4 hover:shadow-sm transition">
                <div class="flex items-start justify-between gap-3 border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center font-bold text-base shrink-0 border border-teal-100">
                            <i class="fa-solid fa-person-running"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-extrabold text-slate-900">Actividades SST</h3>
                            <p class="text-[11px] text-slate-400 font-semibold">Pausas, Charlas y Sensibilización</p>
                        </div>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-teal-50 text-teal-700 border border-teal-200">
                        {{ $actividadesStats['cumplimiento_pct'] }}% Cumplimiento
                    </span>
                </div>

                <!-- Resumen rápido en píldoras -->
                <div class="grid grid-cols-3 gap-2 text-center">
                    <div class="p-2 rounded-xl bg-emerald-50/70 border border-emerald-100">
                        <span class="text-[10px] font-bold text-emerald-600 block uppercase">Realizadas</span>
                        <span class="text-base font-black text-emerald-700">{{ $actividadesStats['realizadas'] }}</span>
                    </div>
                    <div class="p-2 rounded-xl bg-amber-50/70 border border-amber-100">
                        <span class="text-[10px] font-bold text-amber-600 block uppercase">Programadas</span>
                        <span class="text-base font-black text-amber-700">{{ $actividadesStats['programadas'] }}</span>
                    </div>
                    <div class="p-2 rounded-xl bg-rose-50/70 border border-rose-100">
                        <span class="text-[10px] font-bold text-rose-600 block uppercase">Canceladas</span>
                        <span class="text-base font-black text-rose-700">{{ $actividadesStats['canceladas'] }}</span>
                    </div>
                </div>

                <!-- Canvas del gráfico -->
                <div class="relative h-64 flex items-center justify-center">
                    <canvas id="chartActividadesSST"></canvas>
                </div>
            </div>

            <!-- GRÁFICA 2: TIPOS DE EVENTOS -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 flex flex-col justify-between space-y-4 hover:shadow-sm transition">
                <div class="flex items-start justify-between gap-3 border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-base shrink-0 border border-rose-100">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-extrabold text-slate-900">Tipos de Eventos</h3>
                            <p class="text-[11px] text-slate-400 font-semibold">Incidentes, Accidentes y Riesgos</p>
                        </div>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-rose-50 text-rose-700 border border-rose-200">
                        {{ $eventosStats['total'] }} Registrados
                    </span>
                </div>

                <!-- Resumen rápido de estados -->
                <div class="grid grid-cols-2 gap-2 text-center">
                    <div class="p-2 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-[10px] font-bold text-slate-500 block uppercase">Eventos Activos</span>
                        <span class="text-base font-black text-slate-800">{{ $eventosStats['activos'] }}</span>
                    </div>
                    <div class="p-2 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-[10px] font-bold text-slate-400 block uppercase">Categorías en BD</span>
                        <span class="text-base font-black text-slate-700">{{ count($eventosStats['por_categoria']) }}</span>
                    </div>
                </div>

                <!-- Canvas del gráfico -->
                <div class="relative h-64 flex items-center justify-center">
                    <canvas id="chartEventosSST"></canvas>
                </div>
            </div>

            <!-- GRÁFICA 3: INSPECCIONES SST -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 flex flex-col justify-between space-y-4 hover:shadow-sm transition">
                <div class="flex items-start justify-between gap-3 border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-base shrink-0 border border-purple-100">
                            <i class="fa-solid fa-clipboard-check"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-extrabold text-slate-900">Inspecciones de Seguridad</h3>
                            <p class="text-[11px] text-slate-400 font-semibold">Control de Condiciones y Hallazgos</p>
                        </div>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-purple-50 text-purple-700 border border-purple-200">
                        {{ $inspeccionesStats['efectividad_pct'] }}% Atendidas
                    </span>
                </div>

                <!-- Resumen rápido -->
                <div class="grid grid-cols-2 gap-2 text-center">
                    <div class="p-2 rounded-xl bg-emerald-50/70 border border-emerald-100">
                        <span class="text-[10px] font-bold text-emerald-600 block uppercase">Atendidas / Realizadas</span>
                        <span class="text-base font-black text-emerald-700">{{ $inspeccionesStats['realizadas'] }}</span>
                    </div>
                    <div class="p-2 rounded-xl bg-amber-50/70 border border-amber-100">
                        <span class="text-[10px] font-bold text-amber-600 block uppercase">Pendientes</span>
                        <span class="text-base font-black text-amber-700">{{ $inspeccionesStats['pendientes'] }}</span>
                    </div>
                </div>

                <!-- Canvas del gráfico -->
                <div class="relative h-64 flex items-center justify-center">
                    <canvas id="chartInspeccionesSST"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Datos pasados desde el controlador SST
    const dataActividades = @json($actividadesStats);
    const dataEventos = @json($eventosStats);
    const dataInspecciones = @json($inspeccionesStats);

    function initSSTCharts() {
        if (typeof Chart === 'undefined') {
            setTimeout(initSSTCharts, 100);
            return;
        }

        // Destruir instancias previas si existen (al navegar en SPA)
        window.sstCharts = window.sstCharts || {};
        ['actividades', 'eventos', 'inspecciones'].forEach(key => {
            if (window.sstCharts[key]) {
                window.sstCharts[key].destroy();
                window.sstCharts[key] = null;
            }
        });

        // 1. Gráfica de Actividades SST (Doughnut por tipo de actividad)
        const canvasAct = document.getElementById('chartActividadesSST');
        if (canvasAct) {
            const tiposKeys = Object.keys(dataActividades.por_tipo || {});
            const tiposVals = Object.values(dataActividades.por_tipo || {});
            
            const labelsAct = tiposKeys.length > 0 ? tiposKeys : ['Sin actividades'];
            const datasetAct = tiposKeys.length > 0 ? tiposVals : [1];
            const colorsAct = tiposKeys.length > 0 
                ? ['#0d9488', '#f59e0b', '#6366f1', '#3b82f6', '#ec4899', '#10b981'] 
                : ['#e2e8f0'];

            window.sstCharts.actividades = new Chart(canvasAct.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: labelsAct,
                    datasets: [{
                        data: datasetAct,
                        backgroundColor: colorsAct,
                        borderWidth: 2,
                        borderColor: '#ffffff',
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '68%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 10,
                                boxHeight: 10,
                                padding: 12,
                                font: { family: "'Plus Jakarta Sans', sans-serif", size: 11, weight: '600' },
                                color: '#475569'
                            }
                        },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleFont: { family: "'Plus Jakarta Sans', sans-serif", size: 12, weight: '700' },
                            bodyFont: { family: "'Plus Jakarta Sans', sans-serif", size: 11 },
                            padding: 10,
                            cornerRadius: 8
                        }
                    }
                }
            });
        }

        // 2. Gráfica de Tipos de Eventos (Barras estilizadas)
        const canvasEv = document.getElementById('chartEventosSST');
        if (canvasEv) {
            const evCategories = Object.keys(dataEventos.por_categoria || {});
            const evCounts = Object.values(dataEventos.por_categoria || {});

            const labelsEv = evCategories.length > 0 ? evCategories : ['Sin eventos'];
            const datasetEv = evCategories.length > 0 ? evCounts : [0];
            const barColors = [
                '#ef4444', '#f97316', '#e11d48', '#8b5cf6', '#06b6d4', '#64748b'
            ];

            window.sstCharts.eventos = new Chart(canvasEv.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: labelsEv,
                    datasets: [{
                        label: 'Total Reportados',
                        data: datasetEv,
                        backgroundColor: barColors.slice(0, labelsEv.length),
                        borderRadius: 8,
                        borderSkipped: false,
                        maxBarThickness: 36
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleFont: { family: "'Plus Jakarta Sans', sans-serif", size: 12, weight: '700' },
                            bodyFont: { family: "'Plus Jakarta Sans', sans-serif", size: 11 },
                            padding: 10,
                            cornerRadius: 8
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                precision: 0,
                                font: { family: "'Plus Jakarta Sans', sans-serif", size: 10, weight: '600' },
                                color: '#94a3b8'
                            },
                            grid: { color: '#f1f5f9' },
                            border: { dash: [4, 4] }
                        },
                        x: {
                            ticks: {
                                font: { family: "'Plus Jakarta Sans', sans-serif", size: 10, weight: '700' },
                                color: '#475569',
                                callback: function(val, index) {
                                    const text = this.getLabelForValue(val);
                                    return text.length > 14 ? text.substr(0, 12) + '...' : text;
                                }
                            },
                            grid: { display: false }
                        }
                    }
                }
            });
        }

        // 3. Gráfica de Inspecciones SST (Doughnut Atendidas vs Pendientes)
        const canvasInsp = document.getElementById('chartInspeccionesSST');
        if (canvasInsp) {
            const realizadas = Number(dataInspecciones.realizadas || 0);
            const pendientes = Number(dataInspecciones.pendientes || 0);
            const total = realizadas + pendientes;

            const labelsInsp = total > 0 ? ['Atendidas / Realizadas', 'Pendientes'] : ['Sin inspecciones'];
            const datasetInsp = total > 0 ? [realizadas, pendientes] : [1];
            const colorsInsp = total > 0 ? ['#10b981', '#f59e0b'] : ['#e2e8f0'];

            window.sstCharts.inspecciones = new Chart(canvasInsp.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: labelsInsp,
                    datasets: [{
                        data: datasetInsp,
                        backgroundColor: colorsInsp,
                        borderWidth: 2,
                        borderColor: '#ffffff',
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '68%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 10,
                                boxHeight: 10,
                                padding: 12,
                                font: { family: "'Plus Jakarta Sans', sans-serif", size: 11, weight: '600' },
                                color: '#475569'
                            }
                        },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleFont: { family: "'Plus Jakarta Sans', sans-serif", size: 12, weight: '700' },
                            bodyFont: { family: "'Plus Jakarta Sans', sans-serif", size: 11 },
                            padding: 10,
                            cornerRadius: 8
                        }
                    }
                }
            });
        }
    }

    // Inicializar al cargar o tras SPA mount
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSSTCharts);
    } else {
        initSSTCharts();
    }
</script>
@endsection

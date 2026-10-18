@extends('sst::components.layouts.aprendiz')

@section('title', 'Control de Asistencias • SG-SST')

@section('content')
<style>
    /* Ocultar flecha nativa negra ÚNICAMENTE de inputs con datalist */
    input[list]:not([type="date"]):not([type="datetime-local"])::-webkit-calendar-picker-indicator {
        display: none !important;
        -webkit-appearance: none !important;
        opacity: 0 !important;
        width: 0 !important;
        height: 0 !important;
    }
</style>
<div class="space-y-6 max-w-7xl mx-auto">

    <!-- 1. Encabezado de la Sección -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-5 transition-all">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-orange-50 to-amber-50 text-orange-600 flex items-center justify-center border border-orange-200/80 shadow-2xs shrink-0 text-2xl">
                <i class="fa-solid fa-clipboard-user"></i>
            </div>
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-orange-50 text-orange-700 text-xs font-bold mb-1 border border-orange-200/60">
                    <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span> Control Central de Asistencia
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Historial de Asistencias SST</h1>
                <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">Control unificado y planillas de participación en Charlas, Pausas Activas y Sensibilización.</p>
            </div>
        </div>

        <!-- Acciones Rápidas hacia las actividades -->
        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('SST.aprendiz.actividades_sst.charlas.index') }}" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-bold transition border border-blue-200 shadow-2xs">
                <i class="fa-solid fa-comments text-[11px]"></i>
                <span>Charlas</span>
            </a>
            <a href="{{ route('SST.aprendiz.actividades_sst.pausas_activas.index') }}" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-orange-50 hover:bg-orange-100 text-orange-700 text-xs font-bold transition border border-orange-200 shadow-2xs">
                <i class="fa-solid fa-person-running text-[11px]"></i>
                <span>Pausas Activas</span>
            </a>
            <a href="{{ route('SST.aprendiz.actividades_sst.sensibilizacion.index') }}" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-purple-50 hover:bg-purple-100 text-purple-700 text-xs font-bold transition border border-purple-200 shadow-2xs">
                <i class="fa-solid fa-bullhorn text-[11px]"></i>
                <span>Sensibilización</span>
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between shadow-2xs">
        <div class="flex items-center gap-3">
            <span class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center font-bold text-sm shrink-0">
                <i class="fa-solid fa-check"></i>
            </span>
            <p class="text-xs sm:text-sm font-bold">{{ session('success') }}</p>
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 text-xs font-bold cursor-pointer">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    @endif

    <!-- 2. KPIs y Métricas Consolidadas -->
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Tarjeta 1: Total Asistencias -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Asistencias Realizadas</span>
                <span class="text-2xl sm:text-3xl font-black text-slate-800">{{ $stats['total_realizadas'] }}</span>
                <span class="text-[10px] text-emerald-600 font-bold block mt-0.5">Jornadas completadas</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-clipboard-check"></i>
            </div>
        </div>

        <!-- Tarjeta 2: Total Aprendices Asistentes -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Aprendices Asistentes</span>
                <span class="text-2xl sm:text-3xl font-black text-slate-800">{{ $stats['total_asistentes'] }}</span>
                <span class="text-[10px] text-blue-600 font-bold block mt-0.5">Asistencias acumuladas</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>

        <!-- Tarjeta 3: Charlas y Pausas Activas -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Charlas / Pausas</span>
                <div class="flex items-center gap-2">
                    <span class="text-lg sm:text-xl font-black text-blue-600">{{ $stats['charlas_count'] }} <span class="text-xs text-slate-400 font-medium">charlas</span></span>
                    <span class="text-slate-300">|</span>
                    <span class="text-lg sm:text-xl font-black text-orange-600">{{ $stats['pausas_count'] }} <span class="text-xs text-slate-400 font-medium">pausas</span></span>
                </div>
                <span class="text-[10px] text-slate-400 font-semibold block mt-0.5">Con asistencia tomada</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-chart-pie"></i>
            </div>
        </div>

        <!-- Tarjeta 4: Fichas Formativas Impactadas -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Fichas Impactadas</span>
                <span class="text-2xl sm:text-3xl font-black text-purple-700">{{ $stats['fichas_impactadas'] }}</span>
                <span class="text-[10px] text-purple-500 font-bold block mt-0.5">Grupos convocados</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-users-rectangle"></i>
            </div>
        </div>
    </div>

    <!-- 3. Barra de Búsqueda y Filtros -->
    <div class="bg-white p-4 sm:p-5 rounded-3xl border border-slate-200/80 shadow-xs">
        <form method="GET" action="{{ route('SST.aprendiz.asistencia.index') }}" class="space-y-3">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <!-- Buscador de texto -->
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-slate-400 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por tema, ficha, instructor..." class="w-full pl-9 pr-3.5 py-2 text-xs font-medium text-slate-700 bg-slate-50 rounded-2xl border border-slate-200 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition">
                </div>

                <!-- Filtro Tipo de Actividad -->
                <div>
                    <select name="tipo_actividad" class="w-full px-3.5 py-2 text-xs font-semibold text-slate-700 bg-slate-50 rounded-2xl border border-slate-200 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 cursor-pointer transition">
                        <option value="todos" {{ request('tipo_actividad') == 'todos' || !request('tipo_actividad') ? 'selected' : '' }}>Todas las Actividades</option>
                        <option value="Charla" {{ request('tipo_actividad') == 'Charla' ? 'selected' : '' }}>Charlas de Seguridad</option>
                        <option value="Pausa Activa" {{ request('tipo_actividad') == 'Pausa Activa' ? 'selected' : '' }}>Pausas Activas</option>
                        <option value="Sensibilización" {{ request('tipo_actividad') == 'Sensibilización' ? 'selected' : '' }}>Jornadas de Sensibilización</option>
                    </select>
                </div>

                <!-- Filtro Ficha Formativa -->
                <div class="relative">
                    <input type="text" 
                           name="ficha" 
                           list="lista_fichas_filtro_asistencias" 
                           value="{{ request('ficha') }}" 
                           placeholder="Filtrar por ficha o programa..." 
                           autocomplete="off"
                           onclick="this.showPicker ? this.showPicker() : null"
                           class="w-full pl-3.5 pr-8 py-2 text-xs font-semibold text-slate-700 bg-slate-50 rounded-2xl border border-slate-200 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition">
                    <span class="absolute inset-y-0 right-0 flex items-center pr-2.5 pointer-events-none text-slate-400">
                        <i class="fa-solid fa-chevron-down text-[10px]"></i>
                    </span>
                    <datalist id="lista_fichas_filtro_asistencias">
                        @if(isset($cursos) && count($cursos) > 0)
                            @foreach($cursos as $c)
                                <option value="{{ $c->code }}">{{ $c->code }} - {{ $c->program->name ?? '' }}</option>
                            @endforeach
                        @endif
                    </datalist>
                </div>

                <!-- Botones Aplicar y Limpiar -->
                <div class="flex items-center gap-2">
                    <button type="submit" class="flex-1 py-2 px-4 rounded-2xl bg-orange-600 hover:bg-orange-700 text-white text-xs font-bold shadow-xs hover:shadow-md transition cursor-pointer flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-filter text-[11px]"></i>
                        <span>Filtrar</span>
                    </button>
                    @if(request()->hasAny(['search', 'tipo_actividad', 'ficha', 'estado', 'fecha_desde', 'fecha_hasta']))
                    <a href="{{ route('SST.aprendiz.asistencia.index') }}" class="py-2 px-3 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold transition flex items-center justify-center" title="Limpiar filtros">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- 4. Listado Consolidado de Asistencias Registradas -->
    @if(isset($asistencias) && $asistencias->count() > 0)
        <div class="grid grid-cols-1 gap-3.5">
            @foreach($asistencias as $item)
                @php
                    $esCharla = $item->tipo_actividad === 'Charla';
                    $esPausa = $item->tipo_actividad === 'Pausa Activa';
                    $esSensib = $item->tipo_actividad === 'Sensibilización';

                    $colorTema = $esCharla ? 'blue' : ($esPausa ? 'orange' : 'purple');
                    $iconoTema = $esCharla ? 'fa-comments' : ($esPausa ? 'fa-person-running' : 'fa-bullhorn');

                    $payload = base64_encode(json_encode([
                        'id_actividad' => $item->id_actividad,
                        'tipo_actividad' => $item->tipo_actividad,
                        'titulo' => $item->titulo,
                        'descripcion' => $item->descripcion ?? $item->actividad_a_realizar,
                        'fecha' => $item->fecha,
                        'hora_inicio' => $item->hora_inicio,
                        'hora_fin' => $item->hora_fin,
                        'lugar' => $item->lugar,
                        'instructor' => $item->instructor,
                        'responsable' => $item->responsable,
                        'ficha_programa' => $item->ficha_programa,
                        'numero_asistentes' => $item->numero_asistentes,
                        'participantes' => $item->participantes,
                        'observaciones' => $item->observaciones,
                        'estado' => $item->estado
                    ]));
                @endphp

                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs hover:shadow-md transition-all p-4 sm:p-5 flex flex-col md:flex-row md:items-center justify-between gap-4 group hover:border-slate-300">
                    
                    <!-- Columna Izquierda: Ícono del Tipo y Título -->
                    <div class="flex items-start sm:items-center gap-3.5 flex-1 min-w-0">
                        <div class="w-12 h-12 rounded-2xl bg-{{ $colorTema }}-50 text-{{ $colorTema }}-600 border border-{{ $colorTema }}-100 flex items-center justify-center text-xl shrink-0 group-hover:scale-105 transition-transform shadow-2xs">
                            <i class="fa-solid {{ $iconoTema }}"></i>
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2 flex-wrap mb-1">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-{{ $colorTema }}-100 text-{{ $colorTema }}-800 border border-{{ $colorTema }}-200/60 uppercase tracking-wider">
                                    {{ $item->tipo_actividad }}
                                </span>

                                @if($item->estado === 'Realizada')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-300">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Asistencia Registrada
                                    </span>
                                @elseif($item->estado === 'Cancelada')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-50 text-rose-700 border border-rose-300">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Cancelada
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-50 text-amber-700 border border-amber-300">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Programada
                                    </span>
                                @endif

                                <span class="text-[11px] font-bold text-slate-500 flex items-center gap-1">
                                    <i class="fa-regular fa-calendar text-[10px] text-slate-400"></i>
                                    {{ \Carbon\Carbon::parse($item->fecha)->translatedFormat('d M, Y') }}
                                </span>
                            </div>

                            <h3 class="font-extrabold text-sm sm:text-base text-slate-900 group-hover:text-{{ $colorTema }}-600 transition truncate">
                                {{ $item->titulo }}
                            </h3>

                            <div class="flex items-center gap-3 text-xs text-slate-500 flex-wrap mt-1">
                                @if($item->ficha_programa)
                                <span class="inline-flex items-center gap-1.5 text-slate-600 font-semibold truncate max-w-xs" title="{{ $item->ficha_programa }}">
                                    <i class="fa-solid fa-users-rectangle text-{{ $colorTema }}-500 text-[11px]"></i>
                                    {{ $item->ficha_programa }}
                                </span>
                                @endif

                                @if($item->instructor)
                                <span class="inline-flex items-center gap-1 text-slate-500">
                                    <i class="fa-solid fa-chalkboard-user text-slate-400 text-[10px]"></i>
                                    {{ $item->instructor }}
                                </span>
                                @endif

                                @if($item->lugar)
                                <span class="inline-flex items-center gap-1 text-slate-400 text-[11px]">
                                    <i class="fa-solid fa-location-dot text-slate-400 text-[10px]"></i>
                                    {{ $item->lugar }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Columna Derecha: Conteo Asistentes y Botón Ver Planilla -->
                    <div class="flex items-center justify-between md:justify-end gap-3 shrink-0 pt-3 md:pt-0 border-t md:border-t-0 border-slate-100">
                        <div class="text-left md:text-right">
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-slate-100/90 text-slate-800 font-extrabold text-xs">
                                <i class="fa-solid fa-user-check text-emerald-600"></i>
                                <span>{{ (int) $item->numero_asistentes }} Asistentes</span>
                            </div>
                            <span class="text-[10px] text-slate-400 block mt-0.5">Participación confirmada</span>
                        </div>

                        <button type="button" 
                                onclick="openPlanillaModal('{{ $payload }}')" 
                                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold shadow-xs hover:shadow-md transition cursor-pointer">
                            <i class="fa-solid fa-file-invoice text-xs text-orange-400"></i>
                            <span>Ver Planilla</span>
                        </button>
                    </div>

                </div>
            @endforeach
        </div>

        <!-- Paginación -->
        <div class="pt-4">
            {{ $asistencias->links() }}
        </div>
    @else
        <!-- Estado Vacío -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-12 text-center flex flex-col items-center justify-center">
            <div class="w-16 h-16 rounded-2xl bg-orange-50 text-orange-400 flex items-center justify-center text-2xl mb-3 shadow-2xs">
                <i class="fa-solid fa-clipboard-user"></i>
            </div>
            <h3 class="font-extrabold text-slate-800 text-base">No hay registros de asistencia</h3>
            <p class="text-xs text-slate-400 mt-1 max-w-sm">No se encontraron actividades con asistencia registrada bajo los filtros seleccionados.</p>
        </div>
    @endif

</div>

<!-- Modal Planilla Oficial de Asistencia SST -->
<div id="modalPlanillaOverlay" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-3 sm:p-4 hidden opacity-0 transition-opacity duration-200" onclick="closePlanillaModal()">
    <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 max-w-2xl w-full max-h-[92vh] flex flex-col transform transition-transform duration-200 scale-95 overflow-hidden" onclick="event.stopPropagation()">
        
        <!-- Header del Modal Planilla -->
        <div class="p-5 sm:p-6 pb-4 border-b border-slate-100 flex items-center justify-between shrink-0 bg-white">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-orange-50 to-amber-50 text-orange-600 border border-orange-200/80 flex items-center justify-center text-xl shadow-2xs shrink-0">
                    <i class="fa-solid fa-clipboard-check"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="font-extrabold text-base sm:text-lg text-slate-900 tracking-tight leading-tight">Planilla de Asistencia SST</h3>
                        <span id="modalPlanillaTipoBadge" class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase"></span>
                    </div>
                    <p class="text-[11px] font-semibold text-slate-400">Control oficial de llamado a lista y autocuidado laboral</p>
                </div>
            </div>
            
            <div class="flex items-center gap-2">
                <button type="button" onclick="imprimirPlanilla()" class="px-3 py-1.5 rounded-xl bg-orange-50 hover:bg-orange-100 text-orange-700 text-xs font-bold transition flex items-center gap-1.5 cursor-pointer" title="Imprimir Planilla">
                    <i class="fa-solid fa-print text-xs"></i>
                    <span class="hidden sm:inline">Imprimir</span>
                </button>
                <button type="button" onclick="closePlanillaModal()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center text-xs transition cursor-pointer">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>

        <!-- Contenido de la Planilla -->
        <div class="p-5 sm:p-6 space-y-4 overflow-y-auto flex-1 printable-area" id="areaImpresionPlanilla">
            
            <!-- Encabezado Institucional para Planilla -->
            <div class="border-b border-slate-200 pb-3 flex items-center justify-between">
                <div>
                    <h2 class="font-black text-slate-800 text-sm sm:text-base" id="modalPlanillaTitulo"></h2>
                    <p class="text-xs text-slate-500 font-medium" id="modalPlanillaDescripcion"></p>
                </div>
                <div class="text-right shrink-0">
                    <span class="text-[10px] font-bold text-slate-400 block uppercase">Fecha de Jornada</span>
                    <strong class="text-xs text-slate-800 block" id="modalPlanillaFecha"></strong>
                    <span class="text-[10px] text-slate-500 block" id="modalPlanillaHorario"></span>
                </div>
            </div>

            <!-- Ficha Técnica de la Jornada (3 columnas compactas) -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 p-3 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs">
                <div>
                    <span class="text-[10px] font-bold text-slate-400 block uppercase">Ficha / Programa:</span>
                    <strong class="text-slate-800 font-extrabold truncate block" id="modalPlanillaFicha"></strong>
                </div>
                <div>
                    <span class="text-[10px] font-bold text-slate-400 block uppercase">Instructora a Cargo:</span>
                    <span class="text-slate-700 font-bold block" id="modalPlanillaInstructor"></span>
                </div>
                <div>
                    <span class="text-[10px] font-bold text-slate-400 block uppercase">Encargada SST:</span>
                    <span class="text-slate-700 font-bold block" id="modalPlanillaResponsable"></span>
                </div>
            </div>

            <!-- Resumen de Participación -->
            <div class="flex items-center justify-between p-3 rounded-2xl bg-emerald-50/70 border border-emerald-100">
                <div class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center text-xs font-bold">
                        <i class="fa-solid fa-users"></i>
                    </span>
                    <div>
                        <span class="text-[10px] font-bold text-emerald-800 block uppercase">Total Asistentes Registrados</span>
                        <strong class="text-xs sm:text-sm text-emerald-900" id="modalPlanillaTotalAsistentes"></strong>
                    </div>
                </div>
                <span class="px-2.5 py-1 rounded-xl bg-emerald-100 text-emerald-800 font-extrabold text-[11px]">
                    100% Verificado
                </span>
            </div>

            <!-- Listado Detallado de Aprendices Asistentes -->
            <div class="space-y-2">
                <h4 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider flex items-center justify-between">
                    <span>Listado de Aprendices Presentes</span>
                    <span class="text-[10px] text-slate-400 normal-case font-medium" id="modalPlanillaSubtituloLista"></span>
                </h4>

                <div id="modalPlanillaListaAprendices" class="max-h-60 overflow-y-auto space-y-1 rounded-2xl border border-slate-200 p-2 bg-slate-50/50">
                    <!-- Se inyecta dinámicamente -->
                </div>
            </div>

            <!-- Observaciones Registradas -->
            <div id="modalPlanillaObsContainer" class="p-3 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-1 hidden">
                <span class="text-[10px] font-bold text-slate-400 uppercase block">Observaciones y Acuerdos:</span>
                <p class="text-xs text-slate-600 italic leading-relaxed" id="modalPlanillaObservaciones"></p>
            </div>

        </div>

        <!-- Footer del Modal Planilla -->
        <div class="p-4 sm:p-5 border-t border-slate-100 flex items-center justify-between bg-slate-50/70 shrink-0">
            <span class="text-[11px] text-slate-400 font-medium">Sistema de Gestión de Seguridad y Salud en el Trabajo SENA</span>
            <button type="button" onclick="closePlanillaModal()" class="px-5 py-2.5 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition shadow-2xs cursor-pointer">
                Cerrar Planilla
            </button>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    function formatFechaHumana(fechaStr) {
        if (!fechaStr) return 'Fecha por confirmar';
        try {
            const meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
            const p = fechaStr.split('-');
            if (p.length === 3) {
                const dia = parseInt(p[2], 10);
                const mes = meses[parseInt(p[1], 10) - 1];
                const anio = p[0];
                return `${dia} de ${mes} de ${anio}`;
            }
        } catch(e) {}
        return fechaStr;
    }

    function formatHoraHumana(horaStr) {
        if (!horaStr) return '';
        try {
            const partes = horaStr.split(':');
            if (partes.length >= 2) {
                let horas = parseInt(partes[0], 10);
                const minutos = partes[1];
                const ampm = horas >= 12 ? 'PM' : 'AM';
                horas = horas % 12;
                horas = horas ? horas : 12;
                const horasStr = horas < 10 ? '0' + horas : horas;
                return `${horasStr}:${minutos} ${ampm}`;
            }
        } catch(e) {}
        return horaStr;
    }

    function toggleModal(id, show) {
        const overlay = document.getElementById(id);
        if (!overlay) return;
        const box = overlay.querySelector('div');
        if (show) {
            overlay.classList.remove('hidden');
            requestAnimationFrame(() => {
                overlay.classList.remove('opacity-0');
                if (box) { box.classList.remove('scale-95'); box.classList.add('scale-100'); }
            });
        } else {
            overlay.classList.add('opacity-0');
            if (box) { box.classList.remove('scale-100'); box.classList.add('scale-95'); }
            setTimeout(() => overlay.classList.add('hidden'), 200);
        }
    }

    function openPlanillaModal(payloadBase64) {
        try {
            const data = JSON.parse(atob(payloadBase64));
            
            document.getElementById('modalPlanillaTitulo').textContent = data.titulo || 'Actividad SST';
            document.getElementById('modalPlanillaDescripcion').textContent = data.descripcion || 'Registro oficial de asistencia preventiva.';
            document.getElementById('modalPlanillaFecha').textContent = formatFechaHumana(data.fecha);
            
            const hIni = formatHoraHumana(data.hora_inicio);
            const hFin = formatHoraHumana(data.hora_fin);
            document.getElementById('modalPlanillaHorario').textContent = hIni ? (hFin ? `${hIni} – ${hFin}` : hIni) : '';

            // Badge de Tipo
            const badgeTipo = document.getElementById('modalPlanillaTipoBadge');
            badgeTipo.textContent = data.tipo_actividad;
            if (data.tipo_actividad === 'Charla') {
                badgeTipo.className = 'px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-blue-100 text-blue-800';
            } else if (data.tipo_actividad === 'Pausa Activa') {
                badgeTipo.className = 'px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-orange-100 text-orange-800';
            } else {
                badgeTipo.className = 'px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-purple-100 text-purple-800';
            }

            // Datos Ficha Técnica
            document.getElementById('modalPlanillaFicha').textContent = data.ficha_programa || 'General / Sin ficha';
            document.getElementById('modalPlanillaInstructor').textContent = data.instructor || 'Equipo SST';
            document.getElementById('modalPlanillaResponsable').textContent = data.responsable || 'Aprendiz SST';

            const numAsistentes = (data.numero_asistentes !== null && data.numero_asistentes !== undefined) ? parseInt(data.numero_asistentes, 10) : 0;
            document.getElementById('modalPlanillaTotalAsistentes').textContent = `${numAsistentes} Aprendices Presentes`;

            // Listado de Aprendices
            const listaCont = document.getElementById('modalPlanillaListaAprendices');
            const participantesRaw = (data.participantes || '').trim();

            if (participantesRaw) {
                // Separar por salto de línea o por coma
                const lineas = participantesRaw.split(/\r?\n|,/).map(l => l.trim()).filter(l => l.length > 0);
                let html = '';
                lineas.forEach((nombre, index) => {
                    html += `
                        <div class="flex items-center justify-between p-2 rounded-xl bg-white border border-slate-200/70 text-xs">
                            <div class="flex items-center gap-2 truncate">
                                <span class="w-5 h-5 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center font-bold text-[10px] shrink-0">${index + 1}</span>
                                <span class="font-bold text-slate-800 truncate">${nombre}</span>
                            </div>
                            <span class="inline-flex items-center gap-1 text-[10px] font-extrabold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-lg border border-emerald-200 shrink-0">
                                <i class="fa-solid fa-check text-[9px]"></i> Presente
                            </span>
                        </div>
                    `;
                });
                listaCont.innerHTML = html;
                document.getElementById('modalPlanillaSubtituloLista').textContent = `${lineas.length} aprendices en planilla`;
            } else {
                listaCont.innerHTML = `
                    <div class="p-4 text-center text-xs text-slate-400">
                        <i class="fa-solid fa-clipboard-user text-lg mb-1 block text-slate-300"></i>
                        <span>No se ingresaron nombres individuales para esta asistencia (conteo registrado: ${numAsistentes}).</span>
                    </div>
                `;
                document.getElementById('modalPlanillaSubtituloLista').textContent = '';
            }

            // Observaciones
            const obsCont = document.getElementById('modalPlanillaObsContainer');
            if (data.observaciones && data.observaciones.trim()) {
                obsCont.classList.remove('hidden');
                document.getElementById('modalPlanillaObservaciones').textContent = data.observaciones;
            } else {
                obsCont.classList.add('hidden');
            }

            toggleModal('modalPlanillaOverlay', true);
        } catch(e) {
            console.error('Error abriendo planilla modal:', e);
        }
    }

    function closePlanillaModal() {
        toggleModal('modalPlanillaOverlay', false);
    }

    function imprimirPlanilla() {
        window.print();
    }
</script>
@endpush

<style>
@media print {
    body * {
        visibility: hidden;
    }
    #modalPlanillaOverlay, #modalPlanillaOverlay * {
        visibility: visible;
    }
    #modalPlanillaOverlay {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: auto;
        background: white !important;
        padding: 0 !important;
    }
    .printable-area {
        max-height: none !important;
        overflow: visible !important;
    }
    button {
        display: none !important;
    }
}
</style>

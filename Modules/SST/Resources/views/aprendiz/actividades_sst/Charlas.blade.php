@extends('sst::components.layouts.aprendiz')

@section('title', 'Charlas de Seguridad • Actividades SST')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">

    <!-- 1. Encabezado de la Sección -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4 transition-all">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100 shadow-2xs shrink-0 text-2xl">
                <i class="fa-solid fa-comments"></i>
            </div>
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-bold mb-1 border border-blue-200/60">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Actividades SST
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Charlas de Seguridad SST</h1>
                <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">Espacios de inducción, diálogo de 5 minutos y capacitación preventiva en el entorno laboral.</p>
            </div>
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
        <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 text-sm font-bold cursor-pointer">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    @endif

    <!-- 2. Tarjetas de Resumen KPI -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xl shrink-0">
                <i class="fa-solid fa-chalkboard-user"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Charlas</p>
                <h4 class="text-2xl font-extrabold text-slate-800">{{ $stats['total'] }}</h4>
            </div>
        </div>

        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-xl shrink-0">
                <i class="fa-solid fa-clock"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Próximas / Programadas</p>
                <h4 class="text-2xl font-extrabold text-slate-800">{{ $stats['programadas'] }}</h4>
            </div>
        </div>

        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xl shrink-0">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Realizadas</p>
                <h4 class="text-2xl font-extrabold text-slate-800">{{ $stats['realizadas'] }}</h4>
            </div>
        </div>
    </div>

    <!-- 3. Barra de Búsqueda y Filtros -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
        <form method="GET" action="{{ route('SST.aprendiz.actividades_sst.charlas.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="relative sm:col-span-2">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar charla por título, tema, instructor o lugar..."
                    class="w-full pl-9 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
            </div>

            <div class="flex items-center gap-2">
                <select name="estado" onchange="this.form.submit()"
                    class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                    <option value="todos" {{ request('estado') == 'todos' ? 'selected' : '' }}>Todos los estados</option>
                    <option value="Programada" {{ request('estado') == 'Programada' ? 'selected' : '' }}>Programadas</option>
                    <option value="Realizada" {{ request('estado') == 'Realizada' ? 'selected' : '' }}>Realizadas</option>
                    <option value="Cancelada" {{ request('estado') == 'Cancelada' ? 'selected' : '' }}>Canceladas</option>
                </select>

                @if(request()->hasAny(['search', 'estado']))
                    <a href="{{ route('SST.aprendiz.actividades_sst.charlas.index') }}" class="px-3 py-2.5 text-xs font-bold text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition flex items-center justify-center shrink-0" title="Limpiar filtros">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- 4. Listado de Charlas (Horizontal / De largo) -->
    @if($actividades->count() > 0)
        <div class="space-y-3 sm:space-y-3.5">
            @foreach($actividades as $item)
                @php
                    $fechaObj = \Carbon\Carbon::parse($item->fecha);
                    $horaIni = \Carbon\Carbon::parse($item->hora_inicio)->format('h:i A');
                    $horaFin = $item->hora_fin ? \Carbon\Carbon::parse($item->hora_fin)->format('h:i A') : null;
                    $rawData = base64_encode(json_encode($item, JSON_UNESCAPED_UNICODE));
                    
                    $badgeEstado = match($item->estado) {
                        'Programada' => 'bg-amber-50 text-amber-700 border-amber-300',
                        'Realizada' => 'bg-emerald-50 text-emerald-700 border-emerald-300',
                        'Cancelada' => 'bg-rose-50 text-rose-700 border-rose-300',
                        default => 'bg-slate-50 text-slate-700 border-slate-300'
                    };
                @endphp
                <div class="item-card bg-slate-50/70 hover:bg-white p-4 sm:p-5 rounded-3xl border border-slate-200/80 shadow-2xs hover:shadow-md hover:border-blue-300 transition-all flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-5 cursor-pointer group"
                     onclick="openDetailModal(this)"
                     data-raw="{{ $rawData }}">

                    <!-- Ícono de la Charla -->
                    <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200/80 flex flex-col items-center justify-center text-blue-600 shrink-0 shadow-2xs group-hover:border-blue-400 group-hover:scale-105 transition">
                        <i class="fa-solid fa-comments text-2xl text-blue-600"></i>
                    </div>

                    <!-- Contenido Central Estructurado -->
                    <div class="flex-1 flex flex-col justify-between space-y-2.5 w-full">
                        <!-- Fila 1: Título y Estado -->
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <h3 class="font-extrabold text-slate-900 text-sm sm:text-base flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-blue-500 shrink-0"></span>
                                <span class="group-hover:text-blue-600 transition-colors">{{ $item->titulo }}</span>
                            </h3>

                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-extrabold border {{ $badgeEstado }} shadow-2xs select-none">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $item->estado === 'Realizada' ? 'bg-emerald-500' : ($item->estado === 'Cancelada' ? 'bg-rose-500' : 'bg-amber-500') }}"></span>
                                    <span>{{ $item->estado }}</span>
                                </span>
                            </div>
                        </div>

                        <!-- Fila 2: Chips informativos -->
                        <div class="flex flex-wrap items-center gap-2 text-[11px] font-medium text-slate-600">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-white border border-slate-200/80 shadow-2xs" title="Fecha Programada">
                                <i class="fa-regular fa-calendar text-blue-500 text-[10px]"></i>
                                <span class="text-slate-400 font-semibold">Fecha:</span>
                                <strong class="font-bold text-slate-700">{{ $fechaObj->isoFormat('D MMM, Y') }}</strong>
                            </span>

                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-white border border-slate-200/80 shadow-2xs" title="Horario">
                                <i class="fa-regular fa-clock text-blue-500 text-[10px]"></i>
                                <span class="text-slate-400 font-semibold">Horario:</span>
                                <strong class="font-bold text-slate-700">{{ $horaIni }} @if($horaFin) - {{ $horaFin }} @endif</strong>
                            </span>

                            @if($item->lugar)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-white border border-slate-200/80 shadow-2xs" title="Lugar / Ubicación">
                                <i class="fa-solid fa-location-dot text-blue-500 text-[10px]"></i>
                                <span class="text-slate-400 font-semibold">Lugar:</span>
                                <strong class="font-bold text-slate-700">{{ $item->lugar }}</strong>
                            </span>
                            @endif

                            @if($item->instructor)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-white border border-slate-200/80 shadow-2xs" title="Instructor / Responsable">
                                <i class="fa-solid fa-chalkboard-user text-blue-500 text-[10px]"></i>
                                <span class="text-slate-400 font-semibold">Responsable:</span>
                                <strong class="font-bold text-slate-700">{{ $item->instructor }}</strong>
                            </span>
                            @endif

                            @if($item->ficha_programa)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-white border border-slate-200/80 shadow-2xs" title="Ficha / Programa">
                                <i class="fa-solid fa-users-rectangle text-blue-500 text-[10px]"></i>
                                <span class="text-slate-400 font-semibold">Ficha:</span>
                                <strong class="font-bold text-slate-700">{{ $item->ficha_programa }}</strong>
                            </span>
                            @endif
                        </div>

                        <!-- Fila 3: Descripción y Botones Asistencia / Ver Ficha -->
                        <div class="flex items-center justify-between gap-3 pt-0.5">
                            <p class="text-xs text-slate-500 line-clamp-1 flex-1 leading-relaxed">
                                <strong class="font-bold text-slate-700">Contenido:</strong> {{ $item->descripcion ?? $item->actividad_a_realizar ?? 'Sin descripción adicional.' }}
                            </p>

                            <div class="flex items-center gap-1.5 shrink-0">
                                <button type="button" onclick="event.stopPropagation(); openDetailModal(this.closest('.item-card'))" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center text-xs transition cursor-pointer" title="Ver Ficha Técnica">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                                <button type="button" onclick="event.stopPropagation(); openAsistenciaModal(this.closest('.item-card'))" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-xs hover:shadow-md transition cursor-pointer">
                                    <i class="fa-solid fa-clipboard-user text-xs"></i>
                                    <span>Asistencia</span>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

        <!-- Paginación -->
        <div class="pt-4">
            {{ $actividades->links() }}
        </div>
    @else
        <!-- Estado Vacío -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-12 text-center flex flex-col items-center justify-center">
            <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-400 flex items-center justify-center text-2xl mb-3 shadow-2xs">
                <i class="fa-solid fa-comments"></i>
            </div>
            <h3 class="font-extrabold text-slate-800 text-base">No hay charlas registradas</h3>
            <p class="text-xs text-slate-400 mt-1 max-w-sm">No se encontraron charlas de seguridad programadas con los filtros actuales.</p>
        </div>
    @endif

</div>

<!-- Modal Formulario de Asistencia (Llamado a Lista) -->
<div id="modalAsistenciaOverlay" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-3 sm:p-4 hidden opacity-0 transition-opacity duration-200" onclick="closeAsistenciaModal()">
    <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 max-w-xl w-full max-h-[92vh] flex flex-col transform transition-transform duration-200 scale-95 overflow-hidden" onclick="event.stopPropagation()">
        
        <!-- Header del Modal Asistencia -->
        <div class="p-5 sm:p-6 pb-4 border-b border-slate-100 flex items-center justify-between shrink-0 bg-white">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-50 to-indigo-50 text-blue-600 border border-blue-200/80 flex items-center justify-center text-xl shadow-2xs shrink-0">
                    <i class="fa-solid fa-clipboard-user"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-base sm:text-lg text-slate-900 tracking-tight leading-tight">Control de Asistencia SST</h3>
                    <p class="text-[11px] font-semibold text-slate-400">Llamado por Ficha, Instructora y Encargada SST</p>
                </div>
            </div>
            <button type="button" onclick="closeAsistenciaModal()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center text-xs transition cursor-pointer">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Formulario de Asistencia -->
        <form id="formAsistencia" method="POST" action="" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            
            <div class="p-5 sm:p-6 space-y-4 overflow-y-auto flex-1">
                
                <!-- Resumen Compacto de la Charla -->
                <div class="p-3 bg-blue-50/60 rounded-2xl border border-blue-100 flex items-center justify-between gap-3 text-xs">
                    <div class="truncate">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-blue-500 block">Charla Convocada</span>
                        <h4 id="asistenciaCharlaTitulo" class="font-extrabold text-slate-800 text-xs sm:text-sm truncate"></h4>
                    </div>
                    <div class="text-right shrink-0">
                        <span id="asistenciaCharlaFecha" class="font-bold text-slate-700 block text-[11px]"></span>
                        <span id="asistenciaCharlaHorario" class="text-[10px] font-medium text-slate-500"></span>
                    </div>
                </div>

                <!-- Campo 1: Ficha y Programa Formativo (Llamado a lista con Flecha y Desplegable) -->
                <div class="space-y-1 relative" id="ficha_combo_container">
                    <label for="asistencia_ficha_programa" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 flex items-center gap-1.5">
                        <i class="fa-solid fa-users-rectangle text-blue-500 text-xs"></i>
                        Ficha y Programa Formativo <span class="text-rose-500">*</span>
                    </label>
                    
                    <div class="relative">
                        <input type="text" 
                               name="ficha_programa" 
                               id="asistencia_ficha_programa" 
                               required 
                               autocomplete="off"
                               placeholder="Selecciona o busca la ficha (Ej: 32300026 o código de curso)" 
                               class="w-full pl-3.5 pr-10 py-2.5 text-xs font-semibold text-slate-800 rounded-2xl bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                        
                        <button type="button" 
                                id="btnToggleFichas" 
                                onclick="toggleFichasDropdown(event)"
                                class="absolute inset-y-0 right-0 px-3.5 flex items-center text-slate-400 hover:text-blue-600 transition cursor-pointer"
                                title="Desplegar lista de fichas">
                            <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200" id="iconArrowFicha"></i>
                        </button>
                    </div>
                    
                    <p class="text-[10px] text-slate-400">Presiona la flecha para desplegar las fichas o escribe para filtrar.</p>

                    <!-- Menú Desplegable de Fichas (Se abre al presionar la flecha) -->
                    <div id="fichas_dropdown_menu" class="absolute z-50 left-0 right-0 mt-1 max-h-52 overflow-y-auto bg-white rounded-2xl border border-slate-200 shadow-xl divide-y divide-slate-100 hidden">
                        @if(isset($cursos) && count($cursos) > 0)
                            @foreach($cursos as $c)
                                @php
                                    $fichaTexto = $c->code . ' - ' . ($c->program->name ?? 'Programa de Formación');
                                @endphp
                                <div class="ficha-option px-3.5 py-2.5 hover:bg-blue-50 cursor-pointer flex items-center justify-between text-xs transition group"
                                     data-text="{{ strtolower($fichaTexto) }}"
                                     data-value="{{ $fichaTexto }}"
                                     onclick="selectFichaOption(this.getAttribute('data-value'))">
                                    <div class="truncate">
                                        <strong class="font-extrabold text-slate-800 group-hover:text-blue-600">{{ $c->code }}</strong>
                                        <span class="text-slate-500 text-[11px] ml-1.5">{{ $c->program->name ?? '' }}</span>
                                    </div>
                                    <span class="text-[10px] font-bold text-blue-500 opacity-0 group-hover:opacity-100 transition shrink-0 ml-2">Seleccionar</span>
                                </div>
                            @endforeach
                            <div id="no_fichas_match" class="px-4 py-3 text-xs text-slate-400 text-center hidden">No se encontraron fichas coincidentes</div>
                        @else
                            <div class="px-4 py-3 text-xs text-slate-400 text-center">No hay fichas activas registradas en SICA</div>
                        @endif
                    </div>
                </div>

                <!-- Campo 2 y 3: La Instructora y La Encargada de SST en 2 Columnas -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label for="asistencia_instructor" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 flex items-center gap-1.5">
                            <i class="fa-solid fa-chalkboard-user text-blue-500 text-xs"></i>
                            Instructora a Cargo <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="instructor" id="asistencia_instructor" required placeholder="Nombre de la instructora" class="w-full px-3.5 py-2.5 text-xs font-semibold text-slate-800 rounded-2xl bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                    </div>

                    <div class="space-y-1">
                        <label for="asistencia_responsable" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 flex items-center gap-1.5">
                            <i class="fa-solid fa-shield-heart text-blue-500 text-xs"></i>
                            Encargada de SST <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="responsable" id="asistencia_responsable" required placeholder="Responsable / Aprendiz SST" class="w-full px-3.5 py-2.5 text-xs font-semibold text-slate-800 rounded-2xl bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                    </div>
                </div>

                <!-- Total Asistentes y Estado -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label for="asistencia_numero_asistentes" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 flex items-center gap-1.5">
                            <i class="fa-solid fa-user-check text-blue-500 text-xs"></i>
                            Número de Asistentes
                        </label>
                        <input type="number" min="0" name="numero_asistentes" id="asistencia_numero_asistentes" placeholder="0" class="w-full px-3.5 py-2.5 text-xs font-bold text-slate-800 rounded-2xl bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                    </div>

                    <div class="space-y-1">
                        <label for="asistencia_estado" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 flex items-center gap-1.5">
                            <i class="fa-solid fa-tag text-blue-500 text-xs"></i>
                            Estado de la Charla
                        </label>
                        <select name="estado" id="asistencia_estado" class="w-full px-3.5 py-2.5 text-xs font-bold text-slate-700 rounded-2xl bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 cursor-pointer">
                            <option value="Realizada" selected>Realizada (Asistencia Completada)</option>
                            <option value="Programada">Programada</option>
                            <option value="Cancelada">Cancelada</option>
                        </select>
                    </div>
                </div>

                <!-- Sección Dinámica: Llamado a Lista con Checkboxes de Aprendices -->
                <div class="space-y-2 pt-1" id="seccion_llamado_asistencia">
                    <!-- Header de la sección de aprendices -->
                    <div class="flex items-center justify-between gap-2 flex-wrap">
                        <label class="text-[11px] font-bold uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                            <i class="fa-solid fa-clipboard-check text-blue-500 text-xs"></i>
                            <span>Llamado a Lista</span>
                            <span id="badge_conteo_asistentes" class="ml-1.5 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-100 text-blue-700 hidden">
                                <span id="txt_asistentes_count">0</span> seleccionados
                            </span>
                        </label>

                        <div class="flex items-center gap-1.5" id="controles_marcado_asistencia" style="display: none;">
                            <button type="button" onclick="marcarTodosAprendices(true)" class="px-2.5 py-1 text-[10px] font-bold rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 transition cursor-pointer flex items-center gap-1">
                                <i class="fa-solid fa-check-double text-[9px]"></i> Marcar todos
                            </button>
                            <button type="button" onclick="marcarTodosAprendices(false)" class="px-2.5 py-1 text-[10px] font-bold rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 transition cursor-pointer">
                                Desmarcar
                            </button>
                            <button type="button" onclick="toggleModoManual()" id="btnModoManual" class="px-2.5 py-1 text-[10px] font-bold rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 transition cursor-pointer" title="Alternar entre lista de aprendices y texto libre">
                                <i class="fa-solid fa-pen-to-square text-[9px]"></i> Modo manual
                            </button>
                        </div>
                    </div>

                    <!-- Buscador interno de aprendiz (solo visible si hay lista cargada) -->
                    <div id="contenedor_buscador_aprendices" class="relative hidden">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                        <input type="text" id="filtro_buscador_aprendiz" placeholder="Buscar aprendiz por nombre o cédula..." class="w-full pl-8 pr-3 py-1.5 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 transition">
                    </div>

                    <!-- Indicador de Carga (Spinner) -->
                    <div id="loading_aprendices" class="p-4 rounded-2xl bg-blue-50/50 border border-blue-100 text-center text-xs text-blue-600 font-semibold hidden">
                        <i class="fa-solid fa-circle-notch fa-spin mr-2"></i> Consultando aprendices matriculados en la ficha...
                    </div>

                    <!-- Mensaje cuando no se ha seleccionado ficha o ficha no encontrada -->
                    <div id="empty_aprendices_msg" class="p-4 rounded-2xl bg-slate-50 border border-dashed border-slate-200 text-center text-xs text-slate-400 font-medium">
                        <i class="fa-solid fa-users-viewfinder text-lg mb-1 block text-slate-300"></i>
                        <span>Selecciona una ficha arriba para cargar el listado de aprendices y chulear asistencia.</span>
                    </div>

                    <!-- Lista scrolleable de aprendices con checkboxes -->
                    <div id="lista_aprendices_container" class="max-h-56 overflow-y-auto space-y-1.5 pr-1 hidden">
                        <!-- Se llena dinámicamente con JavaScript -->
                    </div>

                    <!-- Campo Textarea Sincronizado (Participantes) -->
                    <div id="contenedor_textarea_participantes" class="space-y-1 hidden">
                        <label for="asistencia_participantes" class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 flex items-center justify-between">
                            <span>Listado / Nombres de Asistentes</span>
                            <span class="text-[9px] text-slate-400 normal-case font-normal">(Sincronizado automáticamente al chulear)</span>
                        </label>
                        <textarea name="participantes" id="asistencia_participantes" rows="2" placeholder="Ingresa los nombres o números de documento de los aprendices que respondieron al llamado..." class="w-full p-2.5 text-xs font-medium text-slate-700 rounded-2xl bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition leading-relaxed"></textarea>
                    </div>
                </div>

                <!-- Observaciones Adicionales -->
                <div class="space-y-1">
                    <label for="asistencia_observaciones" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 flex items-center gap-1.5">
                        <i class="fa-regular fa-comment-dots text-blue-500 text-xs"></i>
                        Observaciones de la Jornada
                    </label>
                    <textarea name="observaciones" id="asistencia_observaciones" rows="2" placeholder="Novedades, observaciones o acuerdos durante la asistencia..." class="w-full p-3 text-xs font-medium text-slate-700 rounded-2xl bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition"></textarea>
                </div>

            </div>

            <!-- Footer del Formulario -->
            <div class="p-4 sm:p-5 border-t border-slate-100 flex items-center justify-between bg-slate-50/70 shrink-0">
                <button type="button" onclick="closeAsistenciaModal()" class="px-5 py-2.5 rounded-2xl bg-white border border-slate-200/90 hover:bg-slate-100 text-slate-700 text-xs font-bold transition shadow-2xs cursor-pointer">
                    Cancelar
                </button>
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-extrabold shadow-sm hover:shadow-md transition cursor-pointer">
                    <i class="fa-solid fa-check text-xs"></i>
                    <span>Guardar Asistencia</span>
                </button>
            </div>
        </form>

    </div>
</div>

<!-- Modal Detalle de la Charla (Mejorado y Moderno) -->
<div id="modalDetalleOverlay" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-3 sm:p-4 hidden opacity-0 transition-opacity duration-200" onclick="closeDetailModal()">
    <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 max-w-xl w-full max-h-[90vh] flex flex-col transform transition-transform duration-200 scale-95 overflow-hidden" onclick="event.stopPropagation()">
        
        <!-- Header del Modal -->
        <div class="p-5 sm:p-6 pb-4 border-b border-slate-100 flex items-center justify-between shrink-0 bg-white">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-50 to-indigo-50 text-blue-600 border border-blue-200/80 flex items-center justify-center text-xl shadow-2xs shrink-0">
                    <i class="fa-solid fa-comments"></i>
                </div>
                <div>
                    <h3 id="modalTitulo" class="font-extrabold text-base sm:text-lg text-slate-900 tracking-tight leading-tight"></h3>
                    <div class="flex items-center gap-2 mt-1">
                        <span id="modalBadgeEstado" class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold border"></span>
                        <span class="text-[11px] font-semibold text-slate-400">Charla Preventiva SST</span>
                    </div>
                </div>
            </div>
            <button type="button" onclick="closeDetailModal()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center text-xs transition cursor-pointer">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Cuerpo del Modal -->
        <div class="p-5 sm:p-6 space-y-4 overflow-y-auto flex-1">
            
            <!-- Ficha Técnica de la Charla -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 bg-slate-50/80 p-3.5 rounded-2xl border border-slate-200/80">
                <div class="space-y-0.5">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 flex items-center gap-1.5">
                        <i class="fa-regular fa-calendar text-blue-500 text-[10px]"></i> Fecha Programada
                    </span>
                    <p id="modalFechaTexto" class="font-extrabold text-slate-800 text-xs sm:text-sm"></p>
                </div>

                <div class="space-y-0.5">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 flex items-center gap-1.5">
                        <i class="fa-regular fa-clock text-blue-500 text-[10px]"></i> Horario
                    </span>
                    <p id="modalHorarioTexto" class="font-bold text-slate-800 text-xs sm:text-sm"></p>
                </div>

                <div class="space-y-0.5 pt-1.5 border-t border-slate-200/60 sm:border-0 sm:pt-0">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 flex items-center gap-1.5">
                        <i class="fa-solid fa-location-dot text-blue-500 text-[10px]"></i> Lugar / Salón
                    </span>
                    <p id="modalLugar" class="font-bold text-slate-700 text-xs sm:text-sm truncate"></p>
                </div>

                <div class="space-y-0.5 pt-1.5 border-t border-slate-200/60 sm:border-0 sm:pt-0">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 flex items-center gap-1.5">
                        <i class="fa-solid fa-chalkboard-user text-blue-500 text-[10px]"></i> Expositor / Responsable
                    </span>
                    <p id="modalInstructor" class="font-bold text-blue-600 text-xs sm:text-sm truncate"></p>
                </div>

                <div class="space-y-0.5 sm:col-span-2 pt-1.5 border-t border-slate-200/60">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 flex items-center gap-1.5">
                        <i class="fa-solid fa-users-rectangle text-blue-500 text-[10px]"></i> Ficha / Programa Convocado
                    </span>
                    <p id="modalFicha" class="font-bold text-slate-700 text-xs sm:text-sm"></p>
                </div>
            </div>

            <!-- Tema y Contenido a Desarrollar -->
            <div class="space-y-1.5">
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 flex items-center gap-1.5">
                    <i class="fa-solid fa-align-left text-blue-500 text-[10px]"></i> Tema y Contenido a Desarrollar
                </label>
                <div id="modalDescripcion" class="p-3.5 rounded-2xl bg-white border border-slate-200/80 shadow-2xs leading-relaxed text-slate-700 whitespace-pre-line text-xs font-medium min-h-[60px]">
                </div>
            </div>

            <!-- Observaciones adicionales (si aplica) -->
            <div id="modalObservacionesContainer" class="hidden p-3.5 rounded-2xl bg-amber-50/80 border border-amber-200/80 text-amber-950 flex items-start gap-3 shadow-2xs">
                <div class="w-7 h-7 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center text-xs shrink-0 font-bold border border-amber-200/60 mt-0.5">
                    <i class="fa-solid fa-circle-info"></i>
                </div>
                <div class="text-xs space-y-0.5 flex-1">
                    <p class="font-bold text-amber-900">Observaciones Adicionales</p>
                    <p id="modalObservaciones" class="text-amber-800 leading-relaxed font-medium"></p>
                </div>
            </div>

        </div>

        <!-- Footer del Modal -->
        <div class="p-4 sm:p-5 border-t border-slate-100 flex items-center justify-between bg-slate-50/60 shrink-0">
            <span class="text-[11px] font-semibold text-slate-400 flex items-center gap-1.5">
                <i class="fa-solid fa-shield-heart text-blue-500"></i> Capacitación Preventiva SST
            </span>
            <button type="button" onclick="closeDetailModal()" class="px-5 py-2.5 rounded-xl bg-white border border-slate-200/90 hover:bg-slate-100 text-slate-700 text-xs font-bold transition shadow-2xs cursor-pointer">
                Cerrar
            </button>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    function formatFechaHumana(fechaStr) {
        if (!fechaStr) return 'Sin fecha programada';
        try {
            const partes = fechaStr.split('-');
            if (partes.length === 3) {
                const meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                const dia = parseInt(partes[2], 10);
                const mesIdx = parseInt(partes[1], 10) - 1;
                const ano = partes[0];
                if (mesIdx >= 0 && mesIdx < 12) {
                    return `${dia} de ${meses[mesIdx]}, ${ano}`;
                }
            }
        } catch(e) {}
        return fechaStr;
    }

    function formatHoraHumana(horaStr) {
        if (!horaStr) return '';
        try {
            const partes = horaStr.trim().split(':');
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

    function openAsistenciaModal(btn) {
        if (!btn) return;
        const raw = btn.dataset.raw || btn.closest('.item-card')?.dataset.raw;
        if (!raw) return;
        try {
            const data = JSON.parse(atob(raw));
            const form = document.getElementById('formAsistencia');
            form.action = "{{ url('Sst/aprendiz/actividades-sst/charlas') }}/" + data.id_actividad + "/asistencia";

            document.getElementById('asistenciaCharlaTitulo').textContent = data.titulo || 'Charla SST';
            document.getElementById('asistenciaCharlaFecha').textContent = formatFechaHumana(data.fecha);
            const hIni = formatHoraHumana(data.hora_inicio);
            const hFin = formatHoraHumana(data.hora_fin);
            document.getElementById('asistenciaCharlaHorario').textContent = hIni ? (hFin ? `${hIni} – ${hFin}` : hIni) : '';

            // Precargar Ficha y Programa, Instructora, Encargada SST
            document.getElementById('asistencia_ficha_programa').value = data.ficha_programa || '';
            document.getElementById('asistencia_instructor').value = data.instructor || '';
            document.getElementById('asistencia_responsable').value = data.responsable || '{{ Auth::user()->person->first_name ?? '' }} {{ Auth::user()->person->first_last_name ?? '' }}';
            document.getElementById('asistencia_numero_asistentes').value = (data.numero_asistentes !== null && data.numero_asistentes !== undefined && data.numero_asistentes !== '') ? data.numero_asistentes : '0';
            document.getElementById('asistencia_participantes').value = data.participantes || '';
            document.getElementById('asistencia_estado').value = (data.estado === 'Cancelada') ? 'Cancelada' : 'Realizada';
            document.getElementById('asistencia_observaciones').value = data.observaciones || '';

            // Cargar aprendices de la ficha para chulear
            cargarAprendicesFicha(data.ficha_programa || '', data.participantes || '');

            toggleModal('modalAsistenciaOverlay', true);
        } catch(e) {
            console.error('Error abriendo asistencia modal:', e);
        }
    }

    /* ==========================================================
       LÓGICA DINÁMICA: APRENDICES POR FICHA Y CONTROL ASISTENCIA
       ========================================================== */
    let listaAprendicesActual = [];

    async function cargarAprendicesFicha(fichaTexto, asistentesExistentesStr = '') {
        const contenedor = document.getElementById('lista_aprendices_container');
        const loading = document.getElementById('loading_aprendices');
        const emptyMsg = document.getElementById('empty_aprendices_msg');
        const controles = document.getElementById('controles_marcado_asistencia');
        const badge = document.getElementById('badge_conteo_asistentes');
        const buscadorCont = document.getElementById('contenedor_buscador_aprendices');
        const textareaCont = document.getElementById('contenedor_textarea_participantes');
        const filtroBuscador = document.getElementById('filtro_buscador_aprendiz');
        if (filtroBuscador) filtroBuscador.value = '';
        
        if (!fichaTexto || !fichaTexto.trim()) {
            if (contenedor) contenedor.classList.add('hidden');
            if (loading) loading.classList.add('hidden');
            if (emptyMsg) {
                emptyMsg.innerHTML = `
                    <i class="fa-solid fa-users-viewfinder text-lg mb-1 block text-slate-300"></i>
                    <span>Selecciona una ficha arriba para cargar el listado de aprendices y chulear asistencia.</span>
                `;
                emptyMsg.classList.remove('hidden');
            }
            if (controles) controles.style.display = 'none';
            if (badge) badge.classList.add('hidden');
            if (buscadorCont) buscadorCont.classList.add('hidden');
            listaAprendicesActual = [];
            return;
        }

        if (loading) loading.classList.remove('hidden');
        if (emptyMsg) emptyMsg.classList.add('hidden');
        if (contenedor) contenedor.classList.add('hidden');
        if (controles) controles.style.display = 'none';
        if (badge) badge.classList.add('hidden');
        if (buscadorCont) buscadorCont.classList.add('hidden');

        try {
            const url = "{{ route('SST.aprendiz.actividades_sst.aprendices_por_ficha') }}?ficha=" + encodeURIComponent(fichaTexto);
            const res = await fetch(url);
            const data = await res.json();
            
            if (loading) loading.classList.add('hidden');

            if (data.success && data.aprendices && data.aprendices.length > 0) {
                listaAprendicesActual = data.aprendices;
                renderListaAprendices(data.aprendices, asistentesExistentesStr);
                if (contenedor) contenedor.classList.remove('hidden');
                if (controles) controles.style.display = 'flex';
                if (badge) badge.classList.remove('hidden');
                if (buscadorCont) buscadorCont.classList.remove('hidden');
                if (emptyMsg) emptyMsg.classList.add('hidden');
            } else {
                listaAprendicesActual = [];
                if (contenedor) contenedor.classList.add('hidden');
                if (emptyMsg) {
                    emptyMsg.innerHTML = `
                        <div class="text-amber-600 font-bold mb-1 flex items-center justify-center gap-1.5">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <span>Ficha sin aprendices registrados en SICA</span>
                        </div>
                        <p class="text-slate-500 text-[11px]">No se encontraron aprendices en la base de datos para esta ficha. Puedes ingresar los nombres manualmente en el campo inferior.</p>
                    `;
                    emptyMsg.classList.remove('hidden');
                }
                if (textareaCont) textareaCont.classList.remove('hidden');
            }
        } catch (e) {
            console.error('Error cargando aprendices:', e);
            if (loading) loading.classList.add('hidden');
            if (emptyMsg) {
                emptyMsg.innerHTML = '<span class="text-rose-500 font-bold">Error consultando aprendices. Puedes usar el modo manual.</span>';
                emptyMsg.classList.remove('hidden');
            }
            if (textareaCont) textareaCont.classList.remove('hidden');
        }
    }

    function renderListaAprendices(aprendices, asistentesExistentesStr = '') {
        const contenedor = document.getElementById('lista_aprendices_container');
        if (!contenedor) return;

        const existentesLower = (asistentesExistentesStr || '').toLowerCase();
        
        let html = '';
        aprendices.forEach(a => {
            const yaMarcado = existentesLower && (
                existentesLower.includes(a.document_number.toString().toLowerCase()) || 
                existentesLower.includes(a.full_name.toLowerCase())
            );

            html += `
                <label class="item-aprendiz-row flex items-center justify-between p-2.5 rounded-xl border transition cursor-pointer select-none group ${yaMarcado ? 'bg-blue-50/80 border-blue-300' : 'bg-slate-50 hover:bg-slate-100 border-slate-200/80'}" data-name="${a.full_name.toLowerCase()}" data-doc="${a.document_number}">
                    <div class="flex items-center gap-2.5 truncate flex-1 min-w-0 pr-2">
                        <input type="checkbox" 
                               value="${a.full_name} (${a.document_number})" 
                               ${yaMarcado ? 'checked' : ''} 
                               onchange="onCambioAsistenciaAprendiz(this)" 
                               class="chk-aprendiz-asistencia w-4 h-4 rounded text-blue-600 focus:ring-blue-500 border-slate-300 transition cursor-pointer shrink-0">
                        <div class="truncate">
                            <div class="font-extrabold text-xs text-slate-800 group-hover:text-blue-700 truncate">${a.full_name}</div>
                            <div class="text-[10px] text-slate-400 font-medium">CC/TI: <span class="font-semibold text-slate-600">${a.document_number}</span> · <span class="text-slate-500 font-semibold">${a.status}</span></div>
                        </div>
                    </div>
                    <span class="badge-estado-asistencia shrink-0 text-[10px] font-extrabold px-2 py-0.5 rounded-lg transition ${yaMarcado ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-500'}">
                        ${yaMarcado ? 'Presente' : 'Ausente'}
                    </span>
                </label>
            `;
        });

        contenedor.innerHTML = html;
        actualizarTotalesAsistencia();
    }

    function onCambioAsistenciaAprendiz(chk) {
        const row = chk.closest('.item-aprendiz-row');
        const badge = row ? row.querySelector('.badge-estado-asistencia') : null;
        if (chk.checked) {
            if (row) {
                row.classList.remove('bg-slate-50', 'hover:bg-slate-100', 'border-slate-200/80');
                row.classList.add('bg-blue-50/80', 'border-blue-300');
            }
            if (badge) {
                badge.className = 'badge-estado-asistencia shrink-0 text-[10px] font-extrabold px-2 py-0.5 rounded-lg transition bg-emerald-100 text-emerald-700';
                badge.textContent = 'Presente';
            }
        } else {
            if (row) {
                row.classList.remove('bg-blue-50/80', 'border-blue-300');
                row.classList.add('bg-slate-50', 'hover:bg-slate-100', 'border-slate-200/80');
            }
            if (badge) {
                badge.className = 'badge-estado-asistencia shrink-0 text-[10px] font-extrabold px-2 py-0.5 rounded-lg transition bg-slate-200 text-slate-500';
                badge.textContent = 'Ausente';
            }
        }
        actualizarTotalesAsistencia();
    }

    function marcarTodosAprendices(marcar) {
        const checkboxes = document.querySelectorAll('.chk-aprendiz-asistencia');
        checkboxes.forEach(chk => {
            const row = chk.closest('.item-aprendiz-row');
            if (!row || !row.classList.contains('hidden')) {
                chk.checked = marcar;
                const badge = row ? row.querySelector('.badge-estado-asistencia') : null;
                if (marcar) {
                    if (row) {
                        row.classList.remove('bg-slate-50', 'hover:bg-slate-100', 'border-slate-200/80');
                        row.classList.add('bg-blue-50/80', 'border-blue-300');
                    }
                    if (badge) {
                        badge.className = 'badge-estado-asistencia shrink-0 text-[10px] font-extrabold px-2 py-0.5 rounded-lg transition bg-emerald-100 text-emerald-700';
                        badge.textContent = 'Presente';
                    }
                } else {
                    if (row) {
                        row.classList.remove('bg-blue-50/80', 'border-blue-300');
                        row.classList.add('bg-slate-50', 'hover:bg-slate-100', 'border-slate-200/80');
                    }
                    if (badge) {
                        badge.className = 'badge-estado-asistencia shrink-0 text-[10px] font-extrabold px-2 py-0.5 rounded-lg transition bg-slate-200 text-slate-500';
                        badge.textContent = 'Ausente';
                    }
                }
            }
        });
        actualizarTotalesAsistencia();
    }

    function actualizarTotalesAsistencia() {
        const chks = document.querySelectorAll('.chk-aprendiz-asistencia');
        const seleccionados = [];
        chks.forEach(c => {
            if (c.checked) {
                seleccionados.push(c.value);
            }
        });

        const count = seleccionados.length;
        const inputNumero = document.getElementById('asistencia_numero_asistentes');
        if (inputNumero) {
            inputNumero.value = count;
        }

        const txtCount = document.getElementById('txt_asistentes_count');
        if (txtCount) {
            txtCount.textContent = count;
        }

        const txtArea = document.getElementById('asistencia_participantes');
        if (txtArea && chks.length > 0) {
            txtArea.value = seleccionados.join('\n');
        }
    }

    function toggleModoManual() {
        const textareaCont = document.getElementById('contenedor_textarea_participantes');
        if (textareaCont) {
            textareaCont.classList.toggle('hidden');
        }
    }

    function toggleFichasDropdown(e) {
        if (e) {
            e.stopPropagation();
            e.preventDefault();
        }
        const menu = document.getElementById('fichas_dropdown_menu');
        const icon = document.getElementById('iconArrowFicha');
        if (!menu) return;
        
        const isHidden = menu.classList.contains('hidden');
        if (isHidden) {
            const options = menu.querySelectorAll('.ficha-option');
            const noMatch = document.getElementById('no_fichas_match');
            if (noMatch) noMatch.classList.add('hidden');
            options.forEach(opt => opt.classList.remove('hidden'));
            menu.classList.remove('hidden');
            if (icon) icon.classList.add('rotate-180');
        } else {
            menu.classList.add('hidden');
            if (icon) icon.classList.remove('rotate-180');
        }
    }

    function selectFichaOption(value) {
        const input = document.getElementById('asistencia_ficha_programa');
        if (input) {
            input.value = value;
        }
        const menu = document.getElementById('fichas_dropdown_menu');
        const icon = document.getElementById('iconArrowFicha');
        if (menu) menu.classList.add('hidden');
        if (icon) icon.classList.remove('rotate-180');

        // Cargar aprendices de la ficha seleccionada
        cargarAprendicesFicha(value);
    }

    function closeAsistenciaModal() {
        const menu = document.getElementById('fichas_dropdown_menu');
        const icon = document.getElementById('iconArrowFicha');
        if (menu) menu.classList.add('hidden');
        if (icon) icon.classList.remove('rotate-180');
        toggleModal('modalAsistenciaOverlay', false);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('asistencia_ficha_programa');
        const menu = document.getElementById('fichas_dropdown_menu');
        const icon = document.getElementById('iconArrowFicha');
        
        if (input && menu) {
            input.addEventListener('focus', function() {
                const isHidden = menu.classList.contains('hidden');
                if (isHidden) {
                    toggleFichasDropdown();
                }
            });

            input.addEventListener('input', function() {
                const val = this.value.toLowerCase().trim();
                menu.classList.remove('hidden');
                if (icon) icon.classList.add('rotate-180');
                
                const options = menu.querySelectorAll('.ficha-option');
                const noMatch = document.getElementById('no_fichas_match');
                let count = 0;
                options.forEach(opt => {
                    const text = opt.getAttribute('data-text') || '';
                    if (!val || text.includes(val)) {
                        opt.classList.remove('hidden');
                        count++;
                    } else {
                        opt.classList.add('hidden');
                    }
                });
                if (noMatch) {
                    if (count === 0 && options.length > 0) {
                        noMatch.classList.remove('hidden');
                    } else {
                        noMatch.classList.add('hidden');
                    }
                }
            });

            input.addEventListener('change', function() {
                cargarAprendicesFicha(this.value);
            });
        }

        const filtroBuscador = document.getElementById('filtro_buscador_aprendiz');
        if (filtroBuscador) {
            filtroBuscador.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();
                const rows = document.querySelectorAll('.item-aprendiz-row');
                rows.forEach(r => {
                    const name = r.getAttribute('data-name') || '';
                    const doc = r.getAttribute('data-doc') || '';
                    if (!query || name.includes(query) || doc.includes(query)) {
                        r.classList.remove('hidden');
                    } else {
                        r.classList.add('hidden');
                    }
                });
            });
        }

        document.addEventListener('click', function(e) {
            const container = document.getElementById('ficha_combo_container');
            const menu = document.getElementById('fichas_dropdown_menu');
            const icon = document.getElementById('iconArrowFicha');
            if (container && !container.contains(e.target) && menu && !menu.classList.contains('hidden')) {
                menu.classList.add('hidden');
                if (icon) icon.classList.remove('rotate-180');
            }
        });
    });

    function openDetailModal(btn) {
        if (!btn) return;
        const raw = btn.dataset.raw || btn.closest('.item-card')?.dataset.raw;
        if (!raw) return;
        try {
            const data = JSON.parse(atob(raw));
            document.getElementById('modalTitulo').textContent = data.titulo || 'Charla SST';
            
            // Estado Badge con indicador
            const badge = document.getElementById('modalBadgeEstado');
            const estado = data.estado || 'Programada';
            let dotColor = 'bg-amber-500';
            let badgeClass = 'bg-amber-50 text-amber-700 border-amber-300';
            if (estado === 'Realizada') {
                dotColor = 'bg-emerald-500';
                badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-300';
            } else if (estado === 'Cancelada') {
                dotColor = 'bg-rose-500';
                badgeClass = 'bg-rose-50 text-rose-700 border-rose-300';
            }
            badge.className = `inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold border ${badgeClass} shadow-2xs select-none`;
            badge.innerHTML = `<span class="w-1.5 h-1.5 rounded-full ${dotColor}"></span><span>${estado}</span>`;

            // Fecha y Horario formateados
            document.getElementById('modalFechaTexto').textContent = formatFechaHumana(data.fecha);
            const hIni = formatHoraHumana(data.hora_inicio);
            const hFin = formatHoraHumana(data.hora_fin);
            document.getElementById('modalHorarioTexto').textContent = hIni ? (hFin ? `${hIni} – ${hFin}` : hIni) : 'Por confirmar';

            // Datos Ficha Técnica
            document.getElementById('modalLugar').textContent = data.lugar || 'Por definir';
            document.getElementById('modalInstructor').textContent = data.instructor || data.responsable || 'Equipo SST';
            document.getElementById('modalFicha').textContent = data.ficha_programa || 'General / Todo el personal';
            document.getElementById('modalDescripcion').textContent = data.descripcion || data.actividad_a_realizar || 'Charla de seguridad y prevención laboral.';
            
            // Observaciones
            const obsContainer = document.getElementById('modalObservacionesContainer');
            if (data.observaciones && data.observaciones.trim()) {
                obsContainer.classList.remove('hidden');
                document.getElementById('modalObservaciones').textContent = data.observaciones;
            } else {
                obsContainer.classList.add('hidden');
            }

            toggleModal('modalDetalleOverlay', true);
        } catch (e) {
            console.error('Error parseando datos:', e);
        }
    }

    function closeDetailModal() {
        toggleModal('modalDetalleOverlay', false);
    }
</script>
@endpush

@extends('sst::components.layouts.aprendiz')

@section('title', 'Cronograma SST • Calendario de Actividades')

@section('content')
<style>
    /* Estructura rígida de celdas para evitar Layout Shift */
    .calendar-grid-day {
        height: 84px !important;
        max-height: 84px !important;
        overflow: hidden !important;
        box-sizing: border-box;
        transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out;
    }
    .calendar-grid-day:hover {
        background-color: #f8fafc;
    }
    .calendar-day-selected {
        border-color: #3b82f6 !important;
        background-color: #eff6ff !important;
        box-shadow: inset 0 0 0 2px #3b82f6 !important;
    }
    .calendar-day-today {
        background-color: #fff7ed;
    }
    .time-slot:hover {
        background-color: #fff7ed;
    }
    /* Ocultar flecha nativa de inputs con datalist */
    input[list]:not([type="date"]):not([type="datetime-local"])::-webkit-calendar-picker-indicator {
        display: none !important;
        -webkit-appearance: none !important;
        opacity: 0 !important;
        width: 0 !important;
        height: 0 !important;
    }
</style>

<div class="space-y-6 max-w-7xl mx-auto">

    <!-- Mensajes Flash -->
    @if(session('success'))
    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between shadow-2xs animate-in fade-in duration-200">
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

    @if($errors->any() || session('error'))
    <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 flex items-center justify-between shadow-2xs animate-in fade-in duration-200">
        <div class="flex items-center gap-3">
            <span class="w-8 h-8 rounded-xl bg-amber-500 text-white flex items-center justify-center font-bold text-sm shrink-0">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </span>
            <div class="space-y-0.5 text-xs sm:text-sm font-bold">
                @if(session('error')) <p>{{ session('error') }}</p> @endif
                @foreach($errors->all() as $err) <p>{{ $err }}</p> @endforeach
            </div>
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="text-amber-500 hover:text-amber-800 text-xs font-bold cursor-pointer">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    @endif

    <!-- 1. Encabezado de la Sección (Estilo Idéntico al Cronograma SST) -->
    <div class="bg-gradient-to-r from-[#00131E] via-[#001A29] to-[#002D44] p-6 sm:p-7 rounded-2xl border-l-4 border-l-[#39A900] text-white shadow-lg flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-white/10 text-[#62E31D] flex items-center justify-center border border-white/20 shadow-inner shrink-0">
                <i class="fa-solid fa-calendar-days text-xl"></i>
            </div>
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-white/10 border border-white/15 text-[10px] font-extrabold uppercase tracking-widest text-[#62E31D]">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#62E31D] animate-pulse"></span>
                        Cronograma Operativo • SST
                    </span>
                </div>
                <h1 class="text-2xl font-black text-white tracking-tight">Cronograma <span class="text-[#62E31D]">SST</span></h1>
                <p class="text-xs sm:text-sm text-white/80 font-normal mt-0.5">Consulta del calendario de actividades, capacitaciones, pausas activas y registro de asistencia.</p>
            </div>
        </div>

        <!-- Badges Informativos -->
        <div class="flex flex-wrap items-center gap-2 self-start md:self-auto">
            <div class="px-3.5 py-1.5 rounded-xl bg-white/10 border border-white/15 text-xs font-bold text-white flex items-center gap-2 shadow-xs">
                <span class="w-2 h-2 rounded-full bg-[#62E31D] animate-pulse"></span>
                <span>Vista Activa: <strong id="headerViewBadge" class="text-[#62E31D]">Mes</strong></span>
            </div>
            <div class="px-3.5 py-1.5 rounded-xl bg-[#39A900] border border-[#62E31D]/40 text-xs font-bold text-white flex items-center gap-1.5 shadow-xs">
                <i class="fa-solid fa-circle-check text-xs text-white"></i>
                <span id="activeEventsCount">{{ count($actividades) }} Actividades</span>
            </div>
        </div>
    </div>

    <!-- 2. Barra de Control de Navegación del Calendario -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row items-center justify-between gap-4">
        
        <!-- Navegación de Fecha + Selector de Fecha -->
        <div class="flex items-center flex-wrap gap-2">
            <div class="flex items-center gap-1">
                <button id="btnPrev" onclick="navigate(-1)" class="w-9 h-9 rounded-xl border border-slate-200 text-slate-600 hover:text-[#2d8500] hover:bg-emerald-50 hover:border-emerald-200 flex items-center justify-center transition cursor-pointer" title="Anterior">
                    <i class="fa-solid fa-chevron-left text-xs"></i>
                </button>
                <button id="btnToday" onclick="goToToday()" class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 hover:text-[#2d8500] hover:bg-emerald-50 hover:border-emerald-200 transition cursor-pointer">
                    Hoy
                </button>
                <button id="btnNext" onclick="navigate(1)" class="w-9 h-9 rounded-xl border border-slate-200 text-slate-600 hover:text-[#2d8500] hover:bg-emerald-50 hover:border-emerald-200 flex items-center justify-center transition cursor-pointer" title="Siguiente">
                    <i class="fa-solid fa-chevron-right text-xs"></i>
                </button>
            </div>

            <!-- Botón / Input de Elección de Fecha -->
            <div class="relative">
                <input type="date" id="datePickerHeader" onchange="onHeaderDateChange(this.value)" class="absolute opacity-0 w-0 h-0 pointer-events-none">
                <button type="button" onclick="triggerDatePicker()" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 hover:text-[#2d8500] hover:bg-emerald-50 hover:border-emerald-200 transition cursor-pointer shadow-2xs">
                    <i class="fa-regular fa-calendar-check text-[#39A900] text-sm"></i>
                    <span>Escoger fecha</span>
                </button>
            </div>

            <!-- Indicador informativo -->
            <div class="hidden lg:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 border border-emerald-200/80 text-[11px] font-semibold text-emerald-700">
                <i class="fa-solid fa-calendar-check text-emerald-600 text-xs"></i>
                <span>Haz clic en cualquier actividad para <strong>tomar o consultar asistencia</strong></span>
            </div>
        </div>

        <!-- Título del Rango o Mes -->
        <h2 id="calendarHeaderTitle" class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight text-center">
            Noviembre de 2026
        </h2>

        <!-- Selector de Vista (Día / Semana / Mes) -->
        <div class="inline-flex p-1 bg-slate-100 rounded-xl border border-slate-200/70 text-xs font-bold text-slate-600">
            <button onclick="switchView('day')" id="viewBtnDay" class="px-3.5 py-1.5 rounded-lg transition cursor-pointer hover:text-slate-900">Día</button>
            <button onclick="switchView('week')" id="viewBtnWeek" class="px-3.5 py-1.5 rounded-lg transition cursor-pointer hover:text-slate-900">Semana</button>
            <button onclick="switchView('month')" id="viewBtnMonth" class="px-3.5 py-1.5 rounded-lg bg-white text-[#39A900] shadow-xs font-extrabold border border-slate-200/60 transition cursor-pointer">Mes</button>
        </div>
    </div>

    <!-- 3. Calendario Principal (Mes / Semana / Día) a Ancho Completo -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden p-4 sm:p-6 flex flex-col justify-between">
        
        <!-- Leyenda Informativa de Colores por Tipo de Actividad SST -->
        <div class="flex items-center gap-2 pb-3.5 mb-2 border-b border-slate-100 flex-wrap text-xs">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mr-1">
                <i class="fa-solid fa-palette text-slate-400 mr-1"></i> Tipos de Actividades:
            </span>
            
            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-sky-50 border border-sky-200/90 text-sky-800 text-[11px] font-extrabold shadow-2xs">
                <span class="w-2.5 h-2.5 rounded-full bg-sky-500 ring-2 ring-sky-200"></span>
                <i class="fa-solid fa-person-running text-sky-600 text-[10px]"></i>
                <span>Pausas Activas</span>
            </div>

            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-blue-50 border border-blue-300/90 text-blue-900 text-[11px] font-extrabold shadow-2xs">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-700 ring-2 ring-blue-200"></span>
                <i class="fa-solid fa-bullhorn text-blue-700 text-[10px]"></i>
                <span>Charlas de Seguridad</span>
            </div>

            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 border border-emerald-200/90 text-emerald-800 text-[11px] font-extrabold shadow-2xs">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 ring-2 ring-emerald-200"></span>
                <i class="fa-solid fa-hand-holding-heart text-emerald-600 text-[10px]"></i>
                <span>Sensibilización</span>
            </div>
        </div>

        <!-- VISTA: MES -->
        <div id="containerMonthView" class="flex-1 flex flex-col space-y-2">
            <div class="grid grid-cols-7 mb-1 text-center text-[11px] font-extrabold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2.5">
                <div>LUN</div>
                <div>MAR</div>
                <div>MIÉ</div>
                <div>JUE</div>
                <div>VIE</div>
                <div>SÁB</div>
                <div>DOM</div>
            </div>

            <div id="calendarGridMonth" class="grid grid-cols-7 gap-1.5 flex-1">
                <!-- Se renderiza dinámicamente -->
            </div>
        </div>

        <!-- VISTA: SEMANA -->
        <div id="containerWeekView" class="hidden flex-1 flex flex-col space-y-3">
            <div class="grid grid-cols-7 gap-2 border-b border-slate-100 pb-3" id="weekHeadersGrid">
                <!-- Encabezados de días -->
            </div>
            <div class="grid grid-cols-7 gap-2 h-[440px] overflow-y-auto" id="weekDaysGrid">
                <!-- Contenido de días de la semana -->
            </div>
        </div>

        <!-- VISTA: DÍA -->
        <div id="containerDayView" class="hidden flex-1 flex flex-col space-y-3">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-[#39A900] font-extrabold text-base flex items-center justify-center border border-emerald-200/60 shrink-0">
                        <i class="fa-regular fa-clock"></i>
                    </div>
                    <div>
                        <h4 id="dayViewSubTitle" class="text-sm font-extrabold text-slate-900 tracking-tight">Cronograma Horario del Día</h4>
                        <p class="text-xs text-slate-500 font-medium mt-0.5">Visualización del cronograma horario del día seleccionado.</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <span id="dayEventsCountBadge" class="px-3 py-1 rounded-xl bg-emerald-100/70 text-[#064e3b] border border-emerald-200 text-xs font-extrabold">
                        0 Eventos hoy
                    </span>
                </div>
            </div>

            <div class="space-y-3 h-[440px] overflow-y-auto pr-1.5 scroll-smooth" id="dayHoursTimeline">
                <!-- Se renderiza timeline dinámicamente -->
            </div>
        </div>

    </div>

    <!-- 4. SECCIÓN INFERIOR COMPLETA (FULL-WIDTH): Listado de Actividades Programadas del Día -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-4">
        
        <!-- Encabezado de la Sección de Actividades -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-200/70">
                    <i class="fa-solid fa-list-check text-base"></i>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                        <span>Actividades Programadas</span>
                        <span id="selectedDayEventsBadge" class="px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-700 font-extrabold text-xs">0 Eventos</span>
                    </h3>
                    <p id="selectedDateSubtitleEvents" class="text-xs text-slate-500 font-medium mt-0.5">
                        Mostrando registros para: Hoy
                    </p>
                </div>
            </div>

            <!-- Filtros Rápidos por Estado -->
            <div class="flex items-center gap-1.5 flex-wrap">
                <button onclick="filterEventsStatus('all')" id="statusFilterAll" class="px-3.5 py-1.5 rounded-xl text-xs font-extrabold bg-slate-900 text-white shadow-xs transition cursor-pointer">Todas Pendientes</button>
                <button onclick="filterEventsStatus('Programada')" id="statusFilterProgramada" class="px-3.5 py-1.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition cursor-pointer">Programadas</button>
                <a href="{{ route('SST.aprendiz.asistencia.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-bold bg-emerald-50 hover:bg-emerald-100 text-[#2d8500] border border-emerald-200 transition cursor-pointer shadow-2xs">
                    <i class="fa-solid fa-clipboard-check text-xs"></i>
                    <span>Ver Asistencias Guardadas</span>
                </a>
            </div>
        </div>

        <!-- Grilla de Actividades del Día Seleccionado -->
        <div id="dayEventsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 pt-1">
            <!-- Se renderiza dinámicamente con JavaScript -->
        </div>

    </div>

</div>

<!-- Modal 1: Formulario de Control de Asistencia (Llamado a Lista de Aprendices) -->
<div id="modalAsistenciaOverlay" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-3 sm:p-4 hidden opacity-0 transition-opacity duration-200" onclick="closeAsistenciaModal()">
    <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 max-w-xl w-full max-h-[92vh] flex flex-col transform transition-transform duration-200 scale-95 overflow-hidden" onclick="event.stopPropagation()">
        
        <!-- Header del Modal Asistencia -->
        <div class="p-5 sm:p-6 pb-4 border-b border-slate-100 flex items-center justify-between shrink-0 bg-white">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-50 to-teal-50 text-[#39A900] border border-emerald-200/80 flex items-center justify-center text-xl shadow-2xs shrink-0" id="modalHeaderIcon">
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
                
                <!-- Resumen Compacto de la Actividad Seleccionada -->
                <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200/70 flex items-center justify-between gap-3 text-xs">
                    <div class="truncate">
                        <span id="asistenciaTipoBadge" class="text-[10px] font-extrabold uppercase tracking-wider text-[#39A900] block">Actividad Convocada</span>
                        <h4 id="asistenciaActividadTitulo" class="font-extrabold text-slate-900 text-xs sm:text-sm truncate"></h4>
                    </div>
                    <div class="text-right shrink-0">
                        <span id="asistenciaActividadFecha" class="font-bold text-slate-700 block text-[11px]"></span>
                        <span id="asistenciaActividadHorario" class="text-[10px] font-medium text-slate-500"></span>
                    </div>
                </div>

                <!-- Campo 1: Ficha y Programa Formativo (Llamado a lista con Flecha y Desplegable) -->
                <div class="space-y-1 relative" id="ficha_combo_container">
                    <label for="asistencia_ficha_programa" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 flex items-center gap-1.5">
                        <i class="fa-solid fa-users-rectangle text-[#39A900] text-xs"></i>
                        Ficha y Programa Formativo <span class="text-rose-500">*</span>
                    </label>
                    
                    <div class="relative">
                        <input type="text" 
                               name="ficha_programa" 
                               id="asistencia_ficha_programa" 
                               required 
                               autocomplete="off"
                               placeholder="Selecciona o escribe la ficha (solo números, Ej: 2711894)" 
                               inputmode="numeric"
                               maxlength="20"
                               pattern="^[0-9]+$"
                               title="Solo números sin espacios ni caracteres especiales"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                               class="w-full pl-3.5 pr-10 py-2.5 text-xs font-semibold text-slate-800 rounded-2xl bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-[#39A900] transition">
                        
                        <button type="button" 
                                id="btnToggleFichas" 
                                onclick="toggleFichasDropdown(event)"
                                class="absolute inset-y-0 right-0 px-3.5 flex items-center text-slate-400 hover:text-[#39A900] transition cursor-pointer"
                                title="Desplegar lista de fichas">
                            <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200" id="iconArrowFicha"></i>
                        </button>
                    </div>
                    
                    <p class="text-[10px] text-slate-400">Presiona la flecha para desplegar las fichas o escribe el número.</p>

                    <!-- Menú Desplegable de Fichas -->
                    <div id="fichas_dropdown_menu" class="absolute z-50 left-0 right-0 mt-1 max-h-52 overflow-y-auto bg-white rounded-2xl border border-slate-200 shadow-xl divide-y divide-slate-100 hidden">
                        @if(isset($cursos) && count($cursos) > 0)
                            @foreach($cursos as $c)
                                @php
                                    $fichaTexto = $c->code . ' - ' . ($c->program->name ?? 'Programa de Formación');
                                @endphp
                                <div class="ficha-option px-3.5 py-2.5 hover:bg-emerald-50 cursor-pointer flex items-center justify-between text-xs transition group"
                                     data-text="{{ strtolower($fichaTexto) }}"
                                     data-value="{{ $c->code }}"
                                     onclick="selectFichaOption(this.getAttribute('data-value'))">
                                    <div class="truncate">
                                        <strong class="font-extrabold text-slate-800 group-hover:text-[#39A900]">{{ $c->code }}</strong>
                                        <span class="text-slate-500 text-[11px] ml-1.5">{{ $c->program->name ?? '' }}</span>
                                    </div>
                                    <span class="text-[10px] font-bold text-[#39A900] opacity-0 group-hover:opacity-100 transition shrink-0 ml-2">Seleccionar</span>
                                </div>
                            @endforeach
                            <div id="no_fichas_match" class="px-4 py-3 text-xs text-slate-400 text-center hidden">No se encontraron fichas coincidentes</div>
                        @else
                            <div class="px-4 py-3 text-xs text-slate-400 text-center">No hay fichas activas registradas en SICA</div>
                        @endif
                    </div>
                </div>

                <!-- Campo 2 y 3: La Instructora y La Encargada de SST -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label for="asistencia_instructor" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 flex items-center gap-1.5">
                            <i class="fa-solid fa-chalkboard-user text-[#39A900] text-xs"></i>
                            Instructora a Cargo <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="instructor" id="asistencia_instructor" required maxlength="150" pattern="^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$" title="Solo letras y espacios" oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]/g, '')" placeholder="Nombre de la instructora" class="w-full px-3.5 py-2.5 text-xs font-semibold text-slate-800 rounded-2xl bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-[#39A900] transition">
                    </div>

                    <div class="space-y-1">
                        <label for="asistencia_responsable" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 flex items-center gap-1.5">
                            <i class="fa-solid fa-shield-heart text-[#39A900] text-xs"></i>
                            Encargada de SST <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="responsable" id="asistencia_responsable" required maxlength="150" pattern="^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$" title="Solo letras y espacios" oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]/g, '')" placeholder="Responsable / Aprendiz SST" class="w-full px-3.5 py-2.5 text-xs font-semibold text-slate-800 rounded-2xl bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-[#39A900] transition">
                    </div>
                </div>

                <!-- Total Asistentes y Estado -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label for="asistencia_numero_asistentes" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 flex items-center gap-1.5">
                            <i class="fa-solid fa-user-check text-[#39A900] text-xs"></i>
                            Número de Asistentes
                        </label>
                        <input type="number" min="0" name="numero_asistentes" id="asistencia_numero_asistentes" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder="0" class="w-full px-3.5 py-2.5 text-xs font-bold text-slate-800 rounded-2xl bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-[#39A900] transition">
                    </div>

                    <div class="space-y-1">
                        <label for="asistencia_estado" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 flex items-center gap-1.5">
                            <i class="fa-solid fa-tag text-[#39A900] text-xs"></i>
                            Estado de la Actividad
                        </label>
                        <select name="estado" id="asistencia_estado" class="w-full px-3.5 py-2.5 text-xs font-bold text-slate-700 rounded-2xl bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-[#39A900] cursor-pointer">
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
                            <i class="fa-solid fa-clipboard-check text-[#39A900] text-xs"></i>
                            <span>Llamado a Lista de Aprendices</span>
                            <span id="badge_conteo_asistentes" class="ml-1.5 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 hidden">
                                <span id="txt_asistentes_count">0</span> seleccionados
                            </span>
                        </label>

                        <div class="flex items-center gap-1.5" id="controles_marcado_asistencia" style="display: none;">
                            <button type="button" onclick="marcarTodosAprendices(true)" class="px-2.5 py-1 text-[10px] font-bold rounded-xl bg-emerald-50 hover:bg-emerald-100 text-[#2d8500] border border-emerald-200 transition cursor-pointer flex items-center gap-1">
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

                    <!-- Buscador interno de aprendiz -->
                    <div id="contenedor_buscador_aprendices" class="relative hidden">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                        <input type="text" id="filtro_buscador_aprendiz" placeholder="Buscar aprendiz por nombre o cédula..." class="w-full pl-8 pr-3 py-1.5 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#39A900] transition">
                    </div>

                    <!-- Indicador de Carga (Spinner) -->
                    <div id="loading_aprendices" class="p-4 rounded-2xl bg-emerald-50/50 border border-emerald-100 text-center text-xs text-emerald-700 font-semibold hidden">
                        <i class="fa-solid fa-circle-notch fa-spin mr-2"></i> Consultando aprendices matriculados en la ficha...
                    </div>

                    <!-- Mensaje cuando no se ha seleccionado ficha -->
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
                        <textarea name="participantes" id="asistencia_participantes" rows="2" placeholder="Ingresa los nombres o números de documento de los aprendices que respondieron al llamado..." class="w-full p-2.5 text-xs font-medium text-slate-700 rounded-2xl bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-[#39A900] transition leading-relaxed"></textarea>
                    </div>
                </div>

                <!-- Observaciones Adicionales -->
                <div class="space-y-1">
                    <label for="asistencia_observaciones" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 flex items-center gap-1.5">
                        <i class="fa-regular fa-comment-dots text-[#39A900] text-xs"></i>
                        Observaciones de la Jornada
                    </label>
                    <textarea name="observaciones" id="asistencia_observaciones" rows="2" placeholder="Novedades, observaciones o acuerdos durante la asistencia..." class="w-full p-3 text-xs font-medium text-slate-700 rounded-2xl bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-[#39A900] transition"></textarea>
                </div>

            </div>

            <!-- Footer del Formulario -->
            <div class="p-4 sm:p-5 border-t border-slate-100 flex items-center justify-between bg-slate-50/70 shrink-0">
                <button type="button" onclick="closeAsistenciaModal()" class="px-5 py-2.5 rounded-2xl bg-white border border-slate-200/90 hover:bg-slate-100 text-slate-700 text-xs font-bold transition shadow-2xs cursor-pointer">
                    Cancelar
                </button>
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-2xl bg-[#39A900] hover:bg-[#2d8500] text-white text-xs font-extrabold shadow-sm hover:shadow-md transition cursor-pointer">
                    <i class="fa-solid fa-check text-xs"></i>
                    <span>Guardar Asistencia</span>
                </button>
            </div>
        </form>

    </div>
</div>

<!-- Modal 2: Detalle Completo de la Actividad -->
<div id="modalDetalleOverlay" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-3 sm:p-4 hidden opacity-0 transition-opacity duration-200" onclick="closeDetailModal()">
    <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 max-w-xl w-full max-h-[90vh] flex flex-col transform transition-transform duration-200 scale-95 overflow-hidden" onclick="event.stopPropagation()">
        
        <div class="p-5 sm:p-6 pb-4 border-b border-slate-100 flex items-center justify-between shrink-0 bg-white">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-[#39A900] border border-emerald-200/80 flex items-center justify-center text-xl shadow-2xs shrink-0" id="detailIcon">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-base sm:text-lg text-slate-900 tracking-tight leading-tight" id="detailTitulo">Ficha Técnica</h3>
                    <p class="text-[11px] font-semibold text-slate-400" id="detailSubtitulo">Detalle de Actividad SST</p>
                </div>
            </div>
            <button type="button" onclick="closeDetailModal()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center text-xs transition cursor-pointer">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="p-5 sm:p-6 space-y-4 overflow-y-auto flex-1 text-xs">
            <div class="grid grid-cols-2 gap-3 p-3.5 bg-slate-50 rounded-2xl border border-slate-100">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Tipo</span>
                    <strong id="detailTipo" class="font-extrabold text-slate-800"></strong>
                </div>
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Estado</span>
                    <strong id="detailEstado" class="font-extrabold text-slate-800"></strong>
                </div>
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Fecha</span>
                    <strong id="detailFecha" class="font-semibold text-slate-700"></strong>
                </div>
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Horario</span>
                    <strong id="detailHorario" class="font-semibold text-slate-700"></strong>
                </div>
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Lugar</span>
                    <strong id="detailLugar" class="font-semibold text-slate-700"></strong>
                </div>
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Ficha</span>
                    <strong id="detailFicha" class="font-semibold text-slate-700"></strong>
                </div>
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Instructor</span>
                    <strong id="detailInstructor" class="font-semibold text-slate-700"></strong>
                </div>
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Responsable</span>
                    <strong id="detailResponsable" class="font-semibold text-slate-700"></strong>
                </div>
            </div>

            <div class="space-y-1">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Descripción</span>
                <p id="detailDescripcion" class="text-xs text-slate-700 bg-slate-50 p-3 rounded-xl border border-slate-100 leading-relaxed"></p>
            </div>

            <div class="space-y-1">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Participantes / Asistentes</span>
                <p id="detailParticipantes" class="text-xs text-slate-700 bg-slate-50 p-3 rounded-xl border border-slate-100 max-h-36 overflow-y-auto"></p>
            </div>

            <div class="space-y-1">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Observaciones</span>
                <p id="detailObservaciones" class="text-xs text-slate-700 bg-slate-50 p-3 rounded-xl border border-slate-100"></p>
            </div>
        </div>

        <div class="p-4 sm:p-5 border-t border-slate-100 flex items-center justify-between bg-slate-50/70 shrink-0">
            <button type="button" onclick="closeDetailModal()" class="px-5 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold transition cursor-pointer">
                Cerrar
            </button>
            <button type="button" id="btnDetailTomarAsistencia" onclick="openAsistenciaFromDetail()" class="inline-flex items-center gap-1.5 px-5 py-2 rounded-xl bg-[#39A900] hover:bg-[#2d8500] text-white text-xs font-bold transition shadow-xs cursor-pointer">
                <i class="fa-solid fa-clipboard-user"></i>
                <span>Gestionar Asistencia</span>
            </button>
        </div>
    </div>
</div>

<!-- SCRIPT DEL CRONOGRAMA INTERACTIVO Y ASISTENCIA -->
<script>
    // Estado del Calendario
    let currentDate = new Date();
    let selectedDate = new Date();
    let currentView = 'month';
    let currentStatusFilter = 'all';

    // Eventos cargados desde la base de datos (solo actividades pendientes sin asistencia)
    let events = [
        @foreach($actividades as $act)
        @if($act->estado !== 'Realizada' && (empty($act->numero_asistentes) || $act->numero_asistentes <= 0))
        {
            id: {{ $act->id_actividad }},
            source: '{{ $act->source ?? "actividad_sst" }}',
            date: '{{ $act->fecha }}',
            name: {!! json_encode($act->nombre) !!},
            time: '{{ substr($act->hora, 0, 5) }}',
            time_end: '{{ $act->hora_fin ? substr($act->hora_fin, 0, 5) : "" }}',
            tipo_actividad: {!! json_encode($act->tipo_actividad ?? 'Pausa Activa') !!},
            lugar_nombre: {!! json_encode($act->lugar_nombre) !!},
            responsable: {!! json_encode($act->responsable) !!},
            instructor: {!! json_encode($act->instructor) !!},
            estado: '{{ $act->estado }}',
            descripcion: {!! json_encode($act->descripcion ?? '') !!},
            ficha_programa: {!! json_encode($act->ficha_programa ?? '') !!},
            participantes: {!! json_encode($act->participantes ?? '') !!},
            numero_asistentes: {{ (int) ($act->numero_asistentes ?? 0) }},
            observaciones: {!! json_encode($act->observaciones ?? '') !!}
        },
        @endif
        @endforeach
    ];

    const monthNames = [
        'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
    ];

    const dayNamesNames = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
    const shortDayNames = ['LUN', 'MAR', 'MIÉ', 'JUE', 'VIE', 'SÁB', 'DOM'];

    document.addEventListener('DOMContentLoaded', function() {
        renderCurrentView();
        updateSelectedDateUI();
        updateEventsCount();
    });

    function switchView(view) {
        currentView = view;

        document.getElementById('viewBtnDay').className = view === 'day' ? 'px-3.5 py-1.5 rounded-lg bg-white text-[#39A900] shadow-xs font-extrabold border border-slate-200/60 transition cursor-pointer' : 'px-3.5 py-1.5 rounded-lg transition cursor-pointer hover:text-slate-900';
        document.getElementById('viewBtnWeek').className = view === 'week' ? 'px-3.5 py-1.5 rounded-lg bg-white text-[#39A900] shadow-xs font-extrabold border border-slate-200/60 transition cursor-pointer' : 'px-3.5 py-1.5 rounded-lg transition cursor-pointer hover:text-slate-900';
        document.getElementById('viewBtnMonth').className = view === 'month' ? 'px-3.5 py-1.5 rounded-lg bg-white text-[#39A900] shadow-xs font-extrabold border border-slate-200/60 transition cursor-pointer' : 'px-3.5 py-1.5 rounded-lg transition cursor-pointer hover:text-slate-900';

        document.getElementById('headerViewBadge').innerText = view === 'month' ? 'Mes' : (view === 'week' ? 'Semana' : 'Día');

        document.getElementById('containerMonthView').classList.toggle('hidden', view !== 'month');
        document.getElementById('containerWeekView').classList.toggle('hidden', view !== 'week');
        document.getElementById('containerDayView').classList.toggle('hidden', view !== 'day');

        renderCurrentView();
    }

    function renderCurrentView() {
        if (currentView === 'month') {
            renderMonthView();
        } else if (currentView === 'week') {
            renderWeekView();
        } else if (currentView === 'day') {
            renderDayView();
        }
        updateEventsCount();
    }

    function navigate(direction) {
        if (currentView === 'month') {
            currentDate.setMonth(currentDate.getMonth() + direction);
        } else if (currentView === 'week') {
            currentDate.setDate(currentDate.getDate() + (direction * 7));
        } else if (currentView === 'day') {
            currentDate.setDate(currentDate.getDate() + direction);
            selectedDate = new Date(currentDate);
            updateSelectedDateUI();
        }
        renderCurrentView();
    }

    function goToToday() {
        currentDate = new Date();
        selectedDate = new Date();
        renderCurrentView();
        updateSelectedDateUI();
    }

    function triggerDatePicker() {
        const picker = document.getElementById('datePickerHeader');
        if (picker) {
            picker.value = formatDateForInput(selectedDate);
            if (typeof picker.showPicker === 'function') {
                picker.showPicker();
            } else {
                picker.click();
            }
        }
    }

    function onHeaderDateChange(val) {
        if (!val) return;
        selectDay(val);
    }

    function formatDateForInput(d) {
        const year = d.getFullYear();
        const month = String(d.getMonth() + 1).padStart(2, '0');
        const day = String(d.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    function isSameDate(d1, d2) {
        return d1.getFullYear() === d2.getFullYear() &&
               d1.getMonth() === d2.getMonth() &&
               d1.getDate() === d2.getDate();
    }

    function updateEventsCount() {
        const count = events.length;
        document.getElementById('activeEventsCount').innerText = `${count} Actividad${count !== 1 ? 'es' : ''}`;
    }

    // Paleta de colores e iconos por tipo de actividad
    function getActivityTheme(tipo, name = '') {
        const textToMatch = `${tipo || ''} ${name || ''}`.trim().toLowerCase();
        
        if (textToMatch.includes('pausa')) {
            return {
                name: 'Pausa Activa',
                badgeClass: 'bg-sky-500 text-white shadow-2xs border-sky-600/30',
                badgeLight: 'bg-sky-50 text-sky-800 border-sky-200',
                dot: 'bg-sky-500 ring-sky-200',
                cellBg: 'bg-sky-50/40 border-sky-200/90 shadow-2xs hover:bg-sky-100/40',
                textColor: 'text-sky-700',
                topBorder: 'border-t-4 border-t-sky-500',
                icon: 'fa-person-running'
            };
        }
        if (textToMatch.includes('charla')) {
            return {
                name: 'Charla',
                badgeClass: 'bg-blue-700 text-white shadow-2xs border-blue-800/30',
                badgeLight: 'bg-blue-50 text-blue-900 border-blue-300',
                dot: 'bg-blue-700 ring-blue-200',
                cellBg: 'bg-blue-50/40 border-blue-300/90 shadow-2xs hover:bg-blue-100/40',
                textColor: 'text-blue-800',
                topBorder: 'border-t-4 border-t-blue-700',
                icon: 'fa-bullhorn'
            };
        }
        if (textToMatch.includes('sensibiliz')) {
            return {
                name: 'Sensibilización',
                badgeClass: 'bg-emerald-600 text-white shadow-2xs border-emerald-700/30',
                badgeLight: 'bg-emerald-50 text-emerald-800 border-emerald-200',
                dot: 'bg-emerald-500 ring-emerald-200',
                cellBg: 'bg-emerald-50/40 border-emerald-200/90 shadow-2xs hover:bg-emerald-100/40',
                textColor: 'text-emerald-700',
                topBorder: 'border-t-4 border-t-emerald-500',
                icon: 'fa-hand-holding-heart'
            };
        }
        return {
            name: tipo || 'Actividad SST',
            badgeClass: 'bg-[#39A900] text-white shadow-2xs border-emerald-700/30',
            badgeLight: 'bg-emerald-50 text-emerald-800 border-emerald-200',
            dot: 'bg-emerald-500 ring-emerald-200',
            cellBg: 'bg-emerald-50/40 border-emerald-200/90 shadow-2xs hover:bg-emerald-100/40',
            textColor: 'text-emerald-700',
            topBorder: 'border-t-4 border-t-[#39A900]',
            icon: 'fa-calendar-check'
        };
    }

    // ----------------------------------------------------
    // 1. RENDER VISTA MES
    // ----------------------------------------------------
    function renderMonthView() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();

        document.getElementById('calendarHeaderTitle').innerText = `${monthNames[month]} de ${year}`;

        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);

        let startingDayIndex = firstDay.getDay() - 1;
        if (startingDayIndex === -1) startingDayIndex = 6;

        const totalDaysInMonth = lastDay.getDate();
        const prevMonthLastDay = new Date(year, month, 0).getDate();

        const grid = document.getElementById('calendarGridMonth');
        grid.innerHTML = '';

        for (let i = startingDayIndex; i > 0; i--) {
            const dayNum = prevMonthLastDay - i + 1;
            const dayCell = createMonthDayCell(dayNum, true, false, false, null);
            grid.appendChild(dayCell);
        }

        for (let day = 1; day <= totalDaysInMonth; day++) {
            const formattedDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const isSelected = selectedDate.getFullYear() === year &&
                               selectedDate.getMonth() === month &&
                               selectedDate.getDate() === day;

            const isToday = (new Date()).getFullYear() === year &&
                            (new Date()).getMonth() === month &&
                            (new Date()).getDate() === day;

            const dayEvents = events.filter(e => e.date === formattedDate);
            const dayCell = createMonthDayCell(day, false, isSelected, isToday, formattedDate, dayEvents);
            grid.appendChild(dayCell);
        }

        const totalRendered = startingDayIndex + totalDaysInMonth;
        const totalGridCells = totalRendered > 35 ? 42 : 35;
        const nextDays = totalGridCells - totalRendered;

        for (let day = 1; day <= nextDays; day++) {
            const dayCell = createMonthDayCell(day, true, false, false, null);
            grid.appendChild(dayCell);
        }
    }

    function createMonthDayCell(dayNum, isOtherMonth, isSelected, isToday, fullDateStr, dayEvents = []) {
        const cell = document.createElement('div');
        
        if (isOtherMonth) {
            cell.className = 'calendar-grid-day border border-slate-100/70 p-1.5 rounded-xl bg-slate-50/40 text-slate-300 flex flex-col justify-between select-none';
            cell.innerHTML = `<span class="text-xs font-semibold pl-1 pt-0.5">${dayNum}</span>`;
            return cell;
        }

        const hasEvents = dayEvents.length > 0;
        const primaryTheme = hasEvents ? getActivityTheme(dayEvents[0].tipo_actividad, dayEvents[0].name) : null;

        let bgClass = 'bg-white';
        if (isSelected) {
            bgClass = 'calendar-day-selected cursor-pointer transition';
        } else if (isToday) {
            bgClass = 'calendar-day-today cursor-pointer transition';
        } else if (hasEvents) {
            bgClass = `${primaryTheme.cellBg} cursor-pointer transition`;
        } else {
            bgClass = 'bg-white hover:bg-slate-50/80 cursor-pointer transition';
        }

        cell.className = `calendar-grid-day border border-slate-200/70 p-1.5 rounded-xl flex flex-col justify-between ${bgClass}`;
        cell.setAttribute('onclick', `selectDay('${fullDateStr}')`);

        let numberBadgeHTML = '';
        if (isSelected) {
            numberBadgeHTML = `<span class="w-5 h-5 rounded-md bg-blue-600 text-white text-[11px] font-extrabold flex items-center justify-center shadow-xs">${dayNum}</span>`;
        } else if (isToday) {
            numberBadgeHTML = `<span class="w-5 h-5 rounded-md bg-[#39A900] text-white text-[11px] font-extrabold flex items-center justify-center shadow-xs">${dayNum}</span>`;
        } else {
            numberBadgeHTML = `<span class="text-xs font-extrabold ${hasEvents ? primaryTheme.textColor : 'text-slate-700'} pl-1">${dayNum}</span>`;
        }

        let eventsHTML = '';
        if (hasEvents) {
            const firstEvt = dayEvents[0];
            const evtTheme = getActivityTheme(firstEvt.tipo_actividad, firstEvt.name);

            eventsHTML = `
                <div class="mt-1 space-y-0.5 overflow-hidden">
                    <div class="px-2 py-0.5 rounded-lg text-[9.5px] font-extrabold ${evtTheme.badgeClass} border truncate flex items-center gap-1.5" title="[${evtTheme.name}] ${firstEvt.name}">
                        <i class="fa-solid ${evtTheme.icon} text-[8.5px] shrink-0 opacity-90"></i>
                        <span class="truncate">${firstEvt.name}</span>
                    </div>
                    ${dayEvents.length > 1 ? `<div class="text-[8px] font-black ${evtTheme.textColor} pl-0.5 tracking-tight">+${dayEvents.length - 1} más</div>` : ''}
                </div>
            `;
        }

        cell.innerHTML = `
            <div class="flex items-center justify-between">
                ${numberBadgeHTML}
                ${hasEvents ? `<span class="w-2.5 h-2.5 rounded-full ${primaryTheme.dot} ring-2 shrink-0"></span>` : ''}
            </div>
            ${eventsHTML}
        `;

        return cell;
    }

    // ----------------------------------------------------
    // 2. RENDER VISTA SEMANA
    // ----------------------------------------------------
    function renderWeekView() {
        const curr = new Date(currentDate);
        const day = curr.getDay();
        const diff = curr.getDate() - day + (day === 0 ? -6 : 1);
        const firstDayOfWeek = new Date(curr.setDate(diff));

        const endOfWeek = new Date(firstDayOfWeek);
        endOfWeek.setDate(firstDayOfWeek.getDate() + 6);

        document.getElementById('calendarHeaderTitle').innerText = 
            `${firstDayOfWeek.getDate()} ${monthNames[firstDayOfWeek.getMonth()]} - ${endOfWeek.getDate()} ${monthNames[endOfWeek.getMonth()]} de ${endOfWeek.getFullYear()}`;

        const headersGrid = document.getElementById('weekHeadersGrid');
        const daysGrid = document.getElementById('weekDaysGrid');
        headersGrid.innerHTML = '';
        daysGrid.innerHTML = '';

        for (let i = 0; i < 7; i++) {
            const dayDate = new Date(firstDayOfWeek);
            dayDate.setDate(firstDayOfWeek.getDate() + i);

            const isSelected = isSameDate(dayDate, selectedDate);
            const isToday = isSameDate(dayDate, new Date());
            const formattedDate = formatDateForInput(dayDate);
            const dayEvents = events.filter(e => e.date === formattedDate);

            const headerCol = document.createElement('div');
            headerCol.className = `p-2 rounded-xl text-center border transition cursor-pointer ${
                isSelected ? 'bg-emerald-50 border-emerald-300 text-[#064e3b] font-extrabold' : 'border-transparent text-slate-600 hover:bg-slate-50'
            }`;
            headerCol.setAttribute('onclick', `selectDay('${formattedDate}')`);
            headerCol.innerHTML = `
                <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400">${shortDayNames[i]}</div>
                <div class="text-base font-extrabold mt-0.5 ${isToday ? 'w-7 h-7 rounded-full bg-[#39A900] text-white flex items-center justify-center mx-auto shadow-xs' : ''}">
                    ${dayDate.getDate()}
                </div>
            `;
            headersGrid.appendChild(headerCol);

            const dayCol = document.createElement('div');
            dayCol.className = `min-h-[380px] p-2 rounded-2xl border border-slate-100 flex flex-col gap-2 ${isSelected ? 'bg-emerald-50/20 border-emerald-200/60' : 'bg-slate-50/40'}`;

            if (dayEvents.length === 0) {
                dayCol.innerHTML = `
                    <div onclick="selectDay('${formattedDate}')" class="h-full flex flex-col items-center justify-center text-center p-2 text-slate-300 hover:text-slate-400 cursor-pointer">
                        <i class="fa-solid fa-calendar text-xs mb-1"></i>
                        <span class="text-[10px] font-semibold">Sin eventos</span>
                    </div>
                `;
            } else {
                dayCol.innerHTML = dayEvents.map(evt => {
                    const evtTheme = getActivityTheme(evt.tipo_actividad, evt.name);
                    return `
                    <div onclick="openAsistenciaModal(${evt.id})" class="p-2.5 rounded-xl bg-white border border-slate-200/80 shadow-2xs hover:shadow-xs transition cursor-pointer space-y-1.5 border-l-4 ${evtTheme.topBorder.replace('border-t-4', '')}">
                        <div class="flex items-center justify-between text-[10px] font-extrabold ${evtTheme.textColor}">
                            <span><i class="fa-regular fa-clock"></i> ${evt.time}</span>
                            <span class="w-2 h-2 rounded-full ${evt.estado === 'Realizada' ? 'bg-emerald-500' : evtTheme.dot}"></span>
                        </div>
                        <h5 class="text-xs font-bold text-slate-800 line-clamp-2 leading-tight">${evt.name}</h5>
                        <div class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[9px] font-bold ${evtTheme.badgeLight} truncate">
                            <i class="fa-solid ${evtTheme.icon} text-[8px]"></i>
                            <span>${evtTheme.name}</span>
                        </div>
                    </div>
                    `;
                }).join('');
            }

            daysGrid.appendChild(dayCol);
        }
    }

    // ----------------------------------------------------
    // 3. RENDER VISTA DÍA
    // ----------------------------------------------------
    function renderDayView() {
        const dayName = dayNamesNames[selectedDate.getDay()];
        const dayNum = selectedDate.getDate();
        const monthName = monthNames[selectedDate.getMonth()];
        const year = selectedDate.getFullYear();

        document.getElementById('calendarHeaderTitle').innerText = `${dayName}, ${dayNum} de ${monthName} de ${year}`;
        document.getElementById('dayViewSubTitle').innerText = `Cronograma Horario - ${dayName} ${dayNum}`;

        const formattedSelected = formatDateForInput(selectedDate);
        const dayEvents = events.filter(e => e.date === formattedSelected);
        document.getElementById('dayEventsCountBadge').innerText = `${dayEvents.length} Evento${dayEvents.length !== 1 ? 's' : ''} hoy`;

        const timeline = document.getElementById('dayHoursTimeline');
        timeline.innerHTML = '';

        const hours = [
            '07:00', '08:00', '09:00', '10:00', '11:00',
            '12:00', '13:00', '14:00', '15:00', '16:00',
            '17:00', '18:00'
        ];

        hours.forEach(hr => {
            const hrEvents = dayEvents.filter(e => e.time.startsWith(hr.substring(0, 2)));

            const slot = document.createElement('div');
            slot.className = 'time-slot flex items-start gap-3 p-2 rounded-xl transition border-b border-slate-100 last:border-0';

            let eventsInSlotHTML = '';
            if (hrEvents.length > 0) {
                eventsInSlotHTML = hrEvents.map(evt => {
                    const evtTheme = getActivityTheme(evt.tipo_actividad, evt.name);
                    return `
                    <div class="flex-1 p-3 rounded-xl ${evtTheme.cellBg} border flex items-center justify-between shadow-2xs">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-extrabold ${evtTheme.textColor}">${evt.name}</span>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[9px] font-bold rounded-md ${evtTheme.badgeLight} border">
                                    <i class="fa-solid ${evtTheme.icon} text-[8px]"></i>
                                    <span>${evt.tipo_actividad || evtTheme.name}</span>
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 font-medium mt-0.5">Resp: ${evt.responsable} ${evt.lugar_nombre ? '• Lugar: ' + evt.lugar_nombre : ''}</p>
                        </div>
                        <button onclick="openAsistenciaModal(${evt.id})" class="px-3 py-1.5 text-xs font-extrabold text-white bg-[#39A900] hover:bg-[#2d8500] rounded-xl shadow-xs transition cursor-pointer flex items-center gap-1.5">
                            <i class="fa-solid fa-clipboard-user"></i>
                            <span>Asistencia</span>
                        </button>
                    </div>
                    `;
                }).join('');
            } else {
                eventsInSlotHTML = `
                    <div class="flex-1 py-2 px-3 text-xs text-slate-400 border border-dashed border-slate-200 rounded-xl flex items-center justify-between">
                        <span>Horario disponible</span>
                    </div>
                `;
            }

            slot.innerHTML = `
                <div class="w-14 text-xs font-extrabold text-slate-400 pt-1 shrink-0 text-right">${hr}</div>
                ${eventsInSlotHTML}
            `;
            timeline.appendChild(slot);
        });
    }

    function selectDay(dateStr) {
        if (!dateStr) return;
        const parts = dateStr.split('-');
        const chosen = new Date(parts[0], parseInt(parts[1]) - 1, parts[2]);
        selectedDate = chosen;
        currentDate = new Date(selectedDate);
        renderCurrentView();
        updateSelectedDateUI();
    }

    function updateSelectedDateUI() {
        const dayName = dayNamesNames[selectedDate.getDay()];
        const dayNum = selectedDate.getDate();
        const monthName = monthNames[selectedDate.getMonth()];
        const year = selectedDate.getFullYear();

        const selectedDateSubtitleEvents = document.getElementById('selectedDateSubtitleEvents');
        if (selectedDateSubtitleEvents) {
            selectedDateSubtitleEvents.innerText = `Mostrando registros para: ${dayName}, ${dayNum} de ${monthName} de ${year}`;
        }

        renderDayEventsGrid();
    }

    function filterEventsStatus(status) {
        currentStatusFilter = status;
        ['All', 'Programada', 'Realizada', 'Cancelada'].forEach(st => {
            const btn = document.getElementById(`statusFilter${st}`);
            if (btn) {
                if (st.toLowerCase() === status.toLowerCase() || (st === 'All' && status === 'all')) {
                    btn.className = 'px-3.5 py-1.5 rounded-xl text-xs font-extrabold bg-slate-900 text-white shadow-xs transition cursor-pointer';
                } else {
                    btn.className = 'px-3.5 py-1.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition cursor-pointer';
                }
            }
        });
        renderDayEventsGrid();
    }

    // ----------------------------------------------------
    // 4. RENDER GRILLA DE ACTIVIDADES PROGRAMADAS DEL DÍA
    // ----------------------------------------------------
    function renderDayEventsGrid() {
        const formattedSelected = formatDateForInput(selectedDate);
        let dayEvents = events.filter(e => e.date === formattedSelected);

        if (currentStatusFilter !== 'all') {
            dayEvents = dayEvents.filter(e => {
                if (currentStatusFilter === 'Realizada') {
                    return e.estado === 'Realizada';
                }
                return e.estado === currentStatusFilter;
            });
        }

        const grid = document.getElementById('dayEventsGrid');
        const badge = document.getElementById('selectedDayEventsBadge');
        badge.innerText = `${dayEvents.length} Evento${dayEvents.length !== 1 ? 's' : ''}`;

        if (dayEvents.length === 0) {
            grid.innerHTML = `
                <div class="col-span-full py-12 px-4 text-center rounded-2xl bg-slate-50/70 border border-dashed border-slate-200">
                    <div class="w-12 h-12 rounded-2xl bg-white shadow-xs text-slate-400 flex items-center justify-center mx-auto mb-3 border border-slate-200/70">
                        <i class="fa-regular fa-calendar-xmark text-xl"></i>
                    </div>
                    <h4 class="text-sm font-bold text-slate-800">Sin actividades registradas</h4>
                    <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto leading-relaxed">No hay eventos agendados para este día o estado en Actividades SST.</p>
                </div>
            `;
            return;
        }

        grid.innerHTML = dayEvents.map(evt => {
            const typeInfo = getActivityTheme(evt.tipo_actividad, evt.name);

            let statusBadge = `
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-50 text-amber-700 border border-amber-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Programada
                </span>
            `;
            if (evt.estado === 'Realizada') {
                statusBadge = `
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Realizada
                    </span>
                `;
            } else if (evt.estado === 'Cancelada') {
                statusBadge = `
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-50 text-rose-700 border border-rose-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Cancelada
                    </span>
                `;
            }

            return `
                <div class="bg-white rounded-2xl border border-slate-200/80 ${typeInfo.topBorder} p-4 shadow-xs hover:shadow-md transition flex flex-col justify-between space-y-3 cursor-pointer group"
                     onclick="openAsistenciaModal(${evt.id})">
                    <div class="space-y-2">
                        <div class="flex items-center justify-between gap-2">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase ${typeInfo.badgeLight} border">
                                <i class="fa-solid ${typeInfo.icon} text-[9px]"></i>
                                <span>${evt.tipo_actividad || typeInfo.name}</span>
                            </span>
                            ${statusBadge}
                        </div>

                        <h4 class="text-sm font-extrabold text-slate-900 leading-snug group-hover:text-[#39A900] transition">
                            ${evt.name}
                        </h4>

                        <div class="text-xs text-slate-600 space-y-1.5 pt-1 border-t border-slate-100">
                            <div class="flex items-center gap-1.5 font-semibold text-slate-700">
                                <i class="fa-regular fa-clock text-[#39A900]"></i>
                                <span>Hora: ${evt.time} hs ${evt.time_end ? ' - ' + evt.time_end : ''}</span>
                            </div>
                            <div class="flex items-center gap-1.5 font-medium text-slate-600">
                                <i class="fa-solid fa-chalkboard-user text-slate-400 text-xs"></i>
                                <span class="truncate">Instructor: <strong>${evt.instructor || evt.responsable}</strong></span>
                            </div>
                            ${evt.ficha_programa ? `
                                <div class="flex items-center gap-1.5 text-[11px] font-semibold text-slate-600">
                                    <i class="fa-solid fa-id-card text-blue-500 text-xs"></i>
                                    <span>Ficha: <strong>${evt.ficha_programa}</strong></span>
                                </div>
                            ` : ''}
                            ${evt.lugar_nombre ? `
                                <div class="flex items-center gap-1.5 text-[11px] font-medium text-slate-500">
                                    <i class="fa-solid fa-location-dot text-emerald-500 text-xs"></i>
                                    <span class="truncate">Lugar: <span class="text-slate-700 font-semibold">${evt.lugar_nombre}</span></span>
                                </div>
                            ` : ''}
                        </div>

                        ${evt.descripcion ? `
                            <p class="text-[11px] text-slate-500 line-clamp-2 bg-slate-50 p-2 rounded-xl border border-slate-100 italic mt-1">
                                "${evt.descripcion}"
                            </p>
                        ` : ''}
                    </div>

                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between" onclick="event.stopPropagation()">
                        <button type="button" onclick="openDetailModal(${evt.id})" class="text-[11px] font-extrabold text-slate-500 hover:text-slate-800 transition cursor-pointer flex items-center gap-1">
                            <i class="fa-solid fa-eye"></i>
                            <span>Ver Detalle</span>
                        </button>
                        
                        <button type="button" onclick="openAsistenciaModal(${evt.id})" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl ${evt.estado === 'Realizada' ? 'bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200' : 'bg-[#39A900] hover:bg-[#2d8500] text-white shadow-xs hover:shadow-md'} text-xs font-bold transition cursor-pointer">
                            <i class="fa-solid ${evt.estado === 'Realizada' ? 'fa-clipboard-check' : 'fa-clipboard-user'} text-xs"></i>
                            <span>${evt.estado === 'Realizada' ? 'Asistencia (' + (evt.numero_asistentes || 0) + ')' : 'Registrar Asistencia'}</span>
                        </button>
                    </div>
                </div>
            `;
        }).join('');
    }

    // ----------------------------------------------------
    // 5. MODAL DE ASISTENCIA Y LLAMADO A LISTA
    // ----------------------------------------------------
    let currentActivityForAttendance = null;
    let aprendicesCache = [];
    let isModoManual = false;

    function openAsistenciaModal(id) {
        const data = events.find(e => e.id == id);
        if (!data) return;

        currentActivityForAttendance = data;

        const form = document.getElementById('formAsistencia');
        form.action = "{{ url('Sst/aprendiz/actividades-sst') }}/" + data.id + "/asistencia";

        document.getElementById('asistenciaActividadTitulo').textContent = data.name || 'Actividad SST';
        document.getElementById('asistenciaTipoBadge').textContent = (data.tipo_actividad || 'Actividad') + ' • Convocada';

        const iconContainer = document.getElementById('modalHeaderIcon');
        const theme = getActivityTheme(data.tipo_actividad, data.name);
        iconContainer.className = `w-12 h-12 rounded-2xl ${theme.badgeLight} border flex items-center justify-center text-xl shadow-2xs shrink-0`;
        iconContainer.innerHTML = `<i class="fa-solid ${theme.icon}"></i>`;

        document.getElementById('asistenciaActividadFecha').textContent = data.date || 'Sin fecha';
        document.getElementById('asistenciaActividadHorario').textContent = (data.time || '') + (data.time_end ? ' - ' + data.time_end : '');

        document.getElementById('asistencia_ficha_programa').value = data.ficha_programa || '';
        document.getElementById('asistencia_instructor').value = data.instructor || data.responsable || '';
        document.getElementById('asistencia_responsable').value = data.responsable || '';
        document.getElementById('asistencia_numero_asistentes').value = data.numero_asistentes || 0;
        document.getElementById('asistencia_observaciones').value = data.observaciones || '';
        document.getElementById('asistencia_participantes').value = data.participantes || '';
        document.getElementById('asistencia_estado').value = data.estado === 'Cancelada' ? 'Cancelada' : 'Realizada';

        const overlay = document.getElementById('modalAsistenciaOverlay');
        overlay.classList.remove('hidden');
        setTimeout(() => {
            overlay.classList.remove('opacity-0');
            overlay.querySelector('div').classList.remove('scale-95');
        }, 10);

        if (data.ficha_programa) {
            consultarAprendicesPorFicha(data.ficha_programa, data.participantes || '');
        } else {
            resetSeccionAprendices();
        }
    }

    function closeAsistenciaModal() {
        const overlay = document.getElementById('modalAsistenciaOverlay');
        overlay.classList.add('opacity-0');
        overlay.querySelector('div').classList.add('scale-95');
        setTimeout(() => {
            overlay.classList.add('hidden');
            cerrarDropdownFichas();
        }, 200);
    }

    function openDetailModal(id) {
        const data = events.find(e => e.id == id);
        if (!data) return;

        currentActivityForAttendance = data;

        document.getElementById('detailTitulo').textContent = data.name || 'Detalle de Actividad';
        document.getElementById('detailSubtitulo').textContent = (data.tipo_actividad || 'Actividad') + ' • Registrada en Cronograma';
        document.getElementById('detailTipo').textContent = data.tipo_actividad || 'No especificado';
        document.getElementById('detailEstado').textContent = data.estado || 'Programada';
        document.getElementById('detailFecha').textContent = data.date || 'Sin fecha';
        document.getElementById('detailHorario').textContent = (data.time || '') + (data.time_end ? ' - ' + data.time_end : '');
        document.getElementById('detailLugar').textContent = data.lugar_nombre || 'No asignado';
        document.getElementById('detailFicha').textContent = data.ficha_programa || 'General';
        document.getElementById('detailInstructor').textContent = data.instructor || data.responsable || 'Sin asignar';
        document.getElementById('detailResponsable').textContent = data.responsable || 'No asignado';
        document.getElementById('detailDescripcion').textContent = data.descripcion || 'Sin descripción adicional.';
        document.getElementById('detailParticipantes').textContent = data.participantes || 'Ningún participante registrado aún.';
        document.getElementById('detailObservaciones').textContent = data.observaciones || 'Sin observaciones.';

        const overlay = document.getElementById('modalDetalleOverlay');
        overlay.classList.remove('hidden');
        setTimeout(() => {
            overlay.classList.remove('opacity-0');
            overlay.querySelector('div').classList.remove('scale-95');
        }, 10);
    }

    function closeDetailModal() {
        const overlay = document.getElementById('modalDetalleOverlay');
        overlay.classList.add('opacity-0');
        overlay.querySelector('div').classList.add('scale-95');
        setTimeout(() => {
            overlay.classList.add('hidden');
        }, 200);
    }

    function openAsistenciaFromDetail() {
        if (!currentActivityForAttendance) return;
        const id = currentActivityForAttendance.id;
        closeDetailModal();
        setTimeout(() => {
            openAsistenciaModal(id);
        }, 250);
    }

    // --- Manejo del Desplegable de Fichas ---
    function toggleFichasDropdown(event) {
        event.stopPropagation();
        const menu = document.getElementById('fichas_dropdown_menu');
        const icon = document.getElementById('iconArrowFicha');
        const isHidden = menu.classList.contains('hidden');

        if (isHidden) {
            menu.classList.remove('hidden');
            if (icon) icon.classList.add('rotate-180');
        } else {
            cerrarDropdownFichas();
        }
    }

    function cerrarDropdownFichas() {
        const menu = document.getElementById('fichas_dropdown_menu');
        const icon = document.getElementById('iconArrowFicha');
        if (menu) menu.classList.add('hidden');
        if (icon) icon.classList.remove('rotate-180');
    }

    function selectFichaOption(codigo) {
        const input = document.getElementById('asistencia_ficha_programa');
        input.value = codigo;
        cerrarDropdownFichas();
        consultarAprendicesPorFicha(codigo);
    }

    // --- Consulta AJAX de Aprendices por Ficha ---
    function consultarAprendicesPorFicha(codigoFicha, participantesGuardados = '') {
        const loading = document.getElementById('loading_aprendices');
        const emptyMsg = document.getElementById('empty_aprendices_msg');
        const listContainer = document.getElementById('lista_aprendices_container');
        const controles = document.getElementById('controles_marcado_asistencia');
        const buscadorContainer = document.getElementById('contenedor_buscador_aprendices');
        const badgeConteo = document.getElementById('badge_conteo_asistentes');

        emptyMsg.classList.add('hidden');
        listContainer.classList.add('hidden');
        listContainer.innerHTML = '';
        loading.classList.remove('hidden');

        fetch("{{ route('SST.aprendiz.actividades_sst.aprendices_por_ficha') }}?ficha=" + encodeURIComponent(codigoFicha))
            .then(res => res.json())
            .then(data => {
                loading.classList.add('hidden');

                if (data.success && data.aprendices && data.aprendices.length > 0) {
                    aprendicesCache = data.aprendices;
                    controles.style.display = 'flex';
                    buscadorContainer.classList.remove('hidden');
                    badgeConteo.classList.remove('hidden');
                    listContainer.classList.remove('hidden');

                    const guardadosLower = participantesGuardados ? participantesGuardados.toLowerCase() : '';

                    let html = '';
                    data.aprendices.forEach(ap => {
                        const isChecked = guardadosLower.includes(ap.full_name.toLowerCase()) || 
                                          (ap.document_number && ap.document_number !== 'S/N' && guardadosLower.includes(ap.document_number));

                        html += `
                            <label class="aprendiz-item flex items-center justify-between p-2.5 rounded-xl bg-slate-50 hover:bg-emerald-50/70 border border-slate-200/70 transition cursor-pointer text-xs group"
                                   data-name="${ap.full_name.toLowerCase()}" 
                                   data-doc="${ap.document_number}">
                                <div class="flex items-center gap-2.5 truncate">
                                    <input type="checkbox" 
                                           class="chk-aprendiz w-4 h-4 rounded text-[#39A900] focus:ring-[#39A900] border-slate-300 cursor-pointer"
                                           value="${ap.full_name}" 
                                           data-doc="${ap.document_number}"
                                           ${isChecked ? 'checked' : ''}
                                           onchange="sincronizarAsistentes()">
                                    <div class="truncate">
                                        <span class="font-bold text-slate-800 group-hover:text-slate-900 block truncate">${ap.full_name}</span>
                                        <span class="text-[10px] text-slate-400 font-semibold block">Doc: ${ap.document_number}</span>
                                    </div>
                                </div>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-white border border-slate-200/80 text-slate-500 shrink-0">
                                    ${ap.status}
                                </span>
                            </label>
                        `;
                    });

                    listContainer.innerHTML = html;
                    sincronizarAsistentes();

                } else {
                    aprendicesCache = [];
                    emptyMsg.innerHTML = '<i class="fa-solid fa-circle-exclamation text-amber-500 text-lg mb-1 block"></i><span>No se encontraron aprendices matriculados en esta ficha. Puedes activar el modo manual para escribir los nombres.</span>';
                    emptyMsg.classList.remove('hidden');
                    controles.style.display = 'flex';
                    activarModoManualDirecto();
                }
            })
            .catch(() => {
                loading.classList.add('hidden');
                emptyMsg.innerHTML = '<i class="fa-solid fa-triangle-exclamation text-rose-500 text-lg mb-1 block"></i><span>Error consultando aprendices. Usa el modo manual.</span>';
                emptyMsg.classList.remove('hidden');
                activarModoManualDirecto();
            });
    }

    function resetSeccionAprendices() {
        document.getElementById('loading_aprendices').classList.add('hidden');
        document.getElementById('empty_aprendices_msg').classList.remove('hidden');
        document.getElementById('lista_aprendices_container').classList.add('hidden');
        document.getElementById('lista_aprendices_container').innerHTML = '';
        document.getElementById('controles_marcado_asistencia').style.display = 'none';
        document.getElementById('contenedor_buscador_aprendices').classList.add('hidden');
        document.getElementById('badge_conteo_asistentes').classList.add('hidden');
        document.getElementById('contenedor_textarea_participantes').classList.add('hidden');
    }

    function marcarTodosAprendices(marcar) {
        const checkboxes = document.querySelectorAll('.chk-aprendiz');
        checkboxes.forEach(cb => {
            const item = cb.closest('.aprendiz-item');
            if (!item || item.style.display !== 'none') {
                cb.checked = marcar;
            }
        });
        sincronizarAsistentes();
    }

    function sincronizarAsistentes() {
        const checkboxes = document.querySelectorAll('.chk-aprendiz:checked');
        const nombres = [];
        checkboxes.forEach(cb => {
            const doc = cb.getAttribute('data-doc');
            nombres.push(cb.value + (doc && doc !== 'S/N' ? ' (CC ' + doc + ')' : ''));
        });

        document.getElementById('asistencia_participantes').value = nombres.join(',\n');
        const count = checkboxes.length;
        document.getElementById('asistencia_numero_asistentes').value = count;
        document.getElementById('txt_asistentes_count').textContent = count;
    }

    function toggleModoManual() {
        isModoManual = !isModoManual;
        const textareaContainer = document.getElementById('contenedor_textarea_participantes');
        const listContainer = document.getElementById('lista_aprendices_container');
        const buscador = document.getElementById('contenedor_buscador_aprendices');
        const btn = document.getElementById('btnModoManual');

        if (isModoManual) {
            textareaContainer.classList.remove('hidden');
            listContainer.classList.add('hidden');
            buscador.classList.add('hidden');
            btn.innerHTML = '<i class="fa-solid fa-list-check text-[9px]"></i> Ver lista';
            btn.classList.add('bg-emerald-100', 'text-emerald-800');
        } else {
            textareaContainer.classList.add('hidden');
            listContainer.classList.remove('hidden');
            buscador.classList.remove('hidden');
            btn.innerHTML = '<i class="fa-solid fa-pen-to-square text-[9px]"></i> Modo manual';
            btn.classList.remove('bg-emerald-100', 'text-emerald-800');
        }
    }

    function activarModoManualDirecto() {
        isModoManual = true;
        document.getElementById('contenedor_textarea_participantes').classList.remove('hidden');
        document.getElementById('lista_aprendices_container').classList.add('hidden');
        document.getElementById('contenedor_buscador_aprendices').classList.add('hidden');
        const btn = document.getElementById('btnModoManual');
        btn.innerHTML = '<i class="fa-solid fa-list-check text-[9px]"></i> Ver lista';
        btn.classList.add('bg-emerald-100', 'text-emerald-800');
    }

    // Buscador interno y listeners
    document.addEventListener('DOMContentLoaded', function() {
        const filtroAprendiz = document.getElementById('filtro_buscador_aprendiz');
        if (filtroAprendiz) {
            filtroAprendiz.addEventListener('input', function() {
                const term = this.value.toLowerCase().trim();
                const items = document.querySelectorAll('.aprendiz-item');
                items.forEach(item => {
                    const name = item.getAttribute('data-name') || '';
                    const doc = item.getAttribute('data-doc') || '';
                    if (name.includes(term) || doc.includes(term)) {
                        item.style.display = 'flex';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        }

        const inputFicha = document.getElementById('asistencia_ficha_programa');
        let debounceTimer;
        if (inputFicha) {
            inputFicha.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                const val = this.value.trim();
                debounceTimer = setTimeout(() => {
                    if (val.length >= 4) {
                        consultarAprendicesPorFicha(val);
                    } else {
                        resetSeccionAprendices();
                    }
                }, 500);
            });
        }

        document.addEventListener('click', function(e) {
            const container = document.getElementById('ficha_combo_container');
            if (container && !container.contains(e.target)) {
                cerrarDropdownFichas();
            }
        });
    });
</script>
@endsection

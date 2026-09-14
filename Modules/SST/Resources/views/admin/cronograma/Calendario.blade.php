@extends('sst::components.layouts.admin')

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
</style>

<div class="space-y-6 max-w-7xl mx-auto">

    <!-- 1. Encabezado de la Sección -->
    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4 transition-all duration-200">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center border border-orange-200/80 shadow-xs">
                <i class="fa-solid fa-calendar-days text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Cronograma SST</h1>
                <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">Configuración y administración del calendario de actividades, capacitaciones y jornadas de SST.</p>
            </div>
        </div>

        <!-- Badges Informativos -->
        <div class="flex flex-wrap items-center gap-2 self-start md:self-auto">
            <div class="px-3.5 py-1.5 rounded-xl bg-slate-100 border border-slate-200 text-xs font-bold text-slate-700 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-orange-500 animate-pulse"></span>
                <span>Vista Activa: <strong id="headerViewBadge" class="text-orange-600">Mes</strong></span>
            </div>
            <div class="px-3.5 py-1.5 rounded-xl bg-emerald-50 border border-emerald-200 text-xs font-bold text-emerald-700 flex items-center gap-1.5">
                <i class="fa-solid fa-circle-check text-xs"></i>
                <span id="activeEventsCount">0 Actividades</span>
            </div>
        </div>
    </div>

    <!-- 2. Barra de Control de Navegación del Calendario -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row items-center justify-between gap-4">
        
        <!-- Navegación de Fecha + Selector de Fecha -->
        <div class="flex items-center flex-wrap gap-2">
            <div class="flex items-center gap-1">
                <button id="btnPrev" onclick="navigate(-1)" class="w-9 h-9 rounded-xl border border-slate-200 text-slate-600 hover:text-orange-600 hover:bg-orange-50 hover:border-orange-200 flex items-center justify-center transition cursor-pointer" title="Anterior">
                    <i class="fa-solid fa-chevron-left text-xs"></i>
                </button>
                <button id="btnToday" onclick="goToToday()" class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 hover:text-orange-600 hover:bg-orange-50 hover:border-orange-200 transition cursor-pointer">
                    Hoy
                </button>
                <button id="btnNext" onclick="navigate(1)" class="w-9 h-9 rounded-xl border border-slate-200 text-slate-600 hover:text-orange-600 hover:bg-orange-50 hover:border-orange-200 flex items-center justify-center transition cursor-pointer" title="Siguiente">
                    <i class="fa-solid fa-chevron-right text-xs"></i>
                </button>
            </div>

            <!-- Botón / Input de Elección de Fecha -->
            <div class="relative">
                <input type="date" id="datePickerHeader" onchange="onHeaderDateChange(this.value)" class="absolute opacity-0 w-0 h-0 pointer-events-none">
                <button type="button" onclick="triggerDatePicker()" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 hover:text-orange-600 hover:bg-orange-50 hover:border-orange-200 transition cursor-pointer shadow-2xs">
                    <i class="fa-regular fa-calendar-check text-orange-500 text-sm"></i>
                    <span>Escoger fecha</span>
                </button>
            </div>
        </div>

        <!-- Título del Rango o Mes -->
        <h2 id="calendarHeaderTitle" class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight text-center">
            Septiembre de 2026
        </h2>

        <!-- Selector de Vista (Día / Semana / Mes) -->
        <div class="inline-flex p-1 bg-slate-100 rounded-xl border border-slate-200/70 text-xs font-bold text-slate-600">
            <button onclick="switchView('day')" id="viewBtnDay" class="px-3.5 py-1.5 rounded-lg transition cursor-pointer hover:text-slate-900">Día</button>
            <button onclick="switchView('week')" id="viewBtnWeek" class="px-3.5 py-1.5 rounded-lg transition cursor-pointer hover:text-slate-900">Semana</button>
            <button onclick="switchView('month')" id="viewBtnMonth" class="px-3.5 py-1.5 rounded-lg bg-white text-orange-600 shadow-xs font-extrabold border border-slate-200/60 transition cursor-pointer">Mes</button>
        </div>
    </div>

    <!-- 3. Layout Principal: Calendario (Izquierda) + Formulario (Derecha) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">

        <!-- LADO IZQUIERDO: Calendario (Grid Mes / Semana / Día) -->
        <div class="lg:col-span-7 xl:col-span-8 bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden p-4 sm:p-5 flex flex-col justify-between">
            
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

                <div id="calendarGridMonth" class="grid grid-cols-7 gap-1 flex-1">
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
                        <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 font-extrabold text-base flex items-center justify-center border border-orange-200/60 shrink-0">
                            <i class="fa-regular fa-clock"></i>
                        </div>
                        <div>
                            <h4 id="dayViewSubTitle" class="text-sm font-extrabold text-slate-900 tracking-tight">Cronograma Horario del Día</h4>
                            <p class="text-xs text-slate-500 font-medium mt-0.5">Haz clic en cualquier horario libre para agendar directamente.</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <span id="dayEventsCountBadge" class="px-3 py-1 rounded-xl bg-orange-100/70 text-orange-800 border border-orange-200 text-xs font-extrabold">
                            0 Eventos hoy
                        </span>
                    </div>
                </div>

                <div class="space-y-3 h-[440px] overflow-y-auto pr-1.5 scroll-smooth" id="dayHoursTimeline">
                    <!-- Se renderiza timeline dinámicamente -->
                </div>
            </div>

        </div>

        <!-- LADO DERECHO: Tarjeta de Registro (Formulario) -->
        <div class="lg:col-span-5 xl:col-span-4 flex flex-col">
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 sm:p-6 flex-1 flex flex-col justify-between">
                
                <div>
                    <!-- Encabezado del Formulario -->
                    <div class="mb-4 pb-3 border-b border-slate-100">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-widest bg-orange-50 text-orange-600 border border-orange-200/70 mb-1.5">
                            <i class="fa-solid fa-plus-circle"></i> REGISTRAR ACTIVIDAD SST
                        </span>
                        <h3 id="selectedDateTitle" class="text-base font-extrabold text-slate-900 tracking-tight">
                            Sábado, 12 de septiembre de 2026
                        </h3>
                    </div>

                    <!-- Formulario -->
                    <form id="activityForm" onsubmit="handleSaveActivity(event)" class="space-y-3.5">
                        
                        <!-- NOMBRE -->
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                                NOMBRE DE LA ACTIVIDAD *
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                                    <i class="fa-solid fa-file-pen"></i>
                                </span>
                                <input type="text" id="inputNombre" required placeholder="Ej: Capacitación en Prevención de Riesgos" 
                                    class="w-full pl-9 pr-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-800 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 transition bg-slate-50/30">
                            </div>
                        </div>

                        <!-- FECHA Y HORA -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                                    FECHA
                                </label>
                                <div class="relative">
                                    <input type="text" id="inputFecha" readonly 
                                        class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 bg-slate-100 cursor-not-allowed">
                                    <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400 text-xs">
                                        <i class="fa-regular fa-calendar"></i>
                                    </span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                                    HORA
                                </label>
                                <div class="relative">
                                    <input type="time" id="inputHora" value="09:00" required
                                        class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-800 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 transition bg-slate-50/30">
                                </div>
                            </div>
                        </div>

                        <!-- RESPONSABLE -->
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                                RESPONSABLE *
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                                    <i class="fa-solid fa-user-gear"></i>
                                </span>
                                <input type="text" id="inputResponsable" required placeholder="Nombre del responsable (Ej: Ing. Carlos)" 
                                    class="w-full pl-9 pr-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-800 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 transition bg-slate-50/30">
                            </div>
                        </div>

                        <!-- ESTADO -->
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                                ESTADO DE LA ACTIVIDAD
                            </label>
                            <select id="selectEstado" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-800 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 transition bg-white cursor-pointer">
                                <option value="Programada">Programada</option>
                                <option value="En Ejecución">En Ejecución</option>
                                <option value="Completada">Completada</option>
                                <option value="Cancelada">Cancelada</option>
                            </select>
                        </div>

                        <!-- DESCRIPCIÓN -->
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                                DESCRIPCIÓN Y NOTAS
                            </label>
                            <textarea id="inputDescripcion" rows="2.5" placeholder="Detalles de la actividad, sala, personal convocado..." 
                                class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-medium text-slate-800 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 transition bg-slate-50/30 resize-none"></textarea>
                        </div>

                        <!-- BOTONES DE ACCIÓN -->
                        <div class="pt-2.5 flex items-center gap-2.5 border-t border-slate-100">
                            <button type="button" onclick="clearForm()" class="flex-1 py-2 px-3 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-100 transition cursor-pointer text-center">
                                Limpiar
                            </button>
                            <button type="submit" class="flex-1 py-2 px-3 rounded-xl bg-orange-600 hover:bg-orange-700 active:bg-orange-800 text-white text-xs font-bold shadow-xs hover:shadow transition cursor-pointer text-center flex items-center justify-center gap-1.5">
                                <i class="fa-solid fa-check text-xs"></i>
                                <span>Registrar</span>
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>

    </div>

    <!-- 4. SECCIÓN INFERIOR COMPLETA (FULL-WIDTH): Listado Profesional de Actividades Registradas -->
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
                        Mostrando registros para: Sábado, 12 de septiembre de 2026
                    </p>
                </div>
            </div>

            <!-- Filtros Rápidos por Estado (State Filter) -->
            <div class="flex items-center gap-1.5 flex-wrap">
                <button onclick="filterEventsStatus('all')" id="statusFilterAll" class="px-3.5 py-1.5 rounded-xl text-xs font-extrabold bg-slate-900 text-white shadow-xs transition cursor-pointer">Todas</button>
                <button onclick="filterEventsStatus('Programada')" id="statusFilterProgramada" class="px-3.5 py-1.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition cursor-pointer">Programadas</button>
                <button onclick="filterEventsStatus('En Ejecución')" id="statusFilterEnEjecucion" class="px-3.5 py-1.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition cursor-pointer">En Ejecución</button>
                <button onclick="filterEventsStatus('Completada')" id="statusFilterCompletada" class="px-3.5 py-1.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition cursor-pointer">Completadas</button>
                <button onclick="filterEventsStatus('Cancelada')" id="statusFilterCancelada" class="px-3.5 py-1.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition cursor-pointer">Canceladas</button>
            </div>
        </div>

        <!-- Grilla Multicolumna Horizontal (3 Columnas) -->
        <div id="dayEventsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 pt-1">
            <!-- Se renderiza dinámicamente -->
        </div>

    </div>

</div>

<!-- MODAL PARA EDITAR ACTIVIDAD SST -->
<div id="modalEditEvent" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-2xl max-w-md w-full p-6 space-y-4 animate-in fade-in zoom-in duration-200">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center font-bold">
                    <i class="fa-solid fa-pen-to-square text-sm"></i>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900">Editar Actividad SST</h3>
                    <p class="text-[11px] text-slate-500 font-medium">Modifica los detalles del evento seleccionado</p>
                </div>
            </div>
            <button onclick="closeEditModal()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition cursor-pointer">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>

        <form onsubmit="handleUpdateActivity(event)" class="space-y-3.5">
            <input type="hidden" id="editEventId">

            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Nombre de la Actividad *</label>
                <input type="text" id="editNombre" required class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-800 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Hora *</label>
                    <input type="time" id="editHora" required class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-800 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Estado de la Actividad</label>
                    <select id="editEstado" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-800 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 bg-white cursor-pointer">
                        <option value="Programada">Programada</option>
                        <option value="En Ejecución">En Ejecución</option>
                        <option value="Completada">Completada</option>
                        <option value="Cancelada">Cancelada</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Responsable *</label>
                <input type="text" id="editResponsable" required class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-800 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Descripción y Notas</label>
                <textarea id="editDescripcion" rows="2.5" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-medium text-slate-800 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 resize-none"></textarea>
            </div>

            <div class="pt-2 flex items-center justify-end gap-2 border-t border-slate-100">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-100 transition cursor-pointer">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-orange-600 hover:bg-orange-700 text-white text-xs font-bold shadow-xs transition cursor-pointer flex items-center gap-1.5">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    <span>Guardar Cambios</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- NOTIFICACIÓN TOAST FLOTANTE (MENSAJE DE ÉXITO) -->
<div id="toastSuccessNotification" class="fixed top-5 right-5 z-50 transform translate-x-full opacity-0 transition-all duration-300 pointer-events-none">
    <div class="bg-white rounded-2xl p-4 shadow-xl border border-slate-100 border-l-4 border-l-emerald-500 flex items-center gap-3.5 max-w-md pointer-events-auto">
        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold shrink-0 border border-emerald-100">
            <i class="fa-solid fa-circle-check text-lg"></i>
        </div>
        <div class="flex-1 pr-2">
            <h4 class="text-xs font-extrabold text-slate-900">¡Registro Éxitoso!</h4>
            <p id="toastMessageText" class="text-xs text-slate-500 font-medium mt-0.5 leading-snug">Operación realizada correctamente.</p>
        </div>
        <button onclick="hideToastNotification()" class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-400 hover:text-slate-600 flex items-center justify-center transition cursor-pointer">
            <i class="fa-solid fa-xmark text-xs"></i>
        </button>
    </div>
</div>

<!-- SCRIPT DEL CALENDARIO E INTERACCIONES -->
<script>
    // Estado del Calendario
    let currentDate = new Date(2026, 8, 12);
    let selectedDate = new Date(2026, 8, 12);
    let currentView = 'month';
    let currentCategoryFilter = 'all';
    let currentStatusFilter = 'all';

    // Base de Datos en Memoria de Actividades SST (Vacío por defecto)
    let events = [];

    const monthNames = [
        'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
    ];

    const dayNamesNames = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
    const shortDayNames = ['LUN', 'MAR', 'MIÉ', 'JUE', 'VIE', 'SÁB', 'DOM'];

    document.addEventListener('DOMContentLoaded', function() {
        renderCurrentView();
        updateSelectedDateUI();
    });

    function switchView(view) {
        currentView = view;

        document.getElementById('viewBtnDay').className = view === 'day' ? 'px-3.5 py-1.5 rounded-lg bg-white text-orange-600 shadow-xs font-extrabold border border-slate-200/60 transition cursor-pointer' : 'px-3.5 py-1.5 rounded-lg transition cursor-pointer hover:text-slate-900';
        document.getElementById('viewBtnWeek').className = view === 'week' ? 'px-3.5 py-1.5 rounded-lg bg-white text-orange-600 shadow-xs font-extrabold border border-slate-200/60 transition cursor-pointer' : 'px-3.5 py-1.5 rounded-lg transition cursor-pointer hover:text-slate-900';
        document.getElementById('viewBtnMonth').className = view === 'month' ? 'px-3.5 py-1.5 rounded-lg bg-white text-orange-600 shadow-xs font-extrabold border border-slate-200/60 transition cursor-pointer' : 'px-3.5 py-1.5 rounded-lg transition cursor-pointer hover:text-slate-900';

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
        const parts = val.split('-');
        const chosen = new Date(parts[0], parseInt(parts[1]) - 1, parts[2]);
        currentDate = chosen;
        selectedDate = new Date(chosen);
        renderCurrentView();
        updateSelectedDateUI();
    }

    // ----------------------------------------------------
    // 1. RENDER VISTA MES
    // ----------------------------------------------------
    function renderMonthView() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();

        document.getElementById('calendarHeaderTitle').innerText = `${monthNames[month]} de ${year}`;

        const grid = document.getElementById('calendarGridMonth');
        grid.innerHTML = '';

        const firstDayOfMonth = new Date(year, month, 1);
        let startingDayIndex = firstDayOfMonth.getDay() - 1;
        if (startingDayIndex === -1) startingDayIndex = 6;

        const totalDaysInMonth = new Date(year, month + 1, 0).getDate();
        const prevMonthDays = new Date(year, month, 0).getDate();

        for (let i = startingDayIndex - 1; i >= 0; i--) {
            const dayNum = prevMonthDays - i;
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

        let bgClass = 'bg-white';
        if (isSelected) {
            bgClass = 'calendar-day-selected';
        } else if (isToday) {
            bgClass = 'calendar-day-today';
        } else if (hasEvents) {
            bgClass = 'bg-orange-50/50 border-orange-200/90 shadow-2xs hover:bg-orange-100/50';
        }

        cell.className = `calendar-grid-day border border-slate-200/70 p-1.5 rounded-xl flex flex-col justify-between cursor-pointer transition ${bgClass}`;
        cell.setAttribute('onclick', `selectDay('${fullDateStr}')`);

        let numberBadgeHTML = '';
        if (isSelected) {
            numberBadgeHTML = `<span class="w-5 h-5 rounded-md bg-blue-600 text-white text-[11px] font-extrabold flex items-center justify-center shadow-xs">${dayNum}</span>`;
        } else if (isToday) {
            numberBadgeHTML = `<span class="w-5 h-5 rounded-md bg-orange-500 text-white text-[11px] font-extrabold flex items-center justify-center shadow-xs">${dayNum}</span>`;
        } else {
            numberBadgeHTML = `<span class="text-xs font-extrabold ${hasEvents ? 'text-orange-950' : 'text-slate-700'} pl-1">${dayNum}</span>`;
        }

        let eventsHTML = '';
        if (hasEvents) {
            const firstEvt = dayEvents[0];

            eventsHTML = `
                <div class="mt-1 space-y-0.5 overflow-hidden">
                    <div class="px-2 py-0.5 rounded-lg text-[9.5px] font-extrabold bg-orange-500 text-white shadow-2xs truncate flex items-center gap-1 border border-orange-600/30" title="${firstEvt.name}">
                        <span class="w-1.5 h-1.5 rounded-full bg-white shrink-0"></span>
                        <span class="truncate">${firstEvt.name}</span>
                    </div>
                    ${dayEvents.length > 1 ? `<div class="text-[8px] font-black text-orange-700 pl-0.5 tracking-tight">+${dayEvents.length - 1} más</div>` : ''}
                </div>
            `;
        }

        cell.innerHTML = `
            <div class="flex items-center justify-between">
                ${numberBadgeHTML}
                ${hasEvents ? `<span class="w-2 h-2 rounded-full bg-orange-500 ring-2 ring-orange-200 shrink-0"></span>` : ''}
            </div>
            ${eventsHTML}
        `;

        return cell;
    }

    // ----------------------------------------------------
    // 2. RENDER VISTA SEMANA
    // ----------------------------------------------------
    function getWeekDays(date) {
        const curr = new Date(date);
        const dayOfWeek = curr.getDay();
        const distanceToMonday = (dayOfWeek + 6) % 7;
        const monday = new Date(curr);
        monday.setDate(curr.getDate() - distanceToMonday);

        const week = [];
        for (let i = 0; i < 7; i++) {
            const nextDay = new Date(monday);
            nextDay.setDate(monday.getDate() + i);
            week.push(nextDay);
        }
        return week;
    }

    function renderWeekView() {
        const weekDays = getWeekDays(currentDate);
        const startDay = weekDays[0];
        const endDay = weekDays[6];

        const startMonthName = monthNames[startDay.getMonth()];
        const endMonthName = monthNames[endDay.getMonth()];

        if (startDay.getMonth() === endDay.getMonth()) {
            document.getElementById('calendarHeaderTitle').innerText = `${startDay.getDate()} al ${endDay.getDate()} de ${startMonthName} de ${startDay.getFullYear()}`;
        } else {
            document.getElementById('calendarHeaderTitle').innerText = `${startDay.getDate()} de ${startMonthName} - ${endDay.getDate()} de ${endMonthName} de ${startDay.getFullYear()}`;
        }

        const headersGrid = document.getElementById('weekHeadersGrid');
        const daysGrid = document.getElementById('weekDaysGrid');

        headersGrid.innerHTML = '';
        daysGrid.innerHTML = '';

        weekDays.forEach((wDate, idx) => {
            const dateStr = formatDateForInput(wDate);
            const isSelected = isSameDate(wDate, selectedDate);
            const isToday = isSameDate(wDate, new Date());

            const hCell = document.createElement('div');
            hCell.className = `p-2 text-center rounded-xl cursor-pointer transition ${
                isSelected ? 'bg-blue-600 text-white font-extrabold shadow-xs' : (isToday ? 'bg-orange-500 text-white font-bold' : 'bg-slate-50 text-slate-700 hover:bg-slate-100')
            }`;
            hCell.setAttribute('onclick', `selectDay('${dateStr}')`);
            hCell.innerHTML = `
                <div class="text-[10px] uppercase font-bold tracking-wider">${shortDayNames[idx]}</div>
                <div class="text-sm font-extrabold mt-0.5">${wDate.getDate()}</div>
            `;
            headersGrid.appendChild(hCell);

            const bCell = document.createElement('div');
            bCell.className = `border border-slate-200/80 rounded-xl p-2 bg-white flex flex-col space-y-2 cursor-pointer transition overflow-y-auto ${
                isSelected ? 'ring-2 ring-blue-500 bg-blue-50/20' : 'hover:bg-slate-50/70'
            }`;
            bCell.setAttribute('onclick', `selectDay('${dateStr}')`);

            const dayEvts = events.filter(e => e.date === dateStr);

            if (dayEvts.length === 0) {
                bCell.innerHTML = `<div class="text-[10px] text-slate-300 font-medium text-center py-6">Sin actividades</div>`;
            } else {
                dayEvts.forEach(evt => {
                    let cardClass = 'bg-orange-50 border-orange-200 text-orange-900';

                    bCell.innerHTML += `
                        <div class="p-2 rounded-lg border text-[11px] font-semibold space-y-1 ${cardClass}" title="${evt.name}">
                            <div class="text-[10px] font-extrabold flex items-center justify-between">
                                <span><i class="fa-regular fa-clock mr-0.5"></i> ${evt.time}</span>
                            </div>
                            <div class="font-bold leading-tight line-clamp-2">${evt.name}</div>
                            <div class="text-[9px] opacity-80 truncate"><i class="fa-solid fa-user text-[8px] mr-1"></i>${evt.responsable}</div>
                        </div>
                    `;
                });
            }
            daysGrid.appendChild(bCell);
        });
    }

    // ----------------------------------------------------
    // 3. RENDER VISTA DÍA PROFESIONAL
    // ----------------------------------------------------
    function renderDayView() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();
        const day = currentDate.getDate();
        const dayOfWeekIndex = currentDate.getDay();

        const formattedTitle = `${dayNamesNames[dayOfWeekIndex]}, ${day} de ${monthNames[month]} de ${year}`;
        document.getElementById('calendarHeaderTitle').innerText = formattedTitle;
        document.getElementById('dayViewSubTitle').innerText = `Cronograma Horario • ${formattedTitle}`;

        const dateStr = formatDateForInput(currentDate);
        const dayEvts = events.filter(e => e.date === dateStr);
        const timeline = document.getElementById('dayHoursTimeline');

        const badge = document.getElementById('dayEventsCountBadge');
        if (badge) {
            badge.innerText = `${dayEvts.length} Evento${dayEvts.length !== 1 ? 's' : ''} hoy`;
        }

        timeline.innerHTML = '';

        const hours = [
            '06:00', '07:00', '08:00', '09:00', '10:00', '11:00', '12:00',
            '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00',
            '20:00', '21:00', '22:00'
        ];

        // Añadir horas personalizadas si el usuario agendó un evento en una hora fuera del rango estándar
        dayEvts.forEach(e => {
            if (e.time) {
                const hourStr = `${e.time.split(':')[0].padStart(2, '0')}:00`;
                if (!hours.includes(hourStr)) {
                    hours.push(hourStr);
                }
            }
        });

        // Ordenar las horas cronológicamente
        hours.sort((a, b) => parseInt(a.split(':')[0]) - parseInt(b.split(':')[0]));

        hours.forEach(hr => {
            const hrEvents = dayEvts.filter(e => {
                if (!e.time) return false;
                const eHour = parseInt(e.time.split(':')[0], 10);
                const slotHour = parseInt(hr.split(':')[0], 10);
                return eHour === slotHour;
            });

            const row = document.createElement('div');
            row.className = 'flex items-start gap-3 group';

            let eventsContent = '';
            if (hrEvents.length === 0) {
                eventsContent = `
                    <div class="flex-1 flex items-center justify-between p-3 rounded-2xl border border-dashed border-slate-200/90 bg-slate-50/40 text-slate-400 hover:bg-orange-50/40 hover:border-orange-300 hover:text-orange-600 transition cursor-pointer group/slot shadow-2xs" onclick="setFormTime('${hr}')">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-slate-300 group-hover/slot:bg-orange-500 transition"></span>
                            <span class="text-xs font-semibold text-slate-500 group-hover/slot:text-orange-700">Horario libre (${hr} hs)</span>
                        </div>
                        <span class="text-[11px] font-extrabold opacity-0 group-hover/slot:opacity-100 transition bg-orange-100 text-orange-700 px-2.5 py-1 rounded-xl flex items-center gap-1">
                            <i class="fa-solid fa-plus text-[10px]"></i> Agendar a las ${hr}
                        </span>
                    </div>
                `;
            } else {
                eventsContent = `<div class="flex-1 space-y-2">`;
                hrEvents.forEach(evt => {
                    let borderAccent = 'border-l-4 border-l-orange-500';
                    let statusStyle = 'bg-orange-100 text-orange-700 border-orange-200';

                    if (evt.estado === 'Completada') {
                        borderAccent = 'border-l-4 border-l-emerald-500';
                        statusStyle = 'bg-emerald-100 text-emerald-700 border-emerald-200';
                    } else if (evt.estado === 'En Ejecución') {
                        borderAccent = 'border-l-4 border-l-blue-500';
                        statusStyle = 'bg-blue-100 text-blue-700 border-blue-200';
                    } else if (evt.estado === 'Cancelada') {
                        borderAccent = 'border-l-4 border-l-rose-500';
                        statusStyle = 'bg-rose-100 text-rose-700 border-rose-200';
                    }

                    eventsContent += `
                        <div class="bg-white p-4 rounded-2xl border border-slate-200/90 ${borderAccent} shadow-xs hover:shadow-md transition flex flex-col md:flex-row md:items-center justify-between gap-3">
                            <div class="space-y-1 flex-1">
                                <div class="flex items-center gap-2">
                                    <h5 class="font-extrabold text-sm text-slate-900">${evt.name}</h5>
                                    
                                    <!-- Selector Rápido de Estado en Timeline -->
                                    <div class="relative">
                                        <select onchange="changeEventStatus(${evt.id}, this.value)" class="text-[10px] font-extrabold px-2.5 py-0.5 rounded-full border cursor-pointer ${statusStyle} focus:outline-none transition appearance-none pr-4">
                                            <option value="Programada" ${evt.estado === 'Programada' ? 'selected' : ''}>Programada</option>
                                            <option value="En Ejecución" ${evt.estado === 'En Ejecución' ? 'selected' : ''}>En Ejecución</option>
                                            <option value="Completada" ${evt.estado === 'Completada' ? 'selected' : ''}>Completada</option>
                                            <option value="Cancelada" ${evt.estado === 'Cancelada' ? 'selected' : ''}>Cancelada</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-3 text-xs text-slate-500 font-medium">
                                    <span><i class="fa-solid fa-user-gear text-slate-400 mr-1 text-[10px]"></i> ${evt.responsable}</span>
                                    <span><i class="fa-regular fa-clock text-orange-500 mr-1 text-[10px]"></i> ${evt.time} hs</span>
                                </div>

                                ${evt.descripcion ? `<p class="text-[11px] text-slate-500 italic bg-slate-50 p-2 rounded-xl border border-slate-100 mt-1">"${evt.descripcion}"</p>` : ''}
                            </div>

                            <div class="flex items-center gap-2 shrink-0 pt-2 md:pt-0 border-t md:border-t-0 border-slate-100">
                                <button onclick="openEditModal(${evt.id})" class="px-2.5 py-1.5 rounded-xl border border-slate-200 hover:bg-slate-100 text-blue-600 font-extrabold text-xs transition cursor-pointer flex items-center gap-1">
                                    <i class="fa-solid fa-pen-to-square"></i> Editar
                                </button>
                                <button onclick="deleteEvent(${evt.id})" class="px-2.5 py-1.5 rounded-xl border border-slate-200 hover:bg-rose-50 text-rose-600 font-extrabold text-xs transition cursor-pointer flex items-center gap-1">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </div>
                        </div>
                    `;
                });
                eventsContent += `</div>`;
            }

            row.innerHTML = `
                <div class="w-14 pt-3.5 text-xs font-extrabold text-slate-500 text-right shrink-0">
                    ${hr}
                </div>
                ${eventsContent}
            `;

            timeline.appendChild(row);
        });
    }

    function setFormTime(hr) {
        document.getElementById('inputHora').value = hr;
        document.getElementById('inputNombre').focus();
    }

    // ----------------------------------------------------
    // FUNCIONES AUXILIARES DE SELECCIÓN Y FORMULARIO
    // ----------------------------------------------------
    function selectDay(dateStr) {
        if (!dateStr) return;
        const parts = dateStr.split('-');
        selectedDate = new Date(parts[0], parseInt(parts[1]) - 1, parts[2]);
        currentDate = new Date(selectedDate);
        renderCurrentView();
        updateSelectedDateUI();
    }

    function updateSelectedDateUI() {
        const year = selectedDate.getFullYear();
        const month = selectedDate.getMonth();
        const day = selectedDate.getDate();
        const dayOfWeekIndex = selectedDate.getDay();

        const formattedTitle = `${dayNamesNames[dayOfWeekIndex]}, ${day} de ${monthNames[month].toLowerCase()} de ${year}`;
        document.getElementById('selectedDateTitle').innerText = formattedTitle;
        document.getElementById('selectedDateSubtitleEvents').innerText = `Mostrando registros para: ${formattedTitle}`;

        const formattedInputDate = `${String(day).padStart(2, '0')}/${String(month + 1).padStart(2, '0')}/${year}`;
        document.getElementById('inputFecha').value = formattedInputDate;

        renderDayEventsGrid();
    }

    // ----------------------------------------------------
    // 4. RENDER MULTICOLUMNA PROFESIONAL DE ACTIVIDADES (FILTRADO POR ESTADO)
    // ----------------------------------------------------
    function renderDayEventsGrid() {
        const dateStr = formatDateForInput(selectedDate);
        let dayEvents = events.filter(e => e.date === dateStr);

        if (currentStatusFilter !== 'all') {
            dayEvents = dayEvents.filter(e => e.estado === currentStatusFilter);
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
                    <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto leading-relaxed">No hay eventos agendados para este día o estado. Puedes programar uno usando el formulario superior.</p>
                </div>
            `;
            return;
        }

        grid.innerHTML = dayEvents.map(evt => {
            let topBorder = 'border-t-4 border-t-orange-500';
            let statusStyle = 'bg-orange-100 text-orange-700 border-orange-200';
            let dotColor = 'bg-orange-500';

            if (evt.estado === 'Completada') {
                statusStyle = 'bg-emerald-100 text-emerald-700 border-emerald-200';
                dotColor = 'bg-emerald-500';
            } else if (evt.estado === 'En Ejecución') {
                statusStyle = 'bg-blue-100 text-blue-700 border-blue-200';
                dotColor = 'bg-blue-500';
            } else if (evt.estado === 'Cancelada') {
                statusStyle = 'bg-rose-100 text-rose-700 border-rose-200';
                dotColor = 'bg-rose-500';
            }

            return `
                <div class="bg-white rounded-2xl border border-slate-200/80 ${topBorder} p-4 shadow-xs hover:shadow-sm transition flex flex-col justify-between space-y-3">
                    
                    <div class="space-y-2">
                        <!-- Top Badges y Cambiador de Estado Interactivo -->
                        <div class="flex items-center justify-between gap-2">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-orange-50 text-orange-700 border border-orange-200">
                                <i class="fa-solid fa-calendar-check text-[9px]"></i> Actividad SST
                            </span>
                            
                            <!-- Selector Rápido de Estado -->
                            <div class="relative">
                                <select onchange="changeEventStatus(${evt.id}, this.value)" class="text-[10px] font-extrabold px-2.5 py-1 rounded-full border cursor-pointer ${statusStyle} focus:outline-none transition appearance-none pr-5">
                                    <option value="Programada" ${evt.estado === 'Programada' ? 'selected' : ''}>● Programada</option>
                                    <option value="En Ejecución" ${evt.estado === 'En Ejecución' ? 'selected' : ''}>● En Ejecución</option>
                                    <option value="Completada" ${evt.estado === 'Completada' ? 'selected' : ''}>● Completada</option>
                                    <option value="Cancelada" ${evt.estado === 'Cancelada' ? 'selected' : ''}>● Cancelada</option>
                                </select>
                                <span class="absolute inset-y-0 right-1.5 flex items-center pointer-events-none text-[8px] opacity-70">
                                    <i class="fa-solid fa-chevron-down"></i>
                                </span>
                            </div>
                        </div>

                        <!-- Título Actividad -->
                        <h4 class="text-sm font-extrabold text-slate-900 leading-snug">
                            ${evt.name}
                        </h4>

                        <!-- Metadata: Hora y Responsable -->
                        <div class="text-xs text-slate-600 space-y-1 pt-1 border-t border-slate-100">
                            <div class="flex items-center gap-1.5 font-semibold text-slate-700">
                                <i class="fa-regular fa-clock text-orange-500"></i>
                                <span>Hora: ${evt.time} hs</span>
                            </div>
                            <div class="flex items-center gap-1.5 font-medium text-slate-500">
                                <i class="fa-solid fa-user-gear text-slate-400"></i>
                                <span class="truncate">Resp: ${evt.responsable}</span>
                            </div>
                        </div>

                        ${evt.descripcion ? `
                            <p class="text-[11px] text-slate-500 line-clamp-2 bg-slate-50 p-2 rounded-xl border border-slate-100 italic mt-1">
                                "${evt.descripcion}"
                            </p>
                        ` : ''}
                    </div>

                    <!-- Botones de Acción: Editar y Eliminar -->
                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                        <button onclick="openEditModal(${evt.id})" class="text-[11px] font-extrabold text-blue-600 hover:text-blue-700 transition cursor-pointer flex items-center gap-1">
                            <i class="fa-solid fa-pen-to-square"></i>
                            <span>Editar</span>
                        </button>
                        <button onclick="deleteEvent(${evt.id})" class="text-[11px] font-extrabold text-rose-600 hover:text-rose-700 transition cursor-pointer flex items-center gap-1">
                            <i class="fa-regular fa-trash-can"></i>
                            <span>Eliminar</span>
                        </button>
                    </div>

                </div>
            `;
        }).join('');
    }

    function filterEventsStatus(st) {
        currentStatusFilter = st;
        
        const statuses = [
            { id: 'statusFilterAll', key: 'all' },
            { id: 'statusFilterProgramada', key: 'Programada' },
            { id: 'statusFilterEnEjecucion', key: 'En Ejecución' },
            { id: 'statusFilterCompletada', key: 'Completada' },
            { id: 'statusFilterCancelada', key: 'Cancelada' }
        ];

        statuses.forEach(item => {
            const btn = document.getElementById(item.id);
            if (btn) {
                if (item.key === st) {
                    btn.className = 'px-3.5 py-1.5 rounded-xl text-xs font-extrabold bg-slate-900 text-white shadow-xs transition cursor-pointer';
                } else {
                    btn.className = 'px-3.5 py-1.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition cursor-pointer';
                }
            }
        });

        renderDayEventsGrid();
    }

    function changeEventStatus(id, newStatus) {
        const evt = events.find(e => e.id === id);
        if (evt) {
            evt.estado = newStatus;
            renderCurrentView();
            updateSelectedDateUI();
            showToastNotification(`Estado de "${evt.name}" cambiado a ${evt.estado}.`);
        }
    }

    function openEditModal(id) {
        const evt = events.find(e => e.id === id);
        if (!evt) return;
        document.getElementById('editEventId').value = evt.id;
        document.getElementById('editNombre').value = evt.name;
        document.getElementById('editHora').value = evt.time;
        document.getElementById('editEstado').value = evt.estado;
        document.getElementById('editResponsable').value = evt.responsable;
        document.getElementById('editDescripcion').value = evt.descripcion || '';
        document.getElementById('modalEditEvent').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('modalEditEvent').classList.add('hidden');
    }

    function handleUpdateActivity(e) {
        e.preventDefault();
        const id = parseInt(document.getElementById('editEventId').value);
        const evt = events.find(e => e.id === id);
        if (evt) {
            evt.name = document.getElementById('editNombre').value;
            evt.time = document.getElementById('editHora').value;
            evt.estado = document.getElementById('editEstado').value;
            evt.responsable = document.getElementById('editResponsable').value;
            evt.descripcion = document.getElementById('editDescripcion').value;

            renderCurrentView();
            updateSelectedDateUI();
            closeEditModal();
            showToastNotification(`Actividad "${evt.name}" actualizada con éxito.`);
        }
    }

    function handleSaveActivity(e) {
        e.preventDefault();
        const dateStr = formatDateForInput(selectedDate);

        const newEvt = {
            id: Date.now(),
            date: dateStr,
            name: document.getElementById('inputNombre').value,
            time: document.getElementById('inputHora').value,
            responsable: document.getElementById('inputResponsable').value,
            estado: document.getElementById('selectEstado').value,
            descripcion: document.getElementById('inputDescripcion').value
        };

        events.push(newEvt);

        // Cambiar automáticamente el filtro a 'Todas' para mostrar el nuevo registro
        filterEventsStatus('all');
        renderCurrentView();
        updateSelectedDateUI();
        clearForm();

        showToastNotification(`Actividad "${newEvt.name}" registrada con éxito.`);
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

    function clearForm() {
        document.getElementById('inputNombre').value = '';
        document.getElementById('inputHora').value = '09:00';
        document.getElementById('inputResponsable').value = '';
        document.getElementById('inputDescripcion').value = '';
        document.getElementById('selectEstado').selectedIndex = 0;
    }

    function deleteEvent(id) {
        const evt = events.find(e => e.id === id);
        events = events.filter(e => e.id !== id);
        renderCurrentView();
        updateSelectedDateUI();
        if (evt) {
            showToastNotification(`Actividad "${evt.name}" eliminada.`);
        }
    }

    function updateEventsCount() {
        const count = events.length;
        document.getElementById('activeEventsCount').innerText = `${count} Activida${count !== 1 ? 'des' : 'd'}`;
    }

    function formatDateForInput(d) {
        const y = d.getFullYear();
        const m = String(d.getMonth() + 1).padStart(2, '0');
        const day = String(d.getDate()).padStart(2, '0');
        return `${y}-${m}-${day}`;
    }

    function isSameDate(d1, d2) {
        return d1.getFullYear() === d2.getFullYear() &&
               d1.getMonth() === d2.getMonth() &&
               d1.getDate() === d2.getDate();
    }
</script>
@endsection

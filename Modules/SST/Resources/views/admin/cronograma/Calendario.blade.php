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
                <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">Configuración y administración del calendario de actividades, capacitaciones, pausas activas y jornadas de SST.</p>
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

            <!-- Indicador informativo de regla de selección -->
            <div class="hidden lg:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200/80 text-[11px] font-semibold text-slate-500" title="Fechas anteriores a 3 días están en modo solo lectura">
                <i class="fa-solid fa-clock-rotate-left text-orange-500 text-xs"></i>
                <span>Edición permitida: <strong>últimos 3 días</strong> en adelante</span>
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
                    <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 font-extrabold text-base flex items-center justify-center border border-orange-200/60 shrink-0">
                        <i class="fa-regular fa-clock"></i>
                    </div>
                    <div>
                        <h4 id="dayViewSubTitle" class="text-sm font-extrabold text-slate-900 tracking-tight">Cronograma Horario del Día</h4>
                        <p class="text-xs text-slate-500 font-medium mt-0.5">Visualización del cronograma horario del día seleccionado.</p>
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
                <button onclick="filterEventsStatus('Realizada')" id="statusFilterRealizada" class="px-3.5 py-1.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition cursor-pointer">Realizadas</button>
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

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Tipo de Actividad *</label>
                    <select id="editTipoActividad" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-800 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 bg-white cursor-pointer">
                        @foreach($tiposActividades as $tipo)
                            <option value="{{ $tipo->nombre }}">{{ $tipo->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Lugar / Ambiente</label>
                    <select id="editLugar" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-800 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 bg-white cursor-pointer">
                        <option value="">-- General / Ninguno --</option>
                        @foreach($lugares as $lugar)
                            <option value="{{ $lugar->id_lugar }}">{{ $lugar->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

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
                        <option value="Realizada">Realizada</option>
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

<!-- MODAL RÁPIDO PARA GESTIONAR Y CREAR TIPOS DE ACTIVIDAD -->
<div id="modalQuickTipoActividad" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-2xl max-w-md w-full p-6 space-y-4 animate-in fade-in zoom-in duration-200">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center font-bold">
                    <i class="fa-solid fa-layer-group text-sm"></i>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900">Tipos de Actividades SST</h3>
                    <p class="text-[11px] text-slate-500 font-medium">Crea nuevos tipos o elimina los que ya no utilices</p>
                </div>
            </div>
            <button onclick="closeQuickTipoActividadModal()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition cursor-pointer">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>

        <!-- 1. Formulario de Creación Rápida -->
        <form onsubmit="handleSaveQuickTipoActividad(event)" class="space-y-3 bg-slate-50/70 p-3.5 rounded-2xl border border-slate-200/70">
            <div class="text-xs font-extrabold text-slate-800 flex items-center gap-1.5">
                <i class="fa-solid fa-plus text-orange-500 text-[10px]"></i>
                <span>Crear Nuevo Tipo</span>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Nombre del Tipo *</label>
                <input type="text" id="quickTipoNombre" required placeholder="Ej: Auditoría Externa, Taller Ergonomía" 
                    class="w-full px-3 py-1.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-800 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 bg-white">
            </div>

            <div class="grid grid-cols-2 gap-2.5">
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Color</label>
                    <select id="quickTipoColor" class="w-full px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-800 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 bg-white cursor-pointer">
                        <option value="blue">🔵 Azul</option>
                        <option value="emerald">🟢 Verde</option>
                        <option value="rose">🔴 Rojo</option>
                        <option value="purple">🟣 Púrpura</option>
                        <option value="amber">🟡 Ámbar</option>
                        <option value="indigo">🟣 Índigo</option>
                        <option value="teal">🟢 Teal</option>
                        <option value="slate">⚪ Gris</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Icono</label>
                    <select id="quickTipoIcono" class="w-full px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-800 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 bg-white cursor-pointer">
                        <option value="fa-clipboard-list">📋 Lista</option>
                        <option value="fa-graduation-cap">🎓 Capacitación</option>
                        <option value="fa-shield-halved">🛡️ Seguridad</option>
                        <option value="fa-heart-pulse">❤️ Salud</option>
                        <option value="fa-triangle-exclamation">🚨 Alerta</option>
                        <option value="fa-person-running">🏃 Dinámica</option>
                        <option value="fa-bullhorn">📢 Charla</option>
                        <option value="fa-users">👥 Reunión</option>
                        <option value="fa-microscope">🔬 Laboratorio</option>
                        <option value="fa-award">🏆 Reconocimiento</option>
                    </select>
                </div>
            </div>

            <div class="pt-1 flex items-center justify-end">
                <button type="submit" class="px-3.5 py-1.5 rounded-xl bg-orange-600 hover:bg-orange-700 text-white text-xs font-bold shadow-xs transition cursor-pointer flex items-center gap-1.5">
                    <i class="fa-solid fa-check text-xs"></i>
                    <span>Guardar Tipo</span>
                </button>
            </div>
        </form>

        <!-- 2. Lista de Tipos Registrados con opción de eliminar -->
        <div class="space-y-2 pt-1 border-t border-slate-100">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-600">Tipos Registrados</span>
                <span id="quickTiposCountBadge" class="text-[10px] font-bold text-slate-400">0 tipos</span>
            </div>
            
            <div id="quickTiposListContainer" class="max-h-44 overflow-y-auto space-y-1.5 pr-1">
                <!-- Se renderizan dinámicamente con botón eliminar -->
            </div>
        </div>

        <div class="pt-2 flex items-center justify-end border-t border-slate-100">
            <button type="button" onclick="closeQuickTipoActividadModal()" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-100 transition cursor-pointer">
                Cerrar
            </button>
        </div>
    </div>
</div>

<!-- NOTIFICACIÓN TOAST FLOTANTE (ADAPTABLE: ÉXITO / ADVERTENCIA / ERROR) -->
<div id="toastSuccessNotification" class="fixed top-5 right-5 z-50 transform translate-x-full opacity-0 transition-all duration-300 pointer-events-none">
    <div id="toastContainerBox" class="bg-white rounded-2xl p-4 shadow-xl border border-slate-100 border-l-4 border-l-emerald-500 flex items-center gap-3.5 max-w-md pointer-events-auto">
        <div id="toastIconBox" class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold shrink-0 border border-emerald-100">
            <i id="toastIcon" class="fa-solid fa-circle-check text-lg"></i>
        </div>
        <div class="flex-1 pr-2">
            <h4 id="toastTitleText" class="text-xs font-extrabold text-slate-900">¡Operación Exitosa!</h4>
            <p id="toastMessageText" class="text-xs text-slate-500 font-medium mt-0.5 leading-snug">Operación realizada correctamente.</p>
        </div>
        <button onclick="hideToastNotification()" class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-400 hover:text-slate-600 flex items-center justify-center transition cursor-pointer">
            <i class="fa-solid fa-xmark text-xs"></i>
        </button>
    </div>
</div>

<!-- SCRIPT DEL CALENDARIO E INTERACCIONES CONECTADAS A LA BASE DE DATOS -->
<script>
    const csrfToken = '{{ csrf_token() }}';
    const baseUrl = '{{ url("Sst/admin/cronograma/calendario") }}';
    const storeTipoUrl = '{{ url("Sst/admin/cronograma/calendario/tipo-actividad") }}';
    const deleteTipoUrl = '{{ url("Sst/admin/cronograma/calendario/tipo-actividad") }}';

    // Estado del Calendario (Fecha actual)
    let currentDate = new Date();
    let selectedDate = new Date();
    let currentView = 'month';
    let currentCategoryFilter = 'all';
    let currentStatusFilter = 'all';

    // Tipos de actividades en memoria
    let tiposActividadesList = @json($tiposActividades);

    // Lugares disponibles mapeados
    const lugaresMap = {
        @foreach($lugares as $lugar)
            {{ $lugar->id_lugar }}: {!! json_encode($lugar->nombre) !!},
        @endforeach
    };

    // Tipos de actividades dinámicos mapeados con sus estilos
    const tiposActividadesMap = {
        @foreach($tiposActividades as $tipo)
            {!! json_encode($tipo->nombre) !!}: {
                icono: {!! json_encode($tipo->icono ?? 'fa-calendar-check') !!},
                color: {!! json_encode($tipo->color ?? 'blue') !!}
            },
        @endforeach
    };

    // Datos cargados directamente desde la base de datos MySQL (actividades_sst)
    let events = [
        @foreach($actividades as $act)
        {
            id: {{ $act->id_actividad }},
            source: '{{ $act->source ?? "actividad_sst" }}',
            date: '{{ $act->fecha }}',
            name: {!! json_encode($act->nombre) !!},
            time: '{{ substr($act->hora, 0, 5) }}',
            tipo_actividad: {!! json_encode($act->tipo_actividad ?? 'Pausa Activa') !!},
            id_lugar: {{ !empty($act->id_lugar) ? $act->id_lugar : 'null' }},
            lugar_nombre: {!! json_encode($act->lugar_nombre ?? (is_object($act->lugar ?? null) ? $act->lugar->nombre : ($act->lugar ?? null))) !!},
            responsable: {!! json_encode($act->responsable) !!},
            estado: '{{ $act->estado }}',
            descripcion: {!! json_encode($act->descripcion ?? '') !!}
        },
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

    // Regla de negocio: Edición permitida solo en los últimos 3 días en adelante.
    // Fechas con más de 3 días de antigüedad entran en modo "Solo lectura (Histórico)"
    function getMinSelectableDate() {
        const d = new Date();
        d.setHours(0, 0, 0, 0);
        d.setDate(d.getDate() - 3);
        return d;
    }

    function isDateReadOnly(d) {
        if (!d) return false;
        const check = new Date(d);
        check.setHours(0, 0, 0, 0);
        return check < getMinSelectableDate();
    }

    function isEventReadOnly(evt) {
        if (!evt || !evt.date) return false;
        const parts = evt.date.split('-');
        const d = new Date(parts[0], parseInt(parts[1]) - 1, parts[2]);
        return isDateReadOnly(d);
    }

    function isDateDisabled(d) {
        // Todas las fechas son navegables y visibles
        return false;
    }

    function showDisabledDateNotice() {
        showToastNotification('Esta fecha tiene más de 3 días de antigüedad y está en modo solo lectura (no se puede editar).', 'warning', 'Fecha Histórica');
    }

    // Paleta de colores e iconos específicos y profesionales para cada tipo de actividad SST
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
                icon: 'fa-person-running',
                pillColor: 'sky'
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
                icon: 'fa-bullhorn',
                pillColor: 'blue'
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
                icon: 'fa-hand-holding-heart',
                pillColor: 'emerald'
            };
        }
        if (textToMatch.includes('simulacro')) {
            return {
                name: 'Simulacro',
                badgeClass: 'bg-rose-600 text-white shadow-2xs border-rose-700/30',
                badgeLight: 'bg-rose-50 text-rose-800 border-rose-200',
                dot: 'bg-rose-500 ring-rose-200',
                cellBg: 'bg-rose-50/40 border-rose-200/90 shadow-2xs hover:bg-rose-100/40',
                textColor: 'text-rose-700',
                topBorder: 'border-t-4 border-t-rose-500',
                icon: 'fa-triangle-exclamation',
                pillColor: 'rose'
            };
        }
        if (textToMatch.includes('inspecc')) {
            return {
                name: 'Inspección',
                badgeClass: 'bg-purple-600 text-white shadow-2xs border-purple-700/30',
                badgeLight: 'bg-purple-50 text-purple-800 border-purple-200',
                dot: 'bg-purple-500 ring-purple-200',
                cellBg: 'bg-purple-50/40 border-purple-200/90 shadow-2xs hover:bg-purple-100/40',
                textColor: 'text-purple-700',
                topBorder: 'border-t-4 border-t-purple-500',
                icon: 'fa-clipboard-check',
                pillColor: 'purple'
            };
        }
        if (textToMatch.includes('salud')) {
            return {
                name: 'Jornada de Salud',
                badgeClass: 'bg-emerald-600 text-white shadow-2xs border-emerald-700/30',
                badgeLight: 'bg-emerald-50 text-emerald-800 border-emerald-200',
                dot: 'bg-emerald-500 ring-emerald-200',
                cellBg: 'bg-emerald-50/40 border-emerald-200/90 shadow-2xs hover:bg-emerald-100/40',
                textColor: 'text-emerald-700',
                topBorder: 'border-t-4 border-t-emerald-500',
                icon: 'fa-heart-pulse',
                pillColor: 'emerald'
            };
        }

        // Tipo estándar o Capacitación
        return {
            name: tipo || 'Capacitación',
            badgeClass: 'bg-blue-600 text-white shadow-2xs border-blue-700/30',
            badgeLight: 'bg-blue-50 text-blue-800 border-blue-200',
            dot: 'bg-blue-500 ring-blue-200',
            cellBg: 'bg-blue-50/40 border-blue-200/90 shadow-2xs hover:bg-blue-100/40',
            textColor: 'text-blue-700',
            topBorder: 'border-t-4 border-t-blue-500',
            icon: 'fa-graduation-cap',
            pillColor: 'blue'
        };
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
            picker.removeAttribute('min');
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

        const parts = fullDateStr.split('-');
        const cellDate = new Date(parts[0], parseInt(parts[1]) - 1, parts[2]);
        const isReadOnly = isDateReadOnly(cellDate);
        const hasEvents = dayEvents.length > 0;
        const primaryTheme = hasEvents ? getActivityTheme(dayEvents[0].tipo_actividad, dayEvents[0].name) : null;

        let bgClass = 'bg-white';
        if (isSelected) {
            bgClass = 'calendar-day-selected cursor-pointer transition';
        } else if (isToday) {
            bgClass = 'calendar-day-today cursor-pointer transition';
        } else if (hasEvents) {
            bgClass = `${primaryTheme.cellBg} cursor-pointer transition`;
        } else if (isReadOnly) {
            bgClass = 'bg-slate-50/50 hover:bg-slate-100/70 cursor-pointer transition';
        } else {
            bgClass = 'bg-white hover:bg-slate-50/80 cursor-pointer transition';
        }

        cell.className = `calendar-grid-day border border-slate-200/70 p-1.5 rounded-xl flex flex-col justify-between ${bgClass}`;
        cell.setAttribute('onclick', `selectDay('${fullDateStr}')`);
        if (isReadOnly) {
            cell.setAttribute('title', 'Fecha histórica (Solo lectura)');
        }

        let numberBadgeHTML = '';
        if (isSelected) {
            numberBadgeHTML = `<span class="w-5 h-5 rounded-md bg-blue-600 text-white text-[11px] font-extrabold flex items-center justify-center shadow-xs">${dayNum}</span>`;
        } else if (isToday) {
            numberBadgeHTML = `<span class="w-5 h-5 rounded-md bg-orange-500 text-white text-[11px] font-extrabold flex items-center justify-center shadow-xs">${dayNum}</span>`;
        } else if (isReadOnly) {
            numberBadgeHTML = `<span class="text-xs font-bold text-slate-500 pl-1">${dayNum}</span>`;
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

            const isReadOnly = isDateReadOnly(dayDate);
            const isSelected = isSameDate(dayDate, selectedDate);
            const isToday = isSameDate(dayDate, new Date());
            const formattedDate = formatDateForInput(dayDate);
            const dayEvents = events.filter(e => e.date === formattedDate);

            const headerCol = document.createElement('div');
            headerCol.className = `p-2 rounded-xl text-center border transition cursor-pointer ${
                isSelected ? 'bg-orange-50 border-orange-300 text-orange-700 font-extrabold' : 'border-transparent text-slate-600 hover:bg-slate-50'
            }`;
            headerCol.setAttribute('onclick', `selectDay('${formattedDate}')`);
            if (isReadOnly) {
                headerCol.setAttribute('title', 'Fecha histórica (Solo lectura)');
            }
            headerCol.innerHTML = `
                <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400">${shortDayNames[i]}</div>
                <div class="text-base font-extrabold mt-0.5 ${isToday ? 'w-7 h-7 rounded-full bg-orange-500 text-white flex items-center justify-center mx-auto shadow-xs' : ''}">
                    ${dayDate.getDate()}
                </div>
            `;
            headersGrid.appendChild(headerCol);

            const dayCol = document.createElement('div');
            dayCol.className = `min-h-[380px] p-2 rounded-2xl border border-slate-100 flex flex-col gap-2 ${isSelected ? 'bg-orange-50/20 border-orange-200/60' : (isReadOnly ? 'bg-slate-50/30' : 'bg-slate-50/40')}`;

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
                    <div onclick="selectDay('${formattedDate}')" class="p-2.5 rounded-xl bg-white border border-slate-200/80 shadow-2xs hover:shadow-xs transition cursor-pointer space-y-1.5 border-l-4 ${evtTheme.topBorder.replace('border-t-4', '')}">
                        <div class="flex items-center justify-between text-[10px] font-extrabold ${evtTheme.textColor}">
                            <span><i class="fa-regular fa-clock"></i> ${evt.time}</span>
                            <span class="w-2 h-2 rounded-full ${evt.estado === 'Completada' || evt.estado === 'Realizada' ? 'bg-emerald-500' : evtTheme.dot}"></span>
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
                        <button onclick="openEditModal(${evt.id})" class="px-2.5 py-1 text-xs font-bold text-slate-700 bg-white rounded-lg border border-slate-200 hover:bg-slate-50 transition cursor-pointer">
                            Ver
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

    function openCreateAtTime(timeStr) {
        if (document.getElementById('inputHora')) document.getElementById('inputHora').value = timeStr;
        if (document.getElementById('inputNombre')) document.getElementById('inputNombre').focus();
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
        const isReadOnly = isDateReadOnly(selectedDate);

        const selectedDateTitle = document.getElementById('selectedDateTitle');
        if (selectedDateTitle) {
            selectedDateTitle.innerHTML = `${dayName}, ${dayNum} de ${monthName} de ${year} ${isReadOnly ? '<span class="ml-2 text-xs font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-full border border-slate-200 inline-flex items-center gap-1"><i class="fa-solid fa-lock text-[10px]"></i>Solo lectura</span>' : ''}`;
        }
        const selectedDateSubtitleEvents = document.getElementById('selectedDateSubtitleEvents');
        if (selectedDateSubtitleEvents) {
            if (isReadOnly) {
                selectedDateSubtitleEvents.innerHTML = `Mostrando registros históricos de: <strong>${dayName}, ${dayNum} de ${monthName} de ${year}</strong> <span class="text-amber-600 font-semibold">(Modo histórico: solo lectura, no editable)</span>`;
            } else {
                selectedDateSubtitleEvents.innerText = `Mostrando registros para: ${dayName}, ${dayNum} de ${monthName} de ${year}`;
            }
        }
        const inputFecha = document.getElementById('inputFecha');
        if (inputFecha) {
            inputFecha.value = formatDateForInput(selectedDate);
        }

        renderDayEventsGrid();
    }

    function getActivityTypeBadge(tipo, name = '') {
        const theme = getActivityTheme(tipo, name);
        return {
            bg: theme.badgeLight,
            badgeClass: theme.badgeClass,
            icon: theme.icon,
            textColor: theme.textColor,
            topBorder: theme.topBorder,
            dot: theme.dot,
            name: theme.name
        };
    }

    function renderDayEventsGrid() {
        const formattedSelected = formatDateForInput(selectedDate);
        const isReadOnly = isDateReadOnly(selectedDate);
        let dayEvents = events.filter(e => e.date === formattedSelected);

        if (currentStatusFilter !== 'all') {
            dayEvents = dayEvents.filter(e => {
                if (currentStatusFilter === 'Realizada' || currentStatusFilter === 'Completada') {
                    return e.estado === 'Realizada' || e.estado === 'Completada';
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
            const typeInfo = getActivityTypeBadge(evt.tipo_actividad, evt.name);

            let statusStyle = 'bg-orange-100 text-orange-700 border-orange-200';
            if (evt.estado === 'Completada' || evt.estado === 'Realizada') {
                statusStyle = 'bg-emerald-100 text-emerald-700 border-emerald-200';
            } else if (evt.estado === 'En Ejecución') {
                statusStyle = 'bg-blue-100 text-blue-700 border-blue-200';
            } else if (evt.estado === 'Cancelada') {
                statusStyle = 'bg-rose-100 text-rose-700 border-rose-200';
            }

            return `
                <div class="bg-white rounded-2xl border border-slate-200/80 ${typeInfo.topBorder} p-4 shadow-xs hover:shadow-sm transition flex flex-col justify-between space-y-3">
                    <div class="space-y-2">
                        <div class="flex items-center justify-between gap-2">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase ${typeInfo.bg} border">
                                <i class="fa-solid ${typeInfo.icon} text-[9px]"></i>
                                <span>${evt.tipo_actividad || typeInfo.name}</span>
                            </span>
                            
                            <div class="relative">
                                ${isReadOnly ? `
                                    <div class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold border ${statusStyle} opacity-90 select-none cursor-default" title="Fecha histórica: No editable">
                                        <span>● ${evt.estado}</span>
                                        <i class="fa-solid fa-lock text-[8px] text-slate-400 ml-1"></i>
                                    </div>
                                ` : `
                                    <select onchange="changeEventStatus(${evt.id}, this.value)" class="text-[10px] font-extrabold px-2.5 py-1 rounded-full border cursor-pointer ${statusStyle} focus:outline-none transition appearance-none pr-5">
                                        <option value="Programada" ${evt.estado === 'Programada' ? 'selected' : ''}>● Programada</option>
                                        <option value="Realizada" ${(evt.estado === 'Realizada' || evt.estado === 'Completada') ? 'selected' : ''}>● Realizada</option>
                                        <option value="Cancelada" ${evt.estado === 'Cancelada' ? 'selected' : ''}>● Cancelada</option>
                                    </select>
                                    <span class="absolute inset-y-0 right-1.5 flex items-center pointer-events-none text-[8px] opacity-70">
                                        <i class="fa-solid fa-chevron-down"></i>
                                    </span>
                                `}
                            </div>
                        </div>

                        <h4 class="text-sm font-extrabold text-slate-900 leading-snug">
                            ${evt.name}
                        </h4>

                        <div class="text-xs text-slate-600 space-y-1.5 pt-1 border-t border-slate-100">
                            <div class="flex items-center gap-1.5 font-semibold text-slate-700">
                                <i class="fa-regular fa-clock text-orange-500"></i>
                                <span>Hora: ${evt.time} hs</span>
                            </div>
                            <div class="flex items-center gap-1.5 font-medium text-slate-600">
                                <i class="fa-solid fa-user-gear text-slate-400 text-xs"></i>
                                <span class="truncate">Resp: <strong>${evt.responsable}</strong></span>
                            </div>
                            ${evt.lugar_nombre ? `
                                <div class="flex items-center gap-1.5 text-[11px] font-medium text-slate-500">
                                    <i class="fa-solid fa-location-dot text-emerald-500 text-xs"></i>
                                    <span class="truncate">Lugar / Ambiente: <span class="text-slate-700 font-semibold">${evt.lugar_nombre}</span></span>
                                </div>
                            ` : ''}
                        </div>

                        ${evt.descripcion ? `
                            <p class="text-[11px] text-slate-500 line-clamp-2 bg-slate-50 p-2 rounded-xl border border-slate-100 italic mt-1">
                                "${evt.descripcion}"
                            </p>
                        ` : ''}
                    </div>

                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                        ${isReadOnly ? `
                            <div class="w-full flex items-center justify-center py-1">
                                <span class="text-[11px] font-bold text-slate-400 flex items-center gap-1.5">
                                    <i class="fa-solid fa-lock text-[10px] text-slate-400"></i>
                                    <span>Histórico (Solo lectura)</span>
                                </span>
                            </div>
                        ` : `
                            <button onclick="openEditModal(${evt.id})" class="text-[11px] font-extrabold text-blue-600 hover:text-blue-700 transition cursor-pointer flex items-center gap-1">
                                <i class="fa-solid fa-pen-to-square"></i>
                                <span>Editar</span>
                            </button>
                            <button onclick="deleteEvent(${evt.id})" class="text-[11px] font-extrabold text-rose-600 hover:text-rose-700 transition cursor-pointer flex items-center gap-1">
                                <i class="fa-regular fa-trash-can"></i>
                                <span>Eliminar</span>
                            </button>
                        `}
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
            { id: 'statusFilterRealizada', key: 'Realizada' },
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

    // ----------------------------------------------------
    // CONEXIONES CON LA BASE DE DATOS (CRUD VIA AJAX / FETCH)
    // ----------------------------------------------------

    async function handleSaveQuickTipoActividad(e) {
        e.preventDefault();
        const nombreInput = document.getElementById('quickTipoNombre').value.trim();
        const colorInput = document.getElementById('quickTipoColor').value;
        const iconoInput = document.getElementById('quickTipoIcono').value;

        if (!nombreInput) return;

        try {
            const response = await fetch(storeTipoUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    nombre: nombreInput,
                    color: colorInput,
                    icono: iconoInput
                })
            });

            const res = await response.json();
            if (res.success) {
                tiposActividadesList.push(res.data);
                tiposActividadesMap[res.data.nombre] = {
                    icono: res.data.icono,
                    color: res.data.color
                };

                // Agregar a los selects
                const selectMain = document.getElementById('selectTipoActividad');
                const selectEdit = document.getElementById('editTipoActividad');

                if (selectMain) {
                    const opt1 = new Option(res.data.nombre, res.data.nombre, true, true);
                    selectMain.add(opt1);
                    selectMain.value = res.data.nombre;
                }
                if (selectEdit) {
                    const opt2 = new Option(res.data.nombre, res.data.nombre);
                    selectEdit.add(opt2);
                }

                document.getElementById('quickTipoNombre').value = '';
                renderQuickTiposList();
                showToastNotification(`Tipo "${res.data.nombre}" creado exitosamente.`);
            } else {
                alert(res.message || 'Error al guardar el tipo de actividad.');
            }
        } catch (error) {
            console.error(error);
            alert('Error al conectar con la base de datos.');
        }
    }

    async function deleteQuickTipoActividad(id, nombre) {
        if (!confirm(`¿Estás seguro de eliminar el tipo de actividad "${nombre}"?`)) return;

        try {
            const response = await fetch(`${deleteTipoUrl}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });

            const res = await response.json();
            if (res.success) {
                // Eliminar de memoria
                tiposActividadesList = tiposActividadesList.filter(t => t.id_tipo_actividad !== id);
                delete tiposActividadesMap[nombre];

                // Eliminar de selects
                const selectMain = document.getElementById('selectTipoActividad');
                const selectEdit = document.getElementById('editTipoActividad');

                if (selectMain) {
                    for (let i = 0; i < selectMain.options.length; i++) {
                        if (selectMain.options[i].value === nombre) {
                            selectMain.remove(i);
                            break;
                        }
                    }
                }
                if (selectEdit) {
                    for (let i = 0; i < selectEdit.options.length; i++) {
                        if (selectEdit.options[i].value === nombre) {
                            selectEdit.remove(i);
                            break;
                        }
                    }
                }

                renderQuickTiposList();
                showToastNotification(`Tipo "${nombre}" eliminado correctamente.`);
            } else {
                alert(res.message || 'Error al eliminar el tipo de actividad.');
            }
        } catch (error) {
            console.error(error);
            alert('Error al conectar con el servidor.');
        }
    }

    function renderQuickTiposList() {
        const container = document.getElementById('quickTiposListContainer');
        const countBadge = document.getElementById('quickTiposCountBadge');
        if (!container) return;

        countBadge.innerText = `${tiposActividadesList.length} tipos`;

        if (tiposActividadesList.length === 0) {
            container.innerHTML = `<div class="text-xs text-slate-400 italic text-center py-3">No hay tipos registrados</div>`;
            return;
        }

        container.innerHTML = tiposActividadesList.map(t => {
            const color = t.color || 'blue';
            return `
                <div class="flex items-center justify-between p-2 rounded-xl bg-slate-50 border border-slate-200/70 hover:bg-slate-100/60 transition">
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-${color}-100 text-${color}-700 flex items-center justify-center text-xs">
                            <i class="fa-solid ${t.icono || 'fa-tag'}"></i>
                        </span>
                        <span class="text-xs font-extrabold text-slate-800">${t.nombre}</span>
                    </div>
                    <button type="button" onclick="deleteQuickTipoActividad(${t.id_tipo_actividad}, '${t.nombre.replace(/'/g, "\\'")}')" 
                        class="w-7 h-7 rounded-lg bg-white hover:bg-rose-50 text-rose-500 hover:text-rose-700 border border-slate-200 flex items-center justify-center transition cursor-pointer shadow-2xs" 
                        title="Eliminar este tipo">
                        <i class="fa-regular fa-trash-can text-xs"></i>
                    </button>
                </div>
            `;
        }).join('');
    }

    function openQuickTipoActividadModal() {
        document.getElementById('quickTipoNombre').value = '';
        renderQuickTiposList();
        document.getElementById('modalQuickTipoActividad').classList.remove('hidden');
        document.getElementById('quickTipoNombre').focus();
    }

    function closeQuickTipoActividadModal() {
        document.getElementById('modalQuickTipoActividad').classList.add('hidden');
    }

    async function handleSaveActivity(e) {
        e.preventDefault();
        if (isDateReadOnly(selectedDate)) {
            showToastNotification('No se pueden registrar actividades en fechas con más de 3 días de antigüedad.', 'warning', 'Acción no permitida');
            return;
        }
        const dateStr = formatDateForInput(selectedDate);
        const lugarVal = document.getElementById('selectLugar').value;

        const payload = {
            nombre: document.getElementById('inputNombre').value,
            fecha: dateStr,
            hora: document.getElementById('inputHora').value,
            tipo_actividad: document.getElementById('selectTipoActividad').value,
            id_lugar: lugarVal ? parseInt(lugarVal) : null,
            responsable: document.getElementById('inputResponsable').value,
            estado: document.getElementById('selectEstado').value,
            descripcion: document.getElementById('inputDescripcion').value
        };

        try {
            const response = await fetch(baseUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            const res = await response.json();
            if (res.success) {
                events.push({
                    id: res.data.id_actividad,
                    date: res.data.fecha,
                    name: res.data.nombre,
                    time: res.data.hora.substring(0, 5),
                    tipo_actividad: res.data.tipo_actividad,
                    id_lugar: res.data.id_lugar,
                    lugar_nombre: res.data.lugar ? res.data.lugar.nombre : (lugaresMap[res.data.id_lugar] || null),
                    responsable: res.data.responsable,
                    estado: res.data.estado,
                    descripcion: res.data.descripcion
                });

                filterEventsStatus('all');
                renderCurrentView();
                updateSelectedDateUI();
                clearForm();

                showToastNotification(`Actividad "${payload.nombre}" guardada en la base de datos.`);
            } else {
                alert('Error al guardar la actividad.');
            }
        } catch (error) {
            console.error(error);
            alert('Error al conectar con la base de datos.');
        }
    }

    async function handleUpdateActivity(e) {
        e.preventDefault();
        const id = parseInt(document.getElementById('editEventId').value);
        const evt = events.find(e => e.id === id);
        if (!evt) return;

        if (isEventReadOnly(evt)) {
            showToastNotification('No se pueden modificar actividades con más de 3 días de antigüedad.', 'warning', 'Acción no permitida');
            closeEditModal();
            return;
        }

        const selectLugar = document.getElementById('editLugar');
        const lugarVal = selectLugar.value;
        const lugarText = selectLugar.options[selectLugar.selectedIndex] ? (lugarVal ? selectLugar.options[selectLugar.selectedIndex].text : '') : '';

        const payload = {
            source: evt.source || 'actividad_sst',
            nombre: document.getElementById('editNombre').value,
            fecha: evt.date,
            hora: document.getElementById('editHora').value,
            tipo_actividad: document.getElementById('editTipoActividad').value,
            id_lugar: lugarVal ? parseInt(lugarVal) : null,
            lugar: lugarText,
            estado: document.getElementById('editEstado').value,
            responsable: document.getElementById('editResponsable').value,
            descripcion: document.getElementById('editDescripcion').value
        };

        try {
            const response = await fetch(`${baseUrl}/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            const res = await response.json();
            if (res.success) {
                evt.name = payload.nombre;
                evt.time = payload.hora;
                evt.tipo_actividad = payload.tipo_actividad;
                evt.id_lugar = payload.id_lugar;
                evt.lugar_nombre = res.data.lugar_nombre || (res.data.lugar ? res.data.lugar.nombre : (lugarText || null));
                evt.estado = res.data.estado || payload.estado;
                evt.responsable = payload.responsable;
                evt.descripcion = payload.descripcion;

                renderCurrentView();
                updateSelectedDateUI();
                closeEditModal();
                showToastNotification(`Actividad "${evt.name}" actualizada en la base de datos.`);
            } else {
                alert('Error al actualizar la actividad.');
            }
        } catch (error) {
            console.error(error);
            alert('Error al conectar con la base de datos.');
        }
    }

    async function changeEventStatus(id, newStatus) {
        const evt = events.find(e => e.id === id);
        if (!evt) return;

        if (isEventReadOnly(evt)) {
            showToastNotification('No se puede cambiar el estado de actividades con más de 3 días de antigüedad.', 'warning', 'Acción no permitida');
            renderDayEventsGrid();
            return;
        }

        const payload = {
            source: evt.source || 'actividad_sst',
            nombre: evt.name,
            fecha: evt.date,
            hora: evt.time,
            tipo_actividad: evt.tipo_actividad,
            id_lugar: evt.id_lugar,
            lugar: evt.lugar_nombre,
            responsable: evt.responsable,
            estado: newStatus,
            descripcion: evt.descripcion
        };

        try {
            const response = await fetch(`${baseUrl}/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            const res = await response.json();
            if (res.success) {
                evt.estado = res.data && res.data.estado ? res.data.estado : newStatus;
                renderCurrentView();
                updateSelectedDateUI();
                showToastNotification(`Estado cambiado a ${evt.estado}.`);
            }
        } catch (error) {
            console.error(error);
        }
    }

    async function deleteEvent(id) {
        const evt = events.find(e => e.id === id);
        if (!evt) return;

        if (isEventReadOnly(evt)) {
            showToastNotification('No se pueden eliminar actividades con más de 3 días de antigüedad.', 'warning', 'Acción no permitida');
            return;
        }

        if (!confirm('¿Estás seguro de eliminar esta actividad del cronograma?')) return;

        try {
            const response = await fetch(`${baseUrl}/${id}?source=${encodeURIComponent(evt.source || 'actividad_sst')}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });

            const res = await response.json();
            if (res.success) {
                events = events.filter(e => e.id !== id);
                renderCurrentView();
                updateSelectedDateUI();
                updateEventsCount();
                showToastNotification(`Actividad "${evt.name}" eliminada de la base de datos.`);
            }
        } catch (error) {
            console.error(error);
            alert('Error al eliminar la actividad.');
        }
    }

    function openEditModal(id) {
        const evt = events.find(e => e.id === id);
        if (!evt) return;

        if (isEventReadOnly(evt)) {
            showToastNotification('Esta actividad es de una fecha histórica y no se puede editar.', 'warning', 'Solo lectura');
            return;
        }
        document.getElementById('editEventId').value = evt.id;
        document.getElementById('editTipoActividad').value = evt.tipo_actividad || 'Pausa Activa';
        
        const selectLugar = document.getElementById('editLugar');
        let matched = false;
        if (evt.id_lugar) {
            selectLugar.value = evt.id_lugar;
            matched = (selectLugar.value == evt.id_lugar);
        }
        if (!matched && evt.lugar_nombre) {
            for (let opt of selectLugar.options) {
                if (opt.text.trim().toLowerCase() === evt.lugar_nombre.trim().toLowerCase()) {
                    selectLugar.value = opt.value;
                    matched = true;
                    break;
                }
            }
        }
        if (!matched) selectLugar.value = '';

        document.getElementById('editNombre').value = evt.name;
        document.getElementById('editHora').value = evt.time;
        document.getElementById('editEstado').value = (evt.estado === 'Completada') ? 'Realizada' : evt.estado;
        document.getElementById('editResponsable').value = evt.responsable;
        document.getElementById('editDescripcion').value = evt.descripcion || '';
        document.getElementById('modalEditEvent').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('modalEditEvent').classList.add('hidden');
    }

    let toastTimeout;
    function showToastNotification(message, type = 'success', title = null) {
        const toast = document.getElementById('toastSuccessNotification');
        const container = document.getElementById('toastContainerBox');
        const iconBox = document.getElementById('toastIconBox');
        const icon = document.getElementById('toastIcon');
        const titleEl = document.getElementById('toastTitleText');
        const msgEl = document.getElementById('toastMessageText');

        if (!toast || !msgEl) return;

        msgEl.innerText = message;

        if (type === 'warning') {
            if (titleEl) titleEl.innerText = title || 'Fecha no permitida';
            if (container) container.className = 'bg-white rounded-2xl p-4 shadow-xl border border-slate-100 border-l-4 border-l-amber-500 flex items-center gap-3.5 max-w-md pointer-events-auto';
            if (iconBox) iconBox.className = 'w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold shrink-0 border border-amber-200';
            if (icon) icon.className = 'fa-solid fa-triangle-exclamation text-lg';
        } else if (type === 'error') {
            if (titleEl) titleEl.innerText = title || 'Error';
            if (container) container.className = 'bg-white rounded-2xl p-4 shadow-xl border border-slate-100 border-l-4 border-l-rose-500 flex items-center gap-3.5 max-w-md pointer-events-auto';
            if (iconBox) iconBox.className = 'w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold shrink-0 border border-rose-200';
            if (icon) icon.className = 'fa-solid fa-circle-xmark text-lg';
        } else {
            if (titleEl) titleEl.innerText = title || '¡Operación Exitosa!';
            if (container) container.className = 'bg-white rounded-2xl p-4 shadow-xl border border-slate-100 border-l-4 border-l-emerald-500 flex items-center gap-3.5 max-w-md pointer-events-auto';
            if (iconBox) iconBox.className = 'w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold shrink-0 border border-emerald-100';
            if (icon) icon.className = 'fa-solid fa-circle-check text-lg';
        }

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
        if (document.getElementById('selectTipoActividad')) document.getElementById('selectTipoActividad').selectedIndex = 0;
        if (document.getElementById('selectLugar')) document.getElementById('selectLugar').selectedIndex = 0;
        if (document.getElementById('inputNombre')) document.getElementById('inputNombre').value = '';
        if (document.getElementById('inputHora')) document.getElementById('inputHora').value = '09:00';
        if (document.getElementById('inputResponsable')) document.getElementById('inputResponsable').value = '';
        if (document.getElementById('inputDescripcion')) document.getElementById('inputDescripcion').value = '';
        if (document.getElementById('selectEstado')) document.getElementById('selectEstado').selectedIndex = 0;
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

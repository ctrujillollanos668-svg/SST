@extends('sst::components.layouts.admin')

@section('title', 'Pausas Activas Programadas • SST')

@section('content')
<style>
    @keyframes modalPopIn {
        0% { opacity: 0; transform: scale(0.95) translateY(8px); }
        100% { opacity: 1; transform: scale(1) translateY(0); }
    }
    .animate-modal-pop {
        animation: modalPopIn 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    input[list]:not([type="date"]):not([type="datetime-local"])::-webkit-calendar-picker-indicator {
        display: none !important;
        -webkit-appearance: none !important;
        opacity: 0 !important;
        width: 0 !important;
        height: 0 !important;
    }
</style>

<div class="space-y-6 max-w-7xl mx-auto">

    <!-- Mensaje Flash de éxito -->
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

    <!-- Mensajes Flash de error / validación -->
    @if($errors->any() || session('error'))
        <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200/80 text-amber-900 flex items-center justify-between shadow-xs animate-in fade-in duration-200">
            <div class="flex items-center gap-3">
                <span class="w-8 h-8 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center font-bold shrink-0">
                    <i class="fa-solid fa-triangle-exclamation text-sm"></i>
                </span>
                <div class="space-y-0.5">
                    @if(session('error'))
                        <p class="text-xs sm:text-sm font-semibold">{{ session('error') }}</p>
                    @endif
                    @foreach($errors->all() as $error)
                        <p class="text-xs sm:text-sm font-semibold">{{ $error }}</p>
                    @endforeach
                </div>
            </div>
            <button onclick="this.parentElement.remove()" class="text-amber-500 hover:text-amber-700 p-1 cursor-pointer">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
    @endif

    <!-- 1. Encabezado de la Sección -->
    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4 transition-all duration-200">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center border border-orange-200/80 shadow-xs">
                    <i class="fa-solid fa-spa text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Pausas Activas Programadas</h1>
                    <p class="text-sm text-slate-500 font-medium mt-0.5">Programación, registro y control de pausas ergonómicas, visuales y osteomusculares.</p>
                </div>
            </div>
        </div>

        <button onclick="openRegisterModal()" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-orange-50 hover:bg-orange-100/80 active:bg-orange-200/70 text-orange-700 text-sm font-bold shadow-xs transition-all duration-200 cursor-pointer group border border-orange-200/90 hover:border-orange-300">
            <i class="fa-solid fa-plus text-xs group-hover:rotate-90 transition-transform duration-300"></i>
            <span>Programar Nueva Pausa</span>
        </button>
    </div>

    <!-- 2. Resumen de Estado -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center font-bold text-lg shrink-0">
                <i class="fa-solid fa-calendar-days"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Programadas</p>
                <h4 class="text-xl font-extrabold text-slate-800">{{ $stats['total'] }}</h4>
            </div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-lg shrink-0">
                <i class="fa-solid fa-clock"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Pendientes / En Espera</p>
                <h4 class="text-xl font-extrabold text-slate-800">{{ $stats['programadas'] }}</h4>
            </div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg shrink-0">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Sesiones Realizadas</p>
                <h4 class="text-xl font-extrabold text-slate-800">{{ $stats['realizadas'] }}</h4>
            </div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-lg shrink-0">
                <i class="fa-solid fa-ban"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Canceladas</p>
                <h4 class="text-xl font-extrabold text-slate-800">{{ $stats['canceladas'] }}</h4>
            </div>
        </div>
    </div>

    <!-- 3. Barra de Filtros -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
        <form method="GET" action="{{ route('SST.admin.pausas_activas.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="relative sm:col-span-2">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por título, lugar, instructor o ficha..." class="w-full pl-9 pr-3 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-medium">
            </div>

            <div class="flex items-center gap-2">
                <select name="estado" onchange="this.form.submit()" class="w-full px-3 py-2 text-xs font-bold rounded-xl bg-slate-50 border border-slate-200 text-slate-600 focus:outline-none focus:border-orange-500 cursor-pointer">
                    <option value="todos">Todos los estados</option>
                    <option value="Programada" {{ request('estado') == 'Programada' ? 'selected' : '' }}>Programadas</option>
                    <option value="Realizada" {{ request('estado') == 'Realizada' ? 'selected' : '' }}>Realizadas</option>
                    <option value="Cancelada" {{ request('estado') == 'Cancelada' ? 'selected' : '' }}>Canceladas</option>
                </select>

                <button type="submit" class="px-4 py-2 text-xs font-bold bg-slate-900 hover:bg-slate-800 text-white rounded-xl shadow-xs transition cursor-pointer">
                    <i class="fa-solid fa-filter"></i>
                </button>
                @if(request()->hasAny(['search', 'estado']))
                    <a href="{{ route('SST.admin.pausas_activas.index') }}" class="px-3 py-2 text-xs font-bold text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition" title="Limpiar filtros">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- 4. Tabla Principal de Pausas -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/90 border-b border-slate-200/80 text-slate-500 text-[11px] font-bold uppercase tracking-wider">
                        <th class="py-3.5 px-4">TÍTULO / ACTIVIDAD</th>
                        <th class="py-3.5 px-4">INSTRUCTOR / RESPONSABLE</th>
                        <th class="py-3.5 px-4">FICHA O PROGRAMA</th>
                        <th class="py-3.5 px-4">FECHA Y HORARIO</th>
                        <th class="py-3.5 px-4">AMBIENTE / UNIDAD</th>
                        <th class="py-3.5 px-4 text-center">ESTADO</th>
                        <th class="py-3.5 px-4 text-right">ACCIONES</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 text-xs font-medium">
                    @forelse($actividades as $item)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-900 flex items-center gap-2">
                                    <span class="w-7 h-7 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center text-xs shrink-0">
                                        <i class="fa-solid fa-spa"></i>
                                    </span>
                                    <div>
                                        <p class="font-bold text-slate-900">{{ $item->titulo }}</p>
                                    </div>
                                </div>
                            </td>

                            <td class="py-3.5 px-4">
                                <div class="font-semibold text-slate-800">{{ $item->instructor ?? 'Sin asignar' }}</div>
                                @if($item->responsable)
                                    <div class="text-[10px] text-slate-400 mt-0.5">{{ $item->responsable }}</div>
                                @endif
                            </td>

                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-100 text-slate-700">
                                    <i class="fa-solid fa-id-card text-[9px] text-slate-400"></i>
                                    {{ $item->ficha_programa ?? 'General' }}
                                </span>
                            </td>

                            <td class="py-3.5 px-4">
                                <div class="font-semibold text-slate-900">
                                    <i class="fa-regular fa-calendar text-slate-400 mr-1"></i>
                                    {{ \Carbon\Carbon::parse($item->fecha)->format('d/m/Y') }}
                                </div>
                                <div class="text-[11px] text-slate-500 mt-0.5">
                                    <i class="fa-regular fa-clock text-slate-400 mr-1"></i>
                                    {{ substr($item->hora_inicio, 0, 5) }} 
                                    @if($item->hora_fin) - {{ substr($item->hora_fin, 0, 5) }} @endif
                                </div>
                            </td>

                            <td class="py-3.5 px-4 text-slate-700">
                                <i class="fa-solid fa-location-dot text-orange-500 mr-1 text-[11px]"></i>
                                {{ $item->lugar }}
                            </td>

                            <td class="py-3.5 px-4 text-center">
                                @if($item->estado === 'Realizada')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Realizada
                                    </span>
                                @elseif($item->estado === 'Programada')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Programada
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Cancelada
                                    </span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button type="button" onclick='openDetailModal(@json($item))' class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition cursor-pointer" title="Ver Detalle">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </button>
                                    <button type="button" onclick='openEditModal(@json($item))' class="w-8 h-8 rounded-xl bg-orange-50 hover:bg-orange-100 text-orange-600 flex items-center justify-center transition cursor-pointer" title="Editar">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </button>
                                    <form action="{{ route('SST.admin.pausas_activas.destroy', $item->id_actividad) }}" method="POST" onsubmit="return confirm('¿Deseas eliminar esta pausa activa?')" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 flex items-center justify-center transition cursor-pointer" title="Eliminar">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12 text-slate-400">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-2">
                                    <i class="fa-solid fa-spa text-lg"></i>
                                </div>
                                <p class="text-sm font-semibold">No se encontraron pausas activas programadas.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($actividades->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $actividades->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Modal 1: Programar Nueva Pausa Activa (Formulario Idéntico a la imagen + Nuevos Campos) -->
<div id="modalRegister" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-100 animate-modal-pop max-h-[92vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
            <h3 class="font-extrabold text-slate-900 text-base flex items-center gap-2">
                <i class="fa-solid fa-spa text-orange-600"></i>
                <span>Programar Nueva Pausa Activa</span>
            </h3>
            <button onclick="closeRegisterModal()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition cursor-pointer">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>

        <form action="{{ route('SST.admin.pausas_activas.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Actividad en Realizar / Dinámica -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Actividad en Realizar / Dinámica *</label>
                <input type="text" name="titulo" required placeholder="Ej: Rutina de 10 ejercicios de estiramiento articular y parpadeo guiado" class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-semibold">
            </div>

            <!-- Descripción de la Actividad -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Descripción de la Actividad</label>
                <textarea name="descripcion" rows="2" placeholder="Ej: Ejercicios de cuello, hombros y muñecas para prevenir fatiga laboral..." class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-medium"></textarea>
            </div>

            <!-- Fecha, Hora Inicio, Hora Fin (En 3 Columnas) -->
            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Fecha *</label>
                    <input type="date" name="fecha" id="register_fecha" min="{{ \Carbon\Carbon::now('America/Bogota')->subDays(3)->format('Y-m-d') }}" value="{{ \Carbon\Carbon::now('America/Bogota')->format('Y-m-d') }}" required class="w-full px-3 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-semibold">
                    <span class="text-[10px] text-orange-600 font-semibold block mt-1"><i class="fa-solid fa-clock-rotate-left mr-0.5"></i> Mínimo: {{ \Carbon\Carbon::now('America/Bogota')->subDays(3)->format('d/m/Y') }}</span>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Hora Inicio *</label>
                    <input type="time" name="hora_inicio" value="10:00" required class="w-full px-3 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-semibold">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Hora Fin</label>
                    <input type="time" name="hora_fin" value="10:15" class="w-full px-3 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-semibold">
                </div>
            </div>

            <!-- Ambiente / Unidad -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Ambiente / Unidad *</label>
                <select name="lugar" required class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-semibold cursor-pointer">
                    <option value="" disabled selected>Selecciona un ambiente o unidad...</option>
                    @foreach($lugares as $lug)
                        <option value="{{ $lug->nombre }}">{{ $lug->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Instructor o Instructora / Responsable -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Instructor o Instructora</label>
                    <input type="text" name="instructor" placeholder="Ej: Ing. Carlos Pérez" class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-medium">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Responsable SST</label>
                    <input type="text" name="responsable" placeholder="Ej: Líder Brigada SST" class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-medium">
                </div>
            </div>

            <!-- Ficha o Programa y Estado -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Ficha o Programa</label>
                    <div class="relative" id="create_ficha_combo_container">
                        <input type="text" 
                               name="ficha_programa" 
                               id="create_ficha_programa"
                               placeholder="Selecciona o escribe la ficha (Ej: 2847386 - ADSO)" 
                               autocomplete="off" 
                               class="w-full pl-3.5 pr-10 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-medium">
                        <button type="button" 
                                onclick="toggleAdminFichasDropdown(event, 'create_ficha_combo_container')"
                                class="absolute inset-y-0 right-0 px-3 flex items-center text-slate-400 hover:text-orange-600 transition cursor-pointer"
                                title="Desplegar fichas activas">
                            <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200 icon-arrow-ficha"></i>
                        </button>

                        <!-- Menú Desplegable con todas las fichas de SICA -->
                        <div class="fichas-dropdown-menu absolute z-50 left-0 right-0 mt-1 max-h-48 overflow-y-auto bg-white rounded-xl border border-slate-200 shadow-xl divide-y divide-slate-100 hidden">
                            @if(isset($cursos) && count($cursos) > 0)
                                @foreach($cursos as $c)
                                    @php
                                        $fichaTexto = $c->code . ' - ' . ($c->program->name ?? 'Programa de Formación');
                                    @endphp
                                    <div class="ficha-option px-3.5 py-2.5 hover:bg-orange-50 cursor-pointer flex items-center justify-between text-xs transition group"
                                         data-text="{{ strtolower($fichaTexto) }}"
                                         data-value="{{ $fichaTexto }}"
                                         onclick="selectAdminFicha('create_ficha_programa', this.getAttribute('data-value'), 'create_ficha_combo_container')">
                                        <div class="truncate">
                                            <strong class="font-extrabold text-slate-800 group-hover:text-orange-600">{{ $c->code }}</strong>
                                            <span class="text-slate-500 text-[11px] ml-1.5">{{ $c->program->name ?? '' }}</span>
                                        </div>
                                        <span class="text-[10px] font-bold text-orange-500 opacity-0 group-hover:opacity-100 transition shrink-0 ml-2">Seleccionar</span>
                                    </div>
                                @endforeach
                                <div class="no-fichas-match px-4 py-3 text-xs text-slate-400 text-center hidden">No se encontraron fichas coincidentes</div>
                            @else
                                <div class="px-4 py-3 text-xs text-slate-400 text-center">No hay fichas activas registradas</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Estado *</label>
                    <select name="estado" required class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-semibold cursor-pointer">
                        <option value="Programada" selected>Programada</option>
                        <option value="Realizada">Realizada</option>
                        <option value="Cancelada">Cancelada</option>
                    </select>
                </div>
            </div>

            <!-- Observación -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Observación</label>
                <textarea name="observaciones" rows="2" placeholder="Notas adicionales o conclusiones de la actividad..." class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-medium"></textarea>
            </div>

            <!-- Botones idénticos a la imagen -->
            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeRegisterModal()" class="px-4 py-2.5 text-xs font-bold text-slate-600 rounded-xl hover:bg-slate-100 transition cursor-pointer">
                    Cancelar
                </button>
                <button type="submit" class="px-5 py-2.5 text-xs font-bold bg-orange-50 hover:bg-orange-100 active:bg-orange-200/70 text-orange-700 border border-orange-200/90 hover:border-orange-300 rounded-xl shadow-xs transition cursor-pointer flex items-center gap-2">
                    <i class="fa-solid fa-check text-xs"></i>
                    <span>Programar Sesión</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal 2: Editar Pausa Activa -->
<div id="modalEdit" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-100 animate-modal-pop max-h-[92vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
            <h3 class="font-extrabold text-slate-900 text-base flex items-center gap-2">
                <i class="fa-solid fa-pen text-orange-600"></i>
                <span>Editar Pausa Activa</span>
            </h3>
            <button onclick="closeEditModal()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition cursor-pointer">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>

        <form id="formEdit" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Actividad en Realizar / Dinámica *</label>
                <input type="text" name="titulo" id="edit_titulo" required class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-semibold">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Descripción de la Actividad</label>
                <textarea name="descripcion" id="edit_descripcion" rows="2" class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-medium"></textarea>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Fecha *</label>
                    <input type="date" name="fecha" id="edit_fecha" min="{{ \Carbon\Carbon::now('America/Bogota')->subDays(3)->format('Y-m-d') }}" required class="w-full px-3 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-semibold">
                    <span class="text-[10px] text-orange-600 font-semibold block mt-1"><i class="fa-solid fa-clock-rotate-left mr-0.5"></i> Mínimo: {{ \Carbon\Carbon::now('America/Bogota')->subDays(3)->format('d/m/Y') }}</span>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Hora Inicio *</label>
                    <input type="time" name="hora_inicio" id="edit_hora_inicio" required class="w-full px-3 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-semibold">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Hora Fin</label>
                    <input type="time" name="hora_fin" id="edit_hora_fin" class="w-full px-3 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-semibold">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Ambiente / Unidad *</label>
                <select name="lugar" id="edit_lugar" required class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-semibold cursor-pointer">
                    <option value="" disabled>Selecciona un ambiente o unidad...</option>
                    @foreach($lugares as $lug)
                        <option value="{{ $lug->nombre }}">{{ $lug->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Instructor o Instructora</label>
                    <input type="text" name="instructor" id="edit_instructor" class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-medium">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Responsable SST</label>
                    <input type="text" name="responsable" id="edit_responsable" class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-medium">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Ficha o Programa</label>
                    <div class="relative" id="edit_ficha_combo_container">
                        <input type="text" 
                               name="ficha_programa" 
                               id="edit_ficha_programa" 
                               placeholder="Selecciona o escribe la ficha..." 
                               autocomplete="off" 
                               class="w-full pl-3.5 pr-10 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-medium">
                        <button type="button" 
                                onclick="toggleAdminFichasDropdown(event, 'edit_ficha_combo_container')"
                                class="absolute inset-y-0 right-0 px-3 flex items-center text-slate-400 hover:text-orange-600 transition cursor-pointer"
                                title="Desplegar fichas activas">
                            <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200 icon-arrow-ficha"></i>
                        </button>

                        <!-- Menú Desplegable con todas las fichas de SICA -->
                        <div class="fichas-dropdown-menu absolute z-50 left-0 right-0 mt-1 max-h-48 overflow-y-auto bg-white rounded-xl border border-slate-200 shadow-xl divide-y divide-slate-100 hidden">
                            @if(isset($cursos) && count($cursos) > 0)
                                @foreach($cursos as $c)
                                    @php
                                        $fichaTexto = $c->code . ' - ' . ($c->program->name ?? 'Programa de Formación');
                                    @endphp
                                    <div class="ficha-option px-3.5 py-2.5 hover:bg-orange-50 cursor-pointer flex items-center justify-between text-xs transition group"
                                         data-text="{{ strtolower($fichaTexto) }}"
                                         data-value="{{ $fichaTexto }}"
                                         onclick="selectAdminFicha('edit_ficha_programa', this.getAttribute('data-value'), 'edit_ficha_combo_container')">
                                        <div class="truncate">
                                            <strong class="font-extrabold text-slate-800 group-hover:text-orange-600">{{ $c->code }}</strong>
                                            <span class="text-slate-500 text-[11px] ml-1.5">{{ $c->program->name ?? '' }}</span>
                                        </div>
                                        <span class="text-[10px] font-bold text-orange-500 opacity-0 group-hover:opacity-100 transition shrink-0 ml-2">Seleccionar</span>
                                    </div>
                                @endforeach
                                <div class="no-fichas-match px-4 py-3 text-xs text-slate-400 text-center hidden">No se encontraron fichas coincidentes</div>
                            @else
                                <div class="px-4 py-3 text-xs text-slate-400 text-center">No hay fichas activas registradas</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Estado *</label>
                    <select name="estado" id="edit_estado" required class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-semibold cursor-pointer">
                        <option value="Programada">Programada</option>
                        <option value="Realizada">Realizada</option>
                        <option value="Cancelada">Cancelada</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Observación</label>
                <textarea name="observaciones" id="edit_observaciones" rows="2" class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-medium"></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-xs font-bold text-slate-600 rounded-xl hover:bg-slate-100 transition cursor-pointer">
                    Cancelar
                </button>
                <button type="submit" class="px-5 py-2 text-xs font-bold bg-orange-50 hover:bg-orange-100 active:bg-orange-200/70 text-orange-700 border border-orange-200/90 hover:border-orange-300 rounded-xl shadow-xs transition cursor-pointer flex items-center gap-1.5">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    <span>Guardar Cambios</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal 3: Ver Detalle Completo -->
<div id="modalDetail" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-100 animate-modal-pop">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
            <h3 class="font-extrabold text-slate-900 text-base flex items-center gap-2">
                <i class="fa-solid fa-circle-info text-orange-600"></i>
                <span>Detalle de la Pausa Activa</span>
            </h3>
            <button onclick="closeDetailModal()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition cursor-pointer">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>

        <div class="space-y-3.5 text-xs">
            <div>
                <span class="text-orange-600 font-bold uppercase tracking-wider text-[10px] block">Actividad en Realizar / Dinámica</span>
                <p id="detail_titulo" class="text-base font-extrabold text-slate-900 mt-0.5"></p>
            </div>

            <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                <span class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1">Descripción</span>
                <p id="detail_descripcion" class="text-slate-700 font-medium"></p>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-100">
                    <span class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block">Fecha y Horario</span>
                    <p id="detail_fecha_hora" class="font-bold text-slate-800 mt-0.5"></p>
                </div>
                <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-100">
                    <span class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block">Ambiente / Unidad</span>
                    <p id="detail_lugar" class="font-bold text-slate-800 mt-0.5"></p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-100">
                    <span class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block">Instructor o Instructora</span>
                    <p id="detail_instructor" class="font-bold text-slate-800 mt-0.5"></p>
                </div>
                <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-100">
                    <span class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block">Responsable SST</span>
                    <p id="detail_responsable" class="font-bold text-slate-800 mt-0.5"></p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-100">
                    <span class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block">Ficha o Programa</span>
                    <p id="detail_ficha_programa" class="font-bold text-slate-800 mt-0.5"></p>
                </div>
                <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-100">
                    <span class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block">Estado</span>
                    <p id="detail_estado" class="font-bold mt-0.5"></p>
                </div>
            </div>

            <div id="detail_observaciones_box" class="p-3 bg-slate-50 rounded-xl border border-slate-100 hidden">
                <span class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1">Observación</span>
                <p id="detail_observaciones" class="text-slate-700 font-medium"></p>
            </div>
        </div>

        <div class="flex justify-end pt-4 border-t border-slate-100 mt-4">
            <button type="button" onclick="closeDetailModal()" class="px-5 py-2 text-xs font-bold text-white bg-slate-900 hover:bg-slate-800 rounded-xl transition cursor-pointer">
                Cerrar
            </button>
        </div>
    </div>
</div>

<script>
    function toggleAdminFichasDropdown(event, containerId, forceOpen = false) {
        if (event) {
            event.stopPropagation();
            event.preventDefault();
        }
        const container = document.getElementById(containerId);
        if (!container) return;
        const menu = container.querySelector('.fichas-dropdown-menu');
        const icon = container.querySelector('.icon-arrow-ficha');
        if (!menu) return;

        const isHidden = menu.classList.contains('hidden');
        if (isHidden || forceOpen) {
            document.querySelectorAll('.fichas-dropdown-menu').forEach(m => m.classList.add('hidden'));
            document.querySelectorAll('.icon-arrow-ficha').forEach(i => i.classList.remove('rotate-180'));

            // Mostramos todas las opciones sin filtro para permitir cambiar de ficha libremente
            const options = menu.querySelectorAll('.ficha-option');
            options.forEach(opt => opt.classList.remove('hidden'));
            const noMatch = menu.querySelector('.no-fichas-match');
            if (noMatch) noMatch.classList.add('hidden');

            menu.classList.remove('hidden');
            if (icon) icon.classList.add('rotate-180');

            // Resaltar la ficha que esté actualmente seleccionada
            const input = container.querySelector('input[name="ficha_programa"]');
            if (input && input.value) {
                const currentVal = input.value.toLowerCase().trim();
                let selectedElem = null;
                options.forEach(opt => {
                    const text = (opt.getAttribute('data-value') || '').toLowerCase().trim();
                    if (text === currentVal || text.startsWith(currentVal) || (currentVal.length > 5 && text.includes(currentVal.substring(0, 7)))) {
                        opt.classList.add('bg-blue-100/70', 'font-bold');
                        if (!selectedElem) selectedElem = opt;
                    } else {
                        opt.classList.remove('bg-blue-100/70', 'font-bold');
                    }
                });
                if (selectedElem) {
                    selectedElem.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
                }
            }
        } else {
            menu.classList.add('hidden');
            if (icon) icon.classList.remove('rotate-180');
        }
    }

    function selectAdminFicha(inputId, value, containerId) {
        const input = document.getElementById(inputId);
        if (input) {
            input.value = value;
            input.dispatchEvent(new Event('change'));
        }
        const container = document.getElementById(containerId);
        if (container) {
            const menu = container.querySelector('.fichas-dropdown-menu');
            const icon = container.querySelector('.icon-arrow-ficha');
            if (menu) menu.classList.add('hidden');
            if (icon) icon.classList.remove('rotate-180');
        }
    }

    function setupAdminFichaFilter(containerId, inputId) {
        const container = document.getElementById(containerId);
        if (!container) return;
        const input = document.getElementById(inputId);
        const menu = container.querySelector('.fichas-dropdown-menu');
        const icon = container.querySelector('.icon-arrow-ficha');
        if (!input || !menu) return;

        // Al hacer clic o foco en el input, desplegar las fichas y seleccionar el texto para cambiarlo rápido
        input.addEventListener('click', function(e) {
            e.stopPropagation();
            if (menu.classList.contains('hidden')) {
                toggleAdminFichasDropdown(null, containerId, true);
            }
            this.select();
        });

        input.addEventListener('focus', function() {
            toggleAdminFichasDropdown(null, containerId, true);
            this.select();
        });

        input.addEventListener('input', function() {
            const val = this.value.toLowerCase().trim();
            menu.classList.remove('hidden');
            if (icon) icon.classList.add('rotate-180');

            const options = menu.querySelectorAll('.ficha-option');
            const noMatch = menu.querySelector('.no-fichas-match');
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
                noMatch.classList.toggle('hidden', count > 0 || options.length === 0);
            }
        });
    }

    function openRegisterModal() {
        document.getElementById('modalRegister').classList.remove('hidden');
    }

    function closeRegisterModal() {
        document.getElementById('modalRegister').classList.add('hidden');
        document.querySelectorAll('.fichas-dropdown-menu').forEach(m => m.classList.add('hidden'));
        document.querySelectorAll('.icon-arrow-ficha').forEach(i => i.classList.remove('rotate-180'));
    }

    function openEditModal(item) {
        const form = document.getElementById('formEdit');
        form.action = "{{ url('SST/admin/pausas-activas') }}/" + item.id_actividad;

        document.getElementById('edit_titulo').value = item.titulo || '';
        document.getElementById('edit_descripcion').value = item.descripcion || '';
        document.getElementById('edit_fecha').value = item.fecha || '';
        document.getElementById('edit_hora_inicio').value = item.hora_inicio ? item.hora_inicio.substring(0, 5) : '';
        document.getElementById('edit_hora_fin').value = item.hora_fin ? item.hora_fin.substring(0, 5) : '';
        const editLugar = document.getElementById('edit_lugar');
        if (editLugar) {
            editLugar.value = item.lugar || '';
            if (item.lugar && editLugar.value !== item.lugar) {
                const opt = document.createElement('option');
                opt.value = item.lugar;
                opt.textContent = item.lugar;
                opt.selected = true;
                editLugar.appendChild(opt);
            }
        }
        document.getElementById('edit_instructor').value = item.instructor || '';
        document.getElementById('edit_responsable').value = item.responsable || '';
        document.getElementById('edit_ficha_programa').value = item.ficha_programa || '';
        document.getElementById('edit_estado').value = item.estado || 'Programada';
        document.getElementById('edit_observaciones').value = item.observaciones || '';

        document.getElementById('modalEdit').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('modalEdit').classList.add('hidden');
        document.querySelectorAll('.fichas-dropdown-menu').forEach(m => m.classList.add('hidden'));
        document.querySelectorAll('.icon-arrow-ficha').forEach(i => i.classList.remove('rotate-180'));
    }

    function openDetailModal(item) {
        document.getElementById('detail_titulo').textContent = item.titulo || 'Sin título';
        document.getElementById('detail_descripcion').textContent = item.descripcion || 'Sin descripción registrada.';
        document.getElementById('detail_instructor').textContent = item.instructor || 'No especificado';
        document.getElementById('detail_responsable').textContent = item.responsable || 'No especificado';
        document.getElementById('detail_ficha_programa').textContent = item.ficha_programa || 'General';
        document.getElementById('detail_fecha_hora').textContent = `${item.fecha} (${(item.hora_inicio || '').substring(0, 5)} - ${(item.hora_fin || '').substring(0, 5)})`;
        document.getElementById('detail_lugar').textContent = item.lugar || 'No especificado';

        const estadoElem = document.getElementById('detail_estado');
        estadoElem.textContent = item.estado;
        if (item.estado === 'Realizada') {
            estadoElem.className = 'font-bold mt-0.5 text-emerald-600';
        } else if (item.estado === 'Programada') {
            estadoElem.className = 'font-bold mt-0.5 text-amber-600';
        } else {
            estadoElem.className = 'font-bold mt-0.5 text-rose-600';
        }

        const obsBox = document.getElementById('detail_observaciones_box');
        if (item.observaciones) {
            document.getElementById('detail_observaciones').textContent = item.observaciones;
            obsBox.classList.remove('hidden');
        } else {
            obsBox.classList.add('hidden');
        }

        document.getElementById('modalDetail').classList.remove('hidden');
    }

    function closeDetailModal() {
        document.getElementById('modalDetail').classList.add('hidden');
    }

    const MIN_SELECTABLE_DATE_STR = "{{ \Carbon\Carbon::now('America/Bogota')->subDays(3)->format('Y-m-d') }}";
    const MIN_SELECTABLE_DATE_FORMATTED = "{{ \Carbon\Carbon::now('America/Bogota')->subDays(3)->format('d/m/Y') }}";

    function validateActivityDate(inputElem) {
        if (!inputElem || !inputElem.value) return true;
        if (inputElem.value < MIN_SELECTABLE_DATE_STR) {
            alert(`⚠️ Fecha no permitida: Solo se pueden seleccionar o registrar actividades a partir del ${MIN_SELECTABLE_DATE_FORMATTED} (máximo 3 días anteriores a hoy).`);
            inputElem.value = "{{ \Carbon\Carbon::now('America/Bogota')->format('Y-m-d') }}";
            inputElem.focus();
            return false;
        }
        return true;
    }

    document.addEventListener('DOMContentLoaded', function() {
        const regFecha = document.getElementById('register_fecha');
        if (regFecha) {
            regFecha.min = MIN_SELECTABLE_DATE_STR;
            regFecha.addEventListener('change', function() { validateActivityDate(this); });
        }
        const editFecha = document.getElementById('edit_fecha');
        if (editFecha) {
            editFecha.min = MIN_SELECTABLE_DATE_STR;
            editFecha.addEventListener('change', function() { validateActivityDate(this); });
        }

        const formRegister = document.querySelector('form[action*="pausas-activas"]');
        if (formRegister) {
            formRegister.addEventListener('submit', function(e) {
                if (regFecha && !validateActivityDate(regFecha)) {
                    e.preventDefault();
                }
            });
        }

        const formEdit = document.getElementById('formEdit');
        if (formEdit) {
            formEdit.addEventListener('submit', function(e) {
                if (editFecha && !validateActivityDate(editFecha)) {
                    e.preventDefault();
                }
            });
        }

        // Inicializar filtros para combobox de fichas
        setupAdminFichaFilter('create_ficha_combo_container', 'create_ficha_programa');
        setupAdminFichaFilter('edit_ficha_combo_container', 'edit_ficha_programa');
    });

    document.addEventListener('click', function(e) {
        if (!e.target.closest('#create_ficha_combo_container') && !e.target.closest('#edit_ficha_combo_container')) {
            document.querySelectorAll('.fichas-dropdown-menu').forEach(m => m.classList.add('hidden'));
            document.querySelectorAll('.icon-arrow-ficha').forEach(i => i.classList.remove('rotate-180'));
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeRegisterModal();
            closeEditModal();
            closeDetailModal();
            document.querySelectorAll('.fichas-dropdown-menu').forEach(m => m.classList.add('hidden'));
            document.querySelectorAll('.icon-arrow-ficha').forEach(i => i.classList.remove('rotate-180'));
        }
    });
</script>
@endsection

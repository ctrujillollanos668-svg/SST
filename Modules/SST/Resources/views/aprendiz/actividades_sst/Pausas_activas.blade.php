@extends('sst::components.layouts.aprendiz')

@section('title', 'Pausas Activas • Actividades SST')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">

    <!-- 1. Encabezado de la Sección -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4 transition-all">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-orange-50 text-orange-500 flex items-center justify-center border border-orange-100 shadow-2xs shrink-0 text-2xl">
                <i class="fa-solid fa-spa"></i>
            </div>
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-orange-50 text-orange-700 text-xs font-bold mb-1 border border-orange-200/60">
                    <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span> Actividades SST
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Pausas Activas Programadas</h1>
                <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">Consulta las jornadas ergonómicas, visuales y osteomusculares para tu bienestar.</p>
            </div>
        </div>
    </div>

    <!-- 2. Tarjetas de Resumen KPI -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center font-bold text-xl shrink-0">
                <i class="fa-solid fa-calendar-days"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Jornadas</p>
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
        <form method="GET" action="{{ route('SST.aprendiz.actividades_sst.pausas_activas.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="relative sm:col-span-2">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por título, lugar, instructor o ficha..."
                    class="w-full pl-9 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition">
            </div>

            <div class="flex items-center gap-2">
                <select name="estado" onchange="this.form.submit()"
                    class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition">
                    <option value="todos" {{ request('estado') == 'todos' ? 'selected' : '' }}>Todos los estados</option>
                    <option value="Programada" {{ request('estado') == 'Programada' ? 'selected' : '' }}>Programadas</option>
                    <option value="Realizada" {{ request('estado') == 'Realizada' ? 'selected' : '' }}>Realizadas</option>
                    <option value="Cancelada" {{ request('estado') == 'Cancelada' ? 'selected' : '' }}>Canceladas</option>
                </select>

                @if(request()->hasAny(['search', 'estado']))
                    <a href="{{ route('SST.aprendiz.actividades_sst.pausas_activas.index') }}" class="px-3 py-2.5 text-xs font-bold text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition flex items-center justify-center shrink-0" title="Limpiar filtros">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- 4. Listado de Actividades (Horizontal / De largo) -->
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
                <div class="item-card bg-slate-50/70 hover:bg-white p-4 sm:p-5 rounded-3xl border border-slate-200/80 shadow-2xs hover:shadow-md hover:border-orange-300 transition-all flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-5 cursor-pointer group"
                     onclick="openDetailModal(this)"
                     data-raw="{{ $rawData }}">

                    <!-- Ícono de la Actividad -->
                    <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-gradient-to-br from-orange-50 to-amber-50 border border-orange-200/80 flex flex-col items-center justify-center text-orange-500 shrink-0 shadow-2xs group-hover:border-orange-400 group-hover:scale-105 transition">
                        <i class="fa-solid fa-spa text-2xl text-orange-500"></i>
                    </div>

                    <!-- Contenido Central Estructurado -->
                    <div class="flex-1 flex flex-col justify-between space-y-2.5 w-full">
                        <!-- Fila 1: Título y Estado -->
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <h3 class="font-extrabold text-slate-900 text-sm sm:text-base flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-orange-500 shrink-0"></span>
                                <span class="group-hover:text-orange-600 transition-colors">{{ $item->titulo }}</span>
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
                                <i class="fa-regular fa-calendar text-orange-500 text-[10px]"></i>
                                <span class="text-slate-400 font-semibold">Fecha:</span>
                                <strong class="font-bold text-slate-700">{{ $fechaObj->isoFormat('D MMM, Y') }}</strong>
                            </span>

                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-white border border-slate-200/80 shadow-2xs" title="Horario">
                                <i class="fa-regular fa-clock text-orange-500 text-[10px]"></i>
                                <span class="text-slate-400 font-semibold">Horario:</span>
                                <strong class="font-bold text-slate-700">{{ $horaIni }} @if($horaFin) - {{ $horaFin }} @endif</strong>
                            </span>

                            @if($item->lugar)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-white border border-slate-200/80 shadow-2xs" title="Lugar / Ubicación">
                                <i class="fa-solid fa-location-dot text-orange-500 text-[10px]"></i>
                                <span class="text-slate-400 font-semibold">Lugar:</span>
                                <strong class="font-bold text-slate-700">{{ $item->lugar }}</strong>
                            </span>
                            @endif

                            @if($item->instructor)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-white border border-slate-200/80 shadow-2xs" title="Instructor / Responsable">
                                <i class="fa-solid fa-chalkboard-user text-orange-500 text-[10px]"></i>
                                <span class="text-slate-400 font-semibold">Responsable:</span>
                                <strong class="font-bold text-slate-700">{{ $item->instructor }}</strong>
                            </span>
                            @endif

                            @if($item->ficha_programa)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-white border border-slate-200/80 shadow-2xs" title="Ficha / Programa">
                                <i class="fa-solid fa-users-rectangle text-orange-500 text-[10px]"></i>
                                <span class="text-slate-400 font-semibold">Ficha:</span>
                                <strong class="font-bold text-slate-700">{{ $item->ficha_programa }}</strong>
                            </span>
                            @endif
                        </div>

                        <!-- Fila 3: Descripción y Botón Ver Detalles -->
                        <div class="flex items-center justify-between gap-3 pt-0.5">
                            <p class="text-xs text-slate-500 line-clamp-1 flex-1 leading-relaxed">
                                <strong class="font-bold text-slate-700">Actividad:</strong> {{ $item->descripcion ?? $item->actividad_a_realizar ?? 'Sin descripción adicional.' }}
                            </p>

                            <button type="button" onclick="event.stopPropagation(); openDetailModal(this.closest('.item-card'))" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-orange-50 hover:bg-orange-100 text-orange-700 text-xs font-bold border border-orange-200/80 transition cursor-pointer shrink-0 shadow-2xs">
                                <i class="fa-solid fa-eye text-xs"></i>
                                <span>Ver Detalles</span>
                            </button>
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
            <div class="w-16 h-16 rounded-2xl bg-orange-50 text-orange-400 flex items-center justify-center text-2xl mb-3 shadow-2xs">
                <i class="fa-solid fa-spa"></i>
            </div>
            <h3 class="font-extrabold text-slate-800 text-base">No hay pausas activas registradas</h3>
            <p class="text-xs text-slate-400 mt-1 max-w-sm">No se encontraron actividades de pausas activas con los filtros actuales o aún no se han programado.</p>
        </div>
    @endif

</div>

<!-- Modal Detalle de la Pausa Activa -->
<div id="modalDetalleOverlay" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-3 sm:p-4 hidden opacity-0 transition-opacity duration-200" onclick="closeDetailModal()">
    <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 max-w-lg w-full max-h-[90vh] flex flex-col transform transition-transform duration-200 scale-95 overflow-hidden" onclick="event.stopPropagation()">
        
        <div class="p-5 sm:p-6 pb-4 border-b border-slate-100 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-2xl bg-orange-50 text-orange-500 flex items-center justify-center text-lg border border-orange-100 shadow-2xs shrink-0">
                    <i class="fa-solid fa-spa"></i>
                </div>
                <div>
                    <h3 id="modalTitulo" class="font-extrabold text-base sm:text-lg text-slate-800 tracking-tight">Detalles de la Pausa Activa</h3>
                    <p id="modalEstadoTexto" class="text-xs font-semibold text-slate-400">Información del evento</p>
                </div>
            </div>
            <button type="button" onclick="closeDetailModal()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center text-xs transition cursor-pointer">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="p-5 sm:p-6 space-y-4 overflow-y-auto">
            <div class="grid grid-cols-2 gap-3 text-xs">
                <div class="bg-slate-50 p-3 rounded-2xl border border-slate-200/80">
                    <span class="text-[10px] font-bold uppercase text-slate-400 block mb-0.5">Fecha y Horario</span>
                    <p id="modalFechaHorario" class="font-extrabold text-slate-800"></p>
                </div>
                <div class="bg-slate-50 p-3 rounded-2xl border border-slate-200/80">
                    <span class="text-[10px] font-bold uppercase text-slate-400 block mb-0.5">Lugar / Ubicación</span>
                    <p id="modalLugar" class="font-extrabold text-slate-800"></p>
                </div>
            </div>

            <div class="space-y-1">
                <span class="text-[10px] font-bold uppercase text-slate-400 tracking-wider">Instructor / Responsable</span>
                <p id="modalInstructor" class="text-xs font-semibold text-slate-700 bg-slate-50 p-3 rounded-2xl border border-slate-200/80"></p>
            </div>

            <div class="space-y-1">
                <span class="text-[10px] font-bold uppercase text-slate-400 tracking-wider">Ficha / Programa Dirigido</span>
                <p id="modalFicha" class="text-xs font-semibold text-slate-700 bg-slate-50 p-3 rounded-2xl border border-slate-200/80"></p>
            </div>

            <div class="space-y-1">
                <span class="text-[10px] font-bold uppercase text-slate-400 tracking-wider">Descripción y Actividad a Realizar</span>
                <p id="modalDescripcion" class="text-xs font-medium text-slate-600 bg-slate-50 p-3 rounded-2xl border border-slate-200/80 leading-relaxed whitespace-pre-line"></p>
            </div>

            <div id="modalObservacionesContainer" class="space-y-1 hidden">
                <span class="text-[10px] font-bold uppercase text-slate-400 tracking-wider">Observaciones</span>
                <p id="modalObservaciones" class="text-xs font-medium text-slate-600 bg-amber-50/70 p-3 rounded-2xl border border-amber-200/70 leading-relaxed"></p>
            </div>
        </div>

        <div class="p-4 sm:p-5 border-t border-slate-100 flex justify-end bg-slate-50/50 shrink-0">
            <button type="button" onclick="closeDetailModal()" class="px-5 py-2.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs sm:text-sm font-bold transition cursor-pointer">
                Cerrar
            </button>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
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

    function openDetailModal(btn) {
        if (!btn) return;
        const raw = btn.dataset.raw || btn.closest('.item-card')?.dataset.raw;
        if (!raw) return;
        try {
            const data = JSON.parse(atob(raw));
            document.getElementById('modalTitulo').textContent = data.titulo || 'Pausa Activa';
            document.getElementById('modalEstadoTexto').textContent = 'Estado: ' + (data.estado || 'Programada');
            
            const horaFin = data.hora_fin ? ' - ' + data.hora_fin : '';
            document.getElementById('modalFechaHorario').textContent = (data.fecha || 'N/A') + ' (' + (data.hora_inicio || '') + horaFin + ')';
            document.getElementById('modalLugar').textContent = data.lugar || 'Por definir';
            document.getElementById('modalInstructor').textContent = data.instructor || data.responsable || 'Equipo SST';
            document.getElementById('modalFicha').textContent = data.ficha_programa || 'General / Todo el personal';
            document.getElementById('modalDescripcion').textContent = data.descripcion || data.actividad_a_realizar || 'Realización de ejercicios ergonómicos y respiratorios guiados.';
            
            const obsContainer = document.getElementById('modalObservacionesContainer');
            if (data.observaciones) {
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

@extends('sst::components.layouts.aprendiz')

@section('title', 'Tipos de Emergencias • SST')

@section('content')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<div class="max-w-6xl mx-auto space-y-4">

    <!-- Mensaje Flash de Éxito -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200/80 text-emerald-800 flex items-center justify-between shadow-xs">
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

    <!-- Tarjeta Principal Unificada -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs min-h-[460px] flex flex-col justify-between">
        
        <div>
            <!-- Encabezado Superior -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center text-xl border border-rose-100/80 shadow-2xs shrink-0">
                        <i class="fa-solid fa-hospital"></i>
                    </div>
                    <div>
                        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-800 tracking-tight">Tipos de Emergencias</h1>
                        <p class="text-xs sm:text-sm text-slate-400 font-medium mt-0.5">Consulta y reporta eventos de seguridad</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 self-start sm:self-auto">
                    <button type="button" onclick="openRegisterModal()" class="inline-flex items-center justify-center gap-1.5 px-5 py-2.5 rounded-2xl bg-[#ea580c] hover:bg-[#c2410c] active:scale-95 text-white text-xs sm:text-sm font-bold shadow-xs transition cursor-pointer">
                        <i class="fa-solid fa-plus text-xs"></i>
                        <span>Registrar</span>
                    </button>
                </div>
            </div>

            <!-- Contenido Principal -->
            <div class="pt-6">
                @if($tiposEmergencias->count() > 0)
                    <!-- Barra de Búsqueda -->
                    <div class="mb-5 flex flex-col sm:flex-row items-center justify-between gap-3">
                        <div class="w-full sm:w-80 relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </div>
                            <input type="text" id="inputBuscarEvento" placeholder="Buscar emergencia..." 
                                class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition">
                        </div>
                        <span class="text-xs font-bold text-slate-400 self-end sm:self-auto">
                            <span id="conteoEventos" class="text-slate-700">{{ $tiposEmergencias->count() }}</span> {{ $tiposEmergencias->count() == 1 ? 'emergencia registrada' : 'emergencias registradas' }}
                        </span>
                    </div>

                    <!-- Listado en Cuadrícula -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="contenedorEventos">
                        @foreach($tiposEmergencias as $item)
                            <div class="item-card bg-slate-50/50 hover:bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-2xs hover:shadow-md hover:border-rose-300 transition-all space-y-2 cursor-pointer relative group"
                                 onclick="openDetailModal(this)"
                                 data-title="{{ $item->nombre }}"
                                 data-modulo="{{ $item->modulo_pertenece ?? 'General' }}"
                                 data-desc="{{ $item->descripcion ?? 'Sin descripción disponible.' }}">
                                
                                <div class="flex items-start justify-between gap-3">
                                    <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-rose-500 shrink-0"></span>
                                        <span class="group-hover:text-rose-600 transition-colors">{{ $item->nombre }}</span>
                                    </h3>
                                    @if($item->modulo_pertenece)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold bg-white text-slate-600 border border-slate-200 shrink-0">
                                            {{ $item->modulo_pertenece }}
                                        </span>
                                    @endif
                                </div>

                                <p class="text-xs text-slate-500 leading-relaxed line-clamp-2">
                                    {{ $item->descripcion ?? 'Sin descripción disponible.' }}
                                </p>
                            </div>
                        @endforeach
                    </div>

                    <div id="noResultsMsg" class="hidden text-center py-12">
                        <p class="text-xs font-bold text-slate-400">No se encontraron emergencias con ese criterio de búsqueda.</p>
                    </div>
                @else
                    <!-- Estado Vacío (Igual a la Referencia) -->
                    <div class="py-16 sm:py-24 text-center flex flex-col items-center justify-center">
                        <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-300 flex items-center justify-center text-2xl mb-3 shadow-2xs">
                            <i class="fa-solid fa-inbox"></i>
                        </div>
                        <p class="text-xs sm:text-sm font-semibold text-slate-400">
                            No hay tipos de emergencias registradas aún.
                        </p>
                    </div>
                @endif
            </div>
        </div>

    </div>

</div>

<!-- Modal 1: Registrar Tipo de Emergencia -->
<div id="modalRegistrarOverlay" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4 hidden opacity-0 transition-opacity duration-200" onclick="closeRegisterModal()">
    <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 max-w-lg w-full p-6 transform transition-transform duration-200 scale-95" onclick="event.stopPropagation()">
        
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-plus"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-sm text-slate-800 tracking-tight">Registrar Tipo de Emergencia</h3>
                    <p class="text-[11px] text-slate-400">Ingresa la información de la nueva emergencia</p>
                </div>
            </div>
            
            <button type="button" onclick="closeRegisterModal()" class="w-7 h-7 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center text-xs transition cursor-pointer">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="{{ route('SST.aprendiz.tipos_eventos.tipos_emergencias.store') }}" method="POST" class="pt-4 space-y-4">
            @csrf

            <div class="space-y-1">
                <label class="block text-xs font-bold text-slate-700">Nombre de la Emergencia <span class="text-rose-500">*</span></label>
                <input type="text" name="nombre" required placeholder="Ej: Incendio estructural, Sismo, Evacuación..." 
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition">
            </div>

            <div class="space-y-1">
                <label class="block text-xs font-bold text-slate-700">Área o Módulo al que Pertenece</label>
                <input type="text" name="modulo_pertenece" placeholder="Ej: Todo el Centro, Bloque A, General..." 
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition">
            </div>

            <div class="space-y-1">
                <label class="block text-xs font-bold text-slate-700">Descripción Técnica</label>
                <textarea name="descripcion" rows="3" placeholder="Describe brevemente los protocolos o características de esta emergencia..." 
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition resize-none"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeRegisterModal()" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold transition">
                    Cancelar
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-[#ea580c] hover:bg-[#c2410c] text-white text-xs font-bold shadow-xs transition">
                    Guardar Emergencia
                </button>
            </div>
        </form>

    </div>
</div>

<!-- Modal 2: Detalle Completo -->
<div id="modalDetalleOverlay" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4 hidden opacity-0 transition-opacity duration-200" onclick="closeDetailModal()">
    <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 max-w-lg w-full p-6 transform transition-transform duration-200 scale-95" onclick="event.stopPropagation()">
        
        <div class="flex items-center justify-between pb-3.5 border-b border-slate-100">
            <div class="flex items-center gap-2.5 text-slate-800">
                <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-hospital"></i>
                </div>
                <h3 class="font-bold text-sm tracking-tight">Detalle de la Emergencia</h3>
            </div>
            
            <button type="button" onclick="closeDetailModal()" class="w-7 h-7 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center text-xs transition cursor-pointer">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="pt-4 space-y-3">
            <div class="flex items-center justify-between gap-2">
                <h4 id="modalDetalleTitulo" class="font-extrabold text-slate-900 text-sm sm:text-base"></h4>
                <span id="modalDetalleModulo" class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200 shrink-0"></span>
            </div>
            
            <div class="max-h-72 overflow-y-auto pr-1">
                <p id="modalDetalleTexto" class="text-xs sm:text-sm text-slate-600 leading-relaxed whitespace-pre-line"></p>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
    function openRegisterModal() {
        const overlay = document.getElementById('modalRegistrarOverlay');
        const modalBox = overlay.querySelector('div');
        overlay.classList.remove('hidden');
        setTimeout(() => {
            overlay.classList.remove('opacity-0');
            modalBox.classList.remove('scale-95');
            modalBox.classList.add('scale-100');
        }, 10);
    }

    function closeRegisterModal() {
        const overlay = document.getElementById('modalRegistrarOverlay');
        const modalBox = overlay.querySelector('div');
        overlay.classList.add('opacity-0');
        modalBox.classList.remove('scale-100');
        modalBox.classList.add('scale-95');
        setTimeout(() => overlay.classList.add('hidden'), 200);
    }

    function openDetailModal(cardElement) {
        const title = cardElement.getAttribute('data-title') || '';
        const desc = cardElement.getAttribute('data-desc') || '';
        const modulo = cardElement.getAttribute('data-modulo') || 'General';

        document.getElementById('modalDetalleTitulo').textContent = title;
        document.getElementById('modalDetalleTexto').textContent = desc;
        document.getElementById('modalDetalleModulo').textContent = modulo;

        const overlay = document.getElementById('modalDetalleOverlay');
        const modalBox = overlay.querySelector('div');
        overlay.classList.remove('hidden');
        setTimeout(() => {
            overlay.classList.remove('opacity-0');
            modalBox.classList.remove('scale-95');
            modalBox.classList.add('scale-100');
        }, 10);
    }

    function closeDetailModal() {
        const overlay = document.getElementById('modalDetalleOverlay');
        const modalBox = overlay.querySelector('div');
        overlay.classList.add('opacity-0');
        modalBox.classList.remove('scale-100');
        modalBox.classList.add('scale-95');
        setTimeout(() => overlay.classList.add('hidden'), 200);
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeRegisterModal();
            closeDetailModal();
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('inputBuscarEvento');
        const cards = document.querySelectorAll('.item-card');
        const noResultsMsg = document.getElementById('noResultsMsg');
        const conteo = document.getElementById('conteoEventos');

        if (searchInput && cards.length > 0) {
            searchInput.addEventListener('input', function () {
                const query = this.value.toLowerCase().trim();
                let visibleCount = 0;

                cards.forEach(card => {
                    const title = card.getAttribute('data-title')?.toLowerCase() || '';
                    const desc = card.getAttribute('data-desc')?.toLowerCase() || '';
                    const modulo = card.getAttribute('data-modulo')?.toLowerCase() || '';

                    if (title.includes(query) || desc.includes(query) || modulo.includes(query)) {
                        card.style.display = 'block';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                if (conteo) conteo.textContent = visibleCount;
                if (noResultsMsg) {
                    if (visibleCount === 0 && query.length > 0) {
                        noResultsMsg.classList.remove('hidden');
                    } else {
                        noResultsMsg.classList.add('hidden');
                    }
                }
            });
        }
    });
</script>
@endpush
@endsection
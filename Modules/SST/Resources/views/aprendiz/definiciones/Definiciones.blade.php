@extends('sst::components.layouts.aprendiz')

@section('title', 'Definiciones SST • Aprendiz')

@section('content')
<style>
    .accordion-content {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.35s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.25s ease;
        opacity: 0;
    }
    .accordion-item.active .accordion-content {
        max-height: 3000px;
        opacity: 1;
    }
    .accordion-chevron {
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .accordion-item.active .accordion-chevron {
        transform: rotate(180deg);
    }
    
    /* Truncar a 2 líneas */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<div class="max-w-6xl mx-auto space-y-6">

    <!-- 1. Encabezado Principal de la Sección -->
    <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-5 transition-all">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center border border-orange-200/80 shadow-xs text-2xl shrink-0">
                <i class="fa-solid fa-book-open-reader"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Definiciones SST</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-orange-100/70 text-orange-700 border border-orange-200/60">
                        Glosario Oficial
                    </span>
                </div>
                <p class="text-sm text-slate-500 font-medium mt-0.5">
                    Conceptos y términos clave en seguridad y salud en el trabajo.
                </p>
            </div>
        </div>

        <!-- Buscador en Tiempo Real -->
        <div class="w-full md:w-80 relative">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-sm">
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>
            <input type="text" id="inputBuscarDefinicion" placeholder="Buscar concepto o término..." 
                class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-semibold text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition shadow-2xs">
        </div>
    </div>

    <!-- 2. Lista de Categorías en Acordeón -->
    <div class="space-y-4" id="listaAcordeonesDefiniciones">

        @foreach($categorias as $key => $cat)
            @php
                $totalItems = $cat['items']->count();
            @endphp
            <div class="accordion-item bg-white rounded-2xl border border-slate-200/80 shadow-2xs transition-all hover:border-slate-300 overflow-hidden" data-category="{{ $key }}">
                
                <!-- Barra / Cabecera del Acordeón -->
                <div class="accordion-header p-4 sm:p-5 flex items-center justify-between cursor-pointer select-none transition hover:bg-slate-50/70" onclick="toggleAccordion('{{ $key }}')">
                    
                    <!-- Lado Izquierdo: Icono, Título y Conteo -->
                    <div class="flex items-center gap-3.5">
                        <div class="w-10 h-10 rounded-xl {{ $cat['bg_light'] }} {{ $cat['text_color'] }} flex items-center justify-center text-lg border {{ $cat['border_color'] }} shrink-0">
                            <i class="{{ $cat['icono'] }}"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-800 tracking-tight">{{ $cat['titulo'] }}</h3>
                            <p class="text-xs font-semibold text-slate-400">
                                <span class="conteo-items font-bold text-slate-600">{{ $totalItems }}</span> {{ $totalItems == 1 ? 'definición disponible' : 'definiciones disponibles' }}
                            </p>
                        </div>
                    </div>

                    <!-- Lado Derecho: Botón de Acción y Flecha Desplegable -->
                    <div class="flex items-center gap-2">
                        <button type="button" class="w-9 h-9 rounded-xl bg-orange-500 hover:bg-orange-600 text-white flex items-center justify-center shadow-xs transition active:scale-95 text-xs" title="Ver detalles">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        </button>
                        <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center text-xs accordion-chevron">
                            <i class="fa-solid fa-chevron-down"></i>
                        </div>
                    </div>

                </div>

                <!-- Contenido Desplegable: Tarjetas de Conceptos a lo largo -->
                <div class="accordion-content border-t border-slate-100 bg-slate-50/40 w-full">
                    <div class="p-4 sm:p-6 w-full">
                        @if($totalItems > 0)
                            <div class="flex flex-col gap-3.5 w-full">
                                @foreach($cat['items'] as $item)
                                    <div class="item-definicion bg-white p-5 rounded-2xl border border-slate-200/90 shadow-2xs hover:shadow-md hover:border-orange-300 transition-all space-y-2 min-w-0 w-full cursor-pointer relative group/card"
                                         onclick="openDetailModal(this)"
                                         data-title="{{ $item->nombre }}"
                                         data-modulo="{{ $item->modulo_pertenece ?? 'General' }}"
                                         data-desc="{{ $item->descripcion ?? 'Sin descripción técnica disponible actualmente.' }}"
                                         data-category="{{ $cat['titulo'] }}">
                                        
                                        <div class="flex items-start justify-between gap-3 min-w-0">
                                            <h4 class="font-bold text-slate-800 text-sm nombre-concepto flex items-center gap-2 min-w-0">
                                                <span class="w-2.5 h-2.5 rounded-full bg-orange-500 shrink-0"></span>
                                                <span class="break-words group-hover/card:text-orange-600 transition-colors">{{ $item->nombre }}</span>
                                            </h4>
                                            
                                            <div class="flex items-center gap-2 shrink-0">
                                                @if($item->modulo_pertenece)
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                                        {{ $item->modulo_pertenece }}
                                                    </span>
                                                @endif
                                                <span class="text-[11px] text-orange-500 opacity-0 group-hover/card:opacity-100 transition-opacity font-semibold flex items-center gap-1 hidden sm:inline-flex">
                                                    <i class="fa-regular fa-eye text-[10px]"></i> Ver descripción
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Texto Truncado a 2 Líneas -->
                                        <p class="text-xs text-slate-600 leading-relaxed descripcion-concepto break-words line-clamp-2">
                                            {{ $item->descripcion ?? 'Sin descripción técnica disponible actualmente.' }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <!-- Estado Vacío Elegante -->
                            <div class="text-center py-8 px-4 bg-white rounded-2xl border border-dashed border-slate-200 w-full">
                                <div class="w-12 h-12 mx-auto rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center text-xl mb-3">
                                    <i class="fa-regular fa-folder-open"></i>
                                </div>
                                <h4 class="text-xs font-bold text-slate-700">No hay definiciones registradas</h4>
                                <p class="text-[11px] text-slate-400 mt-0.5">Actualmente no se han cargado conceptos para la categoría de {{ strtolower($cat['titulo']) }}.</p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        @endforeach

    </div>

</div>

<!-- Modal / Popup Emergente de Descripción Completa con botón X -->
<div id="modalDescripcionOverlay" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4 hidden opacity-0 transition-opacity duration-200" onclick="closeDetailModal()">
    <div class="bg-white rounded-2xl shadow-2xl border border-slate-100 max-w-lg w-full p-5 sm:p-6 transform transition-transform duration-200 scale-95" onclick="event.stopPropagation()">
        
        <!-- Encabezado con Icono Naranja, Título y Botón X -->
        <div class="flex items-center justify-between pb-3.5 border-b border-slate-100">
            <div class="flex items-center gap-2 text-slate-800">
                <i class="fa-solid fa-bars-staggered text-orange-500 text-sm"></i>
                <h3 class="font-bold text-sm tracking-tight">Descripción Completa</h3>
            </div>
            
            <!-- Botón Cerrar X -->
            <button type="button" onclick="closeDetailModal()" class="w-7 h-7 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center text-xs transition cursor-pointer" title="Cerrar">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Contenido Completo -->
        <div class="pt-4 space-y-3">
            <div class="flex items-center justify-between gap-2">
                <h4 id="modalConceptoTitulo" class="font-extrabold text-slate-900 text-sm sm:text-base"></h4>
                <span id="modalConceptoModulo" class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-[10px] font-bold bg-orange-50 text-orange-700 border border-orange-200 shrink-0"></span>
            </div>
            
            <div class="max-h-72 overflow-y-auto pr-1">
                <p id="modalConceptoTexto" class="text-xs sm:text-sm text-slate-600 leading-relaxed break-words break-all whitespace-pre-line"></p>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
    // Función para abrir y cerrar acordeones
    function toggleAccordion(categoryKey) {
        const item = document.querySelector(`[data-category="${categoryKey}"]`);
        if (!item) return;

        const isCurrentlyActive = item.classList.contains('active');

        document.querySelectorAll('.accordion-item').forEach(el => {
            if (el !== item) {
                el.classList.remove('active');
            }
        });

        if (isCurrentlyActive) {
            item.classList.remove('active');
        } else {
            item.classList.add('active');
        }
    }

    // Modal de Descripción Completa
    function openDetailModal(cardElement) {
        const title = cardElement.getAttribute('data-title') || '';
        const desc = cardElement.getAttribute('data-desc') || '';
        const modulo = cardElement.getAttribute('data-modulo') || 'General';

        document.getElementById('modalConceptoTitulo').textContent = title;
        document.getElementById('modalConceptoTexto').textContent = desc;
        document.getElementById('modalConceptoModulo').textContent = modulo;

        const overlay = document.getElementById('modalDescripcionOverlay');
        const modalBox = overlay.querySelector('div');

        overlay.classList.remove('hidden');
        setTimeout(() => {
            overlay.classList.remove('opacity-0');
            modalBox.classList.remove('scale-95');
            modalBox.classList.add('scale-100');
        }, 10);
    }

    function closeDetailModal() {
        const overlay = document.getElementById('modalDescripcionOverlay');
        const modalBox = overlay.querySelector('div');

        overlay.classList.add('opacity-0');
        modalBox.classList.remove('scale-100');
        modalBox.classList.add('scale-95');

        setTimeout(() => {
            overlay.classList.add('hidden');
        }, 200);
    }

    // Cerrar con tecla Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDetailModal();
        }
    });

    // Buscador en tiempo real
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('inputBuscarDefinicion');

        if (searchInput) {
            searchInput.addEventListener('input', function () {
                const query = this.value.toLowerCase().trim();
                const accordionItems = document.querySelectorAll('.accordion-item');

                accordionItems.forEach(accordion => {
                    const cards = accordion.querySelectorAll('.item-definicion');
                    let hasMatchInAccordion = false;

                    if (cards.length > 0) {
                        cards.forEach(card => {
                            const name = card.getAttribute('data-title')?.toLowerCase() || '';
                            const desc = card.getAttribute('data-desc')?.toLowerCase() || '';

                            if (name.includes(query) || desc.includes(query)) {
                                card.style.display = 'block';
                                hasMatchInAccordion = true;
                            } else {
                                card.style.display = 'none';
                            }
                        });
                    }

                    if (query.length > 0) {
                        if (hasMatchInAccordion) {
                            accordion.style.display = 'block';
                            accordion.classList.add('active');
                        } else {
                            const catTitle = accordion.querySelector('h3')?.textContent.toLowerCase() || '';
                            if (catTitle.includes(query)) {
                                accordion.style.display = 'block';
                            } else {
                                accordion.style.display = 'none';
                            }
                        }
                    } else {
                        accordion.style.display = 'block';
                        cards.forEach(c => c.style.display = 'block');
                    }
                });
            });
        }
    });
</script>
@endpush
@endsection

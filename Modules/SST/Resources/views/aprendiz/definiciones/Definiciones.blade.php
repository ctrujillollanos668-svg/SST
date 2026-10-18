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

        @php
            $rutasEventos = [
                'accidentes' => route('SST.aprendiz.tipos_eventos.accidentes.index'),
                'incidentes' => route('SST.aprendiz.tipos_eventos.incidentes.index'),
                'riesgos' => route('SST.aprendiz.tipos_eventos.riesgos.index'),
                'actos_inseguros' => route('SST.aprendiz.tipos_eventos.actos_inseguros.index'),
                'condiciones_inseguras' => route('SST.aprendiz.tipos_eventos.condiciones_inseguras.index'),
                'lesiones' => route('SST.aprendiz.tipos_eventos.lesiones.index'),
                'tipos_emergencias' => route('SST.aprendiz.tipos_eventos.tipos_emergencias.index'),
            ];
        @endphp

        @foreach($categorias as $key => $cat)
            @php
                $totalItems = $cat['items']->count();
                $rutaEvento = $cat['ruta'] ?? ($rutasEventos[$key] ?? '#');
            @endphp
            <div class="accordion-item bg-white rounded-2xl border border-slate-200/80 shadow-2xs transition-all hover:border-slate-300 overflow-hidden" data-category="{{ $key }}" id="cat-{{ $key }}">
                
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
                        <a href="{{ $rutaEvento }}" onclick="event.stopPropagation()" class="w-9 h-9 rounded-xl bg-orange-50 hover:bg-orange-100 text-orange-700 border border-orange-200/90 hover:border-orange-300 flex items-center justify-center shadow-xs transition active:scale-95 text-xs" title="Ver eventos de {{ $cat['titulo'] }}">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        </a>
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
                                    @php
                                        $gravValor = ($item->tipo_gravedad === 'Otro' && $item->tipo_otro) ? $item->tipo_otro : $item->tipo_gravedad;
                                        $grav = strtolower($gravValor ?? '');
                                        $badgeGravedad = 'bg-slate-100 text-slate-600 border-slate-200';
                                        if (str_contains($grav, 'leve')) {
                                            $badgeGravedad = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                        } elseif (str_contains($grav, 'moderado')) {
                                            $badgeGravedad = 'bg-amber-50 text-amber-700 border-amber-200';
                                        } elseif (str_contains($grav, 'grave')) {
                                            $badgeGravedad = 'bg-orange-50 text-orange-700 border-orange-200';
                                        } elseif (str_contains($grav, 'mortal') || str_contains($grav, 'muy')) {
                                            $badgeGravedad = 'bg-rose-50 text-rose-700 border-rose-200';
                                        } elseif ($gravValor) {
                                            $badgeGravedad = 'bg-indigo-50 text-indigo-700 border-indigo-200';
                                        }
                                    @endphp
                                    <div class="item-definicion bg-white p-5 rounded-2xl border border-slate-200/90 shadow-2xs hover:shadow-md hover:border-orange-300 transition-all space-y-3.5 min-w-0 w-full cursor-pointer relative group/card"
                                         onclick="openDetailModal(this)"
                                         data-title="{{ $item->nombre }}"
                                         data-modulo="{{ $item->modulo_pertenece ?? 'General' }}"
                                         data-desc="{{ $item->descripcion ?? 'Sin descripción técnica disponible actualmente.' }}"
                                         data-prevenir="{{ $item->como_prevenir ?? 'No registrada.' }}"
                                         data-gravedad="{{ $gravValor ?? '' }}"
                                         data-gravedad-class="{{ $badgeGravedad }}"
                                         data-category="{{ $cat['titulo'] }}">
                                        
                                        <!-- Encabezado de la Tarjeta: Título y Badges -->
                                        <div class="flex flex-wrap items-center justify-between gap-2.5 pb-2.5 border-b border-slate-100">
                                            <h4 class="font-bold text-slate-800 text-sm sm:text-base nombre-concepto flex items-center gap-2 min-w-0">
                                                <span class="w-2.5 h-2.5 rounded-full bg-orange-500 shrink-0"></span>
                                                <span class="break-words group-hover/card:text-orange-600 transition-colors">{{ $item->nombre }}</span>
                                            </h4>
                                            
                                            <div class="flex items-center gap-2 shrink-0">
                                                @if($gravValor)
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-bold border {{ $badgeGravedad }}">
                                                        <i class="fa-solid fa-triangle-exclamation text-[10px]"></i>
                                                        <span>Gravedad: {{ $gravValor }}</span>
                                                    </span>
                                                @endif
                                                @if($item->modulo_pertenece)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                                        {{ $item->modulo_pertenece }}
                                                    </span>
                                                @endif
                                                <span class="text-[11px] text-orange-500 opacity-0 group-hover/card:opacity-100 transition-opacity font-semibold items-center gap-1 hidden sm:inline-flex">
                                                    <i class="fa-regular fa-eye text-[10px]"></i> Ver detalle
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Bloques / Cuadritos de Campos -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-0.5">
                                            <!-- Cuadrito 1: Definición Técnica -->
                                            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 flex flex-col justify-start">
                                                <span class="text-[10px] font-black uppercase tracking-wider text-slate-500 flex items-center gap-1.5 mb-1.5">
                                                    <i class="fa-solid fa-book-open text-orange-500 text-[11px]"></i>
                                                    Definición Técnica
                                                </span>
                                                <p class="text-xs text-slate-600 leading-relaxed descripcion-concepto break-words line-clamp-3">
                                                    {{ $item->descripcion ?? 'Sin descripción técnica disponible actualmente.' }}
                                                </p>
                                            </div>

                                            <!-- Cuadrito 2: ¿Cómo Prevenir? -->
                                            <div class="p-3.5 rounded-xl bg-emerald-50/50 border border-emerald-200/80 flex flex-col justify-start">
                                                <span class="text-[10px] font-black uppercase tracking-wider text-emerald-800 flex items-center gap-1.5 mb-1.5">
                                                    <i class="fa-solid fa-shield-halved text-emerald-600 text-[11px]"></i>
                                                    ¿Cómo Prevenir?
                                                </span>
                                                <p class="text-xs text-slate-600 leading-relaxed prevenir-concepto break-words line-clamp-3">
                                                    {{ $item->como_prevenir ?: 'No se han registrado medidas preventivas específicas.' }}
                                                </p>
                                            </div>
                                        </div>
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
    <div class="bg-white rounded-2xl shadow-2xl border border-slate-100 max-w-xl w-full p-5 sm:p-6 transform transition-transform duration-200 scale-95" onclick="event.stopPropagation()">
        
        <!-- Encabezado con Icono Naranja, Título y Botón X -->
        <div class="flex items-center justify-between pb-3.5 border-b border-slate-100">
            <div class="flex items-center gap-2 text-slate-800">
                <i class="fa-solid fa-book-bookmark text-orange-500 text-sm"></i>
                <h3 class="font-bold text-sm tracking-tight" id="modalConceptoCategory">Detalle de Definición</h3>
            </div>
            
            <!-- Botón Cerrar X -->
            <button type="button" onclick="closeDetailModal()" class="w-7 h-7 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center text-xs transition cursor-pointer" title="Cerrar">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Contenido Completo -->
        <div class="pt-4 space-y-4">
            <div class="flex flex-wrap items-center justify-between gap-2 pb-1">
                <h4 id="modalConceptoTitulo" class="font-extrabold text-slate-900 text-base sm:text-lg"></h4>
                <div class="flex items-center gap-2">
                    <span id="modalConceptoGravedad" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold border"></span>
                    <span id="modalConceptoModulo" class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200"></span>
                </div>
            </div>
            
            <div class="space-y-3 max-h-[65vh] overflow-y-auto pr-1">
                <!-- Cuadrito 1 en Modal: Definición -->
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80">
                    <span class="text-[11px] font-black uppercase tracking-wider text-slate-500 flex items-center gap-1.5 mb-2">
                        <i class="fa-solid fa-book-open text-orange-500"></i>
                        Definición Técnica
                    </span>
                    <p id="modalConceptoTexto" class="text-xs sm:text-sm text-slate-700 leading-relaxed whitespace-pre-line break-words"></p>
                </div>

                <!-- Cuadrito 2 en Modal: ¿Cómo Prevenir? -->
                <div class="p-4 rounded-xl bg-emerald-50/60 border border-emerald-200/80">
                    <span class="text-[11px] font-black uppercase tracking-wider text-emerald-800 flex items-center gap-1.5 mb-2">
                        <i class="fa-solid fa-shield-halved text-emerald-600"></i>
                        Medidas de Prevención (¿Cómo Prevenir?)
                    </span>
                    <p id="modalConceptoPrevenir" class="text-xs sm:text-sm text-slate-700 leading-relaxed whitespace-pre-line break-words"></p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

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
        const prevenir = cardElement.getAttribute('data-prevenir') || '';
        const gravedad = cardElement.getAttribute('data-gravedad') || '';
        const gravedadClass = cardElement.getAttribute('data-gravedad-class') || '';
        const modulo = cardElement.getAttribute('data-modulo') || 'General';
        const category = cardElement.getAttribute('data-category') || '';

        document.getElementById('modalConceptoTitulo').textContent = title;
        document.getElementById('modalConceptoTexto').textContent = desc;
        document.getElementById('modalConceptoPrevenir').textContent = prevenir;
        document.getElementById('modalConceptoModulo').textContent = modulo;
        if (category) {
            document.getElementById('modalConceptoCategory').textContent = 'Definición de ' + category;
        }

        const badgeGrav = document.getElementById('modalConceptoGravedad');
        if (gravedad && gravedad.trim() !== '') {
            badgeGrav.className = 'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold border ' + gravedadClass;
            badgeGrav.innerHTML = '<i class="fa-solid fa-triangle-exclamation text-[10px]"></i> Gravedad: ' + gravedad;
            badgeGrav.style.display = 'inline-flex';
        } else {
            badgeGrav.style.display = 'none';
        }

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
                            const prev = card.getAttribute('data-prevenir')?.toLowerCase() || '';
                            const grav = card.getAttribute('data-gravedad')?.toLowerCase() || '';

                            if (name.includes(query) || desc.includes(query) || prev.includes(query) || grav.includes(query)) {
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

        // Si la URL contiene un hash como #cat-accidentes, abrir automáticamente ese acordeón
        if (window.location.hash) {
            const target = document.querySelector(window.location.hash);
            if (target && target.classList.contains('accordion-item')) {
                setTimeout(() => {
                    target.classList.add('active');
                    target.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }, 150);
            }
        }
    });
</script>
@endpush

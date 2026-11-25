@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navegación de páginas" class="flex items-center justify-between">
        {{-- Versión Móvil: Botones Simples --}}
        <div class="flex justify-between flex-1 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-4 py-2 text-xs font-bold text-slate-400 bg-slate-100 border border-slate-200 cursor-not-allowed rounded-xl select-none">
                    Anterior
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-xs font-bold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition shadow-2xs">
                    Anterior
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 ml-3 text-xs font-bold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition shadow-2xs">
                    Siguiente
                </a>
            @else
                <span class="relative inline-flex items-center px-4 py-2 ml-3 text-xs font-bold text-slate-400 bg-slate-100 border border-slate-200 cursor-not-allowed rounded-xl select-none">
                    Siguiente
                </span>
            @endif
        </div>

        {{-- Versión Escritorio: Conteo en Español y Botones Numéricos --}}
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-xs text-slate-500 font-medium">
                    Mostrando del
                    <span class="font-bold text-slate-800">{{ $paginator->firstItem() }}</span>
                    al
                    <span class="font-bold text-slate-800">{{ $paginator->lastItem() }}</span>
                    de
                    <span class="font-bold text-slate-800">{{ $paginator->total() }}</span>
                    resultados
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex items-center gap-1">
                    {{-- Botón Anterior --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="Anterior">
                            <span class="relative inline-flex items-center justify-center w-8 h-8 text-slate-300 bg-slate-50 border border-slate-200 cursor-not-allowed rounded-xl" aria-hidden="true">
                                <i class="fa-solid fa-chevron-left text-[11px]"></i>
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center justify-center w-8 h-8 text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-100 hover:text-[#39A900] transition shadow-2xs" aria-label="Anterior">
                            <i class="fa-solid fa-chevron-left text-[11px]"></i>
                        </a>
                    @endif

                    {{-- Elementos de Paginación (Números y Puntos) --}}
                    @foreach ($elements as $element)
                        {{-- Separador de Puntos --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="relative inline-flex items-center justify-center w-8 h-8 text-xs font-bold text-slate-400 select-none">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Array de Enlaces --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="relative inline-flex items-center justify-center w-8 h-8 text-xs font-bold text-white bg-[#39A900] border border-[#39A900] rounded-xl shadow-xs">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="relative inline-flex items-center justify-center w-8 h-8 text-xs font-bold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-100 hover:text-[#39A900] transition shadow-2xs" aria-label="Ir a la página {{ $page }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Botón Siguiente --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center justify-center w-8 h-8 text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-100 hover:text-[#39A900] transition shadow-2xs" aria-label="Siguiente">
                            <i class="fa-solid fa-chevron-right text-[11px]"></i>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="Siguiente">
                            <span class="relative inline-flex items-center justify-center w-8 h-8 text-slate-300 bg-slate-50 border border-slate-200 cursor-not-allowed rounded-xl" aria-hidden="true">
                                <i class="fa-solid fa-chevron-right text-[11px]"></i>
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif

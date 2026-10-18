@extends('sst::components.layouts.aprendiz')

@section('title', 'Seguimiento de Inspecciones • SST')

@section('content')
<style>
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<div class="max-w-6xl mx-auto space-y-4 pb-12">

    <!-- Mensaje Flash de Éxito -->
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

    <!-- Tarjeta Principal Unificada -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs min-h-[460px] flex flex-col justify-between">
        
        <div>
            <!-- Encabezado Superior -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl border border-amber-200/80 shadow-2xs shrink-0">
                        <i class="fa-regular fa-clock"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-800 tracking-tight">Seguimiento de Inspecciones</h1>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-800 border border-amber-300">
                                Seguimiento Activo
                            </span>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-400 font-medium mt-0.5">Control y verificación de reportes con seguimiento activo</p>
                    </div>
                </div>

            </div>

            <!-- Contenido Principal -->
            <div class="pt-6">

                <!-- Barra de Búsqueda -->
                <div class="mb-5 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-2 text-xs font-bold text-slate-600">
                        <span class="px-3 py-1.5 rounded-xl bg-slate-100 border border-slate-200/80 flex items-center gap-2">
                            <i class="fa-regular fa-clock text-amber-500"></i>
                            <span>Reportes en Seguimiento:</span>
                            <span class="px-1.5 py-0.2 rounded-md bg-amber-100 text-amber-800 text-[10px] font-extrabold">{{ $inspecciones->count() }}</span>
                        </span>
                    </div>

                    <!-- Buscador -->
                    <div class="w-full sm:w-72 relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>
                        <input type="text" id="inputBuscarInspeccion" onkeyup="filtrarInspecciones()" placeholder="Buscar en seguimiento..." 
                            class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition">
                    </div>
                </div>

                <!-- Listado Dinámico de Tarjetas desde la Base de Datos -->
                <div class="space-y-4" id="contenedorInspecciones">
                    @forelse($inspecciones as $item)
                        @php
                            $meta = json_decode($item->observaciones, true);
                            if (!is_array($meta)) {
                                $meta = [
                                    'responsable' => $item->user ? $item->user->name : 'Aprendiz SST',
                                    'lider_unidad' => 'Coordinación SST',
                                    'descripcion' => $item->observaciones ?? 'Sin descripción adicional.',
                                    'seguimiento' => in_array($item->estado, ['pendiente', 'en_proceso']),
                                    'fecha_seguimiento' => null,
                                    'evidencias' => []
                                ];
                            }
                            $evidencias = $meta['evidencias'] ?? [];
                            $fotoPrincipal = !empty($evidencias) ? $evidencias[0] : 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=800&q=80';
                            $totalFotos = count($evidencias);
                            $lugarNombre = $item->lugar ? $item->lugar->nombre : ($meta['lugar_nombre'] ?? 'Ambiente de Formación');
                            $responsable = $meta['responsable'] ?? ($item->user ? $item->user->name : 'Aprendiz SST');
                            $unidad = $meta['lider_unidad'] ?? 'Coordinación SST';
                            $desc = $meta['descripcion'] ?? ($item->observaciones ?? 'Sin descripción');
                            $esSeguimiento = in_array($item->estado, ['pendiente', 'en_proceso']) || (!empty($meta['seguimiento']));
                            $fechaSeguimientoPlazo = !empty($meta['fecha_seguimiento']) ? \Carbon\Carbon::parse($meta['fecha_seguimiento'])->format('d/m/Y') : null;
                            $fechaHora = $item->fecha_inspeccion ? \Carbon\Carbon::parse($item->fecha_inspeccion)->format('d/m/Y') : now()->format('d/m/Y');
                            if ($item->hora_inspeccion) {
                                $fechaHora .= ' - ' . \Carbon\Carbon::parse($item->hora_inspeccion)->format('h:i A');
                            }
                            $payloadItem = [
                                'id' => $item->id_inspeccion,
                                'lugar_id' => $item->lugar_id,
                                'lugar_nombre' => $lugarNombre,
                                'responsable' => $responsable,
                                'lider_unidad' => $unidad !== 'Coordinación SST' ? $unidad : 'Coordinación SST',
                                'fecha_inspeccion' => $item->fecha_inspeccion ? \Carbon\Carbon::parse($item->fecha_inspeccion)->format('Y-m-d') : date('Y-m-d'),
                                'fecha_hora_texto' => $fechaHora,
                                'tipo_inspeccion' => $item->tipo_inspeccion,
                                'descripcion' => $desc !== 'Sin descripción' ? $desc : '',
                                'seguimiento' => $esSeguimiento,
                                'fecha_seguimiento' => !empty($meta['fecha_seguimiento']) ? \Carbon\Carbon::parse($meta['fecha_seguimiento'])->format('Y-m-d') : '',
                                'fecha_seguimiento_formato' => $fechaSeguimientoPlazo,
                                'evidencias' => $evidencias
                            ];
                            $encodedItem = base64_encode(json_encode($payloadItem, JSON_UNESCAPED_UNICODE));
                        @endphp
                        
                        <!-- Tarjeta de Inspección -->
                        <div class="item-card bg-slate-50/70 hover:bg-white p-4 sm:p-5 rounded-3xl border border-slate-200/80 shadow-2xs hover:shadow-md hover:border-orange-300 transition-all flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-5 cursor-pointer group"
                             onclick="abrirModalDetalle(this)"
                             data-raw="{{ $encodedItem }}"
                             data-item="{{ htmlspecialchars(json_encode($payloadItem, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') }}"
                             data-es-seguimiento="{{ $esSeguimiento ? '1' : '0' }}">
                            
                            <!-- Miniatura de Imagen con Contador -->
                            <div class="relative w-16 h-16 sm:w-16 sm:h-16 rounded-2xl overflow-hidden border-2 border-orange-200 bg-slate-900/5 shrink-0 group-hover:border-orange-400 transition shadow-2xs">
                                <img src="{{ $fotoPrincipal }}" alt="Evidencia" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                                @if($totalFotos > 1)
                                    <span class="absolute bottom-1 right-1 px-1.5 py-0.5 rounded-md bg-slate-900/85 text-white text-[8px] font-extrabold flex items-center gap-0.5 shadow-xs">
                                        <i class="fa-solid fa-camera text-orange-400 text-[7px]"></i> +{{ $totalFotos }}
                                    </span>
                                @endif
                            </div>

                            <!-- Cuerpo de la Tarjeta -->
                            <div class="flex-1 flex flex-col justify-between space-y-2.5 w-full">
                                
                                <!-- Fila 1: Título y Estado -->
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <h3 class="font-extrabold text-slate-900 text-sm sm:text-base flex items-center gap-2">
                                        <span class="w-2.5 h-2.5 rounded-full {{ str_contains(strtolower($item->tipo_inspeccion), 'condici') ? 'bg-rose-500' : 'bg-orange-500' }} shrink-0"></span>
                                        <span class="group-hover:text-orange-600 transition-colors">{{ $item->tipo_inspeccion }}</span>
                                    </h3>

                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-extrabold bg-amber-50 text-amber-800 border border-amber-300 shadow-2xs">
                                            <i class="fa-regular fa-clock text-[9px] text-amber-600"></i> 
                                            <span>Seguimiento @if($fechaSeguimientoPlazo)<span class="text-amber-900 font-extrabold">({{ $fechaSeguimientoPlazo }})</span>@endif</span>
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-orange-50 text-orange-700 border border-orange-200/80">
                                            Moderado
                                        </span>
                                    </div>
                                </div>

                                <!-- Fila 2: Chips informativos -->
                                <div class="flex flex-wrap items-center gap-2 text-[11px] font-medium text-slate-600">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-white border border-slate-200/80 shadow-2xs">
                                        <i class="fa-solid fa-location-dot text-orange-500 text-[10px]"></i> {{ $lugarNombre }}
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-white border border-slate-200/80 shadow-2xs">
                                        <i class="fa-regular fa-calendar text-orange-500 text-[10px]"></i> {{ $fechaHora }}
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-white border border-slate-200/80 shadow-2xs">
                                        <i class="fa-solid fa-user text-orange-500 text-[10px]"></i> {{ $responsable }}
                                    </span>
                                    @if(!empty($unidad) && $unidad !== 'Coordinación SST')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-white border border-slate-200/80 shadow-2xs">
                                            <i class="fa-solid fa-building-user text-orange-500 text-[10px]"></i> Líder: {{ $unidad }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Fila 3: Descripción y Acciones -->
                                <div class="flex items-center justify-between gap-3 pt-0.5">
                                    <p class="text-xs text-slate-500 line-clamp-1 flex-1 leading-relaxed">
                                        <strong class="font-bold text-slate-700">Descripción:</strong> {{ $desc }}
                                    </p>
                                    
                                    <div class="flex items-center gap-1.5 shrink-0" onclick="event.stopPropagation()">
                                        <button type="button" onclick="event.stopPropagation(); abrirModalEdicion(this.closest('.item-card'))" class="w-8 h-8 rounded-xl bg-orange-50 hover:bg-orange-100 text-orange-600 border border-orange-200/80 flex items-center justify-center transition cursor-pointer shadow-2xs" title="Editar reporte">
                                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                                        </button>
                                        <form action="{{ route('SST.aprendiz.inspecciones.destroy', $item->id_inspeccion) }}" method="POST" onsubmit="return confirm('¿Está seguro de eliminar esta inspección de la base de datos?')" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200/80 flex items-center justify-center transition cursor-pointer shadow-2xs" title="Eliminar">
                                                <i class="fa-regular fa-trash-can text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center bg-slate-50/60 rounded-3xl border border-dashed border-slate-200">
                            <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-500 border border-amber-100 flex items-center justify-center mx-auto mb-3">
                                <i class="fa-solid fa-clock text-2xl"></i>
                            </div>
                            <h3 class="text-base font-bold text-slate-800">
                                No hay inspecciones con seguimiento activo
                            </h3>
                            <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">
                                Todas las inspecciones se encuentran atendidas o sin seguimiento pendiente en este momento.
                            </p>
                            <div class="mt-4">
                                <a href="{{ route('SST.aprendiz.inspecciones.registrar') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-700 hover:text-orange-600 hover:border-orange-300 transition shadow-2xs">
                                    <i class="fa-solid fa-plus text-orange-500"></i> Registrar nueva inspección
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

</div>

<!-- ============================================================== -->
<!-- MODAL: VER DETALLE / INFORMACIÓN DE INSPECCIÓN (SOLO LECTURA)  -->
<!-- ============================================================== -->
<div id="modalDetalle" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden transition-opacity duration-200" onclick="closeModalDetalle()">
    <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 max-w-xl w-full p-6 transform transition-transform duration-200 scale-95 max-h-[90vh] flex flex-col overflow-hidden" onclick="event.stopPropagation()">
        
        <!-- Encabezado del Modal -->
        <div class="flex items-center justify-between pb-3.5 border-b border-slate-100 shrink-0">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-orange-50 border border-orange-200/80 text-orange-600 flex items-center justify-center text-sm shadow-2xs shrink-0">
                    <i class="fa-solid fa-file-shield"></i>
                </div>
                <div>
                    <h2 class="text-base font-extrabold text-slate-900 leading-tight">
                        Detalle de Inspección
                    </h2>
                    <p class="text-[11px] text-slate-400 font-medium">
                        Información registrada del reporte en seguimiento
                    </p>
                </div>
            </div>
            
            <button type="button" onclick="closeModalDetalle()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center text-xs transition cursor-pointer">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Contenido Desplazable -->
        <div class="pt-4 space-y-4 overflow-y-auto pr-1 flex-1 text-xs">
            
            <!-- Fila 1: Título y Badges -->
            <div class="flex flex-wrap items-center justify-between gap-2">
                <h3 id="detalleTipo" class="text-base sm:text-lg font-extrabold text-slate-900 flex items-center gap-2">
                    <span id="detalleDotTipo" class="w-2.5 h-2.5 rounded-full bg-orange-500 shrink-0"></span>
                    <span id="detalleTipoTexto"></span>
                </h3>

                <div class="flex items-center gap-2">
                    <span id="detalleBadgeEstado" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-extrabold"></span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-orange-50 text-orange-700 border border-orange-200/80">
                        Moderado
                    </span>
                </div>
            </div>

            <!-- Fila 2: Cuadrícula de Datos Clave -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 bg-slate-50/80 p-3.5 rounded-2xl border border-slate-200/80">
                <div class="space-y-0.5">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 flex items-center gap-1">
                        <i class="fa-solid fa-location-dot text-orange-500 text-[9px]"></i> Lugar de Formación
                    </span>
                    <p id="detalleLugar" class="font-extrabold text-slate-800 text-xs sm:text-sm"></p>
                </div>

                <div class="space-y-0.5">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 flex items-center gap-1">
                        <i class="fa-regular fa-calendar text-orange-500 text-[9px]"></i> Fecha de Inspección
                    </span>
                    <p id="detalleFecha" class="font-semibold text-slate-800 text-xs sm:text-sm"></p>
                </div>

                <div class="space-y-0.5 pt-1 border-t border-slate-200/50 sm:border-0 sm:pt-0">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 flex items-center gap-1">
                        <i class="fa-solid fa-user text-orange-500 text-[9px]"></i> Responsable
                    </span>
                    <p id="detalleResponsable" class="font-bold text-orange-600 text-xs sm:text-sm"></p>
                </div>

                <div class="space-y-0.5 pt-1 border-t border-slate-200/50 sm:border-0 sm:pt-0">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 flex items-center gap-1">
                        <i class="fa-solid fa-building-user text-orange-500 text-[9px]"></i> Líder de Unidad
                    </span>
                    <p id="detalleLider" class="font-semibold text-slate-700 text-xs sm:text-sm"></p>
                </div>
            </div>

            <!-- Fila 3: Banner de Seguimiento -->
            <div id="detalleBannerSeguimiento" class="hidden p-3 rounded-2xl bg-amber-50/90 border border-amber-200/90 text-amber-900 flex items-center gap-2.5 shadow-2xs">
                <div class="w-7 h-7 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-xs shrink-0 font-bold">
                    <i class="fa-regular fa-clock"></i>
                </div>
                <div class="text-xs">
                    <p class="font-bold text-amber-950">Inspección con Seguimiento Activo</p>
                    <p class="text-[11px] text-amber-800 font-medium">
                        Fecha límite de verificación: <strong id="detalleFechaSeguimientoTexto" class="font-extrabold text-amber-950"></strong>
                    </p>
                </div>
            </div>

            <!-- Fila 4: Descripción Completa -->
            <div class="space-y-1">
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400">
                    Descripción del Hallazgo
                </label>
                <div class="p-3.5 rounded-2xl bg-white border border-slate-200/80 shadow-2xs leading-relaxed text-slate-700 whitespace-pre-line text-xs font-medium" id="detalleDescripcion">
                </div>
            </div>

            <!-- Fila 5: Galería de Evidencias Fotográficas -->
            <div id="detalleFotosContainer" class="space-y-1.5 hidden">
                <div class="flex items-center justify-between">
                    <label class="text-[11px] font-bold uppercase tracking-wider text-slate-400 flex items-center gap-1.5">
                        <i class="fa-solid fa-camera text-orange-500"></i> Evidencias Fotográficas
                    </label>
                    <span id="detalleTotalFotosTexto" class="text-[10px] font-bold text-slate-400"></span>
                </div>
                <div id="detalleFotosGrid" class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                </div>
            </div>

        </div>

        <!-- Footer con botones -->
        <div class="flex items-center justify-between gap-3 pt-3.5 border-t border-slate-100 shrink-0">
            <button type="button" onclick="closeModalDetalle()" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition cursor-pointer">
                Cerrar
            </button>

            <button type="button" id="btnEditarDesdeDetalle" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-orange-50 hover:bg-orange-100 active:bg-orange-200 text-orange-700 border border-orange-200/90 text-xs font-bold shadow-xs transition cursor-pointer">
                <i class="fa-solid fa-pen-to-square text-xs"></i>
                <span>Editar Inspección</span>
            </button>
        </div>

    </div>
</div>

<!-- ================================================================= -->
<!-- MODAL DE EDICIÓN                                                  -->
<!-- ================================================================= -->
<div id="modalEdicion" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center hidden p-3 sm:p-4 overflow-y-auto" onclick="closeModalEdicion()">
    <div class="bg-white rounded-3xl shadow-2xl border-t-[6px] border-orange-500 border-x border-b border-slate-100 w-full max-w-md overflow-hidden animate-in fade-in zoom-in duration-150 my-auto relative" onclick="event.stopPropagation()">

        <div class="p-5 sm:p-6">
            <div class="flex items-center justify-between pb-3.5 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-orange-50 border border-orange-200/80 text-orange-600 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-pen-to-square text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-extrabold text-slate-900 leading-tight">
                            Editar Inspección
                        </h2>
                        <p class="text-[11px] text-slate-400 font-medium">
                            Modificar reporte en seguimiento
                        </p>
                    </div>
                </div>

                <button onclick="closeModalEdicion()" class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition cursor-pointer">
                    <i class="fa-solid fa-xmark text-xs"></i>
                </button>
            </div>

            <form id="formEdicion" action="" method="POST" enctype="multipart/form-data" class="mt-4 space-y-3 text-xs" onsubmit="return validarTipoHallazgo(this)">
                @csrf
                @method('PUT')
                <input type="hidden" id="editInspeccionId" name="id">

                <!-- 1. Responsable -->
                <div class="space-y-1">
                    <label class="block text-[11px] font-bold text-slate-700">Nombre del Responsable *</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-user text-[11px]"></i>
                        </span>
                        <input type="text" name="responsable" id="editInputResponsable" required class="w-full pl-8 pr-3 py-2 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-white focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 transition text-xs font-semibold text-slate-800 placeholder:text-slate-400">
                    </div>
                </div>

                <!-- 2. Lugar y Líder de Unidad (2 columnas) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                    <!-- Lugar de Formación -->
                    <div class="space-y-1">
                        <label class="block text-[11px] font-bold text-slate-700">Lugar de Formación *</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-location-dot text-[11px]"></i>
                            </span>
                            <select name="lugar_id" id="editInputLugar" required class="w-full pl-8 pr-7 py-2 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-white focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 transition text-xs font-semibold text-slate-800 appearance-none cursor-pointer">
                                <option value="" disabled>Ambiente...</option>
                                @forelse($lugares as $lug)
                                    <option value="{{ $lug->id_lugar }}">{{ $lug->nombre }}</option>
                                @empty
                                    <option value="" disabled>Sin lugares registrados</option>
                                @endforelse
                            </select>
                            <span class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400 text-xs">
                                <i class="fa-solid fa-chevron-down text-[9px]"></i>
                            </span>
                        </div>
                    </div>

                    <!-- Líder de Unidad -->
                    <div class="space-y-1">
                        <label class="block text-[11px] font-bold text-slate-700">Líder de Unidad</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-building-user text-[11px]"></i>
                            </span>
                            <input type="text" name="lider_unidad" id="editInputLiderUnidad" placeholder="Ej: Ing. Carlos / TIC" class="w-full pl-8 pr-3 py-2 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-white focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 transition text-xs font-semibold text-slate-800 placeholder:text-slate-400">
                        </div>
                    </div>
                </div>

                <!-- 3. Fecha de Inspección -->
                <div class="space-y-1">
                    <label class="block text-[11px] font-bold text-slate-700">Fecha de Inspección *</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-regular fa-calendar text-[11px]"></i>
                        </span>
                        <input type="date" name="fecha_inspeccion" id="editInputFechaInspeccion" required class="w-full pl-8 pr-3 py-2 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-white focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 transition text-xs font-semibold text-slate-800">
                    </div>
                </div>

                <!-- 4. Actos / Condiciones -->
                <div class="space-y-1">
                    <label class="block text-[11px] font-bold text-slate-700">Tipo de Hallazgo *</label>
                    <div class="grid grid-cols-2 gap-2">
                        <div id="editCardActos" onclick="toggleEditReporte('Actos')" class="p-2 rounded-xl border border-slate-200 bg-white hover:border-orange-300 transition-all cursor-pointer select-none flex items-center justify-between">
                            <span class="text-[11px] font-bold text-slate-800">Actos Inseguros</span>
                            <div id="editCheckIndicatorActos" class="w-4 h-4 rounded border border-slate-300 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-check text-[9px] text-white opacity-0"></i>
                            </div>
                            <input type="checkbox" name="check_actos" id="editCheckActos" value="1" class="hidden">
                        </div>

                        <div id="editCardCondiciones" onclick="toggleEditReporte('Condiciones')" class="p-2 rounded-xl border border-slate-200 bg-white hover:border-orange-300 transition-all cursor-pointer select-none flex items-center justify-between">
                            <span class="text-[11px] font-bold text-slate-800">Condiciones Inseguras</span>
                            <div id="editCheckIndicatorCondiciones" class="w-4 h-4 rounded border border-slate-300 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-check text-[9px] text-white opacity-0"></i>
                            </div>
                            <input type="checkbox" name="check_condiciones" id="editCheckCondiciones" value="1" class="hidden">
                        </div>
                    </div>
                </div>

                <!-- 5. Descripción -->
                <div class="space-y-1">
                    <div class="flex items-center justify-between">
                        <label class="block text-[11px] font-bold text-slate-700">Descripción *</label>
                        <span class="text-[10px] font-bold text-slate-400 font-mono" id="editContadorPalabras">0/700 palabras</span>
                    </div>
                    <textarea name="descripcion" id="editInputDescripcion" rows="2" required oninput="contarPalabrasEdit(this)" placeholder="Describa el hallazgo..." class="w-full p-2.5 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-white focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 transition text-xs font-medium text-slate-800 resize-none leading-snug"></textarea>
                </div>

                <!-- 6. Evidencia Fotográfica -->
                <div class="space-y-1">
                    <div class="flex items-center justify-between">
                        <label class="block text-[11px] font-bold text-slate-700">Evidencia Fotográfica</label>
                        <span class="text-[10px] font-bold text-orange-600 font-mono" id="editContadorFotos">0/3 imágenes</span>
                    </div>
                    <input type="file" name="fotos[]" id="editInputFotosFiles" accept="image/*" multiple onchange="manejarFotosEdit(event)" class="hidden">
                    <input type="hidden" name="fotos_base64" id="editInputFotosBase64">
                    
                    <label for="editInputFotosFiles" class="border border-dashed border-slate-300 hover:border-orange-400 hover:bg-orange-50/30 rounded-xl px-3 py-2 flex items-center justify-between cursor-pointer bg-slate-50/40 transition">
                        <span class="text-[11px] font-semibold text-slate-700"><i class="fa-solid fa-camera mr-1 text-orange-500"></i> Cambiar / Añadir fotografías</span>
                        <span class="text-[10px] text-slate-400">Máx. 3</span>
                    </label>
                    <div id="editFotosPreviewContainer" class="flex flex-wrap gap-2 pt-1 hidden"></div>
                </div>

                <!-- 7. Sección Condicional: Fecha de Seguimiento -->
                <div id="editSeccionFechaSeguimiento" class="p-3 rounded-2xl bg-orange-50/80 border border-orange-200/80 space-y-1.5 transition-all">
                    <div class="flex items-center justify-between">
                        <label class="block text-[11px] font-bold text-orange-800">
                            <i class="fa-regular fa-calendar-check mr-1 text-orange-600"></i> Fecha Límite de Seguimiento *
                        </label>
                        <span class="text-[10px] text-orange-600 font-semibold">Plazo de verificación</span>
                    </div>
                    <input type="date" name="fecha_seguimiento" id="editInputFechaSeguimiento" class="w-full px-3 py-1.5 rounded-xl border border-orange-200 bg-white focus:outline-none focus:border-orange-500 text-xs font-semibold text-slate-800">
                </div>

                <!-- 8. Fila Inferior: Toggle Seguimiento y Botones -->
                <div class="flex items-center justify-between pt-2.5 border-t border-slate-100">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="seguimiento" id="editCheckSeguimiento" value="1" onchange="toggleEditSeguimientoCalendario(this)" class="w-4 h-4 rounded text-orange-500 focus:ring-orange-400 border-slate-300 accent-orange-500 cursor-pointer">
                        <span class="text-[11px] font-bold text-slate-700">En Seguimiento</span>
                    </label>

                    <div class="flex items-center gap-2">
                        <button type="button" onclick="closeModalEdicion()" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold transition cursor-pointer">
                            Cancelar
                        </button>
                        <button type="submit" class="inline-flex items-center justify-center gap-1.5 px-5 py-2 rounded-xl bg-orange-50 hover:bg-orange-100 active:bg-orange-200/70 text-orange-700 border border-orange-200/90 hover:border-orange-300 text-xs font-bold shadow-xs transition cursor-pointer">
                            <i class="fa-solid fa-check text-xs"></i>
                            <span>Guardar</span>
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    let editImagenesCargadas = [];
    let currentCardElementForEdit = null;
    const urlActualizarBase = "{{ url('Sst/aprendiz/inspecciones/actualizar') }}";

    function closeModalEdicion() {
        document.getElementById('modalEdicion').classList.add('hidden');
    }

    function closeModalDetalle() {
        document.getElementById('modalDetalle').classList.add('hidden');
    }

    // Abre el modal para ver la información detallada (solo lectura) al tocar la tarjeta
    function abrirModalDetalle(card) {
        currentCardElementForEdit = card;
        let data;
        const rawBase64 = card.getAttribute('data-raw');
        if (rawBase64) {
            try {
                const decoded = decodeURIComponent(escape(atob(rawBase64)));
                data = JSON.parse(decoded);
            } catch (e) {
                try {
                    data = JSON.parse(atob(rawBase64));
                } catch (e2) {
                    console.error('Error al decodificar base64:', e2);
                }
            }
        }
        if (!data) {
            const raw = card.getAttribute('data-item');
            try {
                data = typeof raw === 'string' ? JSON.parse(raw) : raw;
            } catch (e) {
                console.error('Error al parsear data-item:', e);
                return;
            }
        }
        if (!data) return;

        // Tipo de inspección
        const tipo = data.tipo_inspeccion || 'Inspección de Seguridad';
        document.getElementById('detalleTipoTexto').textContent = tipo;
        const dot = document.getElementById('detalleDotTipo');
        if (tipo.toLowerCase().includes('condici')) {
            dot.className = 'w-2.5 h-2.5 rounded-full bg-rose-500 shrink-0';
        } else {
            dot.className = 'w-2.5 h-2.5 rounded-full bg-orange-500 shrink-0';
        }

        // Estado Badge
        const badgeEstado = document.getElementById('detalleBadgeEstado');
        if (data.seguimiento) {
            badgeEstado.className = 'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-extrabold bg-amber-50 text-amber-800 border border-amber-300';
            badgeEstado.innerHTML = '<i class="fa-regular fa-clock text-[10px]"></i> <span>En Seguimiento</span>';
        } else {
            badgeEstado.className = 'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-extrabold bg-emerald-50 text-emerald-800 border border-emerald-300';
            badgeEstado.innerHTML = '<i class="fa-solid fa-check text-[10px]"></i> <span>Finalizado</span>';
        }

        // Ficha técnica
        document.getElementById('detalleLugar').textContent = data.lugar_nombre || 'Ambiente de Formación';
        document.getElementById('detalleFecha').textContent = data.fecha_hora_texto || (data.fecha_inspeccion || 'Sin fecha');
        document.getElementById('detalleResponsable').textContent = data.responsable || 'Aprendiz SST';
        document.getElementById('detalleLider').textContent = data.lider_unidad || 'Coordinación SST';

        // Banner de seguimiento
        const bannerSeg = document.getElementById('detalleBannerSeguimiento');
        if (data.seguimiento && (data.fecha_seguimiento || data.fecha_seguimiento_formato)) {
            bannerSeg.classList.remove('hidden');
            document.getElementById('detalleFechaSeguimientoTexto').textContent = data.fecha_seguimiento_formato || data.fecha_seguimiento;
        } else {
            bannerSeg.classList.add('hidden');
        }

        // Descripción
        document.getElementById('detalleDescripcion').textContent = data.descripcion || 'Sin observaciones registradas.';

        // Galería de fotos
        const fotos = Array.isArray(data.evidencias) ? data.evidencias : [];
        const contFotos = document.getElementById('detalleFotosContainer');
        const gridFotos = document.getElementById('detalleFotosGrid');
        gridFotos.innerHTML = '';

        if (fotos.length > 0) {
            contFotos.classList.remove('hidden');
            document.getElementById('detalleTotalFotosTexto').textContent = `${fotos.length} foto(s)`;
            fotos.forEach((url, idx) => {
                const itemDiv = document.createElement('div');
                itemDiv.className = 'relative rounded-2xl overflow-hidden border border-slate-200 bg-slate-900/5 aspect-video group cursor-pointer shadow-2xs hover:shadow-md transition';
                itemDiv.innerHTML = `
                    <img src="${url}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" alt="Evidencia #${idx+1}">
                    <span class="absolute bottom-1 right-1 px-1.5 py-0.5 rounded-md bg-slate-900/80 text-white text-[9px] font-bold">Foto #${idx+1}</span>
                `;
                itemDiv.onclick = () => window.open(url, '_blank');
                gridFotos.appendChild(itemDiv);
            });
        } else {
            contFotos.classList.add('hidden');
        }

        // Botón editar desde detalle
        const btnEdit = document.getElementById('btnEditarDesdeDetalle');
        btnEdit.onclick = function() {
            closeModalDetalle();
            abrirModalEdicion(currentCardElementForEdit);
        };

        // Mostrar modal
        document.getElementById('modalDetalle').classList.remove('hidden');
    }

    // Abre el modal con los campos del formulario llenos para la inspección seleccionada
    function abrirModalEdicion(elementOrData) {
        let data;
        if (elementOrData instanceof HTMLElement) {
            const rawBase64 = elementOrData.getAttribute('data-raw');
            if (rawBase64) {
                try {
                    const decoded = decodeURIComponent(escape(atob(rawBase64)));
                    data = JSON.parse(decoded);
                } catch (e) {
                    try {
                        data = JSON.parse(atob(rawBase64));
                    } catch (e2) {
                        console.error('Error al decodificar base64:', e2);
                    }
                }
            }
            if (!data) {
                const raw = elementOrData.getAttribute('data-item');
                try {
                    data = typeof raw === 'string' ? JSON.parse(raw) : raw;
                } catch (e) {
                    console.error('Error al parsear datos de inspección:', e);
                    return;
                }
            }
        } else {
            data = elementOrData;
        }

        if (!data) return;

        document.getElementById('editInspeccionId').value = data.id;
        document.getElementById('formEdicion').action = `${urlActualizarBase}/${data.id}`;
        
        document.getElementById('editInputResponsable').value = data.responsable || '';
        document.getElementById('editInputLugar').value = data.lugar_id || '';
        document.getElementById('editInputLiderUnidad').value = data.lider_unidad || '';
        document.getElementById('editInputFechaInspeccion').value = data.fecha_inspeccion || '';
        document.getElementById('editInputDescripcion').value = data.descripcion || '';
        contarPalabrasEdit(document.getElementById('editInputDescripcion'));

        // Configurar tipo de inspección (Actos / Condiciones)
        const tipo = (data.tipo_inspeccion || '').toLowerCase();
        const tieneActos = tipo.includes('acto');
        const tieneCondiciones = tipo.includes('condici');

        setCheckboxEstado('edit', 'Actos', tieneActos);
        setCheckboxEstado('edit', 'Condiciones', tieneCondiciones);

        // Configurar seguimiento y calendario
        const checkSeg = document.getElementById('editCheckSeguimiento');
        checkSeg.checked = !!data.seguimiento;
        toggleEditSeguimientoCalendario(checkSeg);
        document.getElementById('editInputFechaSeguimiento').value = data.fecha_seguimiento || '';

        // Cargar fotos existentes
        editImagenesCargadas = Array.isArray(data.evidencias) ? [...data.evidencias] : [];
        renderizarPrevisualizacionesEdit();

        document.getElementById('modalEdicion').classList.remove('hidden');
    }

    function setCheckboxEstado(prefix, tipo, activo) {
        const checkbox = document.getElementById(`${prefix}Check${tipo}`);
        const card = document.getElementById(`${prefix}Card${tipo}`);
        const indicator = document.getElementById(`${prefix}CheckIndicator${tipo}`);
        const icon = indicator ? indicator.querySelector('i') : null;

        if (!checkbox || !card || !indicator) return;

        checkbox.checked = activo;
        if (activo) {
            card.classList.add('border-orange-500', 'bg-orange-50/50');
            card.classList.remove('border-slate-200', 'bg-white');
            indicator.classList.add('border-orange-500', 'bg-orange-500');
            indicator.classList.remove('border-slate-300');
            if (icon) icon.classList.remove('opacity-0');
        } else {
            card.classList.remove('border-orange-500', 'bg-orange-50/50');
            card.classList.add('border-slate-200', 'bg-white');
            indicator.classList.remove('border-orange-500', 'bg-orange-500');
            indicator.classList.add('border-slate-300');
            if (icon) icon.classList.add('opacity-0');
        }
    }

    function toggleEditSeguimientoCalendario(checkbox) {
        const seccion = document.getElementById('editSeccionFechaSeguimiento');
        if (checkbox.checked) {
            seccion.classList.remove('hidden');
        } else {
            seccion.classList.add('hidden');
        }
    }

    function toggleEditReporte(tipo) {
        const checkbox = document.getElementById(`editCheck${tipo}`);
        setCheckboxEstado('edit', tipo, !checkbox.checked);
    }

    function validarTipoHallazgo(form) {
        const checkActos = form.querySelector('[name="check_actos"]');
        const checkCond = form.querySelector('[name="check_condiciones"]');
        const actosActivo = checkActos && checkActos.checked;
        const condActivo = checkCond && checkCond.checked;

        if (!actosActivo && !condActivo) {
            alert('Por favor, selecciona al menos un Tipo de Hallazgo (Actos Inseguros o Condiciones Inseguras).');
            return false;
        }
        return true;
    }

    function contarPalabrasEdit(textarea) {
        const texto = textarea.value.trim();
        const numPalabras = texto ? texto.split(/\s+/).length : 0;
        document.getElementById('editContadorPalabras').textContent = `${numPalabras}/700 palabras`;
    }

    // Fotos Edición
    function manejarFotosEdit(event) {
        const files = Array.from(event.target.files);
        files.forEach(file => {
            if (editImagenesCargadas.length < 3) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    editImagenesCargadas.push(e.target.result);
                    renderizarPrevisualizacionesEdit();
                }
                reader.readAsDataURL(file);
            }
        });
    }

    function renderizarPrevisualizacionesEdit() {
        const container = document.getElementById('editFotosPreviewContainer');
        container.innerHTML = '';
        document.getElementById('editContadorFotos').textContent = `${editImagenesCargadas.length}/3 imágenes`;
        document.getElementById('editInputFotosBase64').value = JSON.stringify(editImagenesCargadas);

        if (editImagenesCargadas.length > 0) {
            container.classList.remove('hidden');
            editImagenesCargadas.forEach((src, idx) => {
                const div = document.createElement('div');
                div.className = 'relative inline-block';
                div.innerHTML = `
                    <img src="${src}" class="w-12 h-12 object-cover rounded-lg border border-slate-200">
                    <button type="button" onclick="eliminarFotoEdit(${idx})" class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-rose-500 text-white flex items-center justify-center text-[9px] hover:bg-rose-600 transition shadow-xs cursor-pointer">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                `;
                container.appendChild(div);
            });
        } else {
            container.classList.add('hidden');
        }
    }

    function eliminarFotoEdit(idx) {
        editImagenesCargadas.splice(idx, 1);
        renderizarPrevisualizacionesEdit();
    }

    function filtrarInspecciones() {
        const input = document.getElementById('inputBuscarInspeccion');
        const query = (input ? input.value : '').toLowerCase().trim();
        const cards = document.querySelectorAll('#contenedorInspecciones .item-card');

        cards.forEach(card => {
            const text = card.innerText.toLowerCase();
            if (!query || text.includes(query)) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    }
</script>
@endsection

@extends('sst::components.layouts.aprendiz')

@section('title', 'Accidentes de Trabajo • SST')

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

    <!-- Alerta de Errores de Validación -->
    @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200/80 text-rose-800 shadow-xs">
            <div class="flex items-center gap-2 mb-1.5">
                <i class="fa-solid fa-triangle-exclamation text-rose-600"></i>
                <span class="text-xs font-bold">Por favor verifica los siguientes campos:</span>
            </div>
            <ul class="list-disc list-inside text-xs space-y-0.5 text-rose-700">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Tarjeta Principal Unificada -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs min-h-[460px] flex flex-col justify-between">
        
        <div>
            <!-- Encabezado Superior -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-500 flex items-center justify-center text-xl border border-orange-100/80 shadow-2xs shrink-0">
                        <i class="fa-solid fa-burst"></i>
                    </div>
                    <div>
                        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-800 tracking-tight">Accidentes de Trabajo</h1>
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
                @if($accidentes->count() > 0)
                    <!-- Barra de Búsqueda -->
                    <div class="mb-5 flex flex-col sm:flex-row items-center justify-between gap-3">
                        <div class="w-full sm:w-80 relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </div>
                            <input type="text" id="inputBuscarEvento" placeholder="Buscar accidente..." 
                                class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition">
                        </div>
                        <span class="text-xs font-bold text-slate-400 self-end sm:self-auto">
                            <span id="conteoEventos" class="text-slate-700">{{ $accidentes->count() }}</span> {{ $accidentes->count() == 1 ? 'accidente registrado' : 'accidentes registrados' }}
                        </span>
                    </div>

                    <!-- Listado a lo largo (Horizontal Full-Width Cards) -->
                    <div class="space-y-4" id="contenedorEventos">
                        @foreach($accidentes as $item)
                            @php
                                $data = json_decode($item->descripcion, true);
                                if (!is_array($data)) {
                                    $raw = $item->descripcion ?? '';
                                    preg_match('/Lugar:\s*([^,\n\r]+?)(?=\s*(?:Tipo|Gravedad|Personas|Instructor|Descripci|$))/i', $raw, $mLugar);
                                    preg_match('/Tipo(?:\s*de\s*Accidente)?:\s*([^,\n\r]+?)(?=\s*(?:Gravedad|Personas|Instructor|Descripci|$))/i', $raw, $mTipo);
                                    preg_match('/Gravedad:\s*([^,\n\r]+?)(?=\s*(?:Personas|Instructor|Descripci|$))/i', $raw, $mGrav);
                                    preg_match('/Personas(?:\s*Involucradas)?:\s*([^,\n\r]+?)(?=\s*(?:Instructor|Descripci|$))/i', $raw, $mPers);
                                    preg_match('/Instructor(?:\s*Responsable)?:\s*([^,\n\r]+?)(?=\s*(?:Descripci|$))/i', $raw, $mInst);
                                    preg_match('/Descripci(?:ón|on)(?:\s*Detallada)?:\s*(.*)/is', $raw, $mDesc);

                                    $cleanTipo = preg_replace('/^Accidente:\s*/i', '', $item->nombre);
                                    $cleanTipo = preg_replace('/\s*\([^)]*\)$/', '', $cleanTipo);

                                    $data = [
                                        'tipo_accidente' => !empty($mTipo[1]) ? trim($mTipo[1]) : (!empty($cleanTipo) ? trim($cleanTipo) : 'Accidente de Trabajo'),
                                        'fecha_hora' => $item->created_at ? $item->created_at->format('d/m/Y h:i A') : 'Reciente',
                                        'lugar_formacion' => !empty($mLugar[1]) ? trim($mLugar[1]) : 'Taller / Ambiente',
                                        'gravedad' => !empty($mGrav[1]) ? trim($mGrav[1]) : 'Leve',
                                        'personas_involucradas' => !empty($mPers[1]) ? trim($mPers[1]) : 'Aprendiz',
                                        'instructor_responsable' => !empty($mInst[1]) ? trim($mInst[1]) : '',
                                        'descripcion' => !empty($mDesc[1]) ? trim($mDesc[1]) : $raw,
                                        'evidencia' => null,
                                    ];
                                }
                                $grav = strtolower($data['gravedad'] ?? 'leve');
                                $badgeGrav = match(true) {
                                    str_contains($grav, 'fatal') || str_contains($grav, 'crítico') => 'bg-rose-100 text-rose-800 border-rose-200',
                                    str_contains($grav, 'grave') => 'bg-orange-100 text-orange-800 border-orange-200',
                                    str_contains($grav, 'moderado') => 'bg-amber-100 text-amber-800 border-amber-200',
                                    default => 'bg-emerald-100 text-emerald-800 border-emerald-200'
                                };
                            @endphp
                            @php
                                $fechaRaw = $data['fecha_hora'] ?? '';
                                $fechaFormateada = 'Reciente';
                                if (!empty($fechaRaw)) {
                                    $ts = strtotime($fechaRaw);
                                    if ($ts) {
                                        $fechaFormateada = date('d/m/Y - h:i A', $ts);
                                    } else {
                                        $fechaFormateada = str_replace('T', ' - ', $fechaRaw);
                                    }
                                } elseif ($item->created_at) {
                                    $fechaFormateada = $item->created_at->format('d/m/Y - h:i A');
                                }

                                $evidencias = !empty($data['evidencias']) && is_array($data['evidencias']) ? $data['evidencias'] : (!empty($data['evidencia']) ? [$data['evidencia']] : []);
                                $fotoPrincipal = !empty($evidencias) ? $evidencias[0] : null;
                                $totalFotos = count($evidencias);
                                $estadoItem = strtolower($item->estado ?? 'activo');
                                $tieneRespuesta = !empty($data['respuesta_admin']);
                            @endphp
                            <div class="item-card bg-slate-50/70 hover:bg-white p-4 sm:p-5 rounded-3xl border border-slate-200/80 shadow-2xs hover:shadow-md hover:border-orange-300 transition-all flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-5 cursor-pointer group"
                                 onclick="openDetailModal(this)"
                                 data-title="{{ $data['tipo_accidente'] ?? $item->nombre }}"
                                 data-modulo="{{ $data['lugar_formacion'] ?? 'General' }}"
                                 data-gravedad="{{ $data['gravedad'] ?? 'Leve' }}"
                                 data-personas="{{ $data['personas_involucradas'] ?? 'Aprendiz' }}"
                                 data-instructor="{{ $data['instructor_responsable'] ?? '' }}"
                                 data-fecha="{{ $fechaFormateada }}"
                                 data-evidencia="{{ $fotoPrincipal ?? '' }}"
                                 data-evidencias="{{ json_encode($evidencias) }}"
                                 data-respuesta-admin="{{ json_encode($data['respuesta_admin'] ?? null) }}"
                                 data-estado="{{ $item->estado ?? 'activo' }}"
                                 data-desc="{{ $data['descripcion'] ?? 'Sin descripción adicional.' }}">
                                
                                <!-- Foto a la izquierda pequeña o Icono (Miniatura 60px con badge si hay varias fotos) -->
                                @if(!empty($fotoPrincipal))
                                    <div class="relative w-16 h-16 sm:w-16 sm:h-16 rounded-2xl overflow-hidden border-2 border-orange-200 bg-slate-900/5 shrink-0 group-hover:border-orange-400 transition shadow-2xs">
                                        <img src="{{ $fotoPrincipal }}" alt="Evidencia Fotográfica" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                                        @if($totalFotos > 1)
                                            <span class="absolute bottom-1 right-1 px-1.5 py-0.5 rounded-md bg-slate-900/85 text-white text-[8px] font-extrabold flex items-center gap-0.5 shadow-xs">
                                                <i class="fa-solid fa-camera text-orange-400 text-[7px]"></i> +{{ $totalFotos }}
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-orange-50 to-amber-50 border border-orange-100 flex flex-col items-center justify-center text-orange-500 shrink-0 shadow-2xs">
                                        <i class="fa-solid fa-burst text-2xl"></i>
                                    </div>
                                @endif

                                <!-- Cuerpo estructurado -->
                                <div class="flex-1 flex flex-col justify-between space-y-2.5 w-full">
                                    <!-- Fila 1: Título, Estado SST y Gravedad -->
                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                        <h3 class="font-extrabold text-slate-900 text-base flex items-center gap-2">
                                            <span class="w-2.5 h-2.5 rounded-full bg-orange-500 shrink-0"></span>
                                            <span class="group-hover:text-orange-600 transition-colors">{{ $data['tipo_accidente'] ?? $item->nombre }}</span>
                                        </h3>
                                        <div class="flex items-center gap-1.5 shrink-0">
                                            @if($estadoItem === 'atendido' || $tieneRespuesta)
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-300 shadow-2xs">
                                                    <i class="fa-solid fa-check-double text-[9px] text-emerald-600"></i> Atendido por SST
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-50 text-amber-700 border border-amber-200 shadow-2xs">
                                                    <i class="fa-regular fa-clock text-[9px] text-amber-500"></i> Pendiente
                                                </span>
                                            @endif
                                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-[11px] font-extrabold border {{ $badgeGrav }}">
                                                {{ ucfirst($data['gravedad'] ?? 'Leve') }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Fila 2: Etiquetas de Lugar, Fecha, Afectado e Instructor -->
                                    <div class="flex flex-wrap items-center gap-2 text-xs text-slate-600 font-medium">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-slate-100 text-slate-700 border border-slate-200/80">
                                            <i class="fa-solid fa-location-dot text-orange-500"></i>
                                            <span>{{ $data['lugar_formacion'] ?? 'General' }}</span>
                                        </span>

                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-slate-100 text-slate-700 border border-slate-200/80">
                                            <i class="fa-regular fa-calendar-days text-orange-500"></i>
                                            <span>{{ $fechaFormateada }}</span>
                                        </span>

                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-slate-100 text-slate-700 border border-slate-200/80">
                                            <i class="fa-solid fa-user text-orange-500"></i>
                                            <span>{{ $data['personas_involucradas'] ?? 'Aprendiz' }}</span>
                                        </span>

                                        @if(!empty($data['instructor_responsable']) && $data['instructor_responsable'] !== 'N/A')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-slate-100 text-slate-700 border border-slate-200/80">
                                                <i class="fa-solid fa-chalkboard-user text-orange-500"></i>
                                                <span>Instructor: {{ $data['instructor_responsable'] }}</span>
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Fila 3: Descripción & Botones de Acción -->
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-1.5 border-t border-slate-100/80">
                                        <p class="text-xs text-slate-600 line-clamp-1 flex-1">
                                            <strong class="text-slate-700 font-semibold">Descripción:</strong> {{ $data['descripcion'] ?? 'Sin descripción disponible.' }}
                                        </p>

                                        <div class="flex items-center gap-2 self-end sm:self-auto shrink-0">
                                            <!-- Botón Editar -->
                                            <button type="button" onclick="event.stopPropagation(); openEditModal({{ $item->id_respuesta }}, '{{ addslashes(json_encode($data, JSON_UNESCAPED_UNICODE)) }}')" class="w-8 h-8 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-700 flex items-center justify-center text-xs font-bold border border-amber-200 shadow-2xs transition cursor-pointer" title="Editar reporte">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>

                                            <!-- Botón Eliminar -->
                                            <button type="button" onclick="event.stopPropagation(); confirmDeleteAccidente({{ $item->id_respuesta }}, '{{ addslashes($data['tipo_accidente'] ?? 'Accidente') }}')" class="w-8 h-8 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 flex items-center justify-center text-xs font-bold border border-rose-200 shadow-2xs transition cursor-pointer" title="Eliminar reporte">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div id="noResultsMsg" class="hidden text-center py-12">
                        <p class="text-xs font-bold text-slate-400">No se encontraron accidentes con ese criterio de búsqueda.</p>
                    </div>
                @else
                    <!-- Estado Vacío -->
                    <div class="py-16 sm:py-24 text-center flex flex-col items-center justify-center">
                        <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-300 flex items-center justify-center text-2xl mb-3 shadow-2xs">
                            <i class="fa-solid fa-inbox"></i>
                        </div>
                        <p class="text-xs sm:text-sm font-semibold text-slate-400">
                            No hay accidentes registrados aún.
                        </p>
                    </div>
                @endif
            </div>
        </div>

    </div>

</div>

<!-- ========================================================================= -->
<!-- MODAL: REGISTRAR ACCIDENTE DE TRABAJO (CAMPOS COMPLETOS)                   -->
<!-- ========================================================================= -->
<div id="modalRegistrarOverlay" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-3 sm:p-4 hidden opacity-0 transition-opacity duration-200" onclick="closeRegisterModal()">
    <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 max-w-2xl w-full max-h-[90vh] flex flex-col transform transition-transform duration-200 scale-95 overflow-hidden" onclick="event.stopPropagation()">
        
        <!-- Header del Modal -->
        <div class="p-5 sm:p-6 pb-4 border-b border-slate-100 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-2xl bg-orange-50 text-orange-500 flex items-center justify-center text-lg border border-orange-100/80 shadow-2xs shrink-0">
                    <i class="fa-solid fa-burst"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-base sm:text-lg text-slate-800 tracking-tight">Registrar Accidente de Trabajo</h3>
                    <p class="text-xs text-slate-400 font-medium">Documenta oportunamente el evento</p>
                </div>
            </div>
            
            <button type="button" onclick="closeRegisterModal()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center text-xs transition cursor-pointer">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Formulario con Scroll Interno -->
        <form action="{{ route('SST.aprendiz.tipos_eventos.accidentes.store') }}" method="POST" enctype="multipart/form-data" class="flex-1 overflow-y-auto p-5 sm:p-6 space-y-4">
            @csrf

            <!-- Fila 1: Fecha/Hora y Lugar de Formación -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="block text-[11px] font-extrabold tracking-wider uppercase text-slate-700">
                        FECHA Y HORA DEL ACCIDENTE <span class="text-rose-500">*</span>
                    </label>
                    <input type="datetime-local" name="fecha_hora" required 
                        class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition shadow-2xs">
                </div>

                <div class="space-y-1">
                    <label class="block text-[11px] font-extrabold tracking-wider uppercase text-slate-700">
                        LUGAR DE FORMACIÓN <span class="text-rose-500">*</span>
                    </label>
                    <select name="lugar_formacion" required 
                        class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition shadow-2xs cursor-pointer">
                        <option value="">Seleccione el lugar...</option>
                        @if(isset($lugares) && $lugares->count() > 0)
                            @foreach($lugares as $lugar)
                                <option value="{{ $lugar->nombre }}">{{ $lugar->nombre }}</option>
                            @endforeach
                        @else
                            <option value="Taller de Maquinaria">Taller de Maquinaria</option>
                            <option value="Laboratorio de Agroindustria">Laboratorio de Agroindustria</option>
                            <option value="Ambiente de Formación TIC">Ambiente de Formación TIC</option>
                            <option value="Área de Campo / Pecuaria">Área de Campo / Pecuaria</option>
                            <option value="Zona Administrativa / Pasillos">Zona Administrativa / Pasillos</option>
                        @endif
                    </select>
                </div>
            </div>

            <!-- Fila 2: Tipo de Accidente y Gravedad -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="block text-[11px] font-extrabold tracking-wider uppercase text-slate-700">
                        TIPO DE ACCIDENTE <span class="text-rose-500">*</span>
                    </label>
                    <select name="tipo_accidente" required 
                        class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition shadow-2xs cursor-pointer">
                        <option value="">Seleccione el tipo de accidente...</option>
                        @if(isset($catalogoAccidentes) && $catalogoAccidentes->count() > 0)
                            @foreach($catalogoAccidentes as $acc)
                                <option value="{{ $acc->nombre }}">{{ $acc->nombre }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="block text-[11px] font-extrabold tracking-wider uppercase text-slate-700">
                        GRAVEDAD <span class="text-rose-500">*</span>
                    </label>
                    <select name="gravedad" required 
                        class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition shadow-2xs cursor-pointer">
                        <option value="">Seleccione la gravedad...</option>
                        <option value="Leve">Leve (Atención básica / Primeros auxilios)</option>
                        <option value="Moderado">Moderado (Incapacidad temporal menor)</option>
                        <option value="Grave">Grave (Traslado a centro asistencial / Urgencias)</option>
                        <option value="Fatal">Fatal / Crítico</option>
                    </select>
                </div>
            </div>

            <!-- Personas Involucradas -->
            <div class="space-y-1">
                <label class="block text-[11px] font-extrabold tracking-wider uppercase text-slate-700">
                    PERSONAS INVOLUCRADAS <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="personas_involucradas" required placeholder="Nombres de las personas afectadas..." 
                    class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition shadow-2xs">
            </div>

            <!-- Instructor Responsable -->
            <div class="space-y-1">
                <label class="block text-[11px] font-extrabold tracking-wider uppercase text-slate-700">
                    INSTRUCTOR RESPONSABLE
                </label>
                <input type="text" name="instructor_responsable" placeholder="Nombre del instructor a cargo..." 
                    class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition shadow-2xs">
            </div>

            <!-- Descripción Detallada -->
            <div class="space-y-1">
                <label class="block text-[11px] font-extrabold tracking-wider uppercase text-slate-700">
                    DESCRIPCIÓN DETALLADA <span class="text-rose-500">*</span>
                </label>
                <textarea name="descripcion" rows="3" required placeholder="Describe cómo ocurrió el evento..." 
                    class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition shadow-2xs resize-none"></textarea>
            </div>

            <!-- Evidencia Fotográfica (Hasta 3 imágenes simultáneas) -->
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <label class="block text-[11px] font-extrabold tracking-wider uppercase text-slate-700">
                        EVIDENCIAS FOTOGRÁFICAS <span class="text-slate-400 font-semibold">(Hasta 3 fotos de una vez)</span>
                    </label>
                    <span id="conteoFotosRegistrar" class="text-[10px] font-bold text-slate-400">0 / 3 seleccionadas</span>
                </div>
                
                <div id="dropZoneRegistrar" class="relative border-2 border-dashed border-slate-200 rounded-2xl p-5 text-center hover:border-orange-500 hover:bg-orange-50/20 transition-all bg-slate-50/50 cursor-pointer group" onclick="document.getElementById('inputEvidenciaFile').click()">
                    <input type="file" id="inputEvidenciaFile" name="evidencias[]" multiple accept="image/*" class="hidden" onchange="handleRegistrarFiles(this.files)">
                    <div class="flex flex-col items-center justify-center space-y-2">
                        <div class="w-11 h-11 rounded-2xl bg-white text-slate-400 group-hover:text-orange-500 group-hover:scale-105 flex items-center justify-center text-xl shadow-2xs transition-all border border-slate-200/60">
                            <i class="fa-solid fa-images"></i>
                        </div>
                        <div>
                            <p id="labelEvidenciaTexto" class="text-xs font-bold text-slate-700 group-hover:text-orange-600 transition-colors">
                                Selecciona hasta 3 fotos de una vez o arrástralas aquí
                            </p>
                            <p class="text-[11px] text-slate-400 mt-1 font-medium flex items-center justify-center gap-1">
                                <i class="fa-solid fa-circle-info text-orange-400 text-[10px]"></i>
                                Puedes seleccionar las 3 al mismo tiempo manteniendo presionado <kbd class="px-1.5 py-0.5 rounded bg-slate-200 text-slate-700 font-bold text-[9px]">Ctrl</kbd> o <kbd class="px-1.5 py-0.5 rounded bg-slate-200 text-slate-700 font-bold text-[9px]">Shift</kbd>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Contenedor dinámico de previsualizaciones con botón de eliminar individual -->
                <div id="previewRegistrarGrid" class="hidden grid grid-cols-3 gap-2.5 pt-1"></div>
            </div>

            <!-- Botones de Acción Inferiores -->
            <div class="flex items-center gap-3 pt-3 border-t border-slate-100">
                <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-2xl bg-[#ea580c] hover:bg-[#c2410c] text-white text-xs sm:text-sm font-bold shadow-xs transition cursor-pointer">
                    <i class="fa-solid fa-paper-plane text-xs"></i>
                    <span>Enviar Reporte</span>
                </button>
                <button type="button" onclick="closeRegisterModal()" class="px-5 py-2.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs sm:text-sm font-bold transition cursor-pointer">
                    Cancelar
                </button>
            </div>
        </form>

    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL 2: EDITAR REPORTE DE ACCIDENTE                                       -->
<!-- ========================================================================= -->
<div id="modalEditarOverlay" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-3 sm:p-4 hidden opacity-0 transition-opacity duration-200" onclick="closeEditModal()">
    <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 max-w-2xl w-full max-h-[90vh] flex flex-col transform transition-transform duration-200 scale-95 overflow-hidden" onclick="event.stopPropagation()">
        
        <!-- Header del Modal -->
        <div class="p-5 sm:p-6 pb-4 border-b border-slate-100 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg border border-amber-100/80 shadow-2xs shrink-0">
                    <i class="fa-solid fa-pen-to-square"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-base sm:text-lg text-slate-800 tracking-tight">Editar Reporte de Accidente</h3>
                    <p class="text-xs text-slate-400 font-medium">Modifica la información registrada</p>
                </div>
            </div>
            
            <button type="button" onclick="closeEditModal()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center text-xs transition cursor-pointer">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Formulario con Scroll Interno -->
        <form id="formEditAccidente" action="" method="POST" enctype="multipart/form-data" class="flex-1 overflow-y-auto p-5 sm:p-6 space-y-4">
            @csrf
            @method('PUT')

            <!-- Fila 1: Fecha/Hora y Lugar de Formación -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="block text-[11px] font-extrabold tracking-wider uppercase text-slate-700">
                        FECHA Y HORA DEL ACCIDENTE <span class="text-rose-500">*</span>
                    </label>
                    <input type="datetime-local" id="edit_fecha_hora" name="fecha_hora" required 
                        class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition shadow-2xs">
                </div>

                <div class="space-y-1">
                    <label class="block text-[11px] font-extrabold tracking-wider uppercase text-slate-700">
                        LUGAR DE FORMACIÓN <span class="text-rose-500">*</span>
                    </label>
                    <select id="edit_lugar_formacion" name="lugar_formacion" required 
                        class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition shadow-2xs cursor-pointer">
                        <option value="">Seleccione el lugar...</option>
                        @if(isset($lugares) && $lugares->count() > 0)
                            @foreach($lugares as $lugar)
                                <option value="{{ $lugar->nombre }}">{{ $lugar->nombre }}</option>
                            @endforeach
                        @else
                            <option value="Taller de Maquinaria">Taller de Maquinaria</option>
                            <option value="Laboratorio de Agroindustria">Laboratorio de Agroindustria</option>
                            <option value="Ambiente de Formación TIC">Ambiente de Formación TIC</option>
                            <option value="Área de Campo / Pecuaria">Área de Campo / Pecuaria</option>
                            <option value="Zona Administrativa / Pasillos">Zona Administrativa / Pasillos</option>
                        @endif
                    </select>
                </div>
            </div>

            <!-- Fila 2: Tipo de Accidente y Gravedad -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="block text-[11px] font-extrabold tracking-wider uppercase text-slate-700">
                        TIPO DE ACCIDENTE <span class="text-rose-500">*</span>
                    </label>
                    <select id="edit_tipo_accidente" name="tipo_accidente" required 
                        class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition shadow-2xs cursor-pointer">
                        <option value="">Seleccione el tipo de accidente...</option>
                        @if(isset($catalogoAccidentes) && $catalogoAccidentes->count() > 0)
                            @foreach($catalogoAccidentes as $acc)
                                <option value="{{ $acc->nombre }}">{{ $acc->nombre }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="block text-[11px] font-extrabold tracking-wider uppercase text-slate-700">
                        GRAVEDAD <span class="text-rose-500">*</span>
                    </label>
                    <select id="edit_gravedad" name="gravedad" required 
                        class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition shadow-2xs cursor-pointer">
                        <option value="Leve">Leve (Atención básica / Primeros auxilios)</option>
                        <option value="Moderado">Moderado (Incapacidad temporal menor)</option>
                        <option value="Grave">Grave (Traslado a centro asistencial / Urgencias)</option>
                        <option value="Fatal">Fatal / Crítico</option>
                    </select>
                </div>
            </div>

            <!-- Personas Involucradas -->
            <div class="space-y-1">
                <label class="block text-[11px] font-extrabold tracking-wider uppercase text-slate-700">
                    PERSONAS INVOLUCRADAS <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="edit_personas_involucradas" name="personas_involucradas" required placeholder="Nombres de las personas afectadas..." 
                    class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition shadow-2xs">
            </div>

            <!-- Instructor Responsable -->
            <div class="space-y-1">
                <label class="block text-[11px] font-extrabold tracking-wider uppercase text-slate-700">
                    INSTRUCTOR RESPONSABLE
                </label>
                <input type="text" id="edit_instructor_responsable" name="instructor_responsable" placeholder="Nombre del instructor a cargo..." 
                    class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition shadow-2xs">
            </div>

            <!-- Descripción Detallada -->
            <div class="space-y-1">
                <label class="block text-[11px] font-extrabold tracking-wider uppercase text-slate-700">
                    DESCRIPCIÓN DETALLADA <span class="text-rose-500">*</span>
                </label>
                <textarea id="edit_descripcion" name="descripcion" rows="3" required placeholder="Describe cómo ocurrió el evento..." 
                    class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition shadow-2xs resize-none"></textarea>
            </div>

            <!-- Evidencia Fotográfica (Actual y Nueva) -->
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <label class="block text-[11px] font-extrabold tracking-wider uppercase text-slate-700">
                        EVIDENCIAS FOTOGRÁFICAS <span class="text-slate-400 font-semibold">(Máx. 3 fotos)</span>
                    </label>
                    <span id="conteoFotosEditar" class="text-[10px] font-bold text-slate-400"></span>
                </div>
                
                <!-- Contenedor de fotos actuales registradas -->
                <div id="edit_fotos_actuales_container" class="hidden p-3.5 rounded-2xl bg-slate-50 border border-slate-200 space-y-2.5">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-extrabold text-slate-700 flex items-center gap-1.5 uppercase tracking-wider">
                            <i class="fa-solid fa-images text-orange-500"></i> Fotos Actuales Guardadas
                        </span>
                        <span id="edit_fotos_actuales_badge" class="text-[10px] font-bold text-orange-600 bg-orange-50 px-2.5 py-0.5 rounded-lg border border-orange-200"></span>
                    </div>
                    
                    <!-- Contenedor oculto de inputs de fotos a conservar -->
                    <div id="edit_fotos_conservadas_inputs"></div>

                    <!-- Cuadrícula de fotos actuales con botón para quitar cada una -->
                    <div id="edit_fotos_actuales_grid" class="grid grid-cols-3 gap-2.5"></div>
                    <p class="text-[10px] text-slate-400 font-medium">Puedes presionar <span class="text-rose-500 font-bold">✕</span> sobre cualquier foto actual para quitarla o cambiarla.</p>
                </div>

                <!-- Input para subir nuevas fotos (Dropzone y Selección Múltiple) -->
                <div id="dropZoneEditar" class="relative border-2 border-dashed border-slate-200 rounded-2xl p-4 text-center hover:border-orange-500 hover:bg-orange-50/20 transition-all bg-slate-50/50 cursor-pointer group" onclick="document.getElementById('edit_evidencia_file').click()">
                    <input type="file" id="edit_evidencia_file" name="evidencias[]" multiple accept="image/*" class="hidden" onchange="handleEditarFiles(this.files)">
                    <div class="flex flex-col items-center justify-center space-y-1.5 text-slate-500 group-hover:text-orange-600 transition-colors">
                        <div class="w-9 h-9 rounded-xl bg-white flex items-center justify-center text-base shadow-2xs border border-slate-200/60 text-slate-400 group-hover:text-orange-500 group-hover:scale-105 transition-all">
                            <i class="fa-solid fa-plus text-orange-500"></i>
                        </div>
                        <span id="labelEditEvidenciaTexto" class="text-xs font-bold text-slate-700 group-hover:text-orange-600">Agregar o subir nuevas fotos (puedes seleccionarlas o arrastrarlas)</span>
                        <p id="labelEditCupoDisponibles" class="text-[10px] text-slate-400 font-medium flex items-center justify-center gap-1">
                            <i class="fa-solid fa-circle-info text-orange-400 text-[9px]"></i>
                            Puedes adjuntar hasta 3 fotos en total
                        </p>
                    </div>
                </div>

                <!-- Contenedor dinámico de previsualizaciones de nuevas fotos en edición -->
                <div id="previewEditarGrid" class="hidden grid grid-cols-3 gap-2.5 pt-1"></div>
            </div>

            <!-- Botones de Acción Inferiores -->
            <div class="flex items-center gap-3 pt-3 border-t border-slate-100">
                <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-2xl bg-[#ea580c] hover:bg-[#c2410c] text-white text-xs sm:text-sm font-bold shadow-xs transition cursor-pointer">
                    <i class="fa-solid fa-check text-xs"></i>
                    <span>Guardar Cambios</span>
                </button>
                <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs sm:text-sm font-bold transition cursor-pointer">
                    Cancelar
                </button>
            </div>
        </form>

    </div>
</div>

<!-- Formulario Oculto para Eliminar -->
<form id="formDeleteAccidente" action="" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

<!-- Modal 4: Alerta Personalizada para Confirmar Eliminación -->
<div id="modalEliminarOverlay" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden opacity-0 transition-opacity duration-200" onclick="closeDeleteModal()">
    <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 max-w-md w-full p-6 text-center transform transition-transform duration-200 scale-95 overflow-hidden space-y-4" onclick="event.stopPropagation()">
        
        <!-- Icono de Advertencia -->
        <div class="w-16 h-16 rounded-3xl bg-rose-50 text-rose-600 border border-rose-100 flex items-center justify-center text-2xl mx-auto shadow-sm">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>

        <div>
            <h3 class="text-lg font-extrabold text-slate-800 tracking-tight">¿Eliminar Reporte de Accidente?</h3>
            <p class="text-xs text-slate-500 font-medium mt-1.5 leading-relaxed">
                Estás a punto de eliminar el reporte de <span id="deleteModalTitulo" class="font-bold text-rose-600"></span>. Esta acción no se puede deshacer y borrará también las evidencias fotográficas asociadas.
            </p>
        </div>

        <!-- Botones de Acción -->
        <div class="flex items-center justify-center gap-3 pt-2">
            <button type="button" onclick="closeDeleteModal()" class="px-5 py-2.5 rounded-2xl bg-slate-100 hover:bg-slate-200 active:scale-95 text-slate-700 text-xs sm:text-sm font-bold transition cursor-pointer">
                Cancelar
            </button>
            <button type="button" id="btnConfirmarEliminar" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-rose-600 hover:bg-rose-700 active:scale-95 text-white text-xs sm:text-sm font-bold shadow-xs transition cursor-pointer">
                <i class="fa-solid fa-trash-can text-xs"></i>
                <span>Sí, eliminar reporte</span>
            </button>
        </div>
    </div>
</div>

<!-- Modal 3: Detalle Completo con Galería de Fotos -->
<div id="modalDetalleOverlay" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden opacity-0 transition-opacity duration-200" onclick="closeDetailModal()">
    <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 max-w-xl w-full p-6 transform transition-transform duration-200 scale-95 max-h-[90vh] flex flex-col overflow-hidden" onclick="event.stopPropagation()">
        
        <div class="flex items-center justify-between pb-3.5 border-b border-slate-100 shrink-0">
            <div class="flex items-center gap-2.5 text-slate-800">
                <div class="w-8 h-8 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-burst"></i>
                </div>
                <h3 class="font-bold text-sm tracking-tight">Detalle del Accidente</h3>
            </div>
            
            <button type="button" onclick="closeDetailModal()" class="w-7 h-7 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center text-xs transition cursor-pointer">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="pt-4 space-y-3.5 overflow-y-auto pr-1 flex-1">
            <div class="flex items-center justify-between gap-2">
                <h4 id="modalDetalleTitulo" class="font-extrabold text-slate-900 text-base"></h4>
                <span id="modalDetalleModulo" class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-[10px] font-bold bg-orange-50 text-orange-700 border border-orange-200 shrink-0"></span>
            </div>

            <!-- Galería de Evidencias Fotográficas en Modal Detalle -->
            <div id="modalDetalleGaleriaContainer" class="hidden space-y-1.5">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa-solid fa-camera text-orange-500"></i> Evidencias Registradas
                    </span>
                    <span id="modalDetalleTotalFotos" class="text-[10px] font-bold text-slate-400"></span>
                </div>
                <div id="modalDetalleGaleriaGrid" class="grid gap-2"></div>
            </div>

            <!-- Ficha Técnica de Detalles -->
            <div class="grid grid-cols-2 gap-2 text-xs bg-slate-50 p-3 rounded-2xl border border-slate-200/80">
                <div>
                    <span class="text-slate-400 font-bold block text-[10px] uppercase">Gravedad</span>
                    <span id="modalDetalleGravedad" class="font-extrabold text-slate-800"></span>
                </div>
                <div>
                    <span class="text-slate-400 font-bold block text-[10px] uppercase">Fecha y Hora</span>
                    <span id="modalDetalleFecha" class="font-semibold text-slate-800"></span>
                </div>
                <div>
                    <span class="text-slate-400 font-bold block text-[10px] uppercase">Afectados</span>
                    <span id="modalDetallePersonas" class="font-semibold text-slate-800 truncate block"></span>
                </div>
                <div>
                    <span class="text-slate-400 font-bold block text-[10px] uppercase">Instructor</span>
                    <span id="modalDetalleInstructor" class="font-semibold text-slate-800 truncate block"></span>
                </div>
            </div>
            
            <div>
                <span class="text-slate-400 font-bold block text-[10px] uppercase mb-1">Descripción del Accidente</span>
                <p id="modalDetalleTexto" class="text-xs text-slate-700 leading-relaxed whitespace-pre-line bg-white p-3 rounded-xl border border-slate-200/60"></p>
            </div>

            <!-- Bloque de Respuesta y Atención del Administrador SST -->
            <div id="modalDetalleRespuestaContainer" class="hidden rounded-2xl bg-gradient-to-br from-emerald-50/90 to-teal-50/70 border border-emerald-200 p-4 space-y-2.5 shadow-2xs">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 text-emerald-900 font-extrabold text-xs">
                        <span class="w-6 h-6 rounded-lg bg-emerald-600 text-white flex items-center justify-center text-[11px] shadow-2xs">
                            <i class="fa-solid fa-clipboard-check"></i>
                        </span>
                        <span>Respuesta y Medidas Tomadas por SST</span>
                    </div>
                    <span id="modalDetalleRespuestaFecha" class="text-[10px] font-bold text-emerald-700 bg-emerald-100/90 px-2.5 py-0.5 rounded-lg border border-emerald-200"></span>
                </div>

                <div class="space-y-2 text-xs">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-emerald-800/80 block">Protocolo de Atención Aplicado:</span>
                        <p id="modalDetalleRespuestaProtocolo" class="font-extrabold text-emerald-950 text-xs"></p>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-emerald-800/80 block">Medidas y Procedimientos Ejecutados:</span>
                        <p id="modalDetalleRespuestaMedidas" class="text-slate-700 whitespace-pre-line bg-white/95 p-3 rounded-xl border border-emerald-200/80 leading-relaxed font-medium shadow-2xs"></p>
                    </div>
                    <div class="flex items-center justify-between pt-1.5 border-t border-emerald-200/60 text-[10px] text-emerald-800">
                        <span class="font-medium">Atendido por: <strong id="modalDetalleRespuestaAdmin" class="font-bold text-emerald-950"></strong></span>
                        <span class="inline-flex items-center gap-1 font-extrabold text-emerald-700 bg-emerald-100/60 px-2 py-0.5 rounded-md">
                            <i class="fa-solid fa-circle-check"></i> Reporte Atendido
                        </span>
                    </div>
                </div>
            </div>

            <!-- Bloque informativo cuando aún está pendiente de atención -->
            <div id="modalDetalleSinRespuestaContainer" class="rounded-2xl bg-amber-50/80 border border-amber-200/80 p-3.5 flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center text-xs shrink-0 font-bold border border-amber-200/60">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-amber-900">Reporte en Proceso de Revisión por SST</p>
                    <p class="text-[11px] text-amber-700 font-medium">El equipo de SST revisará este reporte y documentará el protocolo y medidas correspondientes.</p>
                </div>
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

    // =========================================================================
    // GESTOR DE ARCHIVOS: REGISTRAR ACCIDENTE (HASTA 3 IMÁGENES DE UNA VEZ)
    // =========================================================================
    let registrarFiles = [];

    function handleRegistrarFiles(fileList) {
        if (!fileList || fileList.length === 0) return;
        const newFiles = Array.from(fileList);

        for (let f of newFiles) {
            if (registrarFiles.length < 3) {
                // Evitar duplicar el mismo archivo
                if (!registrarFiles.some(existing => existing.name === f.name && existing.size === f.size)) {
                    registrarFiles.push(f);
                }
            }
        }

        if (registrarFiles.length > 3) {
            registrarFiles = registrarFiles.slice(0, 3);
        }

        syncRegistrarInput();
        renderRegistrarPreview();
    }

    function removeRegistrarFile(index) {
        registrarFiles.splice(index, 1);
        syncRegistrarInput();
        renderRegistrarPreview();
    }

    function syncRegistrarInput() {
        const input = document.getElementById('inputEvidenciaFile');
        const dt = new DataTransfer();
        registrarFiles.forEach(f => dt.items.add(f));
        input.files = dt.files;
    }

    function renderRegistrarPreview() {
        const grid = document.getElementById('previewRegistrarGrid');
        const label = document.getElementById('labelEvidenciaTexto');
        const conteo = document.getElementById('conteoFotosRegistrar');
        grid.innerHTML = '';

        conteo.textContent = `${registrarFiles.length} / 3 seleccionadas`;

        if (registrarFiles.length === 0) {
            grid.classList.add('hidden');
            label.textContent = 'Selecciona hasta 3 fotos de una vez o arrástralas aquí';
            label.classList.remove('text-orange-600');
            conteo.classList.remove('text-orange-600');
            return;
        }

        label.textContent = `${registrarFiles.length} foto(s) lista(s) para enviar`;
        label.classList.add('text-orange-600');
        conteo.classList.add('text-orange-600');
        grid.classList.remove('hidden');

        registrarFiles.forEach((file, idx) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const card = document.createElement('div');
                card.className = 'relative rounded-2xl overflow-hidden border-2 border-orange-400 aspect-square shadow-2xs group bg-slate-900';
                card.innerHTML = `
                    <img src="${e.target.result}" alt="Preview ${idx+1}" class="w-full h-full object-cover">
                    <span class="absolute top-1.5 left-1.5 px-1.5 py-0.5 rounded-md bg-slate-900/80 text-white text-[9px] font-extrabold backdrop-blur-xs">
                        #${idx+1}
                    </span>
                    <button type="button" onclick="event.stopPropagation(); removeRegistrarFile(${idx})" class="absolute top-1.5 right-1.5 w-6 h-6 rounded-full bg-rose-600 hover:bg-rose-700 text-white flex items-center justify-center text-[11px] shadow-sm transition cursor-pointer" title="Quitar foto">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                `;
                grid.appendChild(card);
            };
            reader.readAsDataURL(file);
        });
    }

    // =========================================================================
    // GESTOR DE ARCHIVOS: EDITAR ACCIDENTE (FOTOS CONSERVADAS + NUEVAS FOTOS)
    // =========================================================================
    let fotosConservadas = [];
    let editarFiles = [];

    function renderFotosConservadas() {
        const container = document.getElementById('edit_fotos_actuales_container');
        const grid = document.getElementById('edit_fotos_actuales_grid');
        const badge = document.getElementById('edit_fotos_actuales_badge');
        const inputsContainer = document.getElementById('edit_fotos_conservadas_inputs');
        
        grid.innerHTML = '';
        inputsContainer.innerHTML = '';

        if (fotosConservadas.length > 0) {
            badge.textContent = `${fotosConservadas.length} foto(s) guardada(s)`;
            fotosConservadas.forEach((url, idx) => {
                // Input oculto para que Laravel conserve esta foto
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'fotos_conservadas[]';
                hiddenInput.value = url;
                inputsContainer.appendChild(hiddenInput);

                // Tarjeta miniatura con botón para eliminar la foto conservada
                const item = document.createElement('div');
                item.className = 'relative rounded-2xl overflow-hidden border-2 border-slate-300 aspect-square shadow-2xs group bg-slate-900';
                item.innerHTML = `
                    <img src="${url}" alt="Foto ${idx+1}" class="w-full h-full object-cover">
                    <span class="absolute top-1.5 left-1.5 px-1.5 py-0.5 rounded-md bg-slate-900/80 text-white text-[9px] font-bold backdrop-blur-xs">
                        #${idx+1}
                    </span>
                    <a href="${url}" target="_blank" class="absolute inset-0 bg-slate-950/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs">
                        <i class="fa-solid fa-magnifying-glass-plus"></i>
                    </a>
                    <button type="button" onclick="event.stopPropagation(); removeConservadaFile(${idx})" class="absolute top-1.5 right-1.5 w-6 h-6 rounded-full bg-rose-600 hover:bg-rose-700 text-white flex items-center justify-center text-[11px] shadow-sm transition cursor-pointer z-10" title="Eliminar esta foto">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                `;
                grid.appendChild(item);
            });
            container.classList.remove('hidden');
        } else {
            container.classList.add('hidden');
        }

        updateEditarSummary();
    }

    function removeConservadaFile(index) {
        fotosConservadas.splice(index, 1);
        renderFotosConservadas();
    }

    function handleEditarFiles(fileList) {
        if (!fileList || fileList.length === 0) return;
        const newFiles = Array.from(fileList);
        const maxNuevas = Math.max(0, 3 - fotosConservadas.length);

        if (maxNuevas === 0) {
            alert('Ya tienes 3 fotos guardadas. Por favor elimina alguna de las fotos actuales para poder subir nuevas.');
            return;
        }

        for (let f of newFiles) {
            if (editarFiles.length < maxNuevas) {
                if (!editarFiles.some(existing => existing.name === f.name && existing.size === f.size)) {
                    editarFiles.push(f);
                }
            }
        }

        if (editarFiles.length > maxNuevas) {
            editarFiles = editarFiles.slice(0, maxNuevas);
        }

        syncEditarInput();
        renderEditarPreview();
    }

    function removeEditarFile(index) {
        editarFiles.splice(index, 1);
        syncEditarInput();
        renderEditarPreview();
    }

    function syncEditarInput() {
        const input = document.getElementById('edit_evidencia_file');
        const dt = new DataTransfer();
        editarFiles.forEach(f => dt.items.add(f));
        input.files = dt.files;
    }

    function renderEditarPreview() {
        const grid = document.getElementById('previewEditarGrid');
        grid.innerHTML = '';

        if (editarFiles.length === 0) {
            grid.classList.add('hidden');
        } else {
            grid.classList.remove('hidden');
            editarFiles.forEach((file, idx) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const card = document.createElement('div');
                    card.className = 'relative rounded-2xl overflow-hidden border-2 border-orange-400 aspect-square shadow-2xs group bg-slate-900';
                    card.innerHTML = `
                        <img src="${e.target.result}" alt="Preview ${idx+1}" class="w-full h-full object-cover">
                        <span class="absolute top-1.5 left-1.5 px-1.5 py-0.5 rounded-md bg-slate-900/80 text-white text-[9px] font-extrabold backdrop-blur-xs">
                            Nueva #${idx+1}
                        </span>
                        <button type="button" onclick="event.stopPropagation(); removeEditarFile(${idx})" class="absolute top-1.5 right-1.5 w-6 h-6 rounded-full bg-rose-600 hover:bg-rose-700 text-white flex items-center justify-center text-[11px] shadow-sm transition cursor-pointer" title="Quitar foto">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    `;
                    grid.appendChild(card);
                };
                reader.readAsDataURL(file);
            });
        }

        updateEditarSummary();
    }

    function updateEditarSummary() {
        const total = fotosConservadas.length + editarFiles.length;
        const conteo = document.getElementById('conteoFotosEditar');
        const label = document.getElementById('labelEditEvidenciaTexto');
        const cupoInfo = document.getElementById('labelEditCupoDisponibles');
        const cuposLibres = Math.max(0, 3 - fotosConservadas.length);

        conteo.textContent = `${total} / 3 fotos en total (${fotosConservadas.length} guardadas + ${editarFiles.length} nuevas)`;
        conteo.classList.toggle('text-orange-600', total > 0);

        if (cuposLibres === 0) {
            label.textContent = 'Límite de 3 fotos alcanzado (elimina alguna foto arriba para cambiarla o subir otra)';
            label.classList.remove('text-orange-600');
            if (cupoInfo) cupoInfo.textContent = 'No hay cupos disponibles. Quita una foto arriba con ✕ para subir otra.';
        } else {
            label.textContent = `Agregar hasta ${cuposLibres} foto(s) nueva(s) (puedes seleccionarlas o arrastrarlas)`;
            label.classList.add('text-orange-600');
            if (cupoInfo) cupoInfo.textContent = `Puedes agregar hasta ${cuposLibres} foto(s) más para completar 3.`;
        }
    }

    function openEditModal(id, rawData) {
        let data = {};
        try {
            data = JSON.parse(rawData);
        } catch(e) {
            data = {};
        }

        const form = document.getElementById('formEditAccidente');
        form.action = `{{ url('Sst/aprendiz/tipos-eventos/accidentes') }}/${id}`;

        if (data.fecha_hora) {
            let fh = data.fecha_hora.replace(' ', 'T').substring(0, 16);
            document.getElementById('edit_fecha_hora').value = fh;
        }

        if (data.lugar_formacion) {
            document.getElementById('edit_lugar_formacion').value = data.lugar_formacion;
        }

        if (data.tipo_accidente) {
            document.getElementById('edit_tipo_accidente').value = data.tipo_accidente;
        }

        if (data.gravedad) {
            document.getElementById('edit_gravedad').value = data.gravedad;
        }

        document.getElementById('edit_personas_involucradas').value = data.personas_involucradas || '';
        document.getElementById('edit_instructor_responsable').value = data.instructor_responsable || '';
        document.getElementById('edit_descripcion').value = data.descripcion || '';

        // Cargar fotos guardadas actuales
        fotosConservadas = [];
        if (data.evidencias && Array.isArray(data.evidencias) && data.evidencias.length > 0) {
            fotosConservadas = [...data.evidencias];
        } else if (data.evidencia && data.evidencia.trim() !== '') {
            fotosConservadas = [data.evidencia];
        }

        renderFotosConservadas();

        // Reset input de fotos nuevas
        editarFiles = [];
        syncEditarInput();
        renderEditarPreview();

        const overlay = document.getElementById('modalEditarOverlay');
        const modalBox = overlay.querySelector('div');
        overlay.classList.remove('hidden');
        setTimeout(() => {
            overlay.classList.remove('opacity-0');
            modalBox.classList.remove('scale-95');
            modalBox.classList.add('scale-100');
        }, 10);
    }

    function closeEditModal() {
        const overlay = document.getElementById('modalEditarOverlay');
        const modalBox = overlay.querySelector('div');
        overlay.classList.add('opacity-0');
        modalBox.classList.remove('scale-100');
        modalBox.classList.add('scale-95');
        setTimeout(() => overlay.classList.add('hidden'), 200);
    }

    // =========================================================================
    // MODAL DE CONFIRMACIÓN PARA ELIMINAR (ALERTA PERSONALIZADA)
    // =========================================================================
    let idAccidenteAEliminar = null;

    function confirmDeleteAccidente(id, title) {
        idAccidenteAEliminar = id;
        document.getElementById('deleteModalTitulo').textContent = `"${title}"`;

        const overlay = document.getElementById('modalEliminarOverlay');
        const modalBox = overlay.querySelector('div');
        overlay.classList.remove('hidden');
        setTimeout(() => {
            overlay.classList.remove('opacity-0');
            modalBox.classList.remove('scale-95');
            modalBox.classList.add('scale-100');
        }, 10);
    }

    function closeDeleteModal() {
        idAccidenteAEliminar = null;
        const overlay = document.getElementById('modalEliminarOverlay');
        const modalBox = overlay.querySelector('div');
        overlay.classList.add('opacity-0');
        modalBox.classList.remove('scale-100');
        modalBox.classList.add('scale-95');
        setTimeout(() => overlay.classList.add('hidden'), 200);
    }

    document.getElementById('btnConfirmarEliminar')?.addEventListener('click', function () {
        if (idAccidenteAEliminar) {
            const form = document.getElementById('formDeleteAccidente');
            form.action = `{{ url('Sst/aprendiz/tipos-eventos/accidentes') }}/${idAccidenteAEliminar}`;
            form.submit();
        }
    });

    function openDetailModal(cardElement) {
        const title = cardElement.getAttribute('data-title') || '';
        const desc = cardElement.getAttribute('data-desc') || '';
        const modulo = cardElement.getAttribute('data-modulo') || 'General';
        const gravedad = cardElement.getAttribute('data-gravedad') || 'Leve';
        const personas = cardElement.getAttribute('data-personas') || 'Aprendiz';
        const instructor = cardElement.getAttribute('data-instructor') || 'N/A';
        const fecha = cardElement.getAttribute('data-fecha') || 'Reciente';
        const evidenciasRaw = cardElement.getAttribute('data-evidencias') || '[]';

        document.getElementById('modalDetalleTitulo').textContent = title;
        document.getElementById('modalDetalleTexto').textContent = desc;
        document.getElementById('modalDetalleModulo').textContent = modulo;
        document.getElementById('modalDetalleGravedad').textContent = gravedad;
        document.getElementById('modalDetallePersonas').textContent = personas;
        document.getElementById('modalDetalleInstructor').textContent = instructor || 'N/A';
        document.getElementById('modalDetalleFecha').textContent = fecha;

        let evidencias = [];
        try {
            evidencias = JSON.parse(evidenciasRaw);
        } catch(e) {
            evidencias = [];
        }

        const galeriaContainer = document.getElementById('modalDetalleGaleriaContainer');
        const galeriaGrid = document.getElementById('modalDetalleGaleriaGrid');
        const totalFotosBadge = document.getElementById('modalDetalleTotalFotos');
        galeriaGrid.innerHTML = '';

        if (Array.isArray(evidencias) && evidencias.length > 0) {
            totalFotosBadge.textContent = `${evidencias.length} foto(s)`;
            
            // Ajustar columnas dinámicas
            if (evidencias.length === 1) {
                galeriaGrid.className = 'grid grid-cols-1 gap-2';
            } else if (evidencias.length === 2) {
                galeriaGrid.className = 'grid grid-cols-2 gap-2';
            } else {
                galeriaGrid.className = 'grid grid-cols-3 gap-2';
            }

            evidencias.forEach((url, idx) => {
                const item = document.createElement('div');
                const heightClass = evidencias.length === 1 ? 'h-52' : 'h-32 sm:h-36';
                item.className = `relative rounded-2xl overflow-hidden border border-slate-200 bg-slate-900/5 ${heightClass} group shadow-2xs`;
                item.innerHTML = `
                    <img src="${url}" alt="Evidencia ${idx+1}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    <span class="absolute top-2 left-2 px-2 py-0.5 rounded-lg bg-slate-900/80 text-white text-[10px] font-extrabold backdrop-blur-xs">
                        Foto #${idx+1}
                    </span>
                    <a href="${url}" target="_blank" class="absolute bottom-2 right-2 px-2.5 py-1 rounded-xl bg-slate-900/85 hover:bg-slate-900 text-white text-[11px] font-bold backdrop-blur-xs flex items-center gap-1 transition shadow-xs">
                        <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i> Ver
                    </a>
                `;
                galeriaGrid.appendChild(item);
            });

            galeriaContainer.classList.remove('hidden');
        } else {
            galeriaContainer.classList.add('hidden');
        }

        // Gestión de Respuesta del Administrador SST
        const respuestaRaw = cardElement.getAttribute('data-respuesta-admin');
        const respContainer = document.getElementById('modalDetalleRespuestaContainer');
        const sinRespContainer = document.getElementById('modalDetalleSinRespuestaContainer');

        let respuestaAdmin = null;
        if (respuestaRaw && respuestaRaw !== 'null' && respuestaRaw !== '') {
            try {
                respuestaAdmin = JSON.parse(respuestaRaw);
            } catch(e) {
                respuestaAdmin = null;
            }
        }

        if (respuestaAdmin) {
            document.getElementById('modalDetalleRespuestaProtocolo').textContent = respuestaAdmin.protocolo || 'Primeros Auxilios Básicos y Remisión';
            document.getElementById('modalDetalleRespuestaMedidas').textContent = respuestaAdmin.medidas_tomadas || 'Sin observaciones adicionales.';
            document.getElementById('modalDetalleRespuestaFecha').textContent = respuestaAdmin.fecha_atencion || 'Atendido recientemente';
            document.getElementById('modalDetalleRespuestaAdmin').textContent = respuestaAdmin.atendido_por || 'Equipo SST';
            
            respContainer.classList.remove('hidden');
            sinRespContainer.classList.add('hidden');
        } else {
            respContainer.classList.add('hidden');
            sinRespContainer.classList.remove('hidden');
        }

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
            closeEditModal();
            closeDeleteModal();
            closeDetailModal();
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        // Soporte Drag & Drop para Registrar
        const dropZoneReg = document.getElementById('dropZoneRegistrar');
        if (dropZoneReg) {
            ['dragenter', 'dragover'].forEach(eventName => {
                dropZoneReg.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    dropZoneReg.classList.add('border-orange-500', 'bg-orange-50/40');
                });
            });
            ['dragleave', 'drop'].forEach(eventName => {
                dropZoneReg.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    dropZoneReg.classList.remove('border-orange-500', 'bg-orange-50/40');
                });
            });
            dropZoneReg.addEventListener('drop', (e) => {
                if (e.dataTransfer && e.dataTransfer.files) {
                    handleRegistrarFiles(e.dataTransfer.files);
                }
            });
        }

        // Soporte Drag & Drop para Editar
        const dropZoneEdit = document.getElementById('dropZoneEditar');
        if (dropZoneEdit) {
            ['dragenter', 'dragover'].forEach(eventName => {
                dropZoneEdit.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    dropZoneEdit.classList.add('border-orange-500', 'bg-orange-50/40');
                });
            });
            ['dragleave', 'drop'].forEach(eventName => {
                dropZoneEdit.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    dropZoneEdit.classList.remove('border-orange-500', 'bg-orange-50/40');
                });
            });
            dropZoneEdit.addEventListener('drop', (e) => {
                if (e.dataTransfer && e.dataTransfer.files) {
                    handleEditarFiles(e.dataTransfer.files);
                }
            });
        }

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
                        card.style.display = 'flex';
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
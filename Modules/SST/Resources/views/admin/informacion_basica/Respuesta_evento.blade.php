@extends('sst::components.layouts.admin')

@section('title', 'Respuesta y Gestión de Eventos • SST')

@section('content')
<style>
    @keyframes modalPopIn {
        0% { opacity: 0; transform: scale(0.95) translateY(10px); }
        100% { opacity: 1; transform: scale(1) translateY(0); }
    }
    .animate-modal-pop {
        animation: modalPopIn 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

@php
    $categories = [
        'accidentes' => [
            'slug' => 'accidentes',
            'title' => 'Accidentes',
            'subtitle' => 'Accidentes de trabajo reportados por aprendices',
            'icon' => 'fa-burst',
            'color' => 'rose',
            'border_color' => 'border-l-rose-500',
            'icon_bg' => 'bg-rose-50 text-rose-600',
            'badge_bg' => 'bg-rose-100 text-rose-800',
            'items' => $accidentes ?? collect(),
            'tipo_key' => 'tipo_accidente',
            'tipo_label' => 'TIPO ACCIDENTE',
            'empty_text' => 'No hay accidentes reportados por los aprendices aún.'
        ],
        'incidentes' => [
            'slug' => 'incidentes',
            'title' => 'Incidentes',
            'subtitle' => 'Casi-accidentes y situaciones preventivas sin lesiones',
            'icon' => 'fa-triangle-exclamation',
            'color' => 'amber',
            'border_color' => 'border-l-amber-500',
            'icon_bg' => 'bg-amber-50 text-amber-600',
            'badge_bg' => 'bg-amber-100 text-amber-800',
            'items' => $incidentes ?? collect(),
            'tipo_key' => 'tipo_incidente',
            'tipo_label' => 'TIPO INCIDENTE',
            'empty_text' => 'No hay incidentes reportados por los aprendices aún.'
        ],
        'riesgos' => [
            'slug' => 'riesgos',
            'title' => 'Riesgos',
            'subtitle' => 'Factores y condiciones de peligro identificadas',
            'icon' => 'fa-bolt',
            'color' => 'purple',
            'border_color' => 'border-l-purple-500',
            'icon_bg' => 'bg-purple-50 text-purple-600',
            'badge_bg' => 'bg-purple-100 text-purple-800',
            'items' => $riesgos ?? collect(),
            'tipo_key' => 'tipo_riesgo',
            'tipo_label' => 'TIPO RIESGO',
            'empty_text' => 'No hay riesgos identificados reportados por aprendices aún.'
        ],
        'actos_inseguros' => [
            'slug' => 'actos_inseguros',
            'title' => 'Actos Inseguros',
            'subtitle' => 'Comportamientos o prácticas inseguras observadas',
            'icon' => 'fa-person-falling-burst',
            'color' => 'orange',
            'border_color' => 'border-l-orange-500',
            'icon_bg' => 'bg-orange-50 text-orange-600',
            'badge_bg' => 'bg-orange-100 text-orange-800',
            'items' => $actosInseguros ?? collect(),
            'tipo_key' => 'tipo_acto',
            'tipo_label' => 'TIPO ACTO INSEGURO',
            'empty_text' => 'No hay actos inseguros reportados por aprendices aún.'
        ],
        'condiciones_inseguras' => [
            'slug' => 'condiciones_inseguras',
            'title' => 'Condiciones Inseguras',
            'subtitle' => 'Fallas locativas, herramientas o riesgos ambientales',
            'icon' => 'fa-triangle-exclamation',
            'color' => 'sky',
            'border_color' => 'border-l-sky-500',
            'icon_bg' => 'bg-sky-50 text-sky-600',
            'badge_bg' => 'bg-sky-100 text-sky-800',
            'items' => $condicionesInseguras ?? collect(),
            'tipo_key' => 'tipo_condicion',
            'tipo_label' => 'TIPO CONDICIÓN INSEGURA',
            'empty_text' => 'No hay condiciones inseguras reportadas por aprendices aún.'
        ],
        'lesiones' => [
            'slug' => 'lesiones',
            'title' => 'Lesiones',
            'subtitle' => 'Lesiones físicas sufridas durante la formación',
            'icon' => 'fa-heart-pulse',
            'color' => 'red',
            'border_color' => 'border-l-red-500',
            'icon_bg' => 'bg-red-50 text-red-600',
            'badge_bg' => 'bg-red-100 text-red-800',
            'items' => $lesiones ?? collect(),
            'tipo_key' => 'tipo_lesion',
            'tipo_label' => 'TIPO LESIÓN',
            'empty_text' => 'No hay lesiones reportadas por aprendices aún.'
        ],
        'emergencias' => [
            'slug' => 'emergencias',
            'title' => 'Condiciones de Emergencias',
            'subtitle' => 'Situaciones críticas de emergencia o contingencia',
            'icon' => 'fa-fire-extinguisher',
            'color' => 'emerald',
            'border_color' => 'border-l-emerald-500',
            'icon_bg' => 'bg-emerald-50 text-emerald-600',
            'badge_bg' => 'bg-emerald-100 text-emerald-800',
            'items' => $emergencias ?? collect(),
            'tipo_key' => 'tipo_emergencia',
            'tipo_label' => 'TIPO EMERGENCIA',
            'empty_text' => 'No hay condiciones de emergencia reportadas por aprendices aún.'
        ],
    ];

    $totalGeneral = $respuestas->count();
@endphp

<div class="space-y-6 max-w-7xl mx-auto">

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

    <!-- 1. Encabezado General -->
    <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4 transition-all duration-200">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center border border-orange-200/80 shadow-xs shrink-0">
                <i class="fa-solid fa-clipboard-check text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Gestión y Respuesta a Eventos</h1>
                <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">Seguimiento en tiempo real a los 7 tipos de reportes de aprendices, evidencias fotográficas y protocolos correctivos.</p>
            </div>
        </div>

        <div class="flex items-center gap-2 shrink-0">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-orange-50 text-orange-700 text-xs font-extrabold border border-orange-200">
                <span class="w-2 h-2 rounded-full bg-orange-500 animate-pulse"></span>
                <span>{{ $totalGeneral }} Reportes en Total</span>
            </span>
        </div>
    </div>

    <!-- 2. Pestañas de Navegación Rápida entre las 7 Categorías -->
    <div class="bg-slate-100/80 p-1.5 rounded-2xl border border-slate-200/80 overflow-x-auto flex items-center gap-1.5 scrollbar-none">
        @foreach($categories as $slug => $cat)
            @php
                $isFirst = $loop->first;
                $count = $cat['items']->count();
            @endphp
            <button type="button" 
                    onclick="switchEventTab('{{ $slug }}')" 
                    data-tab="{{ $slug }}"
                    class="event-tab-btn inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs whitespace-nowrap transition cursor-pointer {{ $isFirst ? 'bg-white shadow-xs border border-slate-300 text-slate-900 font-black' : 'bg-transparent hover:bg-white/60 text-slate-500 font-bold border border-transparent' }}">
                <i class="fa-solid {{ $cat['icon'] }} text-xs"></i>
                <span>{{ $cat['title'] }}</span>
                <span class="px-1.5 py-0.5 rounded-md text-[10px] font-black {{ $count > 0 ? $cat['badge_bg'] : 'bg-slate-200/70 text-slate-600' }}">
                    {{ $count }}
                </span>
            </button>
        @endforeach
    </div>

    <!-- 3. Paneles de Tablas para las 7 Categorías -->
    @foreach($categories as $slug => $cat)
        @php
            $isFirst = $loop->first;
            $items = $cat['items'];
        @endphp
        <div id="panel_{{ $slug }}" class="event-tab-panel space-y-4 {{ $isFirst ? '' : 'hidden' }}">
            
            <div class="bg-white rounded-3xl border border-slate-200/80 border-l-[6px] {{ $cat['border_color'] }} shadow-xs overflow-hidden">
                
                <!-- Encabezado del Bloque de Categoría -->
                <div class="p-5 sm:p-6 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-2xl {{ $cat['icon_bg'] }} flex items-center justify-center font-bold text-lg shrink-0 border border-slate-100 shadow-2xs">
                            <i class="fa-solid {{ $cat['icon'] }}"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-extrabold text-slate-900">{{ $cat['title'] }}</h2>
                            <p class="text-xs text-slate-400 font-semibold">{{ $cat['subtitle'] }}</p>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                        <!-- Buscador en tiempo real de la tabla -->
                        <div class="relative w-full sm:w-64">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 text-xs">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </div>
                            <input type="text" 
                                   id="buscar_{{ $slug }}" 
                                   onkeyup="filterTableRows('buscar_{{ $slug }}', 'tabla_{{ $slug }}')" 
                                   placeholder="Buscar en {{ strtolower($cat['title']) }}..." 
                                   class="w-full pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition">
                        </div>

                        <span class="text-xs font-black tracking-widest text-slate-400 uppercase text-right sm:text-left self-center">
                            <span class="text-sm font-black text-slate-800" id="count_{{ $slug }}">{{ $items->count() }}</span> REGISTROS
                        </span>
                    </div>
                </div>

                <!-- Tabla Estandarizada y Limpia -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse table-auto" id="tabla_{{ $slug }}">
                        <thead>
                            <tr class="bg-slate-50/90 border-b border-slate-200/80 text-slate-500 text-[10px] font-extrabold uppercase tracking-wider">
                                <th class="py-3 px-2.5 text-center w-8">#</th>
                                <th class="py-3 px-2.5 whitespace-nowrap"><i class="fa-regular fa-calendar mr-1"></i> FECHA / HORA</th>
                                <th class="py-3 px-2.5 whitespace-nowrap"><i class="fa-solid fa-location-dot mr-1"></i> LUGAR</th>
                                <th class="py-3 px-2.5 whitespace-nowrap"><i class="fa-solid {{ $cat['icon'] }} mr-1"></i> {{ $cat['tipo_label'] }}</th>
                                <th class="py-3 px-2 text-center whitespace-nowrap"><i class="fa-solid fa-gauge-high mr-1"></i> GRAVEDAD</th>
                                <th class="py-3 px-2.5 whitespace-nowrap"><i class="fa-solid fa-user-group mr-1"></i> AFECTADOS</th>
                                <th class="py-3 px-2 whitespace-nowrap"><i class="fa-solid fa-chalkboard-user mr-1"></i> INSTRUCTOR</th>
                                <th class="py-3 px-2.5 max-w-[160px]"><i class="fa-regular fa-file-lines mr-1"></i> DESCRIPCIÓN</th>
                                <th class="py-3 px-2 text-center whitespace-nowrap"><i class="fa-regular fa-image mr-1"></i> EVIDENCIA</th>
                                <th class="py-3 px-2 text-center whitespace-nowrap"><i class="fa-solid fa-circle-check mr-1"></i> ESTADO</th>
                                <th class="py-3 px-2.5 text-center whitespace-nowrap"><i class="fa-solid fa-comment-dots mr-1"></i> GESTIÓN</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700 text-xs font-medium">
                            @forelse($items as $index => $item)
                                @php
                                    $data = json_decode($item->descripcion, true);
                                    if (!is_array($data)) {
                                        $raw = $item->descripcion ?? '';
                                        preg_match('/Lugar:\s*([^,\n\r]+?)(?=\s*(?:Tipo|Gravedad|Prioridad|Personas|Instructor|Descripci|$))/i', $raw, $mLugar);
                                        preg_match('/Tipo(?:\s*de\s*\w+)?:\s*([^,\n\r]+?)(?=\s*(?:Gravedad|Prioridad|Personas|Instructor|Descripci|$))/i', $raw, $mTipo);
                                        preg_match('/(?:Gravedad|Prioridad):\s*([^,\n\r]+?)(?=\s*(?:Personas|Instructor|Descripci|$))/i', $raw, $mGrav);
                                        preg_match('/Personas(?:\s*Involucradas)?:\s*([^,\n\r]+?)(?=\s*(?:Instructor|Descripci|$))/i', $raw, $mPers);
                                        preg_match('/Instructor(?:\s*Responsable)?:\s*([^,\n\r]+?)(?=\s*(?:Descripci|$))/i', $raw, $mInst);
                                        preg_match('/Descripci(?:ón|on)(?:\s*Detallada)?:\s*(.*)/is', $raw, $mDesc);

                                        $cleanTitle = preg_replace('/^[^:]+:\s*/i', '', $item->nombre);
                                        $cleanTitle = preg_replace('/\s*\([^)]*\)$/', '', $cleanTitle);

                                        $data = [
                                            $cat['tipo_key'] => !empty($mTipo[1]) ? trim($mTipo[1]) : (!empty($cleanTitle) ? trim($cleanTitle) : $cat['title']),
                                            'fecha_hora' => $item->created_at ? $item->created_at->format('d/m/Y h:i A') : 'Reciente',
                                            'lugar_formacion' => !empty($mLugar[1]) ? trim($mLugar[1]) : 'Instalaciones CEFA',
                                            'lugar_especifico' => '',
                                            'gravedad' => !empty($mGrav[1]) ? trim($mGrav[1]) : 'Media',
                                            'prioridad' => !empty($mGrav[1]) ? trim($mGrav[1]) : 'Media',
                                            'persona_afectada' => !empty($mPers[1]) ? trim($mPers[1]) : 'Aprendiz',
                                            'personas_involucradas' => !empty($mPers[1]) ? trim($mPers[1]) : 'Aprendiz',
                                            'instructor_responsable' => !empty($mInst[1]) ? trim($mInst[1]) : '',
                                            'descripcion' => !empty($mDesc[1]) ? trim($mDesc[1]) : $raw,
                                            'evidencia' => null,
                                            'evidencias' => [],
                                        ];
                                    }

                                    // Formato de Fecha
                                    $fechaRaw = $data['fecha_hora'] ?? '';
                                    $fechaDia = 'Reciente';
                                    $fechaHora = '';
                                    if (!empty($fechaRaw)) {
                                        $ts = strtotime($fechaRaw);
                                        if ($ts) {
                                            $fechaDia = date('d/m/Y', $ts);
                                            $fechaHora = date('h:i A', $ts);
                                        } else {
                                            $partes = explode('T', str_replace(' - ', 'T', $fechaRaw));
                                            $fechaDia = $partes[0] ?? $fechaRaw;
                                            $fechaHora = $partes[1] ?? '';
                                        }
                                    } elseif ($item->created_at) {
                                        $fechaDia = $item->created_at->format('d/m/Y');
                                        $fechaHora = $item->created_at->format('h:i A');
                                    }

                                    // Evidencias (hasta 3 fotos)
                                    $evidencias = !empty($data['evidencias']) && is_array($data['evidencias']) 
                                        ? $data['evidencias'] 
                                        : (!empty($data['evidencia']) ? [$data['evidencia']] : []);

                                    $evidencias = array_map(function($url) {
                                        if (is_string($url) && str_contains($url, 'uploads/evidencias_sst/')) {
                                            return asset('uploads/evidencias_sst/' . basename($url));
                                        }
                                        return $url;
                                    }, $evidencias);
                                    $fotoPrincipal = !empty($evidencias) ? $evidencias[0] : null;
                                    $totalFotos = count($evidencias);

                                    // Gravedad o Prioridad
                                    $gravVal = strtolower($data['gravedad'] ?? $data['prioridad'] ?? 'media');
                                    $badgeGrav = match(true) {
                                        str_contains($gravVal, 'fatal') || str_contains($gravVal, 'crítico') || str_contains($gravVal, 'alta') || str_contains($gravVal, 'urgente') => 'bg-rose-100 text-rose-800 border-rose-200',
                                        str_contains($gravVal, 'grave') => 'bg-orange-100 text-orange-800 border-orange-200',
                                        str_contains($gravVal, 'moderado') || str_contains($gravVal, 'media') => 'bg-amber-100 text-amber-800 border-amber-200',
                                        default => 'bg-emerald-100 text-emerald-800 border-emerald-200'
                                    };

                                    // Tipo específico
                                    $tipoEspecifico = $data[$cat['tipo_key']] ?? $data['tipo_suceso'] ?? null;
                                    if (empty($tipoEspecifico)) {
                                        $tipoEspecifico = preg_replace('/^[^:]+:\s*/i', '', $item->nombre);
                                        $tipoEspecifico = preg_replace('/\s*\([^)]*\)$/', '', $tipoEspecifico);
                                    }

                                    // Estado y Respuesta
                                    $estadoItem = strtolower($item->estado ?? 'activo');
                                    $tieneRespuesta = !empty($data['respuesta_admin']) || $estadoItem === 'atendido';
                                    $afectadoStr = $data['persona_afectada'] ?? $data['personas_involucradas'] ?? 'Personal';
                                    $lugarStr = $data['lugar_formacion'] ?? 'Ambiente';
                                    $lugarEspStr = $data['lugar_especifico'] ?? '';
                                    $descStr = $data['descripcion'] ?? $data['descripcion_original'] ?? 'Sin descripción adicional.';
                                @endphp

                                <tr class="event-row hover:bg-slate-50/80 transition-colors">
                                    <!-- 1. Número -->
                                    <td class="py-3 px-2.5 text-center font-bold text-slate-400">
                                        {{ $index + 1 }}
                                    </td>

                                    <!-- 2. Fecha / Hora -->
                                    <td class="py-3 px-2.5 whitespace-nowrap">
                                        <div class="flex items-center gap-1.5">
                                            <span class="w-6 h-6 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center text-[10px] shrink-0">
                                                <i class="fa-regular fa-clock"></i>
                                            </span>
                                            <div>
                                                <p class="font-bold text-slate-900 leading-tight">{{ $fechaDia }}</p>
                                                @if(!empty($fechaHora))
                                                    <span class="text-[10px] text-slate-400 font-semibold">{{ $fechaHora }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <!-- 3. Lugar -->
                                    <td class="py-3 px-2.5 whitespace-nowrap">
                                        <p class="font-extrabold text-slate-800">{{ $lugarStr }}</p>
                                        @if(!empty($lugarEspStr))
                                            <span class="text-[10px] text-amber-700 bg-amber-50 px-1.5 py-0.5 rounded border border-amber-200/80 inline-block font-semibold">
                                                <i class="fa-solid fa-map-pin mr-0.5 text-[8px]"></i> {{ $lugarEspStr }}
                                            </span>
                                        @endif
                                    </td>

                                    <!-- 4. Tipo Específico -->
                                    <td class="py-3 px-2.5 whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-[11px] font-extrabold border bg-slate-50 text-slate-800 border-slate-200">
                                            <i class="fa-solid {{ $cat['icon'] }} text-orange-500 text-[10px]"></i>
                                            <span>{{ $tipoEspecifico }}</span>
                                        </span>
                                    </td>

                                    <!-- 5. Gravedad / Prioridad -->
                                    <td class="py-3 px-2 text-center whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black border {{ $badgeGrav }}">
                                            {{ ucfirst($data['gravedad'] ?? $data['prioridad'] ?? 'Media') }}
                                        </span>
                                    </td>

                                    <!-- 6. Afectados / Involucrados -->
                                    <td class="py-3 px-2.5 whitespace-nowrap">
                                        <p class="font-bold text-slate-800 text-xs">{{ $afectadoStr }}</p>
                                        @if(!empty($data['personas_involucradas']) && $data['personas_involucradas'] !== $afectadoStr)
                                            <span class="text-[10px] text-slate-400 block truncate max-w-[130px]" title="{{ $data['personas_involucradas'] }}">
                                                Otras: {{ $data['personas_involucradas'] }}
                                            </span>
                                        @endif
                                    </td>

                                    <!-- 7. Instructor -->
                                    <td class="py-3 px-2 whitespace-nowrap">
                                        @if(!empty($instructor) && $instructor !== 'N/A')
                                            <span class="inline-flex items-center gap-1 text-[11px] text-slate-700 font-semibold">
                                                <i class="fa-solid fa-chalkboard-user text-orange-500 text-[10px]"></i>
                                                <span>{{ $instructor }}</span>
                                            </span>
                                        @else
                                            <span class="text-slate-400 text-[11px] italic">No asignado</span>
                                        @endif
                                    </td>

                                    <!-- 8. Descripción Decodificada y Limpia -->
                                    <td class="py-3 px-2.5 max-w-[160px]">
                                        <p class="line-clamp-2 text-[11px] text-slate-600 leading-snug cursor-help" title="{{ $descStr }}">
                                            {{ $descStr }}
                                        </p>
                                    </td>

                                    <!-- 9. Evidencias Fotográficas -->
                                    <td class="py-3 px-2 text-center whitespace-nowrap">
                                        @if($totalFotos > 0)
                                            <button type="button" 
                                                    onclick="showEvidencePhoto({{ json_encode($evidencias) }}, '{{ addslashes($tipoEspecifico) }}')"
                                                    class="inline-flex items-center gap-1 px-2 py-1 rounded-xl bg-orange-50 hover:bg-orange-100 text-orange-700 text-[10px] font-black border border-orange-200/80 shadow-2xs transition cursor-pointer">
                                                <i class="fa-solid fa-camera text-[10px]"></i>
                                                <span>{{ $totalFotos }} {{ $totalFotos == 1 ? 'Foto' : 'Fotos' }}</span>
                                            </button>
                                        @else
                                            <span class="text-[10px] font-semibold text-slate-400 italic">Sin fotos</span>
                                        @endif
                                    </td>

                                    <!-- 10. Estado -->
                                    <td class="py-3 px-2 text-center whitespace-nowrap">
                                        @if($tieneRespuesta)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-300 shadow-2xs">
                                                <i class="fa-solid fa-check-double text-[9px] text-emerald-600"></i> Atendido
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-50 text-amber-700 border border-amber-200 shadow-2xs">
                                                <i class="fa-regular fa-clock text-[9px] text-amber-500"></i> Pendiente
                                            </span>
                                        @endif
                                    </td>

                                    <!-- 11. Gestión / Responder -->
                                    <td class="py-3 px-2.5 text-center whitespace-nowrap">
                                        <button type="button"
                                                onclick="openResponseModal({{ $item->id_respuesta }}, '{{ addslashes($tipoEspecifico) }}', '{{ addslashes($afectadoStr) }}', '{{ addslashes(json_encode($data, JSON_UNESCAPED_UNICODE)) }}', '{{ $item->estado }}')" 
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl bg-orange-50 hover:bg-orange-100 active:bg-orange-200/70 text-orange-800 text-[11px] font-bold border border-orange-200 shadow-2xs transition cursor-pointer">
                                            <i class="fa-solid fa-reply text-[9px]"></i>
                                            <span>{{ $tieneRespuesta ? 'Ver / Editar' : 'Responder' }}</span>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="py-12 text-center text-slate-400">
                                        <div class="w-12 h-12 mx-auto rounded-2xl bg-slate-100 text-slate-300 flex items-center justify-center text-xl mb-2">
                                            <i class="fa-solid fa-inbox"></i>
                                        </div>
                                        <p class="text-xs font-semibold">{{ $cat['empty_text'] }}</p>
                                    </td>
                                </tr>
                            @endforelse

                            <!-- Fila mostrada cuando la búsqueda no da resultados -->
                            <tr class="empty-search-row hidden">
                                <td colspan="11" class="py-8 text-center text-slate-400">
                                    <p class="text-xs font-semibold">No se encontraron resultados para la búsqueda ingresada.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    @endforeach

</div>

<!-- ========================================================================= -->
<!-- MODAL: VISUALIZAR EVIDENCIA FOTOGRÁFICA                                   -->
<!-- ========================================================================= -->
<div id="modalEvidenciaPhoto" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/70 backdrop-blur-xs p-4" onclick="closeEvidencePhoto()">
    <div class="bg-white rounded-3xl max-w-xl w-full p-6 shadow-2xl border border-slate-100 animate-modal-pop overflow-hidden max-h-[90vh] flex flex-col" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between pb-3.5 border-b border-slate-100 mb-4 shrink-0">
            <h3 class="font-extrabold text-slate-900 text-sm flex items-center gap-2">
                <i class="fa-solid fa-camera text-orange-600"></i>
                <span id="photoModalTitle">Evidencias Fotográficas Adjuntas</span>
            </h3>
            <button onclick="closeEvidencePhoto()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition cursor-pointer">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>

        <div id="adminPhotoGalleryContainer" class="flex-1 overflow-y-auto space-y-3">
            <div id="adminPhotoGalleryGrid" class="grid gap-3"></div>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL: RESPUESTA / ATENCIÓN DEL ADMINISTRADOR SST AL EVENTO                -->
<!-- ========================================================================= -->
<div id="modalResponse" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4" onclick="closeResponseModal()">
    <div class="bg-white rounded-3xl max-w-xl w-full p-6 shadow-2xl border border-slate-100 animate-modal-pop max-h-[90vh] flex flex-col overflow-hidden" onclick="event.stopPropagation()">
        
        <!-- Header del Modal -->
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-3 shrink-0">
            <div class="flex items-center gap-2.5">
                <div class="w-10 h-10 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center border border-orange-200/80 shadow-2xs">
                    <i class="fa-solid fa-clipboard-check text-base"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base">Atención y Retroalimentación SST</h3>
                    <p class="text-xs text-slate-400 font-medium" id="modalResponseTitle">Respuesta al reporte</p>
                </div>
            </div>
            <button onclick="closeResponseModal()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition cursor-pointer">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>

        <!-- Formulario Real -->
        <form id="formAdminResponse" action="" method="POST" class="space-y-4 overflow-y-auto pr-1 flex-1">
            @csrf

            <!-- Banner si ya tiene respuesta previa -->
            <div id="modalPreviousResponseAlert" class="hidden rounded-2xl bg-emerald-50 border border-emerald-200 p-3.5 space-y-1">
                <div class="flex items-center gap-2 text-emerald-800 font-extrabold text-xs">
                    <i class="fa-solid fa-circle-check text-emerald-600"></i>
                    <span>Este reporte ya tiene una atención registrada</span>
                </div>
                <p class="text-[11px] text-emerald-700 font-medium" id="modalPreviousResponseInfo"></p>
            </div>

            <!-- Galería de fotos en el modal de respuesta si existen -->
            <div id="modalResponseImgContainer" class="hidden rounded-2xl overflow-hidden border border-slate-200 bg-slate-50 p-2.5 space-y-1.5">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Evidencias Adjuntas del Reporte</span>
                <div id="modalResponseGalleryGrid" class="grid gap-2"></div>
            </div>

            <!-- Detalle original del reporte -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Detalle del Reporte del Aprendiz:</label>
                <div id="modalReportDetail" class="w-full p-3.5 text-xs rounded-xl bg-slate-50 border border-slate-200 font-medium text-slate-700 max-h-36 overflow-y-auto whitespace-pre-line"></div>
            </div>

            <!-- Selector de Protocolo de Atención -->
            <div class="space-y-1">
                <label class="block text-xs font-bold text-slate-700">
                    Protocolo de Atención Aplicado <span class="text-rose-500">*</span>
                </label>
                <select id="modalSelectProtocol" name="protocolo" required class="w-full px-3.5 py-2.5 text-xs rounded-xl bg-white border border-slate-200 text-slate-800 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 font-semibold cursor-pointer shadow-2xs transition">
                    <option value="Primeros Auxilios Básicos y Remisión">Primeros Auxilios Básicos y Remisión</option>
                    <option value="Acción Correctiva Locativa / Mantenimiento">Acción Correctiva Locativa / Mantenimiento</option>
                    <option value="Capacitación y Refuerzo de Seguridad">Capacitación y Refuerzo de Seguridad</option>
                    <option value="Investigación Formal COPASST">Investigación Formal COPASST</option>
                    <option value="Aislamiento preventivo del área">Aislamiento preventivo del área</option>
                    <option value="Evaluación Médica / EPS">Evaluación Médica / EPS</option>
                    <option value="Activación de Brigada de Emergencia">Activación de Brigada de Emergencia</option>
                </select>
            </div>

            <!-- Estado del Caso -->
            <div class="space-y-1">
                <label class="block text-xs font-bold text-slate-700">
                    Estado del Caso <span class="text-rose-500">*</span>
                </label>
                <select id="modalSelectEstado" name="estado" required class="w-full px-3.5 py-2.5 text-xs rounded-xl bg-white border border-slate-200 text-slate-800 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 font-semibold cursor-pointer shadow-2xs transition">
                    <option value="atendido">Atendido / Resuelto (Visible para Aprendiz)</option>
                    <option value="en_proceso">En Proceso / Seguimiento</option>
                </select>
            </div>

            <!-- Observaciones / Medidas Tomadas -->
            <div class="space-y-1">
                <label class="block text-xs font-bold text-slate-700">
                    Observaciones y Medidas Tomadas <span class="text-rose-500">*</span>
                </label>
                <textarea id="modalResponseText" name="medidas_tomadas" required rows="3" placeholder="Describe claramente el procedimiento realizado, remitente o medidas preventivas que verá el aprendiz..." class="w-full px-3.5 py-2.5 text-xs rounded-xl bg-white border border-slate-200 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 font-medium text-slate-800 shadow-2xs transition resize-none"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeResponseModal()" class="px-4 py-2.5 text-xs font-bold text-slate-600 rounded-xl hover:bg-slate-100 transition cursor-pointer">
                    Cancelar
                </button>
                <button type="submit" class="px-5 py-2.5 text-xs font-bold text-white bg-[#ea580c] hover:bg-[#c2410c] rounded-xl shadow-xs transition cursor-pointer flex items-center gap-1.5">
                    <i class="fa-solid fa-paper-plane text-xs"></i>
                    <span>Guardar y Enviar Respuesta</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Cambio entre pestañas de las 7 categorías
    function switchEventTab(targetSlug) {
        document.querySelectorAll('.event-tab-btn').forEach(btn => {
            const isActive = btn.dataset.tab === targetSlug;
            if (isActive) {
                btn.className = 'event-tab-btn inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs whitespace-nowrap transition cursor-pointer bg-white shadow-xs border border-slate-300 text-slate-900 font-black';
            } else {
                btn.className = 'event-tab-btn inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs whitespace-nowrap transition cursor-pointer bg-transparent hover:bg-white/60 text-slate-500 font-bold border border-transparent';
            }
        });

        document.querySelectorAll('.event-tab-panel').forEach(panel => {
            if (panel.id === `panel_${targetSlug}`) {
                panel.classList.remove('hidden');
            } else {
                panel.classList.add('hidden');
            }
        });
    }

    // Buscador en tiempo real de filas en cada tabla
    function filterTableRows(inputId, tableId) {
        const input = document.getElementById(inputId);
        const query = (input ? input.value : '').toLowerCase().trim();
        const rows = document.querySelectorAll(`#${tableId} tbody tr.event-row`);
        let visibleCount = 0;

        rows.forEach(tr => {
            const text = tr.innerText.toLowerCase();
            const match = !query || text.includes(query);
            tr.style.display = match ? '' : 'none';
            if (match) visibleCount++;
        });

        const emptyRow = document.querySelector(`#${tableId} tbody tr.empty-search-row`);
        if (emptyRow) {
            emptyRow.classList.toggle('hidden', visibleCount > 0 || rows.length === 0);
        }
    }

    // Galería Modal de Evidencias Fotográficas
    function showEvidencePhoto(inputData, title) {
        let photos = [];
        if (Array.isArray(inputData)) {
            photos = inputData;
        } else if (typeof inputData === 'string' && inputData.trim() !== '') {
            try {
                const parsed = JSON.parse(inputData);
                photos = Array.isArray(parsed) ? parsed : [inputData];
            } catch(e) {
                photos = [inputData];
            }
        }

        const grid = document.getElementById('adminPhotoGalleryGrid');
        grid.innerHTML = '';

        if (photos.length === 1) {
            grid.className = 'grid grid-cols-1 gap-2';
        } else if (photos.length === 2) {
            grid.className = 'grid grid-cols-2 gap-2';
        } else {
            grid.className = 'grid grid-cols-3 gap-2';
        }

        photos.forEach((url, idx) => {
            const item = document.createElement('div');
            const h = photos.length === 1 ? 'h-64' : 'h-40';
            item.className = `relative rounded-2xl overflow-hidden border border-slate-200 bg-slate-900/5 ${h} group shadow-2xs`;
            item.innerHTML = `
                <img src="${url}" alt="Evidencia ${idx+1}" class="w-full h-full object-cover">
                <span class="absolute top-2 left-2 px-2 py-0.5 rounded-lg bg-slate-900/80 text-white text-[10px] font-extrabold">
                    Foto #${idx+1}
                </span>
                <a href="${url}" target="_blank" class="absolute bottom-2 right-2 px-2.5 py-1 rounded-xl bg-slate-900/85 hover:bg-slate-900 text-white text-[11px] font-bold flex items-center gap-1 transition shadow-xs">
                    <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i> Ver
                </a>
            `;
            grid.appendChild(item);
        });

        document.getElementById('photoModalTitle').innerText = 'Evidencias: ' + title + ` (${photos.length} foto${photos.length > 1 ? 's' : ''})`;
        document.getElementById('modalEvidenciaPhoto').classList.remove('hidden');
    }

    function closeEvidencePhoto() {
        document.getElementById('modalEvidenciaPhoto').classList.add('hidden');
    }

    // Modal de Respuesta / Atención del Administrador SST
    function openResponseModal(id, title, reporter, rawData, currentStatus) {
        document.getElementById('modalResponseTitle').innerText = title;
        
        // Configurar ruta en formulario
        const baseUrl = "{{ url('Sst/admin/informacion-basica/respuesta-eventos') }}";
        document.getElementById('formAdminResponse').action = `${baseUrl}/${id}/atender`;

        let textContent = '';
        let evidenciasUrls = [];
        let respuestaAdmin = null;

        try {
            const parsed = typeof rawData === 'string' ? JSON.parse(rawData) : rawData;
            const tipoReporte = parsed.tipo_accidente || parsed.tipo_incidente || parsed.tipo_riesgo || parsed.tipo_acto || parsed.tipo_condicion || parsed.tipo_lesion || parsed.tipo_emergencia || parsed.tipo_suceso || title;
            const gravedadReporte = parsed.gravedad || parsed.prioridad || 'N/A';
            const personasReporte = parsed.persona_afectada || parsed.personas_involucradas || reporter;
            const lugarReporte = parsed.lugar_formacion || 'N/A';
            const lugarEsp = parsed.lugar_especifico ? ` (Punto: ${parsed.lugar_especifico})` : '';

            textContent = `📍 Lugar: ${lugarReporte}${lugarEsp}\n`
                        + `💼 Tipo: ${tipoReporte}\n`
                        + `🩺 Nivel / Gravedad: ${gravedadReporte}\n`
                        + `👥 Afectado / Involucrados: ${personasReporte}\n`
                        + (parsed.instructor_responsable ? `👨‍🏫 Instructor: ${parsed.instructor_responsable}\n` : '')
                        + `📅 Fecha: ${parsed.fecha_hora || 'N/A'}\n\n`
                        + `📝 Descripción: ${parsed.descripcion || parsed.descripcion_original || 'Sin descripción adicional'}`;
            
            if (parsed.evidencias && Array.isArray(parsed.evidencias)) {
                evidenciasUrls = parsed.evidencias;
            } else if (parsed.evidencia) {
                evidenciasUrls = [parsed.evidencia];
            }

            if (parsed.respuesta_admin) {
                respuestaAdmin = parsed.respuesta_admin;
            }
        } catch(e) {
            textContent = rawData;
        }

        const imgContainer = document.getElementById('modalResponseImgContainer');
        const imgGrid = document.getElementById('modalResponseGalleryGrid');
        imgGrid.innerHTML = '';

        if (evidenciasUrls.length > 0) {
            imgGrid.className = evidenciasUrls.length === 1 ? 'grid grid-cols-1 gap-2' : (evidenciasUrls.length === 2 ? 'grid grid-cols-2 gap-2' : 'grid grid-cols-3 gap-2');
            evidenciasUrls.forEach((url, idx) => {
                const thumb = document.createElement('div');
                thumb.className = 'relative rounded-xl overflow-hidden border border-slate-200 h-28 group';
                thumb.innerHTML = `
                    <img src="${url}" alt="Foto ${idx+1}" class="w-full h-full object-cover">
                    <a href="${url}" target="_blank" class="absolute inset-0 bg-slate-950/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs">
                        <i class="fa-solid fa-magnifying-glass-plus"></i>
                    </a>
                `;
                imgGrid.appendChild(thumb);
            });
            imgContainer.classList.remove('hidden');
        } else {
            imgContainer.classList.add('hidden');
        }

        document.getElementById('modalReportDetail').innerText = textContent;

        // Comprobar si ya existe respuesta previa
        const prevAlert = document.getElementById('modalPreviousResponseAlert');
        const prevInfo = document.getElementById('modalPreviousResponseInfo');
        if (respuestaAdmin) {
            prevAlert.classList.remove('hidden');
            prevInfo.innerText = `Atendido por ${respuestaAdmin.atendido_por || 'SST'} el ${respuestaAdmin.fecha_atencion || 'Reciente'}. Puedes modificar las medidas a continuación.`;
            
            if (respuestaAdmin.protocolo) {
                document.getElementById('modalSelectProtocol').value = respuestaAdmin.protocolo;
            }
            if (respuestaAdmin.medidas_tomadas) {
                document.getElementById('modalResponseText').value = respuestaAdmin.medidas_tomadas;
            }
            if (respuestaAdmin.estado) {
                document.getElementById('modalSelectEstado').value = respuestaAdmin.estado;
            }
        } else {
            prevAlert.classList.add('hidden');
            document.getElementById('modalResponseText').value = '';
            document.getElementById('modalSelectEstado').value = 'atendido';
        }

        document.getElementById('modalResponse').classList.remove('hidden');
    }

    function closeResponseModal() {
        document.getElementById('modalResponse').classList.add('hidden');
    }
</script>
@endsection

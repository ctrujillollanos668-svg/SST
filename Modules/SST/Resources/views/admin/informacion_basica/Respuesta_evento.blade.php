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
            'color' => 'emerald',
            'border_color' => 'border-l-[#39A900]',
            'icon_bg' => 'bg-emerald-50 text-[#39A900]',
            'badge_bg' => 'bg-emerald-100 text-[#064e3b]',
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

    <!-- 1. Encabezado General (Estilo Dirección Ejecutivo) -->
    <div class="bg-gradient-to-r from-[#00131E] via-[#001A29] to-[#002D44] p-6 sm:p-7 rounded-2xl border-l-4 border-l-[#39A900] text-white shadow-lg flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-white/10 text-[#62E31D] flex items-center justify-center border border-white/20 shadow-inner shrink-0">
                <i class="fa-solid fa-clipboard-check text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-black text-white tracking-tight">Gestión y Respuesta a <span class="text-[#62E31D]">Eventos</span></h1>
                <p class="text-xs sm:text-sm text-white/80 font-normal mt-0.5">Seguimiento en tiempo real a los 7 tipos de reportes de aprendices, evidencias fotográficas y protocolos correctivos.</p>
            </div>
        </div>

        <div class="flex items-center gap-2 shrink-0">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-white/10 text-[#62E31D] text-xs font-extrabold border border-white/20">
                <span class="w-2 h-2 rounded-full bg-[#62E31D] animate-pulse"></span>
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
                                   class="w-full pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#39A900]/20 focus:border-[#39A900] transition">
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
                                <th class="py-2.5 px-2 text-center w-8">#</th>
                                <th class="py-2.5 px-2 whitespace-nowrap"><i class="fa-regular fa-calendar mr-1"></i> FECHA / HORA</th>
                                <th class="py-2.5 px-2"><i class="fa-solid fa-location-dot mr-1"></i> LUGAR</th>
                                <th class="py-2.5 px-2"><i class="fa-solid {{ $cat['icon'] }} mr-1"></i> {{ $cat['tipo_label'] }}</th>
                                <th class="py-2.5 px-1.5 text-center whitespace-nowrap"><i class="fa-solid fa-gauge-high mr-1"></i> GRAVEDAD</th>
                                <th class="py-2.5 px-2"><i class="fa-solid fa-user-group mr-1"></i> AFECTADOS</th>
                                <th class="py-2.5 px-1.5 text-center whitespace-nowrap"><i class="fa-solid fa-chalkboard-user mr-1"></i> INSTRUCTOR</th>
                                <th class="py-2.5 px-2 max-w-[140px]"><i class="fa-regular fa-file-lines mr-1"></i> DESCRIPCIÓN</th>
                                <th class="py-2.5 px-1.5 text-center whitespace-nowrap"><i class="fa-regular fa-image mr-1"></i> EVIDENCIA</th>
                                <th class="py-2.5 px-2 text-center whitespace-nowrap"><i class="fa-solid fa-circle-check mr-1"></i> ESTADO</th>
                                <th class="py-2.5 px-2 text-center whitespace-nowrap"><i class="fa-solid fa-comment-dots mr-1"></i> GESTIÓN</th>
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
                                        str_contains($gravVal, 'grave') => 'bg-emerald-100 text-[#064e3b] border-emerald-200',
                                        str_contains($gravVal, 'moderado') || str_contains($gravVal, 'media') => 'bg-amber-100 text-amber-800 border-amber-200',
                                        default => 'bg-emerald-100 text-emerald-800 border-emerald-200'
                                    };

                                    // Tipo específico
                                    $tipoEspecifico = $data[$cat['tipo_key']] ?? $data['tipo_suceso'] ?? null;
                                    if (empty($tipoEspecifico)) {
                                        $tipoEspecifico = preg_replace('/^[^:]+:\s*/i', '', $item->nombre);
                                        $tipoEspecifico = preg_replace('/\s*\([^)]*\)$/', '', $tipoEspecifico);
                                    }

                                    // Estado y Seguimientos
                                    $estadoItem = strtolower($item->estado ?? 'activo');
                                    $seguimientos = $data['seguimientos'] ?? [];
                                    if (empty($seguimientos) && !empty($data['respuesta_admin'])) {
                                        $seguimientos[] = [
                                            'id' => 1,
                                            'fecha' => $data['respuesta_admin']['fecha_atencion'] ?? 'Atendido',
                                            'atendido_por' => $data['respuesta_admin']['atendido_por'] ?? 'Administrador SST',
                                            'proceso' => $data['respuesta_admin']['medidas_tomadas'] ?? ($data['respuesta_admin']['proceso'] ?? 'Atención inicial'),
                                            'foto' => $data['respuesta_admin']['foto'] ?? null,
                                        ];
                                    }
                                    $totalSeguimientos = count($seguimientos);
                                    $estadoCaso = $data['estado_caso'] ?? ($data['respuesta_admin']['estado'] ?? ($estadoItem === 'atendido' ? 'atendido' : ($totalSeguimientos > 0 ? 'en_proceso' : 'pendiente')));
                                    $esFinalizado = ($estadoCaso === 'atendido');
                                    $esEnProceso = ($estadoCaso === 'en_proceso' || ($totalSeguimientos > 0 && !$esFinalizado));
                                    $afectadoStr = $data['persona_afectada'] ?? $data['personas_involucradas'] ?? 'Personal';
                                    $lugarStr = $data['lugar_formacion'] ?? 'Ambiente';
                                    $lugarEspStr = $data['lugar_especifico'] ?? '';
                                    $descStr = $data['descripcion'] ?? $data['descripcion_original'] ?? 'Sin descripción adicional.';
                                @endphp

                                <tr class="event-row hover:bg-slate-50/80 transition-colors">
                                    <!-- 1. Número -->
                                    <td class="py-2.5 px-2 text-center font-bold text-slate-400">
                                        {{ $index + 1 }}
                                    </td>

                                    <!-- 2. Fecha / Hora -->
                                    <td class="py-2.5 px-2 whitespace-nowrap">
                                        <p class="font-bold text-slate-900 leading-tight text-xs">{{ $fechaDia }}</p>
                                        @if(!empty($fechaHora))
                                            <span class="text-[10px] text-slate-400 font-semibold block">{{ $fechaHora }}</span>
                                        @endif
                                    </td>

                                    <!-- 3. Lugar -->
                                    <td class="py-2.5 px-2 max-w-[120px]">
                                        <p class="font-extrabold text-slate-800 text-xs truncate" title="{{ $lugarStr }}">{{ $lugarStr }}</p>
                                        @if(!empty($lugarEspStr))
                                            <span class="text-[9px] text-amber-700 bg-amber-50 px-1 py-0.5 rounded border border-amber-200/80 inline-block font-semibold truncate max-w-full" title="{{ $lugarEspStr }}">
                                                <i class="fa-solid fa-map-pin mr-0.5 text-[7px]"></i> {{ $lugarEspStr }}
                                            </span>
                                        @endif
                                    </td>

                                    <!-- 4. Tipo Específico -->
                                    <td class="py-2.5 px-2 max-w-[130px]">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[11px] font-extrabold border bg-slate-50 text-slate-800 border-slate-200 truncate max-w-full" title="{{ $tipoEspecifico }}">
                                            <i class="fa-solid {{ $cat['icon'] }} text-[#39A900] text-[9px] shrink-0"></i>
                                            <span class="truncate">{{ $tipoEspecifico }}</span>
                                        </span>
                                    </td>

                                    <!-- 5. Gravedad / Prioridad -->
                                    <td class="py-2.5 px-1.5 text-center whitespace-nowrap">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black border {{ $badgeGrav }}">
                                            {{ ucfirst($data['gravedad'] ?? $data['prioridad'] ?? 'Media') }}
                                        </span>
                                    </td>

                                    <!-- 6. Afectados / Involucrados -->
                                    <td class="py-2.5 px-2 max-w-[110px]">
                                        <p class="font-bold text-slate-800 text-xs truncate" title="{{ $afectadoStr }}">{{ $afectadoStr }}</p>
                                        @if(!empty($data['personas_involucradas']) && $data['personas_involucradas'] !== $afectadoStr)
                                            <span class="text-[10px] text-slate-400 block truncate" title="{{ $data['personas_involucradas'] }}">
                                                {{ $data['personas_involucradas'] }}
                                            </span>
                                        @endif
                                    </td>

                                    <!-- 7. Instructor -->
                                    <td class="py-2.5 px-1.5 max-w-[95px] text-center whitespace-nowrap">
                                        @if(!empty($instructor) && $instructor !== 'N/A')
                                            <span class="inline-flex items-center gap-0.5 text-[11px] text-slate-700 font-semibold truncate" title="{{ $instructor }}">
                                                <i class="fa-solid fa-chalkboard-user text-[#39A900] text-[9px]"></i>
                                                <span class="truncate">{{ $instructor }}</span>
                                            </span>
                                        @else
                                            <span class="text-slate-300 text-xs block text-center">-</span>
                                        @endif
                                    </td>

                                    <!-- 8. Descripción Decodificada y Limpia -->
                                    <td class="py-2.5 px-2 max-w-[140px]">
                                        <p class="line-clamp-1 text-[11px] text-slate-600 leading-snug cursor-help" title="{{ $descStr }}">
                                            {{ $descStr }}
                                        </p>
                                    </td>

                                    <!-- 9. Evidencias Fotográficas -->
                                    <td class="py-2.5 px-1.5 text-center whitespace-nowrap">
                                        @if($totalFotos > 0)
                                            <button type="button" 
                                                    onclick="showEvidencePhoto({{ json_encode($evidencias) }}, '{{ addslashes($tipoEspecifico) }}')"
                                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-[#064e3b] text-[10px] font-bold border border-emerald-200/80 shadow-2xs transition cursor-pointer"
                                                    title="Ver fotos de evidencia">
                                                <i class="fa-solid fa-camera text-[9px]"></i>
                                                <span>{{ $totalFotos }}</span>
                                            </button>
                                        @else
                                            <span class="text-slate-300 text-xs">-</span>
                                        @endif
                                    </td>

                                    <!-- 10. Estado -->
                                    <td class="py-2.5 px-2 text-center whitespace-nowrap">
                                        @if($esFinalizado)
                                            <button type="button"
                                                    onclick="openHistorialModal({{ $item->id_respuesta }}, '{{ addslashes($tipoEspecifico) }}', '{{ addslashes(json_encode($data, JSON_UNESCAPED_UNICODE)) }}', '{{ $estadoCaso }}')"
                                                    class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 hover:bg-emerald-200 text-emerald-800 border border-emerald-300 shadow-2xs transition cursor-pointer"
                                                    title="Click para ver historial de atención">
                                                <i class="fa-solid fa-check-double text-[9px] text-emerald-600"></i> Finalizado
                                            </button>
                                        @elseif($esEnProceso)
                                            <button type="button"
                                                    onclick="openHistorialModal({{ $item->id_respuesta }}, '{{ addslashes($tipoEspecifico) }}', '{{ addslashes(json_encode($data, JSON_UNESCAPED_UNICODE)) }}', '{{ $estadoCaso }}')"
                                                    class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-sky-50 hover:bg-sky-100 text-sky-800 border border-sky-300 shadow-2xs transition cursor-pointer"
                                                    title="Click para ver los {{ $totalSeguimientos }} seguimientos">
                                                <i class="fa-solid fa-arrows-rotate text-[9px] text-sky-600 animate-spin-slow"></i> En Proceso ({{ $totalSeguimientos }})
                                            </button>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-50 text-amber-700 border border-amber-200 shadow-2xs">
                                                <i class="fa-regular fa-clock text-[9px] text-amber-500"></i> Pendiente
                                            </span>
                                        @endif
                                    </td>

                                    <!-- 11. Gestión / Responder -->
                                    <td class="py-2.5 px-2 text-center whitespace-nowrap">
                                        @if($esFinalizado)
                                            <button type="button"
                                                    onclick="openHistorialModal({{ $item->id_respuesta }}, '{{ addslashes($tipoEspecifico) }}', '{{ addslashes(json_encode($data, JSON_UNESCAPED_UNICODE)) }}', '{{ $estadoCaso }}')" 
                                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-[11px] font-bold border border-slate-200 shadow-2xs transition cursor-pointer"
                                                    title="Ver caso resuelto">
                                                <i class="fa-solid fa-folder-open text-[10px]"></i>
                                                <span>Ver Caso</span>
                                            </button>
                                        @elseif($totalSeguimientos > 0)
                                            <button type="button"
                                                    onclick="openResponseModal({{ $item->id_respuesta }}, '{{ addslashes($tipoEspecifico) }}', '{{ addslashes($afectadoStr) }}', '{{ addslashes(json_encode($data, JSON_UNESCAPED_UNICODE)) }}', '{{ $estadoCaso }}')" 
                                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl bg-sky-50 hover:bg-sky-100 text-sky-800 text-[11px] font-bold border border-sky-300 shadow-2xs transition cursor-pointer"
                                                    title="Registrar nuevo seguimiento / avance">
                                                <i class="fa-solid fa-plus-circle text-[10px] text-sky-600"></i>
                                                <span>Nuevo Avance</span>
                                            </button>
                                        @else
                                            <button type="button"
                                                    onclick="openResponseModal({{ $item->id_respuesta }}, '{{ addslashes($tipoEspecifico) }}', '{{ addslashes($afectadoStr) }}', '{{ addslashes(json_encode($data, JSON_UNESCAPED_UNICODE)) }}', '{{ $estadoCaso }}')" 
                                                    class="inline-flex items-center gap-1 px-3 py-1 rounded-xl bg-emerald-50 hover:bg-emerald-100 active:bg-emerald-200/70 text-[#064e3b] text-[11px] font-bold border border-emerald-200 shadow-2xs transition cursor-pointer"
                                                    title="Atender este reporte">
                                                <i class="fa-solid fa-reply text-[9px]"></i>
                                                <span>Atender</span>
                                            </button>
                                        @endif
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
                <i class="fa-solid fa-camera text-[#39A900]"></i>
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
<!-- MODAL: REGISTRAR SEGUIMIENTO / PROCESO DEL ADMINISTRADOR SST               -->
<!-- ========================================================================= -->
<div id="modalResponse" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-3 sm:p-4" onclick="closeResponseModal()">
    <div class="bg-white rounded-3xl max-w-xl w-full p-5 sm:p-6 shadow-2xl border border-slate-100 animate-modal-pop max-h-[92vh] flex flex-col overflow-hidden" onclick="event.stopPropagation()">
        
        <!-- Header del Modal -->
        <div class="flex items-center justify-between pb-3.5 border-b border-slate-100 mb-3 shrink-0">
            <div class="flex items-center gap-2.5">
                <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-[#39A900] flex items-center justify-center border border-emerald-200/80 shadow-2xs">
                    <i class="fa-solid fa-clipboard-check text-base"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base">Registrar Proceso / Seguimiento</h3>
                    <p class="text-xs text-slate-400 font-medium" id="modalResponseTitle">Avance del caso</p>
                </div>
            </div>
            <button onclick="closeResponseModal()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition cursor-pointer">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>

        <!-- Formulario Real con multipart/form-data -->
        <form id="formAdminResponse" action="" method="POST" enctype="multipart/form-data" class="space-y-4 overflow-y-auto pr-1 flex-1">
            @csrf

            <!-- Banner si ya tiene seguimientos previos -->
            <div id="modalPreviousResponseAlert" class="hidden rounded-2xl bg-sky-50 border border-sky-200 p-3 space-y-1">
                <div class="flex items-center gap-2 text-sky-800 font-extrabold text-xs">
                    <i class="fa-solid fa-arrows-rotate text-sky-600"></i>
                    <span>Este caso ya cuenta con seguimientos registrados</span>
                </div>
                <p class="text-[11px] text-sky-700 font-medium" id="modalPreviousResponseInfo"></p>
            </div>

            <!-- Galería de fotos del reporte inicial del aprendiz -->
            <div id="modalResponseImgContainer" class="hidden rounded-2xl overflow-hidden border border-slate-200 bg-slate-50 p-2.5 space-y-1.5">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Evidencias Adjuntas del Reporte</span>
                <div id="modalResponseGalleryGrid" class="grid gap-2"></div>
            </div>

            <!-- Detalle original del reporte -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Detalle del Reporte del Aprendiz:</label>
                <div id="modalReportDetail" class="w-full p-3 text-xs rounded-xl bg-slate-50 border border-slate-200 font-medium text-slate-700 max-h-32 overflow-y-auto whitespace-pre-line"></div>
            </div>

            <!-- Campo: Proceso (Obligatorio) -->
            <div class="space-y-1">
                <div class="flex items-center justify-between">
                    <label class="block text-xs font-bold text-slate-700">
                        Proceso <span class="text-rose-500">*</span>
                    </label>
                    <span class="text-[10px] text-slate-400 font-semibold" id="procesoCharCount">0 / 2000</span>
                </div>
                <textarea id="modalResponseText" name="proceso" required rows="3" maxlength="2000" 
                    oninput="this.value = this.value.replace(/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑüÜ\s.,;:()\-]/g, ''); document.getElementById('procesoCharCount').innerText = this.value.length + ' / 2000'"
                    placeholder="Describe claramente las medidas tomadas, procedimiento aplicado, remisiones o seguimiento médico..." 
                    class="w-full px-3.5 py-2.5 text-xs rounded-xl bg-white border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#39A900]/20 focus:border-[#39A900] font-medium text-slate-800 shadow-2xs transition resize-none"></textarea>
                <p class="text-[10px] text-slate-400 font-medium">No se permiten caracteres especiales (@, #, $, %, &, *, etc.). Solo letras, números, espacios y signos básicos (. , : ; - ()).</p>
            </div>

            <!-- Campo: Foto del Proceso (Opcional - Máx 1 foto) -->
            <div class="space-y-1.5">
                <div class="flex items-center justify-between">
                    <label class="block text-xs font-bold text-slate-700">
                        Foto del Proceso <span class="text-slate-400 text-[11px] font-normal">(Opcional, máx 1 foto)</span>
                    </label>
                    <span class="text-[10px] text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-lg border border-emerald-200 font-semibold">JPG, PNG, WEBP</span>
                </div>

                <div id="dropzoneFotoProceso" 
                     onclick="document.getElementById('inputFotoProceso').click()" 
                     class="relative border-2 border-dashed border-slate-200 hover:border-[#39A900] hover:bg-emerald-50/20 rounded-2xl p-3.5 text-center transition cursor-pointer bg-slate-50/50 group">
                    <input type="file" id="inputFotoProceso" name="foto_proceso" accept="image/jpeg,image/png,image/webp,image/jpg" class="hidden" onchange="handleFotoProcesoChange(this.files)">
                    <div class="flex items-center justify-center gap-3" id="fotoProcesoPrompt">
                        <div class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 group-hover:text-[#39A900] group-hover:scale-105 transition shadow-2xs">
                            <i class="fa-solid fa-camera"></i>
                        </div>
                        <div class="text-left">
                            <p class="text-xs font-bold text-slate-700 group-hover:text-[#39A900] transition">Click o arrastra para adjuntar foto del proceso</p>
                            <p class="text-[10px] text-slate-400">Si no necesitas adjuntar foto, puedes dejar este campo vacío</p>
                        </div>
                    </div>
                </div>

                <!-- Preview de la foto seleccionada -->
                <div id="fotoProcesoPreviewContainer" class="hidden p-2.5 rounded-2xl border border-emerald-200 bg-emerald-50/40 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5 overflow-hidden">
                        <img id="fotoProcesoPreviewImg" src="" alt="Previsualización" class="w-12 h-12 rounded-xl object-cover border border-emerald-300 shrink-0">
                        <div class="overflow-hidden">
                            <p id="fotoProcesoFileName" class="text-xs font-bold text-slate-800 truncate"></p>
                            <span class="text-[10px] text-emerald-700 font-semibold flex items-center gap-1">
                                <i class="fa-solid fa-circle-check text-emerald-500"></i> Imagen seleccionada
                            </span>
                        </div>
                    </div>
                    <button type="button" onclick="clearFotoProceso()" class="px-2.5 py-1.5 rounded-xl bg-white hover:bg-rose-50 text-rose-600 border border-slate-200 hover:border-rose-200 text-xs font-bold transition shadow-2xs cursor-pointer flex items-center gap-1">
                        <i class="fa-solid fa-trash-can text-[10px]"></i>
                        <span>Quitar</span>
                    </button>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeResponseModal()" class="w-full sm:w-auto px-4 py-2.5 text-xs font-bold text-slate-600 rounded-xl hover:bg-slate-100 transition cursor-pointer">
                    Cancelar
                </button>
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <!-- Botón 1: Guardar Avance -->
                    <button type="submit" name="accion" value="guardar" class="flex-1 sm:flex-none px-4 py-2.5 text-xs font-bold text-sky-800 bg-sky-50 hover:bg-sky-100 border border-sky-300 rounded-xl shadow-2xs transition cursor-pointer flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-floppy-disk text-xs"></i>
                        <span>Guardar Avance</span>
                    </button>
                    <!-- Botón 2: Guardar y Finalizar Caso -->
                    <button type="submit" name="accion" value="finalizar" class="flex-1 sm:flex-none px-4 py-2.5 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs transition cursor-pointer flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-check-double text-xs"></i>
                        <span>Guardar y Finalizar</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL: HISTORIAL DE SEGUIMIENTOS SST                                      -->
<!-- ========================================================================= -->
<div id="modalHistorialSeguimientos" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-3 sm:p-4" onclick="closeHistorialModal()">
    <div class="bg-white rounded-3xl max-w-2xl w-full p-5 sm:p-6 shadow-2xl border border-slate-100 animate-modal-pop max-h-[92vh] flex flex-col overflow-hidden" onclick="event.stopPropagation()">
        
        <!-- Header -->
        <div class="flex items-center justify-between pb-3.5 border-b border-slate-100 mb-3 shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-200/80 shadow-2xs shrink-0">
                    <i class="fa-solid fa-timeline text-base"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="font-extrabold text-slate-900 text-base">Historial de Seguimientos</h3>
                        <span id="historialEstadoBadge" class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black border"></span>
                    </div>
                    <p class="text-xs text-slate-500 font-medium truncate max-w-md" id="historialCasoTitle">Caso</p>
                </div>
            </div>
            <button onclick="closeHistorialModal()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition cursor-pointer">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>

        <!-- Contenedor Timeline con Scroll -->
        <div class="flex-1 overflow-y-auto pr-1 space-y-3.5" id="historialTimelineContainer">
            <!-- Timeline dinámico generado por JavaScript -->
        </div>

        <!-- Footer / Acciones del caso -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-2.5 pt-3.5 border-t border-slate-100 shrink-0 mt-3">
            <div id="historialFooterLeft">
                <!-- Botón finalizar si está en proceso -->
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                <button type="button" onclick="closeHistorialModal()" class="px-4 py-2.5 text-xs font-bold text-slate-600 rounded-xl hover:bg-slate-100 transition cursor-pointer">
                    Cerrar
                </button>
                <button type="button" id="btnHistorialNuevoAvance" onclick="nuevoAvanceDesdeHistorial()" class="px-4 py-2.5 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs transition cursor-pointer flex items-center gap-1.5">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Agregar Avance</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Cambio entre pestañas de las 7 categorías
    function switchEventTab(targetSlug) {
        try { sessionStorage.setItem('sst_active_event_tab', targetSlug); } catch(e) {}
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

    document.addEventListener('DOMContentLoaded', function() {
        try {
            const saved = sessionStorage.getItem('sst_active_event_tab');
            if (saved && document.getElementById('panel_' + saved)) {
                switchEventTab(saved);
            }
        } catch(e) {}
    });

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

    // Manejo de la foto única de proceso
    function handleFotoProcesoChange(files) {
        if (!files || files.length === 0) return;
        const file = files[0];
        if (!file.type.startsWith('image/')) {
            alert('Por favor selecciona un archivo de imagen válido (JPG, PNG, WEBP).');
            clearFotoProceso();
            return;
        }
        if (file.size > 5 * 1024 * 1024) {
            alert('La imagen no debe superar los 5MB.');
            clearFotoProceso();
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('fotoProcesoPreviewImg').src = e.target.result;
            document.getElementById('fotoProcesoFileName').innerText = file.name;
            document.getElementById('fotoProcesoPreviewContainer').classList.remove('hidden');
            document.getElementById('dropzoneFotoProceso').classList.add('hidden');
        };
        reader.readAsDataURL(file);
    }

    function clearFotoProceso() {
        const input = document.getElementById('inputFotoProceso');
        if (input) input.value = '';
        const preview = document.getElementById('fotoProcesoPreviewContainer');
        if (preview) preview.classList.add('hidden');
        const dropzone = document.getElementById('dropzoneFotoProceso');
        if (dropzone) dropzone.classList.remove('hidden');
    }

    // Modal de Registro de Seguimiento / Avance
    function openResponseModal(id, title, reporter, rawData, currentStatus) {
        document.getElementById('modalResponseTitle').innerText = title;
        clearFotoProceso();
        
        const baseUrl = "{{ url('Sst/admin/informacion-basica/respuesta-eventos') }}";
        document.getElementById('formAdminResponse').action = `${baseUrl}/${id}/atender`;

        let textContent = '';
        let evidenciasUrls = [];
        let seguimientos = [];

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

            seguimientos = parsed.seguimientos || [];
            if (seguimientos.length === 0 && parsed.respuesta_admin) {
                seguimientos = [{
                    id: 1,
                    fecha: parsed.respuesta_admin.fecha_atencion || 'Atención inicial',
                    atendido_por: parsed.respuesta_admin.atendido_por || 'SST',
                    proceso: parsed.respuesta_admin.medidas_tomadas || parsed.respuesta_admin.proceso || 'Medidas registradas',
                    foto: parsed.respuesta_admin.foto || null
                }];
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
                thumb.className = 'relative rounded-xl overflow-hidden border border-slate-200 h-24 group';
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

        const prevAlert = document.getElementById('modalPreviousResponseAlert');
        const prevInfo = document.getElementById('modalPreviousResponseInfo');
        if (seguimientos.length > 0) {
            prevAlert.classList.remove('hidden');
            const ultimo = seguimientos[seguimientos.length - 1];
            prevInfo.innerText = `Lleva ${seguimientos.length} seguimiento(s). Último registrado por ${ultimo.atendido_por || 'SST'} el ${ultimo.fecha || ''}.`;
        } else {
            prevAlert.classList.add('hidden');
        }

        document.getElementById('modalResponseText').value = '';
        document.getElementById('procesoCharCount').innerText = '0 / 2000';
        document.getElementById('modalResponse').classList.remove('hidden');
    }

    function closeResponseModal() {
        document.getElementById('modalResponse').classList.add('hidden');
        clearFotoProceso();
    }

    // Modal de Historial de Seguimientos
    let currentHistorialCase = null;

    function openHistorialModal(id, title, rawData, currentStatus) {
        currentHistorialCase = { id, title, rawData, currentStatus };
        document.getElementById('historialCasoTitle').innerText = title;

        let parsed = {};
        try {
            parsed = typeof rawData === 'string' ? JSON.parse(rawData) : rawData;
        } catch(e) {
            parsed = {};
        }

        let seguimientos = parsed.seguimientos || [];
        if (seguimientos.length === 0 && parsed.respuesta_admin) {
            seguimientos = [{
                id: 1,
                fecha: parsed.respuesta_admin.fecha_atencion || 'Atención inicial',
                atendido_por: parsed.respuesta_admin.atendido_por || 'SST',
                proceso: parsed.respuesta_admin.medidas_tomadas || parsed.respuesta_admin.proceso || 'Medidas registradas',
                foto: parsed.respuesta_admin.foto || null
            }];
        }

        const isFinalizado = (currentStatus === 'atendido' || (parsed && parsed.estado_caso === 'atendido'));
        const badge = document.getElementById('historialEstadoBadge');
        if (isFinalizado) {
            badge.className = 'inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800 border border-emerald-300';
            badge.innerHTML = '<i class="fa-solid fa-check-double text-[9px]"></i> Finalizado';
        } else {
            badge.className = 'inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black bg-sky-50 text-sky-800 border border-sky-300';
            badge.innerHTML = '<i class="fa-solid fa-arrows-rotate text-[9px] animate-spin-slow"></i> En Proceso (' + seguimientos.length + ')';
        }

        const container = document.getElementById('historialTimelineContainer');
        container.innerHTML = '';

        if (seguimientos.length === 0) {
            container.innerHTML = `
                <div class="py-12 text-center text-slate-400">
                    <div class="w-12 h-12 mx-auto rounded-2xl bg-slate-100 text-slate-300 flex items-center justify-center text-xl mb-2">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                    <p class="text-xs font-bold text-slate-600">No hay seguimientos registrados aún</p>
                    <p class="text-[11px] text-slate-400">Presiona "Agregar Avance" para registrar el primer procedimiento.</p>
                </div>
            `;
        } else {
            seguimientos.forEach((seg, idx) => {
                const num = seg.id || (idx + 1);
                const fotoHtml = seg.foto ? `
                    <div class="mt-2.5 pt-2.5 border-t border-slate-100 flex items-center gap-3">
                        <div class="w-16 h-16 rounded-xl overflow-hidden border border-slate-200 bg-slate-900/5 shrink-0 group relative cursor-pointer" onclick="showEvidencePhoto(['${seg.foto}'], 'Foto Seguimiento #${num}')">
                            <img src="${seg.foto}" alt="Foto Proceso #${num}" class="w-full h-full object-cover group-hover:scale-105 transition">
                            <div class="absolute inset-0 bg-slate-950/25 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white text-[10px]">
                                <i class="fa-solid fa-expand"></i>
                            </div>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Foto de evidencia</span>
                            <a href="${seg.foto}" target="_blank" class="text-xs font-bold text-[#39A900] hover:underline flex items-center gap-1">
                                <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i> Ver imagen completa
                            </a>
                        </div>
                    </div>
                ` : '';

                const card = document.createElement('div');
                card.className = 'relative pl-6 pb-2 border-l-2 border-emerald-300 last:border-l-transparent';
                card.innerHTML = `
                    <span class="absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-emerald-500 border-2 border-white shadow-2xs"></span>
                    <div class="bg-slate-50/80 hover:bg-white rounded-2xl border border-slate-200/90 p-4 shadow-2xs transition">
                        <div class="flex flex-wrap items-center justify-between gap-2 pb-2 border-b border-slate-100">
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded-lg bg-emerald-100 text-[#064e3b] text-[10px] font-extrabold border border-emerald-200">
                                    Seguimiento #${num}
                                </span>
                                <span class="text-[11px] font-bold text-slate-700">${seg.atendido_por || 'SST'}</span>
                            </div>
                            <span class="text-[10px] font-semibold text-slate-400 flex items-center gap-1">
                                <i class="fa-regular fa-clock text-[9px]"></i> ${seg.fecha || ''}
                            </span>
                        </div>
                        <div class="pt-2.5">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Proceso Realizado:</span>
                            <p class="text-xs text-slate-800 font-medium whitespace-pre-line leading-relaxed">${seg.proceso || ''}</p>
                            ${fotoHtml}
                        </div>
                    </div>
                `;
                container.appendChild(card);
            });
        }

        const leftFooter = document.getElementById('historialFooterLeft');
        leftFooter.innerHTML = '';
        const btnNuevoAvance = document.getElementById('btnHistorialNuevoAvance');

        if (!isFinalizado) {
            btnNuevoAvance.classList.remove('hidden');
            const finalizarUrl = "{{ url('Sst/admin/informacion-basica/respuesta-eventos') }}/" + id + "/finalizar";
            leftFooter.innerHTML = `
                <form method="POST" action="${finalizarUrl}" onsubmit="return confirm('¿Confirmas que deseas Finalizar y Cerrar este caso?');">
                    @csrf
                    <button type="submit" class="px-3.5 py-2 text-xs font-bold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 rounded-xl shadow-2xs transition cursor-pointer flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-check text-xs"></i>
                        <span>Finalizar Caso Ahora</span>
                    </button>
                </form>
            `;
        } else {
            btnNuevoAvance.classList.add('hidden');
            leftFooter.innerHTML = `
                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-800 bg-emerald-50 px-3 py-1.5 rounded-xl border border-emerald-200">
                    <i class="fa-solid fa-check-double text-emerald-600"></i> Este caso se encuentra Resuelto y Finalizado
                </span>
            `;
        }

        document.getElementById('modalHistorialSeguimientos').classList.remove('hidden');
    }

    function closeHistorialModal() {
        document.getElementById('modalHistorialSeguimientos').classList.add('hidden');
    }

    function nuevoAvanceDesdeHistorial() {
        if (!currentHistorialCase) return;
        closeHistorialModal();
        openResponseModal(
            currentHistorialCase.id,
            currentHistorialCase.title,
            '',
            currentHistorialCase.rawData,
            currentHistorialCase.currentStatus
        );
    }
</script>
@endsection

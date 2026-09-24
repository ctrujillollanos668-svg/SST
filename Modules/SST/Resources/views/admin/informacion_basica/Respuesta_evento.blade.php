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

<div class="space-y-8 max-w-7xl mx-auto">

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
    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4 transition-all duration-200">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center border border-orange-200/80 shadow-xs">
                <i class="fa-solid fa-clipboard-check text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Gestión y Respuesta a Eventos</h1>
                <p class="text-sm text-slate-500 font-medium mt-0.5">Seguimiento en tiempo real a reportes de aprendices, evidencias y medidas correctivas.</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-orange-50 text-orange-700 text-xs font-bold border border-orange-200">
                <span class="w-2 h-2 rounded-full bg-orange-500 animate-pulse"></span>
                Conectado con Aprendices
            </span>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- BLOQUE 1: ACCIDENTES LABORALES REPORTADOS                                 -->
    <!-- ========================================================================= -->
    <div class="bg-white rounded-2xl border border-slate-200/80 border-l-[6px] border-l-rose-500 shadow-xs overflow-hidden">
        
        <!-- Header del Bloque Accidentes -->
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-lg shrink-0">
                    <i class="fa-solid fa-burst"></i>
                </div>
                <div>
                    <h2 class="text-lg font-extrabold text-slate-900">Accidentes Laborales Reportados</h2>
                    <p class="text-xs text-slate-400 font-semibold">Reportes generados desde el panel de Aprendiz</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <span class="text-xs font-black tracking-widest text-slate-400 uppercase">
                    <span class="text-base text-rose-600 font-extrabold" id="countAccidentes">{{ $accidentes->count() }}</span> REGISTROS
                </span>
            </div>
        </div>

        <!-- Tabla de Accidentes -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse table-auto" id="tablaAccidentes">
                <thead>
                    <tr class="bg-slate-50/90 border-b border-slate-200/80 text-slate-500 text-[10px] font-bold uppercase tracking-wider">
                        <th class="py-3 px-2 text-center w-8">#</th>
                        <th class="py-3 px-2.5 whitespace-nowrap"><i class="fa-regular fa-calendar mr-1"></i> FECHA / HORA</th>
                        <th class="py-3 px-2.5 whitespace-nowrap"><i class="fa-solid fa-location-dot mr-1"></i> LUGAR</th>
                        <th class="py-3 px-2.5 whitespace-nowrap"><i class="fa-solid fa-burst mr-1"></i> TIPO ACCIDENTE</th>
                        <th class="py-3 px-2 text-center whitespace-nowrap"><i class="fa-solid fa-stethoscope mr-1"></i> GRAVEDAD</th>
                        <th class="py-3 px-2 whitespace-nowrap"><i class="fa-solid fa-users mr-1"></i> AFECTADOS</th>
                        <th class="py-3 px-2 whitespace-nowrap"><i class="fa-solid fa-chalkboard-user mr-1"></i> INSTRUCTOR</th>
                        <th class="py-3 px-2.5 max-w-[140px]"><i class="fa-regular fa-file-lines mr-1"></i> DESCRIPCIÓN</th>
                        <th class="py-3 px-2 text-center whitespace-nowrap"><i class="fa-regular fa-image mr-1"></i> EVIDENCIA</th>
                        <th class="py-3 px-2 text-center whitespace-nowrap"><i class="fa-solid fa-circle-check mr-1"></i> ESTADO</th>
                        <th class="py-3 px-2.5 text-center whitespace-nowrap"><i class="fa-solid fa-comment-dots mr-1"></i> GESTIÓN</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 text-xs font-medium">
                    @forelse($accidentes as $index => $acc)
                        @php
                            $data = json_decode($acc->descripcion, true);
                            if (!is_array($data)) {
                                $raw = $acc->descripcion ?? '';
                                preg_match('/Lugar:\s*([^,\n\r]+?)(?=\s*(?:Tipo|Gravedad|Personas|Instructor|Descripci|$))/i', $raw, $mLugar);
                                preg_match('/Tipo(?:\s*de\s*Accidente)?:\s*([^,\n\r]+?)(?=\s*(?:Gravedad|Personas|Instructor|Descripci|$))/i', $raw, $mTipo);
                                preg_match('/Gravedad:\s*([^,\n\r]+?)(?=\s*(?:Personas|Instructor|Descripci|$))/i', $raw, $mGrav);
                                preg_match('/Personas(?:\s*Involucradas)?:\s*([^,\n\r]+?)(?=\s*(?:Instructor|Descripci|$))/i', $raw, $mPers);
                                preg_match('/Instructor(?:\s*Responsable)?:\s*([^,\n\r]+?)(?=\s*(?:Descripci|$))/i', $raw, $mInst);
                                preg_match('/Descripci(?:ón|on)(?:\s*Detallada)?:\s*(.*)/is', $raw, $mDesc);

                                $cleanTipo = preg_replace('/^Accidente:\s*/i', '', $acc->nombre);
                                $cleanTipo = preg_replace('/\s*\([^)]*\)$/', '', $cleanTipo);

                                $data = [
                                    'tipo_accidente' => !empty($mTipo[1]) ? trim($mTipo[1]) : (!empty($cleanTipo) ? trim($cleanTipo) : 'Accidente'),
                                    'fecha_hora' => $acc->created_at ? $acc->created_at->format('d/m/Y h:i A') : 'Reciente',
                                    'lugar_formacion' => !empty($mLugar[1]) ? trim($mLugar[1]) : 'Taller / Ambiente',
                                    'gravedad' => !empty($mGrav[1]) ? trim($mGrav[1]) : 'Leve',
                                    'personas_involucradas' => !empty($mPers[1]) ? trim($mPers[1]) : 'Aprendiz',
                                    'instructor_responsable' => !empty($mInst[1]) ? trim($mInst[1]) : 'N/A',
                                    'descripcion' => !empty($mDesc[1]) ? trim($mDesc[1]) : $raw,
                                    'evidencia' => null,
                                ];
                            }
                            $grav = strtolower($data['gravedad'] ?? 'leve');
                            $badgeGrav = match(true) {
                                str_contains($grav, 'fatal') || str_contains($grav, 'crítico') => 'bg-rose-100 text-rose-800 border-rose-300',
                                str_contains($grav, 'grave') => 'bg-orange-100 text-orange-800 border-orange-300',
                                str_contains($grav, 'moderado') => 'bg-amber-100 text-amber-800 border-amber-300',
                                default => 'bg-emerald-100 text-emerald-800 border-emerald-300'
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-3 px-2 text-center font-bold text-slate-400">{{ $index + 1 }}</td>
                            
                            <!-- 1. Fecha y Hora -->
                            <td class="py-3 px-2.5 whitespace-nowrap">
                                <p class="font-bold text-slate-900 text-[11px]">{{ $data['fecha_hora'] ?? ($acc->created_at ? $acc->created_at->format('d/m/Y h:i A') : 'N/A') }}</p>
                            </td>

                            <!-- 2. Lugar de Formación -->
                            <td class="py-3 px-2.5 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ $data['lugar_formacion'] ?? 'General' }}
                                </span>
                            </td>

                            <!-- 3. Tipo de Accidente -->
                            <td class="py-3 px-2.5 font-bold text-slate-900 text-xs whitespace-nowrap">
                                {{ $data['tipo_accidente'] ?? $acc->nombre }}
                            </td>

                            <!-- 4. Gravedad -->
                            <td class="py-3 px-2 text-center whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold border {{ $badgeGrav }}">
                                    {{ ucfirst($data['gravedad'] ?? 'Leve') }}
                                </span>
                            </td>

                            <!-- 5. Personas Involucradas -->
                            <td class="py-3 px-2 font-semibold text-slate-800 text-xs whitespace-nowrap max-w-[120px] truncate" title="{{ $data['personas_involucradas'] ?? 'N/A' }}">
                                {{ $data['personas_involucradas'] ?? 'N/A' }}
                            </td>

                            <!-- 6. Instructor Responsable -->
                            <td class="py-3 px-2 text-slate-600 text-xs whitespace-nowrap max-w-[110px] truncate" title="{{ !empty($data['instructor_responsable']) ? $data['instructor_responsable'] : 'N/A' }}">
                                {{ !empty($data['instructor_responsable']) ? $data['instructor_responsable'] : 'N/A' }}
                            </td>

                            <!-- 7. Descripción -->
                            <td class="py-3 px-2.5 text-slate-600 max-w-[140px]">
                                <p class="truncate text-[11px] leading-relaxed" title="{{ $data['descripcion'] ?? 'Sin descripción.' }}">
                                    {{ $data['descripcion'] ?? 'Sin descripción.' }}
                                </p>
                            </td>

                            <!-- 8. Evidencia Fotográfica -->
                            <td class="py-3 px-2 text-center whitespace-nowrap">
                                @php
                                    $evidenciasAdmin = !empty($data['evidencias']) && is_array($data['evidencias']) ? $data['evidencias'] : (!empty($data['evidencia']) ? [$data['evidencia']] : []);
                                    $fotoPrincipalAdmin = !empty($evidenciasAdmin) ? $evidenciasAdmin[0] : null;
                                    $totalFotosAdmin = count($evidenciasAdmin);
                                @endphp
                                @if(!empty($fotoPrincipalAdmin))
                                    <div class="flex items-center justify-center">
                                        <button type="button" onclick="showEvidencePhoto({{ json_encode($evidenciasAdmin) }}, '{{ addslashes($data['tipo_accidente'] ?? 'Accidente') }}')" class="relative group/photo p-0.5 rounded-xl border-2 border-orange-200 hover:border-orange-500 bg-white transition-all shadow-2xs hover:shadow-md cursor-pointer overflow-hidden inline-flex items-center justify-center" title="Clic para ampliar evidencia">
                                            <img src="{{ $fotoPrincipalAdmin }}" alt="Evidencia" class="w-9 h-9 rounded-lg object-cover group-hover/photo:scale-110 transition duration-200">
                                            @if($totalFotosAdmin > 1)
                                                <span class="absolute bottom-0.5 right-0.5 px-1 py-0.2 rounded bg-slate-900/85 text-white text-[7px] font-extrabold shadow-2xs">
                                                    +{{ $totalFotosAdmin }}
                                                </span>
                                            @endif
                                            <div class="absolute inset-0 bg-slate-950/40 opacity-0 group-hover/photo:opacity-100 transition-opacity flex items-center justify-center text-white rounded-lg text-xs">
                                                <i class="fa-solid fa-magnifying-glass-plus"></i>
                                            </div>
                                        </button>
                                    </div>
                                @else
                                    <span class="inline-flex items-center gap-1 text-[10px] text-slate-300 font-semibold italic">
                                        <i class="fa-regular fa-image"></i> Sin foto
                                    </span>
                                @endif
                            </td>

                            <!-- 9. Estado -->
                            <td class="py-3 px-2 text-center whitespace-nowrap">
                                @php
                                    $estadoAcc = strtolower($acc->estado ?? 'activo');
                                    $tieneRespuesta = !empty($data['respuesta_admin']);
                                @endphp
                                @if($estadoAcc === 'atendido' || $tieneRespuesta)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                        <i class="fa-solid fa-check-double text-[9px]"></i> Atendido
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-50 text-amber-700 border border-amber-200">
                                        <i class="fa-regular fa-clock text-[9px]"></i> Pendiente
                                    </span>
                                @endif
                            </td>

                            <!-- 10. Gestionar / Responder -->
                            <td class="py-3 px-2.5 text-center whitespace-nowrap">
                                <button onclick="openResponseModal({{ $acc->id_respuesta }}, '{{ addslashes($data['tipo_accidente'] ?? $acc->nombre) }}', '{{ addslashes($data['personas_involucradas'] ?? 'Aprendiz') }}', '{{ addslashes(json_encode($data, JSON_UNESCAPED_UNICODE)) }}', '{{ $acc->estado }}')" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 text-[11px] font-bold border border-rose-200 shadow-2xs transition cursor-pointer">
                                    <i class="fa-solid fa-reply text-[9px]"></i>
                                    <span>{{ !empty($data['respuesta_admin']) ? 'Ver / Editar Respuesta' : 'Responder' }}</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="py-12 text-center text-slate-400">
                                <div class="w-12 h-12 mx-auto rounded-2xl bg-slate-100 text-slate-300 flex items-center justify-center text-xl mb-2">
                                    <i class="fa-solid fa-inbox"></i>
                                </div>
                                <p class="text-xs font-semibold">No hay accidentes reportados por los aprendices aún.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


    <!-- ========================================================================= -->
    <!-- BLOQUE 2: INCIDENTES DE TRABAJO REPORTADOS                                -->
    <!-- ========================================================================= -->
    <div class="bg-white rounded-2xl border border-slate-200/80 border-l-[6px] border-l-amber-500 shadow-xs overflow-hidden">
        
        <!-- Header del Bloque Incidentes -->
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-lg shrink-0">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div>
                    <h2 class="text-lg font-extrabold text-slate-900">Incidentes de Trabajo (Casi-Accidentes)</h2>
                    <p class="text-xs text-slate-400 font-semibold">Reportes preventivos registrados por aprendices</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <span class="text-xs font-black tracking-widest text-slate-400 uppercase">
                    <span class="text-base text-amber-600 font-extrabold" id="countIncidentes">{{ $incidentes->count() }}</span> REGISTROS
                </span>
            </div>
        </div>

        <!-- Tabla de Incidentes -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="tablaIncidentes">
                <thead>
                    <tr class="bg-slate-50/90 border-b border-slate-200/80 text-slate-500 text-[10px] font-bold uppercase tracking-wider">
                        <th class="py-3.5 px-3 text-center w-10">#</th>
                        <th class="py-3.5 px-3 whitespace-nowrap"><i class="fa-regular fa-calendar mr-1"></i> FECHA Y HORA</th>
                        <th class="py-3.5 px-3 whitespace-nowrap"><i class="fa-solid fa-triangle-exclamation mr-1"></i> ASUNTO / INCIDENTE</th>
                        <th class="py-3.5 px-3 min-w-[240px]"><i class="fa-regular fa-file-lines mr-1"></i> DESCRIPCIÓN</th>
                        <th class="py-3.5 px-3 text-center whitespace-nowrap"><i class="fa-solid fa-circle-check mr-1"></i> ESTADO</th>
                        <th class="py-3.5 px-3 text-center whitespace-nowrap"><i class="fa-solid fa-comment-dots mr-1"></i> GESTIONAR</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 text-xs font-medium">
                    @forelse($incidentes as $index => $inc)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-4 px-3 text-center font-bold text-slate-400">{{ $index + 1 }}</td>
                            <td class="py-4 px-3 whitespace-nowrap">
                                <p class="font-bold text-slate-900">{{ $inc->created_at ? $inc->created_at->format('d/m/Y') : now()->format('d/m/Y') }}</p>
                                <span class="text-[10px] text-slate-400">{{ $inc->created_at ? $inc->created_at->format('h:i A') : now()->format('h:i A') }}</span>
                            </td>
                            <td class="py-4 px-3 font-bold text-slate-900">
                                {{ $inc->nombre }}
                            </td>
                            <td class="py-4 px-3 text-slate-600">
                                <p class="line-clamp-2 text-[11px] leading-relaxed">{{ $inc->descripcion ?? 'Sin descripción.' }}</p>
                            </td>
                            <td class="py-4 px-3 text-center">
                                @php
                                    $incData = json_decode($inc->descripcion, true);
                                    $estadoInc = strtolower($inc->estado ?? 'activo');
                                    $tieneRespuestaInc = is_array($incData) && !empty($incData['respuesta_admin']);
                                @endphp
                                @if($estadoInc === 'atendido' || $tieneRespuestaInc)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                        <i class="fa-solid fa-check-double text-[9px]"></i> Atendido
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-50 text-amber-700 border border-amber-200">
                                        <i class="fa-regular fa-clock text-[9px]"></i> Pendiente
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-3 text-center whitespace-nowrap">
                                <button onclick="openResponseModal({{ $inc->id_respuesta }}, '{{ addslashes($inc->nombre) }}', 'Aprendiz', '{{ addslashes($inc->descripcion) }}', '{{ $inc->estado }}')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-800 text-xs font-bold border border-amber-200 shadow-2xs transition cursor-pointer">
                                    <i class="fa-solid fa-reply text-[10px]"></i>
                                    <span>{{ $tieneRespuestaInc ? 'Ver / Editar' : 'Responder' }}</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <div class="w-12 h-12 mx-auto rounded-2xl bg-slate-100 text-slate-300 flex items-center justify-center text-xl mb-2">
                                    <i class="fa-solid fa-inbox"></i>
                                </div>
                                <p class="text-xs font-semibold">No hay incidentes reportados por los aprendices aún.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

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
            textContent = `📍 Lugar: ${parsed.lugar_formacion || 'N/A'}\n`
                        + `💼 Tipo: ${parsed.tipo_accidente || title}\n`
                        + `🩺 Gravedad: ${parsed.gravedad || 'N/A'}\n`
                        + `👥 Personas: ${parsed.personas_involucradas || reporter}\n`
                        + (parsed.instructor_responsable ? `👨‍🏫 Instructor: ${parsed.instructor_responsable}\n` : '')
                        + `📅 Fecha: ${parsed.fecha_hora || 'N/A'}\n\n`
                        + `📝 Descripción: ${parsed.descripcion || parsed.descripcion_original || 'Sin descripción'}`;
            
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

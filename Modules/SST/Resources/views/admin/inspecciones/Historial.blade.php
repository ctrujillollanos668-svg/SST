@extends('sst::components.layouts.admin')

@section('title', 'Historial de Inspecciones SST • Administrador')

@section('content')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<div class="space-y-6 max-w-7xl mx-auto pb-12">

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

    <!-- 1. Encabezado General (Idéntico a Respuesta a Eventos) -->
    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4 transition-all duration-200">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center border border-orange-200/80 shadow-xs">
                <i class="fa-solid fa-clipboard-check text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Historial de Inspecciones SST</h1>
                <p class="text-sm text-slate-500 font-medium mt-0.5">Seguimiento en tiempo real a inspecciones realizadas por aprendices, evidencias y hallazgos.</p>
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
    <!-- BLOQUE PRINCIPAL: INSPECCIONES DE SEGURIDAD REPORTADAS                   -->
    <!-- ========================================================================= -->
    <div class="bg-white rounded-2xl border border-slate-200/80 border-l-[6px] border-l-orange-500 shadow-xs overflow-hidden">
        
        <!-- Header del Bloque -->
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center font-bold text-lg shrink-0">
                    <i class="fa-solid fa-magnifying-glass-location"></i>
                </div>
                <div>
                    <h2 class="text-lg font-extrabold text-slate-900">Inspecciones de Seguridad Realizadas</h2>
                    <p class="text-xs text-slate-400 font-semibold">Reportes de campo generados desde el panel de Aprendiz</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <span class="text-xs font-black tracking-widest text-slate-400 uppercase">
                    <span class="text-base text-orange-600 font-extrabold">{{ $inspecciones->count() }}</span> REGISTROS
                </span>
            </div>
        </div>

        <!-- Tabla de Inspecciones -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse table-auto">
                <thead>
                    <tr class="bg-slate-50/90 border-b border-slate-200/80 text-slate-500 text-[10px] font-bold uppercase tracking-wider">
                        <th class="py-3 px-3 text-center w-8">#</th>
                        <th class="py-3 px-3 whitespace-nowrap"><i class="fa-regular fa-calendar mr-1"></i> FECHA / HORA</th>
                        <th class="py-3 px-3 whitespace-nowrap"><i class="fa-solid fa-location-dot mr-1"></i> LUGAR</th>
                        <th class="py-3 px-3 whitespace-nowrap"><i class="fa-solid fa-list-check mr-1"></i> TIPO INSPECCIÓN</th>
                        <th class="py-3 px-3 whitespace-nowrap"><i class="fa-solid fa-user-check mr-1"></i> RESPONSABLE</th>
                        <th class="py-3 px-3 whitespace-nowrap"><i class="fa-solid fa-user-tie mr-1"></i> LÍDER UNIDAD</th>
                        <th class="py-3 px-3 max-w-[160px]"><i class="fa-regular fa-file-lines mr-1"></i> HALLAZGO / DESCRIPCIÓN</th>
                        <th class="py-3 px-3 text-center whitespace-nowrap"><i class="fa-regular fa-image mr-1"></i> EVIDENCIA</th>
                        <th class="py-3 px-3 text-center whitespace-nowrap"><i class="fa-solid fa-circle-check mr-1"></i> ESTADO</th>
                        <th class="py-3 px-3 text-center whitespace-nowrap"><i class="fa-solid fa-comment-dots mr-1"></i> GESTIÓN</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 text-xs font-medium">
                    @forelse($inspecciones as $index => $insp)
                        @php
                            $obs = json_decode($insp->observaciones, true);
                            if (!is_array($obs)) {
                                $obs = ['descripcion' => $insp->observaciones];
                            }
                            $responsable = $obs['responsable'] ?? ($insp->user ? ($insp->user->name ?? $insp->user->nombres) : 'Aprendiz');
                            $lugarNombre = $insp->lugar ? $insp->lugar->nombre : ($obs['lugar_nombre'] ?? 'Ambiente SENA');
                            $lider = $obs['lider_unidad'] ?? 'Coordinación SST';
                            $desc = $obs['descripcion'] ?? 'Sin descripción';
                            $evidencias = $obs['evidencias'] ?? [];
                            $revision = $obs['revision_admin'] ?? null;
                            $tipo = $insp->tipo_inspeccion ?? 'Inspección';

                            $badgeTipo = match(true) {
                                str_contains(strtolower($tipo), 'acto y condición') => 'bg-purple-50 text-purple-700 border-purple-200',
                                str_contains(strtolower($tipo), 'acto') => 'bg-amber-50 text-amber-700 border-amber-200',
                                default => 'bg-blue-50 text-blue-700 border-blue-200'
                            };

                            $estadoActual = strtolower($insp->estado ?? 'pendiente');
                            $badgeEstado = match($estadoActual) {
                                'completada' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'en_proceso' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'con_hallazgos' => 'bg-rose-50 text-rose-700 border-rose-200',
                                default => 'bg-amber-50 text-amber-700 border-amber-200'
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-3.5 px-3 text-center font-bold text-slate-400">{{ $index + 1 }}</td>
                            
                            <!-- 1. Fecha / Hora -->
                            <td class="py-3.5 px-3 whitespace-nowrap">
                                <span class="font-bold text-slate-900 block">{{ \Carbon\Carbon::parse($insp->fecha_inspeccion)->format('d/m/Y') }}</span>
                                <span class="text-[10px] text-slate-400 font-medium">{{ $insp->hora_inspeccion ? \Carbon\Carbon::parse($insp->hora_inspeccion)->format('h:i A') : 'N/A' }}</span>
                            </td>

                            <!-- 2. Lugar -->
                            <td class="py-3.5 px-3">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 font-bold text-[11px] border border-slate-200/80">
                                    <i class="fa-solid fa-location-dot text-orange-500 text-[10px]"></i>
                                    {{ $lugarNombre }}
                                </span>
                            </td>

                            <!-- 3. Tipo Inspección -->
                            <td class="py-3.5 px-3 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-bold border {{ $badgeTipo }}">
                                    {{ $tipo }}
                                </span>
                            </td>

                            <!-- 4. Responsable / Aprendiz -->
                            <td class="py-3.5 px-3">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center font-extrabold text-[10px] shrink-0 border border-slate-200">
                                        {{ strtoupper(substr($responsable, 0, 1)) }}
                                    </div>
                                    <span class="font-semibold text-slate-800 whitespace-nowrap">{{ $responsable }}</span>
                                </div>
                            </td>

                            <!-- 5. Líder Unidad -->
                            <td class="py-3.5 px-3 text-slate-600 font-medium whitespace-nowrap">
                                {{ $lider }}
                            </td>

                            <!-- 6. Descripción / Hallazgos -->
                            <td class="py-3.5 px-3 max-w-[160px]">
                                @if(!empty($desc) && $desc !== 'Sin descripción')
                                    <p class="line-clamp-2 text-slate-600 cursor-pointer hover:text-orange-600 transition-colors" onclick="showFullDesc(event, {{ json_encode($desc) }})" title="Clic para ver descripción completa">
                                        {{ $desc }}
                                    </p>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">Sin observaciones</span>
                                @endif
                            </td>

                            <!-- 7. Evidencias -->
                            <td class="py-3.5 px-3 text-center whitespace-nowrap">
                                @if(is_array($evidencias) && count($evidencias) > 0)
                                    <div class="flex items-center justify-center -space-x-2">
                                        @foreach(array_slice($evidencias, 0, 3) as $fotoIndex => $fotoUrl)
                                            <img src="{{ $fotoUrl }}" onclick="openImageModal({{ json_encode($fotoUrl) }})" class="w-8 h-8 rounded-lg object-cover border-2 border-white shadow-xs cursor-pointer hover:scale-110 hover:z-10 transition-transform" alt="Evidencia">
                                        @endforeach
                                        @if(count($evidencias) > 3)
                                            <span class="w-8 h-8 rounded-lg bg-slate-900 text-white font-bold text-[10px] flex items-center justify-center border-2 border-white shadow-xs">
                                                +{{ count($evidencias) - 3 }}
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">Sin fotos</span>
                                @endif
                            </td>

                            <!-- 8. Estado -->
                            <td class="py-3.5 px-3 text-center whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border capitalize {{ $badgeEstado }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $estadoActual === 'completada' ? 'bg-emerald-500' : ($estadoActual === 'con_hallazgos' ? 'bg-rose-500' : 'bg-amber-500') }}"></span>
                                    {{ str_replace('_', ' ', $estadoActual) }}
                                </span>
                            </td>

                            <!-- 9. Gestión -->
                            <td class="py-3.5 px-3 text-center whitespace-nowrap">
                                <button onclick="openManageModal({{ json_encode($insp) }}, {{ json_encode($obs) }})" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-orange-50 hover:bg-orange-100 text-orange-700 font-bold text-xs border border-orange-200/90 shadow-xs transition cursor-pointer hover:scale-105">
                                    <i class="fa-regular fa-comment-dots text-xs"></i>
                                    <span>Gestionar</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-12 px-6 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400 space-y-2">
                                    <i class="fa-solid fa-folder-open text-3xl text-slate-300"></i>
                                    <p class="text-sm font-semibold text-slate-600">No hay inspecciones registradas por los aprendices aún.</p>
                                    <p class="text-xs text-slate-400">Cuando un aprendiz complete una inspección desde su panel, aparecerá automáticamente aquí en tiempo real.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL: GESTIONAR INSPECCIÓN (ESTADO Y OBSERVACIÓN DEL ADMINISTRADOR)      -->
<!-- ========================================================================= -->
<div id="modalManage" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center hidden p-4">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 w-full max-w-xl overflow-hidden animate-in fade-in zoom-in duration-200">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-clipboard-check text-orange-500"></i>
                <span>Gestión y Seguimiento a Inspección</span>
            </h3>
            <button onclick="closeManageModal()" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition cursor-pointer">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="formManageInspeccion" action="" method="POST">
            @csrf
            <div class="p-6 space-y-4 text-xs max-h-[75vh] overflow-y-auto">
                <!-- Tarjeta Resumen -->
                <div class="bg-slate-50 rounded-xl p-4 border border-slate-200/80 space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-400 font-bold uppercase tracking-wider text-[10px]">Detalle del Reporte</span>
                        <span id="detailTipoBadge" class="px-2 py-0.5 rounded font-bold text-[10px] bg-orange-100 text-orange-700"></span>
                    </div>
                    <div class="grid grid-cols-2 gap-3 text-slate-700">
                        <div>
                            <span class="text-slate-400 block text-[10px]">Lugar / Ambiente:</span>
                            <span id="detailLugar" class="font-bold text-slate-900"></span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px]">Responsable (Aprendiz):</span>
                            <span id="detailResponsable" class="font-bold text-slate-900"></span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px]">Líder de Unidad:</span>
                            <span id="detailLider" class="font-bold text-slate-900"></span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px]">Fecha Registro:</span>
                            <span id="detailFecha" class="font-bold text-slate-900"></span>
                        </div>
                    </div>
                    <div class="pt-2 border-t border-slate-200/60">
                        <span class="text-slate-400 block text-[10px] mb-0.5">Hallazgo / Observaciones del Aprendiz:</span>
                        <p id="detailDesc" class="text-slate-700 italic bg-white p-2.5 rounded-lg border border-slate-200/70 text-xs"></p>
                    </div>

                    <!-- Galería de Evidencias en Modal -->
                    <div id="detailEvidenciasWrap" class="pt-2 border-t border-slate-200/60 hidden">
                        <span class="text-slate-400 block text-[10px] mb-1.5 font-bold uppercase">Fotografías de Evidencia:</span>
                        <div id="detailEvidenciasGrid" class="flex flex-wrap gap-2"></div>
                    </div>

                    <!-- Revisión Previa -->
                    <div id="prevRevisionWrap" class="pt-2 border-t border-slate-200/60 hidden">
                        <span class="text-emerald-700 block text-[10px] mb-0.5 font-bold">Última Gestión Registrada:</span>
                        <p id="prevRevisionText" class="text-slate-600 bg-emerald-50/60 p-2.5 rounded-lg border border-emerald-200/60 text-xs"></p>
                    </div>
                </div>

                <!-- Formulario de Atención -->
                <div class="space-y-3 pt-2">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Estado de la Inspección *</label>
                        <select name="estado" id="manageEstadoSelect" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 transition text-xs font-semibold bg-white">
                            <option value="pendiente">Pendiente (Requiere Revisión)</option>
                            <option value="en_proceso">En Proceso (Medidas en Ejecución)</option>
                            <option value="con_hallazgos">Con Hallazgos (Crítico / No Conforme)</option>
                            <option value="completada">Completada (Cerrada y Conforme)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Medidas Correctivas / Observación del Administrador</label>
                        <textarea name="observacion_admin" id="manageObservacionInput" rows="3" placeholder="Escriba las acciones tomadas, compromisos asignados o recomendaciones para este hallazgo..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 transition text-xs"></textarea>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex justify-end gap-2">
                <button type="button" onclick="closeManageModal()" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-200/70 transition cursor-pointer">Cerrar</button>
                <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-orange-600 hover:bg-orange-700 active:bg-orange-800 text-white shadow-xs transition cursor-pointer">Guardar Respuesta / Estado</button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL: VISOR DE IMAGEN EN TAMAÑO COMPLETO                                -->
<!-- ========================================================================= -->
<div id="modalImage" class="fixed inset-0 z-50 bg-slate-900/80 backdrop-blur-sm flex items-center justify-center hidden p-4" onclick="closeImageModal()">
    <div class="relative max-w-3xl max-h-[90vh] p-2" onclick="event.stopPropagation()">
        <button onclick="closeImageModal()" class="absolute -top-3 -right-3 w-8 h-8 rounded-full bg-white text-slate-800 flex items-center justify-center shadow-lg font-bold hover:bg-slate-100 transition cursor-pointer">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <img id="modalImageTarget" src="" class="rounded-2xl max-w-full max-h-[85vh] object-contain shadow-2xl border border-white/20" alt="Evidencia">
    </div>
</div>

<!-- ========================================================================= -->
<!-- POPOVER FLOTANTE PARA DESCRIPCIÓN                                        -->
<!-- ========================================================================= -->
<div id="descPopover" class="fixed z-50 hidden bg-white border border-slate-200 text-slate-800 p-4 rounded-2xl shadow-xl max-w-md text-xs leading-relaxed animate-in fade-in zoom-in duration-150">
    <div class="flex items-center justify-between gap-3 pb-2 mb-2 border-b border-slate-100">
        <span class="font-bold text-slate-900 flex items-center gap-1.5">
            <i class="fa-solid fa-align-left text-orange-500"></i> Descripción Completa
        </span>
        <button onclick="closeDescPopover()" class="w-6 h-6 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition cursor-pointer">
            <i class="fa-solid fa-xmark text-xs"></i>
        </button>
    </div>
    <p id="descPopoverText" class="text-slate-600 text-xs leading-relaxed"></p>
</div>

<script>
    function showFullDesc(event, text) {
        event.stopPropagation();
        const popover = document.getElementById('descPopover');
        const popoverText = document.getElementById('descPopoverText');
        popoverText.innerText = text;

        const rect = event.currentTarget.getBoundingClientRect();
        let top = rect.bottom + 8;
        let left = rect.left;

        const popoverWidth = 380;
        if (left + popoverWidth > window.innerWidth - 20) {
            left = Math.max(10, window.innerWidth - popoverWidth - 20);
        }

        if (top + 160 > window.innerHeight) {
            top = Math.max(10, rect.top - 160);
        }

        popover.style.top = top + 'px';
        popover.style.left = left + 'px';
        popover.classList.remove('hidden');
    }

    function closeDescPopover() {
        document.getElementById('descPopover').classList.add('hidden');
    }

    document.addEventListener('click', function (e) {
        const popover = document.getElementById('descPopover');
        if (popover && !popover.contains(e.target)) {
            popover.classList.add('hidden');
        }
    });

    function openImageModal(url) {
        document.getElementById('modalImageTarget').src = url;
        document.getElementById('modalImage').classList.remove('hidden');
    }

    function closeImageModal() {
        document.getElementById('modalImage').classList.add('hidden');
    }

    function openManageModal(insp, obs) {
        const form = document.getElementById('formManageInspeccion');
        form.action = "{{ url('Sst/admin/inspecciones/historial') }}/" + insp.id_inspeccion + "/atender";

        document.getElementById('detailTipoBadge').innerText = insp.tipo_inspeccion || 'Inspección';
        document.getElementById('detailLugar').innerText = (insp.lugar && insp.lugar.nombre) ? insp.lugar.nombre : (obs.lugar_nombre || 'Ambiente SENA');
        document.getElementById('detailResponsable').innerText = obs.responsable || 'Aprendiz';
        document.getElementById('detailLider').innerText = obs.lider_unidad || 'Coordinación SST';
        document.getElementById('detailFecha').innerText = insp.fecha_inspeccion + (insp.hora_inspeccion ? ' (' + insp.hora_inspeccion + ')' : '');
        document.getElementById('detailDesc').innerText = obs.descripcion || 'Sin descripción detallada registrada.';

        document.getElementById('manageEstadoSelect').value = insp.estado || 'pendiente';

        // Evidencias
        const grid = document.getElementById('detailEvidenciasGrid');
        grid.innerHTML = '';
        if (obs.evidencias && obs.evidencias.length > 0) {
            obs.evidencias.forEach(function(url) {
                const img = document.createElement('img');
                img.src = url;
                img.className = 'w-14 h-14 rounded-xl object-cover border border-slate-200 cursor-pointer hover:scale-105 transition';
                img.onclick = function() { openImageModal(url); };
                grid.appendChild(img);
            });
            document.getElementById('detailEvidenciasWrap').classList.remove('hidden');
        } else {
            document.getElementById('detailEvidenciasWrap').classList.add('hidden');
        }

        // Revisión previa
        const prevWrap = document.getElementById('prevRevisionWrap');
        if (obs.revision_admin) {
            document.getElementById('prevRevisionText').innerText = 
                (obs.revision_admin.revisado_por || 'Admin') + ' (' + (obs.revision_admin.fecha_revision || '') + '): ' + 
                (obs.revision_admin.observacion || 'Sin nota');
            prevWrap.classList.remove('hidden');
            document.getElementById('manageObservacionInput').value = obs.revision_admin.observacion || '';
        } else {
            prevWrap.classList.add('hidden');
            document.getElementById('manageObservacionInput').value = '';
        }

        document.getElementById('modalManage').classList.remove('hidden');
    }

    function closeManageModal() {
        document.getElementById('modalManage').classList.add('hidden');
    }
</script>
@endsection

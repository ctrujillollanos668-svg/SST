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
</style>

<div class="space-y-8 max-w-7xl mx-auto">

    <!-- 1. Encabezado General -->
    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4 transition-all duration-200">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center border border-orange-200/80 shadow-xs">
                <i class="fa-solid fa-clipboard-check text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Gestión y Respuesta a Eventos</h1>
                <p class="text-sm text-slate-500 font-medium mt-0.5">Seguimiento a reportes de aprendices y trabajadores, atención médica y medidas correctivas.</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-orange-50 text-orange-700 text-xs font-bold border border-orange-200">
                <span class="w-2 h-2 rounded-full bg-orange-500 animate-pulse"></span>
                Módulo en Línea con Aprendices
            </span>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- BLOQUE 1: ACCIDENTES LABORALES (Tarjeta con borde lateral Rojo)            -->
    <!-- ========================================================================= -->
    <div class="bg-white rounded-2xl border border-slate-200/80 border-l-[6px] border-l-rose-500 shadow-xs overflow-hidden">
        
        <!-- Header del Bloque Accidentes -->
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-lg shrink-0">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div>
                    <h2 class="text-lg font-extrabold text-slate-900">Accidentes Laborales</h2>
                    <p class="text-xs text-slate-400 font-semibold">Evaluación y Gestión de Riesgos</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <span class="text-xs font-black tracking-widest text-slate-400 uppercase">
                    <span class="text-base text-rose-600 font-extrabold" id="countAccidentes">1</span> REGISTROS
                </span>
            </div>
        </div>

        <!-- Barra de Filtro de Fecha -->
        <div class="p-4 bg-slate-50/60 border-b border-slate-100 flex flex-wrap items-center gap-3">
            <label class="text-xs font-bold text-slate-600">Fecha del Accidente:</label>
            <div class="relative w-48">
                <input type="date" id="dateFilterAccidente" class="w-full px-3 py-1.5 text-xs font-medium rounded-xl bg-white border border-slate-200 text-slate-700 focus:outline-none focus:border-rose-500 shadow-2xs">
            </div>
            <button onclick="filterAccidentes()" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-white hover:bg-slate-100 border border-slate-200 text-slate-700 text-xs font-bold shadow-2xs transition cursor-pointer">
                <i class="fa-solid fa-filter text-[10px] text-slate-400"></i>
                <span>Filtrar</span>
            </button>
            <button onclick="clearFilterAccidentes()" class="text-[11px] font-bold text-slate-400 hover:text-slate-600">Limpiar</button>
        </div>

        <!-- Tabla de Accidentes -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="tablaAccidentes">
                <thead>
                    <tr class="bg-slate-50/90 border-b border-slate-200/80 text-slate-500 text-[10px] font-bold uppercase tracking-wider">
                        <th class="py-3.5 px-4 text-center w-12">#</th>
                        <th class="py-3.5 px-4 whitespace-nowrap"><i class="fa-regular fa-calendar mr-1"></i> FECHA Y HORA</th>
                        <th class="py-3.5 px-4 whitespace-nowrap"><i class="fa-solid fa-location-dot mr-1"></i> UBICACIÓN</th>
                        <th class="py-3.5 px-4 whitespace-nowrap"><i class="fa-solid fa-circle-info mr-1"></i> TIPO DE RIESGO</th>
                        <th class="py-3.5 px-4 whitespace-nowrap"><i class="fa-solid fa-briefcase mr-1"></i> TIPO DE ACCIDENTE</th>
                        <th class="py-3.5 px-4 min-w-[220px]"><i class="fa-regular fa-file-lines mr-1"></i> DESCRIPCIÓN DEL ACCIDENTE</th>
                        <th class="py-3.5 px-4 text-center whitespace-nowrap"><i class="fa-solid fa-stethoscope mr-1"></i> GRAVEDAD</th>
                        <th class="py-3.5 px-4 text-center whitespace-nowrap"><i class="fa-regular fa-image mr-1"></i> EVIDENCIA</th>
                        <th class="py-3.5 px-4 whitespace-nowrap"><i class="fa-solid fa-user-pen mr-1"></i> CREADO POR</th>
                        <th class="py-3.5 px-4 whitespace-nowrap"><i class="fa-solid fa-users mr-1"></i> PERSONAS</th>
                        <th class="py-3.5 px-4 text-center whitespace-nowrap"><i class="fa-solid fa-comment-dots mr-1"></i> RESPUESTA</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 text-xs font-medium">
                    <!-- Fila Accidente 1 -->
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="py-4 px-4 text-center font-bold text-slate-400">1</td>
                        <td class="py-4 px-4 whitespace-nowrap">
                            <p class="font-bold text-slate-900">14/09/2026</p>
                            <span class="text-[10px] text-slate-400">10:45 AM</span>
                        </td>
                        <td class="py-4 px-4 font-semibold text-slate-800">Taller de Mecanización (Bloque B)</td>
                        <td class="py-4 px-4">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                Mecánico
                            </span>
                        </td>
                        <td class="py-4 px-4 font-semibold text-slate-800">Corte con Herramienta</td>
                        <td class="py-4 px-4 text-slate-600">
                            <p class="line-clamp-2">Aprendiz sufrió herida leve en la palma de la mano izquierda al manipular una segueta sin guantes de protección.</p>
                        </td>
                        <td class="py-4 px-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-800 border border-amber-300">
                                Leve
                            </span>
                        </td>
                        <td class="py-4 px-4 text-center">
                            <button onclick="showEvidence('Herida por segueta en mano izquierda', 'Corte de 2 cm superficial.')" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 inline-flex items-center justify-center transition cursor-pointer" title="Ver foto / evidencia adjunta">
                                <i class="fa-solid fa-camera text-xs"></i>
                            </button>
                        </td>
                        <td class="py-4 px-4">
                            <p class="font-bold text-slate-900">Juan Diego Gómez</p>
                            <span class="text-[10px] text-orange-600 font-bold">Aprendiz Mecánica</span>
                        </td>
                        <td class="py-4 px-4">
                            <span class="font-medium text-slate-700">Juan Diego Gómez</span>
                        </td>
                        <td class="py-4 px-4 text-center whitespace-nowrap">
                            <!-- Botón para Responder o ver la respuesta -->
                            <button onclick="openResponseModal('Accidente #1 - Corte en Mano', 'Juan Diego Gómez', 'Atención inmediata con curación, desinfección en enfermería y reporte a la póliza estudiantil. Se capacitó en uso de EPP.')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold border border-rose-200 shadow-2xs transition cursor-pointer">
                                <i class="fa-solid fa-reply text-[10px]"></i>
                                <span>Ver Respuesta</span>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>


    <!-- ========================================================================= -->
    <!-- BLOQUE 2: INCIDENTES DE TRABAJO (Tarjeta con borde lateral Amarillo)       -->
    <!-- ========================================================================= -->
    <div class="bg-white rounded-2xl border border-slate-200/80 border-l-[6px] border-l-amber-500 shadow-xs overflow-hidden">
        
        <!-- Header del Bloque Incidentes -->
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-lg shrink-0">
                    <i class="fa-solid fa-briefcase"></i>
                </div>
                <div>
                    <h2 class="text-lg font-extrabold text-slate-900">Incidentes de Trabajo (Casi-Accidentes)</h2>
                    <p class="text-xs text-slate-400 font-semibold">Evaluación y Gestión de Riesgos Preventivos</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <span class="text-xs font-black tracking-widest text-slate-400 uppercase">
                    <span class="text-base text-amber-600 font-extrabold" id="countIncidentes">2</span> REGISTROS
                </span>
            </div>
        </div>

        <!-- Barra de Filtro de Fecha -->
        <div class="p-4 bg-slate-50/60 border-b border-slate-100 flex flex-wrap items-center gap-3">
            <label class="text-xs font-bold text-slate-600">Fecha del Incidente:</label>
            <div class="relative w-48">
                <input type="date" id="dateFilterIncidente" class="w-full px-3 py-1.5 text-xs font-medium rounded-xl bg-white border border-slate-200 text-slate-700 focus:outline-none focus:border-amber-500 shadow-2xs">
            </div>
            <button onclick="filterIncidentes()" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-white hover:bg-slate-100 border border-slate-200 text-slate-700 text-xs font-bold shadow-2xs transition cursor-pointer">
                <i class="fa-solid fa-filter text-[10px] text-slate-400"></i>
                <span>Filtrar</span>
            </button>
            <button onclick="clearFilterIncidentes()" class="text-[11px] font-bold text-slate-400 hover:text-slate-600">Limpiar</button>
        </div>

        <!-- Tabla de Incidentes -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="tablaIncidentes">
                <thead>
                    <tr class="bg-slate-50/90 border-b border-slate-200/80 text-slate-500 text-[10px] font-bold uppercase tracking-wider">
                        <th class="py-3.5 px-4 text-center w-12">#</th>
                        <th class="py-3.5 px-4 whitespace-nowrap"><i class="fa-regular fa-calendar mr-1"></i> FECHA Y HORA</th>
                        <th class="py-3.5 px-4 whitespace-nowrap"><i class="fa-solid fa-location-dot mr-1"></i> UBICACIÓN</th>
                        <th class="py-3.5 px-4 whitespace-nowrap"><i class="fa-solid fa-circle-info mr-1"></i> TIPO DE RIESGO</th>
                        <th class="py-3.5 px-4 whitespace-nowrap"><i class="fa-solid fa-briefcase mr-1"></i> TIPO DE INCIDENTE</th>
                        <th class="py-3.5 px-4 min-w-[220px]"><i class="fa-regular fa-file-lines mr-1"></i> DESCRIPCIÓN DEL INCIDENTE</th>
                        <th class="py-3.5 px-4 text-center whitespace-nowrap"><i class="fa-solid fa-stethoscope mr-1"></i> GRAVEDAD</th>
                        <th class="py-3.5 px-4 text-center whitespace-nowrap"><i class="fa-regular fa-image mr-1"></i> EVIDENCIA</th>
                        <th class="py-3.5 px-4 whitespace-nowrap"><i class="fa-solid fa-user-pen mr-1"></i> CREADO POR</th>
                        <th class="py-3.5 px-4 whitespace-nowrap"><i class="fa-solid fa-users mr-1"></i> PERSONAS</th>
                        <th class="py-3.5 px-4 text-center whitespace-nowrap"><i class="fa-solid fa-comment-dots mr-1"></i> RESPUESTA</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 text-xs font-medium">
                    
                    <!-- Fila Incidente 1 -->
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="py-4 px-4 text-center font-bold text-slate-400">1</td>
                        <td class="py-4 px-4 whitespace-nowrap">
                            <p class="font-bold text-slate-900">12/09/2026</p>
                            <span class="text-[10px] text-slate-400">02:15 PM</span>
                        </td>
                        <td class="py-4 px-4 font-semibold text-slate-800">Pasillo Bloque C (Agroindustria)</td>
                        <td class="py-4 px-4">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-blue-50 text-blue-800 border border-blue-200">
                                Locativo
                            </span>
                        </td>
                        <td class="py-4 px-4 font-semibold text-slate-800">Piso Húmedo Resbaladizo</td>
                        <td class="py-4 px-4 text-slate-600">
                            <p class="line-clamp-2">Derrame de agua sin señalizar en la salida del laboratorio; una aprendiz resbaló pero logró sostenerse del pasamanos sin caer.</p>
                        </td>
                        <td class="py-4 px-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-50 text-blue-800 border border-blue-200">
                                Sin Lesión
                            </span>
                        </td>
                        <td class="py-4 px-4 text-center">
                            <button onclick="showEvidence('Piso húmedo sin cono preventivo', 'Foto tomada con el celular al charco de agua.')" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 inline-flex items-center justify-center transition cursor-pointer" title="Ver foto">
                                <i class="fa-solid fa-camera text-xs"></i>
                            </button>
                        </td>
                        <td class="py-4 px-4">
                            <p class="font-bold text-slate-900">Camila Silva Ortiz</p>
                            <span class="text-[10px] text-orange-600 font-bold">Aprendiz Agroindustria</span>
                        </td>
                        <td class="py-4 px-4">
                            <span class="font-medium text-slate-700">Camila Silva Ortiz</span>
                        </td>
                        <td class="py-4 px-4 text-center whitespace-nowrap">
                            <button onclick="openResponseModal('Incidente #1 - Piso Húmedo', 'Camila Silva Ortiz', 'Se procedió al secado inmediato del área y se instaló cono reflectivo de advertencia de piso mojado permanente.')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-800 text-xs font-bold border border-amber-200 shadow-2xs transition cursor-pointer">
                                <i class="fa-solid fa-reply text-[10px]"></i>
                                <span>Ver Respuesta</span>
                            </button>
                        </td>
                    </tr>

                    <!-- Fila Incidente 2 -->
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="py-4 px-4 text-center font-bold text-slate-400">2</td>
                        <td class="py-4 px-4 whitespace-nowrap">
                            <p class="font-bold text-slate-900">10/09/2026</p>
                            <span class="text-[10px] text-slate-400">08:30 AM</span>
                        </td>
                        <td class="py-4 px-4 font-semibold text-slate-800">Bodega de Almacenamiento</td>
                        <td class="py-4 px-4">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-purple-50 text-purple-800 border border-purple-200">
                                Físico / Caída de Objeto
                            </span>
                        </td>
                        <td class="py-4 px-4 font-semibold text-slate-800">Caja Mal Apilada</td>
                        <td class="py-4 px-4 text-slate-600">
                            <p class="line-clamp-2">Una caja de archivo cayó desde la estantería alta al pasar un instructor, cayendo a centímetros de su pie.</p>
                        </td>
                        <td class="py-4 px-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-50 text-blue-800 border border-blue-200">
                                Sin Lesión
                            </span>
                        </td>
                        <td class="py-4 px-4 text-center">
                            <button onclick="showEvidence('Estantería sobrecargada', 'Foto de las cajas inclinadas en el estante.')" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 inline-flex items-center justify-center transition cursor-pointer" title="Ver foto">
                                <i class="fa-solid fa-camera text-xs"></i>
                            </button>
                        </td>
                        <td class="py-4 px-4">
                            <p class="font-bold text-slate-900">Andrés Felipe Mora</p>
                            <span class="text-[10px] text-slate-500 font-bold">Instructor Agropecuario</span>
                        </td>
                        <td class="py-4 px-4">
                            <span class="font-medium text-slate-700">Andrés Felipe Mora</span>
                        </td>
                        <td class="py-4 px-4 text-center whitespace-nowrap">
                            <button onclick="openResponseModal('Incidente #2 - Caja Mal Apilada', 'Andrés Felipe Mora', '')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-orange-600 hover:bg-orange-700 text-white text-xs font-bold shadow-2xs transition cursor-pointer">
                                <i class="fa-solid fa-pen text-[10px]"></i>
                                <span>Responder</span>
                            </button>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- ========================================================================= -->
<!-- MODAL: RESPUESTA / ATENCIÓN DEL ADMINISTRADOR SST AL EVENTO                -->
<!-- ========================================================================= -->
<div id="modalResponse" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-100 animate-modal-pop">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
            <h3 class="font-extrabold text-slate-900 text-base flex items-center gap-2">
                <i class="fa-solid fa-comment-dots text-orange-600"></i>
                <span id="modalResponseTitle">Respuesta y Medidas Tomadas</span>
            </h3>
            <button onclick="closeResponseModal()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition cursor-pointer">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>

        <form onsubmit="submitResponseDemo(event)" class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Reportado por:</label>
                <input type="text" id="modalReporter" readonly class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-100 border border-slate-200 font-bold text-slate-700">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Protocolo / Tipo de Respuesta *</label>
                <select id="modalSelectProtocol" class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 text-slate-800 focus:outline-none focus:border-orange-500 font-semibold cursor-pointer">
                    <option>Primeros Auxilios Básicos y Remisión</option>
                    <option>Acción Correctiva Locativa / Mantenimiento</option>
                    <option>Capacitación y Refuerzo de Seguridad</option>
                    <option>Investigación Formal COPASST</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Detalle de la Respuesta y Acciones Tomadas *</label>
                <textarea id="modalResponseText" rows="4" required placeholder="Describe las medidas de atención, primeros auxilios, traslados o reparaciones que se ejecutaron..." class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-medium"></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Estado del Evento</label>
                <select class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 text-slate-800 focus:outline-none focus:border-orange-500 font-semibold cursor-pointer">
                    <option value="Atendido y Cerrado">✅ Atendido y Cerrado</option>
                    <option value="En Seguimiento">⏳ En Seguimiento</option>
                    <option value="En Investigación">🔍 En Investigación</option>
                </select>
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeResponseModal()" class="px-4 py-2 text-xs font-bold text-slate-600 rounded-xl hover:bg-slate-100 transition cursor-pointer">
                    Cerrar
                </button>
                <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-orange-600 hover:bg-orange-700 rounded-xl shadow-xs transition cursor-pointer flex items-center gap-1.5">
                    <i class="fa-solid fa-paper-plane text-xs"></i>
                    <span>Guardar Respuesta</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openResponseModal(title, reporter, existingResponse) {
        document.getElementById('modalResponseTitle').innerText = title;
        document.getElementById('modalReporter').value = reporter;
        document.getElementById('modalResponseText').value = existingResponse || '';
        document.getElementById('modalResponse').classList.remove('hidden');
    }

    function closeResponseModal() {
        document.getElementById('modalResponse').classList.add('hidden');
    }

    function submitResponseDemo(e) {
        e.preventDefault();
        alert('¡Respuesta guardada con éxito! El aprendiz podrá verla en su panel.');
        closeResponseModal();
    }

    function showEvidence(title, desc) {
        alert(`📸 EVIDENCIA ADJUNTA POR EL APRENDIZ:\n\nAsunto: ${title}\nDetalle: ${desc}`);
    }

    function filterAccidentes() {
        const val = document.getElementById('dateFilterAccidente').value;
        if (!val) {
            alert('Por favor selecciona una fecha');
            return;
        }
        alert(`Filtrando accidentes para la fecha: ${val}`);
    }

    function clearFilterAccidentes() {
        document.getElementById('dateFilterAccidente').value = '';
    }

    function filterIncidentes() {
        const val = document.getElementById('dateFilterIncidente').value;
        if (!val) {
            alert('Por favor selecciona una fecha');
            return;
        }
        alert(`Filtrando incidentes para la fecha: ${val}`);
    }

    function clearFilterIncidentes() {
        document.getElementById('dateFilterIncidente').value = '';
    }
</script>
@endsection

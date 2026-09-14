@extends('sst::components.layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 pb-12">

    <!-- 1. Cabecera Principal -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center border border-orange-200/80 shadow-xs">
                <i class="fa-solid fa-clipboard-check text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Registrar Nueva Inspección</h1>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Diligencia los detalles de la inspección en los ambientes y sedes del SENA.</p>
            </div>
        </div>

        <div>
            <a href="{{ route('SST.admin.inspecciones.historial') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 text-xs font-bold shadow-xs transition border border-slate-200">
                <i class="fa-solid fa-arrow-left text-xs text-slate-400"></i>
                <span>Volver al Historial</span>
            </a>
        </div>
    </div>

    <!-- 2. Tarjeta Única del Formulario Simple -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 md:p-8">
        <form id="simpleInspectionForm" onsubmit="handleSimpleSubmit(event)" class="space-y-6 text-xs">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                
                <!-- Título / Nombre de la Inspección -->
                <div class="md:col-span-2">
                    <label class="block font-bold text-slate-700 mb-1.5">Título / Nombre de la Inspección *</label>
                    <input type="text" id="inspTitle" required placeholder="Ej: Inspección mensual de extintores - Ambiente Software" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 transition text-sm text-slate-800 placeholder:text-slate-400">
                </div>

                <!-- Tipo de Inspección -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5">Tipo de Inspección *</label>
                    <select id="inspType" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 transition text-sm bg-white font-medium text-slate-700">
                        <option value="Extintores">Extintores y Red Contra Incendios</option>
                        <option value="Botiquines">Botiquines de Primeros Auxilios</option>
                        <option value="EPP">Uso de EPP y Elementos de Seguridad</option>
                        <option value="Locativa">Condiciones Locativas, Orden y Aseo</option>
                        <option value="Herramientas">Herramientas y Maquinaria</option>
                    </select>
                </div>

                <!-- Funcionario / Inspector -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5">Funcionario / Inspector Responsable *</label>
                    <input type="text" id="inspInspector" value="{{ Auth::check() ? Auth::user()->name : 'Carlos Trujillo (Funcionario SST)' }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 transition text-sm font-medium text-slate-700">
                </div>

                <!-- Ambiente de Formación / Lugar SENA -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5">Ambiente de Formación / Sede SENA *</label>
                    <select id="inspEnvironment" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 transition text-sm bg-white font-medium text-slate-700">
                        <option value="Ambiente de Software y TIC">Ambiente de Software y TIC</option>
                        <option value="Taller de Mecanización Agrícola">Taller de Mecanización Agrícola</option>
                        <option value="Laboratorio de Calidad y Alimentos">Laboratorio de Calidad y Alimentos</option>
                        <option value="Bloque A - Aulas de Formación">Bloque A - Aulas de Formación</option>
                        <option value="Bodega y Almacén General">Bodega y Almacén General</option>
                        <option value="Unidad de Ganadería y Pecuaria">Unidad de Ganadería y Pecuaria</option>
                        <option value="Casino y Cafetería SENA">Casino y Cafetería SENA</option>
                        <option value="Oficinas Administrativas">Oficinas Administrativas</option>
                    </select>
                </div>

                <!-- Fecha de Inspección -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5">Fecha de la Inspección *</label>
                    <input type="date" 
                           id="inspDate" 
                           name="inspDate"
                           value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" 
                           required 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 transition text-sm font-medium text-slate-700 bg-white">
                </div>

                <!-- Resultado General -->
                <div class="md:col-span-2">
                    <label class="block font-bold text-slate-700 mb-1.5">Resultado General de la Inspección *</label>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <label class="flex items-center gap-2.5 p-3 rounded-xl border border-emerald-200 bg-emerald-50/50 hover:bg-emerald-50 cursor-pointer transition">
                            <input type="radio" name="inspResult" value="Aprobado" checked class="text-emerald-600 focus:ring-emerald-500">
                            <span class="font-bold text-emerald-800 text-xs">🟢 Aprobado / Sin Novedad</span>
                        </label>
                        <label class="flex items-center gap-2.5 p-3 rounded-xl border border-amber-200 bg-amber-50/50 hover:bg-amber-50 cursor-pointer transition">
                            <input type="radio" name="inspResult" value="Con Observaciones" class="text-amber-600 focus:ring-amber-500">
                            <span class="font-bold text-amber-800 text-xs">🟡 Con Observaciones</span>
                        </label>
                        <label class="flex items-center gap-2.5 p-3 rounded-xl border border-rose-200 bg-rose-50/50 hover:bg-rose-50 cursor-pointer transition">
                            <input type="radio" name="inspResult" value="No Aprobado" class="text-rose-600 focus:ring-rose-500">
                            <span class="font-bold text-rose-800 text-xs">🔴 No Aprobado / Crítico</span>
                        </label>
                    </div>
                </div>

                <!-- Observaciones y Hallazgos -->
                <div class="md:col-span-2">
                    <label class="block font-bold text-slate-700 mb-1.5">Observaciones y Hallazgos</label>
                    <textarea id="inspNotes" rows="4" placeholder="Describa cualquier hallazgo, anomalía o recomendación encontrada durante la revisión en el ambiente..." class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100 transition text-sm text-slate-800 placeholder:text-slate-400"></textarea>
                </div>

            </div>

            <!-- Botones de Acción -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('SST.admin.inspecciones.historial') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition cursor-pointer">
                    Cancelar
                </a>
                <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl bg-orange-50 hover:bg-orange-100/80 active:bg-orange-200/70 text-orange-700 text-sm font-bold shadow-xs transition-all duration-200 cursor-pointer group border border-orange-200/90 hover:border-orange-300">
                    <i class="fa-solid fa-check text-xs group-hover:scale-110 transition-transform"></i>
                    <span>Guardar Inspección</span>
                </button>
            </div>

        </form>
    </div>

    <!-- Modal de Éxito -->
    <div id="modalSuccess" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center hidden p-4">
        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 w-full max-w-md overflow-hidden animate-in fade-in zoom-in duration-200 p-6 text-center">
            <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-4 border border-emerald-200/60 shadow-xs">
                <i class="fa-solid fa-circle-check text-2xl"></i>
            </div>
            <h3 class="text-lg font-extrabold text-slate-800">¡Inspección Guardada con Éxito!</h3>
            <p class="text-xs text-slate-500 font-medium mt-1 mb-6" id="successMsg">El registro fue guardado correctamente en el sistema SST.</p>
            <div class="flex items-center justify-center gap-3">
                <button onclick="resetAndClose()" class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition cursor-pointer">
                    Registrar Otra
                </button>
                <a href="{{ route('SST.admin.inspecciones.historial') }}" class="px-5 py-2 rounded-xl text-xs font-bold bg-orange-50 hover:bg-orange-100 active:bg-orange-200 text-orange-700 border border-orange-200 shadow-xs transition">
                    Ir al Historial
                </a>
            </div>
        </div>
    </div>

</div>

<script>
    function handleSimpleSubmit(e) {
        e.preventDefault();
        const title = document.getElementById('inspTitle').value;
        const env = document.getElementById('inspEnvironment').value;
        document.getElementById('successMsg').textContent = `La inspección "${title}" para el ambiente "${env}" se registró correctamente.`;
        document.getElementById('modalSuccess').classList.remove('hidden');
    }

    function resetAndClose() {
        document.getElementById('modalSuccess').classList.add('hidden');
        document.getElementById('simpleInspectionForm').reset();
    }
</script>
@endsection

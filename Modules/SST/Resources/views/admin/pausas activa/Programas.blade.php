@extends('sst::components.layouts.admin')

@section('title', 'Pausas Activas Programadas • SST')

@section('content')
<style>
    @keyframes modalPopIn {
        0% { opacity: 0; transform: scale(0.95) translateY(8px); }
        100% { opacity: 1; transform: scale(1) translateY(0); }
    }
    .animate-modal-pop {
        animation: modalPopIn 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>

<div class="space-y-6 max-w-7xl mx-auto">

    <!-- Mensaje Flash de éxito -->
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

    <!-- 1. Encabezado de la Sección -->
    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4 transition-all duration-200">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center border border-orange-200/80 shadow-xs">
                    <i class="fa-solid fa-spa text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Pausas Activas Programadas</h1>
                    <p class="text-sm text-slate-500 font-medium mt-0.5">Programación, registro y control de pausas ergonómicas, visuales y osteomusculares.</p>
                </div>
            </div>
        </div>

        <button onclick="openRegisterModal()" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-orange-50 hover:bg-orange-100/80 active:bg-orange-200/70 text-orange-700 text-sm font-bold shadow-xs transition-all duration-200 cursor-pointer group border border-orange-200/90 hover:border-orange-300">
            <i class="fa-solid fa-plus text-xs group-hover:rotate-90 transition-transform duration-300"></i>
            <span>Programar Nueva Pausa</span>
        </button>
    </div>

    <!-- 2. Resumen de Estado -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center font-bold text-lg shrink-0">
                <i class="fa-solid fa-calendar-days"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Programadas</p>
                <h4 class="text-xl font-extrabold text-slate-800">{{ $pausas->total() ?? count($pausas) }}</h4>
            </div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg shrink-0">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Sesiones Activas</p>
                <h4 class="text-xl font-extrabold text-slate-800">{{ $pausas->where('estado', 'activo')->count() }}</h4>
            </div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg shrink-0">
                <i class="fa-solid fa-user-check"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Asistencias Totales</p>
                <h4 class="text-xl font-extrabold text-slate-800">
                    {{ $pausas->sum(fn($p) => $p->asistencias ? $p->asistencias->count() : 0) }}
                </h4>
            </div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-lg shrink-0">
                <i class="fa-solid fa-database"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Conexión MySQL</p>
                <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600 mt-1">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> En Línea
                </span>
            </div>
        </div>
    </div>

    <!-- 3. Barra de Filtros -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="relative w-full md:w-80">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
            <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Buscar por área o tipo de pausa..." class="w-full pl-9 pr-4 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-medium">
        </div>

        <div class="flex items-center gap-2 w-full md:w-auto">
            <select id="statusFilter" onchange="filterTable()" class="px-3.5 py-2 text-xs font-bold rounded-xl bg-slate-50 border border-slate-200 text-slate-600 focus:outline-none focus:border-orange-500 cursor-pointer">
                <option value="">Todos los estados</option>
                <option value="activo">Activas</option>
                <option value="inactivo">Inactivas</option>
            </select>
        </div>
    </div>

    <!-- 4. Tabla Principal de Pausas Conectada a MySQL -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="pausasTable">
                <thead>
                    <tr class="bg-slate-50/90 border-b border-slate-200/80 text-slate-500 text-[11px] font-bold uppercase tracking-wider">
                        <th class="py-4 px-6">TIPO DE PAUSA ACTIVA</th>
                        <th class="py-4 px-6">ÁREA / LUGAR DESTINO</th>
                        <th class="py-4 px-6 text-center">FECHA</th>
                        <th class="py-4 px-6 text-center">HORARIO</th>
                        <th class="py-4 px-6 text-center">ASISTENCIAS</th>
                        <th class="py-4 px-6 text-center">ESTADO</th>
                        <th class="py-4 px-6 text-center">ACCIONES</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 text-xs font-medium">
                    @forelse($pausas as $p)
                        @php
                            $horaIni = $p->hora_inicio ? \Carbon\Carbon::parse($p->hora_inicio)->format('h:i A') : 'N/A';
                            $horaFin = $p->hora_fin ? \Carbon\Carbon::parse($p->hora_fin)->format('h:i A') : '';
                            $totalAsist = $p->asistencias ? $p->asistencias->count() : 0;
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors pausa-row">
                            <td class="py-4 px-6 font-bold text-slate-900 flex items-center gap-2.5">
                                <span class="w-8 h-8 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center text-xs shrink-0 border border-orange-100">
                                    <i class="fa-solid fa-person-walking"></i>
                                </span>
                                <div>
                                    <p class="font-bold text-slate-900 leading-tight">{{ $p->titulo }}</p>
                                    @if($p->descripcion)
                                        <p class="text-[10px] text-slate-400 truncate max-w-xs font-normal mt-0.5">{{ $p->descripcion }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4 px-6 text-slate-600 font-medium">
                                <i class="fa-solid fa-location-dot text-orange-500 mr-1 text-[11px]"></i>
                                {{ $p->lugar ?? 'Ambiente General' }}
                            </td>
                            <td class="py-4 px-6 text-center font-medium text-slate-600">
                                {{ $p->fecha ? \Carbon\Carbon::parse($p->fecha)->format('d/m/Y') : 'Hoy' }}
                            </td>
                            <td class="py-4 px-6 text-center font-bold text-slate-800">
                                {{ $horaIni }} {{ $horaFin ? '- ' . $horaFin : '' }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                    <i class="fa-solid fa-users text-[10px]"></i> {{ $totalAsist }} registrados
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if($p->estado === 'activo')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Activa
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Inactiva
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center whitespace-nowrap">
                                <button onclick="openAsistenciaModal({{ $p->id_pausa }}, '{{ addslashes($p->titulo) }}')" 
                                    class="px-3 py-1.5 text-[11px] font-bold text-orange-700 bg-orange-50 hover:bg-orange-100/80 active:bg-orange-200/70 rounded-xl border border-orange-200/90 hover:border-orange-300 transition shadow-2xs cursor-pointer inline-flex items-center gap-1.5">
                                    <i class="fa-solid fa-clipboard-user text-[10px]"></i>
                                    <span>Asistencia</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12 text-slate-400">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-2">
                                    <i class="fa-solid fa-spa text-lg"></i>
                                </div>
                                <p class="text-sm font-semibold">No se encontraron pausas activas programadas.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($pausas) && method_exists($pausas, 'hasPages') && $pausas->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $pausas->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Modal 1: Programar Nueva Pausa Activa -->
<div id="modalRegister" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-100 animate-modal-pop">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
            <h3 class="font-extrabold text-slate-900 text-base flex items-center gap-2">
                <i class="fa-solid fa-spa text-orange-600"></i>
                <span>Programar Nueva Pausa Activa</span>
            </h3>
            <button onclick="closeRegisterModal()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition cursor-pointer">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>

        <form action="{{ route('SST.admin.pausas_activas.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Título / Tipo de Pausa *</label>
                <input type="text" name="titulo" required placeholder="Ej: Gimnasia Cerebral y Relajación Visual" class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-semibold">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Descripción de la Actividad</label>
                <textarea name="descripcion" rows="2" placeholder="Ej: Ejercicios de cuello, hombros y muñecas para prevenir fatiga laboral..." class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-medium"></textarea>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Fecha *</label>
                    <input type="date" name="fecha" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-semibold">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Hora Inicio *</label>
                    <input type="time" name="hora_inicio" value="10:00" required class="w-full px-3 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-semibold">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Hora Fin</label>
                    <input type="time" name="hora_fin" value="10:15" class="w-full px-3 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-semibold">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Lugar / Área Destino *</label>
                <input type="text" name="lugar" list="lugaresList" required placeholder="Selecciona o escribe el lugar..." class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-semibold">
                <datalist id="lugaresList">
                    @foreach($lugares as $lug)
                        <option value="{{ $lug->nombre }}"></option>
                    @endforeach
                    <option value="Área Administrativa y Docentes"></option>
                    <option value="Salas de Cómputo e Informática"></option>
                    <option value="Ambientes de Taller y Agroindustria"></option>
                </datalist>
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeRegisterModal()" class="px-4 py-2 text-xs font-bold text-slate-600 rounded-xl hover:bg-slate-100 transition cursor-pointer">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-orange-600 hover:bg-orange-700 active:bg-orange-800 rounded-xl shadow-xs transition cursor-pointer flex items-center gap-1.5">
                    <i class="fa-solid fa-check text-xs"></i>
                    <span>Programar Sesión</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal 2: Registrar Asistencia a la Pausa Activa -->
<div id="modalAsistencia" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-100 animate-modal-pop">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
            <h3 class="font-extrabold text-slate-900 text-base flex items-center gap-2">
                <i class="fa-solid fa-clipboard-user text-orange-600"></i>
                <span>Registrar Asistencia</span>
            </h3>
            <button onclick="closeAsistenciaModal()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition cursor-pointer">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>

        <form action="{{ route('SST.admin.pausas_activas.asistencia') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="id_pausa" id="asistenciaPausaId">

            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">Pausa Seleccionada</label>
                <p id="asistenciaPausaTitulo" class="font-extrabold text-slate-800 text-xs px-3.5 py-2.5 bg-slate-50 rounded-xl border border-slate-200"></p>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Funcionario / Asistente *</label>
                <select name="user_id" required class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 text-slate-800 focus:outline-none focus:border-orange-500 font-semibold cursor-pointer">
                    <option value="">-- Selecciona el funcionario --</option>
                    @foreach($usuarios as $u)
                        <option value="{{ $u->id }}">{{ $u->full_name }} ({{ $u->email }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Persona Responsable / Instructor SST</label>
                <input type="text" name="persona_responsable" value="Líder Brigada SST" class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-medium">
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeAsistenciaModal()" class="px-4 py-2 text-xs font-bold text-slate-600 rounded-xl hover:bg-slate-100 transition cursor-pointer">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-orange-600 hover:bg-orange-700 active:bg-orange-800 rounded-xl shadow-xs transition cursor-pointer flex items-center gap-1.5">
                    <i class="fa-solid fa-check text-xs"></i>
                    <span>Confirmar Asistencia</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openRegisterModal() {
        document.getElementById('modalRegister').classList.remove('hidden');
    }

    function closeRegisterModal() {
        document.getElementById('modalRegister').classList.add('hidden');
    }

    function openAsistenciaModal(pausaId, pausaTitulo) {
        document.getElementById('asistenciaPausaId').value = pausaId;
        document.getElementById('asistenciaPausaTitulo').innerText = pausaTitulo;
        document.getElementById('modalAsistencia').classList.remove('hidden');
    }

    function closeAsistenciaModal() {
        document.getElementById('modalAsistencia').classList.add('hidden');
    }

    function filterTable() {
        const query = document.getElementById('searchInput').value.toLowerCase();
        const status = document.getElementById('statusFilter').value.toLowerCase();
        const rows = document.querySelectorAll('#pausasTable tbody tr.pausa-row');

        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            const matchesQuery = text.includes(query);
            const matchesStatus = !status || text.includes(status);
            row.style.display = (matchesQuery && matchesStatus) ? '' : 'none';
        });
    }
</script>
@endsection

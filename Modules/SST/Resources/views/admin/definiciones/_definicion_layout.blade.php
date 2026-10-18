@extends('sst::components.layouts.admin')

@section('title', 'Definiciones de ' . $titulo . ' • SST')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">

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

    <!-- 1. Encabezado de la Sección -->
    <div class="bg-white p-6 sm:p-7 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4 transition-all duration-200">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center border border-orange-200/80 shadow-xs">
                <i class="fa-solid {{ $icono }} text-xl"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <span class="px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider bg-orange-100 text-orange-700 border border-orange-200">
                        Glosario para Aprendices
                    </span>
                </div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight mt-1">Definiciones de {{ $titulo }}</h1>
                <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">Administra los conceptos técnicos y descripciones que los aprendices consultan en su módulo.</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button onclick="openModalCreate()" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-orange-50 hover:bg-orange-100/80 active:bg-orange-200/70 text-orange-700 text-sm font-bold shadow-xs transition-all duration-200 cursor-pointer group border border-orange-200/90 hover:border-orange-300">
                <i class="fa-solid fa-plus text-xs group-hover:rotate-90 transition-transform duration-300"></i>
                <span>Nueva Definición</span>
            </button>
        </div>
    </div>

    <!-- 2. Tarjetas de Resumen y Estado -->
    @php
        $total = $definiciones->count();
        $activas = $definiciones->where('estado', 'activo')->count();
        $inactivas = $definiciones->where('estado', 'inactivo')->count();
    @endphp
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase">Total Definiciones</p>
                <p class="text-2xl font-black text-slate-800">{{ $total }}</p>
                <p class="text-[11px] text-slate-500 font-medium mt-0.5">En la categoría {{ $titulo }}</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-book-bookmark"></i>
            </div>
        </div>

        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-emerald-600 uppercase">Visibles para Aprendices</p>
                <p class="text-2xl font-black text-emerald-700">{{ $activas }}</p>
                <p class="text-[11px] text-emerald-600 font-medium mt-0.5">Estado: Activo</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-eye"></i>
            </div>
        </div>

        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase">Ocultas / En Borrador</p>
                <p class="text-2xl font-black text-slate-700">{{ $inactivas }}</p>
                <p class="text-[11px] text-slate-400 font-medium mt-0.5">Estado: Inactivo</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center text-lg">
                <i class="fa-solid fa-eye-slash"></i>
            </div>
        </div>
    </div>

    <!-- 3. Barra de Búsqueda Rápida -->
    <div class="bg-white p-3.5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-3">
        <div class="relative w-full sm:w-80">
            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <i class="fa-solid fa-magnifying-glass text-xs"></i>
            </span>
            <input type="text" id="inputBusquedaDefinicion" onkeyup="filtrarDefiniciones()" placeholder="Buscar concepto o descripción..." class="w-full pl-9 pr-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-medium">
        </div>
        <div class="text-xs text-slate-400 font-medium flex items-center gap-1.5 self-end sm:self-center">
            <i class="fa-solid fa-info-circle text-orange-500"></i>
            <span>Los aprendices ven los conceptos ordenados alfabéticamente</span>
        </div>
    </div>

    <!-- 4. Tabla de Definiciones Registradas -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="tablaDefiniciones">
                <thead>
                    <tr class="bg-slate-50/90 border-b border-slate-200/80 text-slate-500 text-[11px] font-bold uppercase tracking-wider">
                        <th class="py-4 px-6 w-1/4">TÉRMINO / CONCEPTO</th>
                        <th class="py-4 px-6 w-1/4">DEFINICIÓN</th>
                        <th class="py-4 px-6 w-1/4">CÓMO PREVENIR</th>
                        <th class="py-4 px-6 text-center w-36">GRAVEDAD</th>
                        <th class="py-4 px-6 text-center w-28">ESTADO</th>
                        <th class="py-4 px-6 text-center w-28">ACCIONES</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 text-xs font-medium">
                    @forelse($definiciones as $item)
                        @php $isActivo = $item->estado === 'activo'; @endphp
                        <tr class="fila-definicion hover:bg-slate-50/60 transition">
                            <td class="py-4 px-6 align-top">
                                <div class="font-bold text-slate-900 text-[13px] flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full {{ $isActivo ? 'bg-orange-500' : 'bg-slate-300' }} shrink-0"></span>
                                    <span class="col-nombre">{{ $item->nombre }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 align-top">
                                <p class="text-slate-600 line-clamp-2 leading-relaxed col-desc">
                                    {{ $item->descripcion ?? 'Sin definición disponible.' }}
                                </p>
                            </td>
                            <td class="py-4 px-6 align-top">
                                <div class="flex items-start gap-1.5 text-slate-600">
                                    <i class="fa-solid fa-shield-halved text-emerald-500 text-[11px] mt-0.5 shrink-0"></i>
                                    <p class="line-clamp-2 leading-relaxed col-prevenir">
                                        {{ $item->como_prevenir ?: 'No registrada' }}
                                    </p>
                                </div>
                            </td>
                            <td class="py-4 px-6 align-top text-center">
                                <div class="flex flex-col items-center justify-center col-gravedad">
                                    @php
                                        $gravValor = ($item->tipo_gravedad === 'Otro' && $item->tipo_otro) ? $item->tipo_otro : $item->tipo_gravedad;
                                        $grav = strtolower($gravValor ?? '');
                                        $badgeClass = 'bg-slate-100 text-slate-600 border-slate-200';
                                        if (str_contains($grav, 'leve')) {
                                            $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                        } elseif (str_contains($grav, 'moderado')) {
                                            $badgeClass = 'bg-amber-50 text-amber-700 border-amber-200';
                                        } elseif (str_contains($grav, 'grave')) {
                                            $badgeClass = 'bg-orange-50 text-orange-700 border-orange-200';
                                        } elseif (str_contains($grav, 'mortal') || str_contains($grav, 'muy')) {
                                            $badgeClass = 'bg-rose-50 text-rose-700 border-rose-200';
                                        } elseif ($gravValor) {
                                            $badgeClass = 'bg-indigo-50 text-indigo-700 border-indigo-200';
                                        }
                                    @endphp
                                    @if($gravValor)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[10px] font-bold border {{ $badgeClass }}">
                                            <i class="fa-solid fa-triangle-exclamation text-[9px]"></i>
                                            {{ $gravValor }}
                                        </span>
                                    @else
                                        <span class="text-slate-400 text-[11px] italic">No asignada</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4 px-6 align-top text-center">
                                <form action="{{ route('SST.admin.definiciones.toggle_estado', $item->id_definicion) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold border transition cursor-pointer {{ $isActivo ? 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-500 border-slate-200 hover:bg-slate-200' }}" title="Clic para alternar visibilidad">
                                        <i class="fa-solid {{ $isActivo ? 'fa-check' : 'fa-ban' }}"></i>
                                        <span>{{ $isActivo ? 'Visible' : 'Oculto' }}</span>
                                    </button>
                                </form>
                            </td>
                            <td class="py-4 px-6 align-top text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button type="button" 
                                        onclick="openModalEdit(
                                            {{ $item->id_definicion }}, 
                                            @js($item->nombre), 
                                            @js($item->descripcion), 
                                            @js($item->como_prevenir), 
                                            @js($item->tipo_gravedad), 
                                            @js($item->tipo_otro), 
                                            @js($item->estado)
                                        )" 
                                        class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-orange-50 hover:text-orange-600 text-slate-500 flex items-center justify-center transition cursor-pointer" 
                                        title="Editar definición">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </button>
                                    <form action="{{ route('SST.admin.definiciones.destroy', $item->id_definicion) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta definición? Ya no estará visible para los aprendices.');" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-rose-50 hover:text-rose-600 text-slate-500 flex items-center justify-center transition cursor-pointer" title="Eliminar definición">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="filaVacia">
                            <td colspan="6" class="py-12 text-center text-slate-400 font-semibold">
                                <div class="w-12 h-12 mx-auto rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center text-xl mb-2">
                                    <i class="fa-regular fa-folder-open"></i>
                                </div>
                                <p class="text-xs">No hay definiciones registradas en la categoría {{ $titulo }}.</p>
                                <button onclick="openModalCreate()" class="mt-3 text-xs font-bold text-orange-600 hover:text-orange-700 underline cursor-pointer">
                                    Crear la primera definición ahora
                                </button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- MODAL CREAR DEFINICIÓN -->
<div id="modalCreateDefinicion" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl max-w-xl w-full p-6 shadow-2xl border border-slate-100 animate-in fade-in zoom-in-95 duration-200 my-8">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center font-bold text-sm border border-orange-100">
                    <i class="fa-solid {{ $icono }}"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-900 text-sm">Nueva Definición de {{ $titulo }}</h3>
                    <p class="text-[11px] text-slate-400 font-semibold">Glosario oficial y pautas de prevención para aprendices</p>
                </div>
            </div>
            <button onclick="closeModalCreate()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition cursor-pointer">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>

        <form action="{{ route('SST.admin.definiciones.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="tipo_categoria" value="{{ $categoria }}">

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nombre / Término de la Definición *</label>
                <input type="text" name="nombre" required placeholder="Ej: {{ $categoria === 'accidente' ? 'Caída a distinto nivel / Atrapamiento' : 'Nombre del concepto' }}" class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-semibold">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Definición Técnica para el Aprendiz *</label>
                <textarea name="descripcion" required rows="3" placeholder="Escribe aquí la definición clara y detallada según la normativa técnica..." class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-normal leading-relaxed"></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1 flex items-center gap-1.5">
                    <i class="fa-solid fa-shield-halved text-emerald-500"></i>
                    <span>¿Cómo Prevenir? (Medidas de Prevención)</span>
                </label>
                <textarea name="como_prevenir" rows="3" placeholder="Describe las medidas preventivas, precauciones y recomendaciones para evitar que ocurra..." class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-normal leading-relaxed"></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Tipo de Gravedad</label>
                <select name="tipo_gravedad" id="createTipoGravedad" onchange="toggleGravedadOtro(this, 'create')" class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-semibold">
                    <option value="">-- Seleccionar gravedad --</option>
                    <option value="Leve">Leve</option>
                    <option value="Moderado">Moderado</option>
                    <option value="Grave">Grave</option>
                    <option value="Mortal">Mortal / Muy Grave</option>
                    <option value="Otro">Otro</option>
                </select>
            </div>

            <!-- Campo Especifique Gravedad (Condicional para Otro) -->
            <div id="contenedorGravedadOtroCreate" class="hidden">
                <label class="block text-xs font-bold text-orange-600 mb-1">Especifique el nivel de gravedad *</label>
                <input type="text" name="tipo_otro" id="createGravedadOtro" placeholder="Ej: Crítico, Catastrófico, Severo..." class="w-full px-3.5 py-2 text-xs rounded-xl bg-orange-50/50 border border-orange-200 focus:outline-none focus:border-orange-500 font-semibold text-slate-800">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Visibilidad para el Aprendiz *</label>
                <select name="estado" required class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-semibold">
                    <option value="activo" selected>Activo (Visible en el glosario del aprendiz)</option>
                    <option value="inactivo">Inactivo (Oculto / En borrador)</option>
                </select>
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeModalCreate()" class="px-4 py-2 text-xs font-bold text-slate-600 rounded-xl hover:bg-slate-100 transition cursor-pointer">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-orange-600 hover:bg-orange-700 active:bg-orange-800 rounded-xl shadow-xs transition cursor-pointer flex items-center gap-1.5">
                    <i class="fa-solid fa-check text-xs"></i>
                    <span>Guardar y Publicar</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDITAR DEFINICIÓN -->
<div id="modalEditDefinicion" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl max-w-xl w-full p-6 shadow-2xl border border-slate-100 animate-in fade-in zoom-in-95 duration-200 my-8">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center font-bold text-sm border border-orange-100">
                    <i class="fa-solid fa-pen-to-square"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-900 text-sm">Editar Definición</h3>
                    <p class="text-[11px] text-slate-400 font-semibold">Actualiza el término, medidas de prevención y nivel de gravedad</p>
                </div>
            </div>
            <button onclick="closeModalEdit()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition cursor-pointer">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>

        <form id="formEditDefinicion" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nombre / Término de la Definición *</label>
                <input type="text" id="editNombre" name="nombre" required class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-semibold">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Definición Técnica para el Aprendiz *</label>
                <textarea id="editDescripcion" name="descripcion" required rows="3" class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-normal leading-relaxed"></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1 flex items-center gap-1.5">
                    <i class="fa-solid fa-shield-halved text-emerald-500"></i>
                    <span>¿Cómo Prevenir? (Medidas de Prevención)</span>
                </label>
                <textarea id="editComoPrevenir" name="como_prevenir" rows="3" class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-normal leading-relaxed"></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Tipo de Gravedad</label>
                <select id="editTipoGravedad" name="tipo_gravedad" onchange="toggleGravedadOtro(this, 'edit')" class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-semibold">
                    <option value="">-- Seleccionar gravedad --</option>
                    <option value="Leve">Leve</option>
                    <option value="Moderado">Moderado</option>
                    <option value="Grave">Grave</option>
                    <option value="Mortal">Mortal / Muy Grave</option>
                    <option value="Otro">Otro</option>
                </select>
            </div>

            <!-- Campo Especifique Gravedad (Condicional para Otro en Edición) -->
            <div id="contenedorGravedadOtroEdit" class="hidden">
                <label class="block text-xs font-bold text-orange-600 mb-1">Especifique el nivel de gravedad *</label>
                <input type="text" name="tipo_otro" id="editGravedadOtro" placeholder="Ej: Crítico, Catastrófico, Severo..." class="w-full px-3.5 py-2 text-xs rounded-xl bg-orange-50/50 border border-orange-200 focus:outline-none focus:border-orange-500 font-semibold text-slate-800">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Visibilidad para el Aprendiz *</label>
                <select id="editEstado" name="estado" required class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 font-semibold">
                    <option value="activo">Activo (Visible en el glosario del aprendiz)</option>
                    <option value="inactivo">Inactivo (Oculto / En borrador)</option>
                </select>
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeModalEdit()" class="px-4 py-2 text-xs font-bold text-slate-600 rounded-xl hover:bg-slate-100 transition cursor-pointer">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-orange-600 hover:bg-orange-700 active:bg-orange-800 rounded-xl shadow-xs transition cursor-pointer flex items-center gap-1.5">
                    <i class="fa-solid fa-check text-xs"></i>
                    <span>Actualizar Definición</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModalCreate() {
        document.getElementById('modalCreateDefinicion').classList.remove('hidden');
    }
    function closeModalCreate() {
        document.getElementById('modalCreateDefinicion').classList.add('hidden');
    }

    function toggleGravedadOtro(selectElement, context) {
        const container = context === 'create' 
            ? document.getElementById('contenedorGravedadOtroCreate') 
            : document.getElementById('contenedorGravedadOtroEdit');
        const input = context === 'create' 
            ? document.getElementById('createGravedadOtro') 
            : document.getElementById('editGravedadOtro');

        if (selectElement.value === 'Otro') {
            container.classList.remove('hidden');
            input.setAttribute('required', 'required');
            input.focus();
        } else {
            container.classList.add('hidden');
            input.removeAttribute('required');
            if (context === 'create') {
                input.value = '';
            }
        }
    }

    function openModalEdit(id, nombre, descripcion, comoPrevenir, tipoGravedad, tipoOtro, estado) {
        const form = document.getElementById('formEditDefinicion');
        form.action = "{{ url('Sst/admin/definiciones/update') }}/" + id;
        document.getElementById('editNombre').value = nombre || '';
        document.getElementById('editDescripcion').value = descripcion || '';
        document.getElementById('editComoPrevenir').value = comoPrevenir || '';
        
        const selectGrav = document.getElementById('editTipoGravedad');
        const inputOtro = document.getElementById('editGravedadOtro');
        const containerOtro = document.getElementById('contenedorGravedadOtroEdit');

        const opcionesEstandar = ['Leve', 'Moderado', 'Grave', 'Mortal'];

        if (tipoGravedad === 'Otro' || (tipoGravedad && !opcionesEstandar.includes(tipoGravedad))) {
            selectGrav.value = 'Otro';
            inputOtro.value = tipoOtro || tipoGravedad || '';
            containerOtro.classList.remove('hidden');
            inputOtro.setAttribute('required', 'required');
        } else {
            selectGrav.value = tipoGravedad || '';
            inputOtro.value = '';
            containerOtro.classList.add('hidden');
            inputOtro.removeAttribute('required');
        }

        document.getElementById('editEstado').value = estado || 'activo';
        document.getElementById('modalEditDefinicion').classList.remove('hidden');
    }
    function closeModalEdit() {
        document.getElementById('modalEditDefinicion').classList.add('hidden');
    }

    function filtrarDefiniciones() {
        const input = document.getElementById('inputBusquedaDefinicion');
        const filter = input.value.toLowerCase();
        const filas = document.querySelectorAll('.fila-definicion');

        filas.forEach(fila => {
            const nombre = (fila.querySelector('.col-nombre')?.textContent || '').toLowerCase();
            const desc = (fila.querySelector('.col-desc')?.textContent || '').toLowerCase();
            const prev = (fila.querySelector('.col-prevenir')?.textContent || '').toLowerCase();
            const grav = (fila.querySelector('.col-gravedad')?.textContent || '').toLowerCase();

            if (nombre.includes(filter) || desc.includes(filter) || prev.includes(filter) || grav.includes(filter)) {
                fila.style.display = '';
            } else {
                fila.style.display = 'none';
            }
        });
    }
</script>
@endsection


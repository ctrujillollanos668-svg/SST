<style>
    .submenu-transition {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .menu-wrapper.open .submenu-transition {
        max-height: 420px;
    }
    .arrow-icon {
        transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .menu-wrapper.open .arrow-icon {
        transform: rotate(180deg);
    }
    #sidebarMenu {
        transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1), left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    #sidebarMenu.sidebar-hidden {
        left: -270px;
    }
    #sidebarMenu nav::-webkit-scrollbar {
        width: 4px;
    }
    #sidebarMenu nav::-webkit-scrollbar-track {
        background: transparent;
    }
    #sidebarMenu nav::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 9999px;
    }

    /* MODO MINI APRENDIZ */
    #sidebarMenu.sidebar-mini {
        width: 76px !important;
        overflow: visible !important;
    }
    #sidebarMenu.sidebar-mini nav {
        overflow-y: visible !important;
        overflow-x: visible !important;
    }
    #sidebarMenu.sidebar-mini .sidebar-text-full {
        display: none !important;
    }
    #sidebarMenu.sidebar-mini .arrow-icon {
        display: none !important;
    }
    #sidebarMenu.sidebar-mini .submenu-transition {
        display: none !important;
    }
    #sidebarMenu.sidebar-mini .menu-btn-item {
        justify-content: center !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
    }
    #sidebarMenu.sidebar-mini .logo-full {
        display: none !important;
    }
    #sidebarMenu.sidebar-mini .logo-mini {
        display: flex !important;
    }

    /* MENÚ FLOTANTE FLYOUT (EN MODO MINI) */
    .sidebar-flyout {
        display: none !important;
    }
    #sidebarMenu.sidebar-mini .sidebar-flyout {
        display: block !important;
        position: absolute;
        left: 70px;
        top: 0;
        width: 230px;
        background: #ffffff;
        border-radius: 1rem;
        border: 1px solid rgba(226, 232, 240, 0.95);
        box-shadow: 0 16px 36px -4px rgba(15, 23, 42, 0.16), 0 4px 12px -2px rgba(0, 0, 0, 0.06);
        padding: 0.5rem;
        z-index: 100;
        opacity: 0;
        visibility: hidden;
        transform: translateX(8px);
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        pointer-events: none;
    }
    #sidebarMenu.sidebar-mini .menu-wrapper:hover .sidebar-flyout,
    #sidebarMenu.sidebar-mini .menu-wrapper.flyout-open .sidebar-flyout {
        opacity: 1 !important;
        visibility: visible !important;
        transform: translateX(0) !important;
        pointer-events: auto !important;
    }
</style>

<!-- SIDEBAR APRENDIZ SG-SST -->
<aside id="sidebarMenu" class="w-[270px] bg-white text-slate-600 flex flex-col h-screen fixed left-0 top-0 z-40 shadow-sm border-r border-slate-200/80 select-none">
    
    <!-- Logo & Header -->
    <div class="flex flex-col items-center pt-5 pb-4 px-3 border-b border-slate-100">
        <!-- Logo Grande -->
        <div class="logo-full flex flex-col items-center">
            <div class="w-14 h-14 bg-slate-50 rounded-2xl p-1.5 border border-slate-200/70 flex items-center justify-center mb-2 shadow-xs overflow-hidden">
                <img src="{{ asset('img/logodesst.jpeg') }}" alt="Logo SENA SST" class="max-w-full max-h-full object-contain rounded-xl">
            </div>
            <div class="text-[11px] text-center font-extrabold uppercase tracking-widest text-slate-800 leading-tight sidebar-text-full">
                Sistema de Gestión <span class="text-orange-600">SST</span>
            </div>
            <div class="inline-flex items-center gap-1.5 mt-1.5 px-2.5 py-0.5 rounded-full bg-slate-100 border border-slate-200/80 text-[10px] font-bold text-slate-600 sidebar-text-full">
                <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>
                La Angostura
            </div>
        </div>

        <!-- Logo Mini -->
        <div class="logo-mini hidden w-11 h-11 bg-slate-50 rounded-xl p-1 border border-slate-200/80 items-center justify-center shadow-xs overflow-hidden" title="SG-SST • La Angostura">
            <img src="{{ asset('img/logodesst.jpeg') }}" alt="Logo SST" class="max-w-full max-h-full object-contain rounded-lg">
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="py-3 flex-grow overflow-y-auto space-y-1 px-2.5 text-xs font-semibold">

        <!-- DEFINICIONES (Enlace Único Directo al Glosario Completo) -->
        @php
            $isDefiniciones = request()->routeIs('SST.aprendiz.definiciones*');
        @endphp
        <div class="relative menu-wrapper">
            <a href="{{ route('SST.aprendiz.definiciones') }}" 
               class="menu-btn-item flex items-center justify-between px-3 py-2.5 rounded-xl {{ $isDefiniciones ? 'text-orange-600 bg-orange-50/70 font-bold' : 'text-slate-700 hover:text-orange-600 hover:bg-slate-50' }} transition group cursor-pointer"
               title="Definiciones SST">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-xl {{ $isDefiniciones ? 'bg-orange-100 text-orange-600' : 'bg-slate-100 text-slate-500 group-hover:bg-orange-50 group-hover:text-orange-600' }} flex items-center justify-center transition shrink-0">
                        <i class="fa-solid fa-book text-xs"></i>
                    </span>
                    <span class="font-bold text-[13px] sidebar-text-full">Definiciones</span>
                </div>
            </a>
            <!-- Tarjeta Flotante (Flyout Mini) -->
            <div class="sidebar-flyout">
                <div class="px-3 py-2">
                    <a href="{{ route('SST.aprendiz.definiciones') }}" class="font-extrabold text-xs text-slate-800 hover:text-orange-600 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                        <span>Definiciones SST</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- TIPOS DE EVENTOS -->
        @php
            $isTiposEventos = request()->routeIs('SST.aprendiz.tipos_eventos.*');
        @endphp
        <div class="flex flex-col menu-wrapper relative {{ $isTiposEventos ? 'open' : '' }}" id="tiposEventosWrapper">
            <div class="menu-btn-item flex items-center justify-between px-3 py-2.5 rounded-xl {{ $isTiposEventos ? 'text-orange-600 bg-orange-50/70 font-bold' : 'text-slate-700 hover:text-orange-600 hover:bg-slate-50' }} transition cursor-pointer group" id="btnToggleTiposEventos">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-xl {{ $isTiposEventos ? 'bg-orange-100 text-orange-600' : 'bg-slate-100 text-slate-500 group-hover:bg-orange-50 group-hover:text-orange-600' }} flex items-center justify-center transition shrink-0">
                        <i class="fa-solid fa-list-check text-xs"></i>
                    </span>
                    <span class="font-bold text-[13px] sidebar-text-full">Tipos de Eventos</span>
                </div>
                <span class="text-[10px] {{ $isTiposEventos ? 'text-orange-600' : 'text-slate-400 group-hover:text-orange-600' }} arrow-icon" id="arrowTiposEventos">
                    <i class="fa-solid fa-chevron-down"></i>
                </span>
            </div>
            <!-- Acordeón Normal -->
            <ul class="submenu-transition bg-slate-50/70 rounded-xl mt-1 mx-1 p-1 border border-slate-100 space-y-0.5">
                @php $actAcc = request()->routeIs('SST.aprendiz.tipos_eventos.accidentes.*'); @endphp
                <li>
                    <a href="{{ route('SST.aprendiz.tipos_eventos.accidentes.index') }}" class="flex items-center pl-6 pr-3 py-1.5 rounded-lg transition font-medium {{ $actAcc ? 'text-orange-600 bg-white shadow-xs font-bold' : 'text-slate-600 hover:text-orange-600 hover:bg-white hover:shadow-xs' }}">
                        <span class="mr-2.5 text-xs text-orange-500 w-4 text-center"><i class="fa-solid fa-burst"></i></span> 
                        Accidentes
                    </a>
                </li>

                @php $actInc = request()->routeIs('SST.aprendiz.tipos_eventos.incidentes.*'); @endphp
                <li>
                    <a href="{{ route('SST.aprendiz.tipos_eventos.incidentes.index') }}" class="flex items-center pl-6 pr-3 py-1.5 rounded-lg transition font-medium {{ $actInc ? 'text-orange-600 bg-white shadow-xs font-bold' : 'text-slate-600 hover:text-orange-600 hover:bg-white hover:shadow-xs' }}">
                        <span class="mr-2.5 text-xs text-orange-500 w-4 text-center"><i class="fa-solid fa-triangle-exclamation"></i></span> 
                        Incidentes
                    </a>
                </li>

                @php $actRiesg = request()->routeIs('SST.aprendiz.tipos_eventos.riesgos.*'); @endphp
                <li>
                    <a href="{{ route('SST.aprendiz.tipos_eventos.riesgos.index') }}" class="flex items-center pl-6 pr-3 py-1.5 rounded-lg transition font-medium {{ $actRiesg ? 'text-orange-600 bg-white shadow-xs font-bold' : 'text-slate-600 hover:text-orange-600 hover:bg-white hover:shadow-xs' }}">
                        <span class="mr-2.5 text-xs text-orange-500 w-4 text-center"><i class="fa-solid fa-biohazard"></i></span> 
                        Riesgos
                    </a>
                </li>

                @php $actActos = request()->routeIs('SST.aprendiz.tipos_eventos.actos_inseguros.*'); @endphp
                <li>
                    <a href="{{ route('SST.aprendiz.tipos_eventos.actos_inseguros.index') }}" class="flex items-center pl-6 pr-3 py-1.5 rounded-lg transition font-medium {{ $actActos ? 'text-orange-600 bg-white shadow-xs font-bold' : 'text-slate-600 hover:text-orange-600 hover:bg-white hover:shadow-xs' }}">
                        <span class="mr-2.5 text-xs text-orange-500 w-4 text-center"><i class="fa-solid fa-lightbulb"></i></span> 
                        Actos Inseguros
                    </a>
                </li>

                @php $actCond = request()->routeIs('SST.aprendiz.tipos_eventos.condiciones_inseguras.*'); @endphp
                <li>
                    <a href="{{ route('SST.aprendiz.tipos_eventos.condiciones_inseguras.index') }}" class="flex items-center pl-6 pr-3 py-1.5 rounded-lg transition font-medium {{ $actCond ? 'text-orange-600 bg-white shadow-xs font-bold' : 'text-slate-600 hover:text-orange-600 hover:bg-white hover:shadow-xs' }}">
                        <span class="mr-2.5 text-xs text-orange-500 w-4 text-center"><i class="fa-solid fa-triangle-exclamation"></i></span> 
                        Condiciones Inseguras
                    </a>
                </li>

                @php $actLes = request()->routeIs('SST.aprendiz.tipos_eventos.lesiones.*'); @endphp
                <li>
                    <a href="{{ route('SST.aprendiz.tipos_eventos.lesiones.index') }}" class="flex items-center pl-6 pr-3 py-1.5 rounded-lg transition font-medium {{ $actLes ? 'text-orange-600 bg-white shadow-xs font-bold' : 'text-slate-600 hover:text-orange-600 hover:bg-white hover:shadow-xs' }}">
                        <span class="mr-2.5 text-xs text-orange-500 w-4 text-center"><i class="fa-solid fa-crutch"></i></span> 
                        Lesiones
                    </a>
                </li>

                @php $actEmerg = request()->routeIs('SST.aprendiz.tipos_eventos.tipos_emergencias.*'); @endphp
                <li>
                    <a href="{{ route('SST.aprendiz.tipos_eventos.tipos_emergencias.index') }}" class="flex items-center pl-6 pr-3 py-1.5 rounded-lg transition font-medium {{ $actEmerg ? 'text-orange-600 bg-white shadow-xs font-bold' : 'text-slate-600 hover:text-orange-600 hover:bg-white hover:shadow-xs' }}">
                        <span class="mr-2.5 text-xs text-orange-500 w-4 text-center"><i class="fa-solid fa-hospital"></i></span> 
                        Condiciones de Emergencias
                    </a>
                </li>
            </ul>
            <!-- Tarjeta Flotante (Flyout Mini) -->
            <div class="sidebar-flyout">
                <div class="px-3 py-2 border-b border-slate-100 flex items-center justify-between">
                    <span class="font-extrabold text-xs text-slate-800 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                        <span>Tipos de Eventos</span>
                    </span>
                    <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-orange-100 text-orange-700">7</span>
                </div>
                <div class="py-1 space-y-0.5">
                    <a href="{{ route('SST.aprendiz.tipos_eventos.accidentes.index') }}" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $actAcc ? 'bg-orange-50 text-orange-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-orange-600' }}">
                        <span>Accidentes</span>
                        @if($actAcc)<span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>@endif
                    </a>
                    <a href="{{ route('SST.aprendiz.tipos_eventos.incidentes.index') }}" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $actInc ? 'bg-orange-50 text-orange-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-orange-600' }}">
                        <span>Incidentes</span>
                        @if($actInc)<span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>@endif
                    </a>
                    <a href="{{ route('SST.aprendiz.tipos_eventos.riesgos.index') }}" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $actRiesg ? 'bg-orange-50 text-orange-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-orange-600' }}">
                        <span>Riesgos</span>
                        @if($actRiesg)<span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>@endif
                    </a>
                    <a href="{{ route('SST.aprendiz.tipos_eventos.actos_inseguros.index') }}" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $actActos ? 'bg-orange-50 text-orange-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-orange-600' }}">
                        <span>Actos Inseguros</span>
                        @if($actActos)<span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>@endif
                    </a>
                    <a href="{{ route('SST.aprendiz.tipos_eventos.condiciones_inseguras.index') }}" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $actCond ? 'bg-orange-50 text-orange-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-orange-600' }}">
                        <span>Condiciones Inseguras</span>
                        @if($actCond)<span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>@endif
                    </a>
                    <a href="{{ route('SST.aprendiz.tipos_eventos.lesiones.index') }}" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $actLes ? 'bg-orange-50 text-orange-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-orange-600' }}">
                        <span>Lesiones</span>
                        @if($actLes)<span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>@endif
                    </a>
                    <a href="{{ route('SST.aprendiz.tipos_eventos.tipos_emergencias.index') }}" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $actEmerg ? 'bg-orange-50 text-orange-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-orange-600' }}">
                        <span>Condiciones de Emergencias</span>
                        @if($actEmerg)<span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>@endif
                    </a>
                </div>
            </div>
        </div>

        <!-- EMERGENCIAS -->
        <div class="flex flex-col menu-wrapper relative" id="emergenciasWrapper">
            <div class="menu-btn-item flex items-center justify-between px-3 py-2.5 rounded-xl text-slate-700 hover:text-orange-600 hover:bg-slate-50 transition cursor-pointer group" id="btnToggleEmergencias">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center group-hover:bg-orange-50 group-hover:text-orange-600 transition shrink-0">
                        <i class="fa-solid fa-phone text-xs"></i>
                    </span>
                    <span class="font-bold text-[13px] sidebar-text-full">Emergencias</span>
                </div>
                <span class="text-[10px] text-slate-400 group-hover:text-orange-600 arrow-icon" id="arrowEmergencias">
                    <i class="fa-solid fa-chevron-down"></i>
                </span>
            </div>
            <ul class="submenu-transition bg-slate-50/70 rounded-xl mt-1 mx-1 p-1 border border-slate-100 space-y-0.5">
                <li><a href="{{ route('SST.aprendiz.emergencias.contactos') }}" class="flex items-center pl-7 pr-3 py-1.5 text-slate-600 rounded-lg hover:text-orange-600 hover:bg-white hover:shadow-xs transition font-medium"><span class="text-[6px] mr-2.5 text-slate-400"><i class="fa-solid fa-circle"></i></span> Contactos</a></li>
            </ul>
            <div class="sidebar-flyout">
                <div class="px-3 py-2 border-b border-slate-100">
                    <span class="font-extrabold text-xs text-slate-800">Emergencias</span>
                </div>
                <div class="py-1">
                    <a href="{{ route('SST.aprendiz.emergencias.contactos') }}" class="flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-orange-600 transition">Contactos de Emergencia</a>
                </div>
            </div>
        </div>


        <!-- INSPECCIONES -->
        @php
            $isInspecciones = request()->routeIs('SST.aprendiz.inspecciones.*');
        @endphp
        <div class="flex flex-col menu-wrapper relative {{ $isInspecciones ? 'open' : '' }}" id="inspeccionesWrapper">
            <div class="menu-btn-item flex items-center justify-between px-3 py-2.5 rounded-xl {{ $isInspecciones ? 'text-orange-600 bg-orange-50/70 font-bold' : 'text-slate-700 hover:text-orange-600 hover:bg-slate-50' }} transition cursor-pointer group" id="btnToggleInspecciones">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-xl {{ $isInspecciones ? 'bg-orange-100 text-orange-600' : 'bg-slate-100 text-slate-500 group-hover:bg-orange-50 group-hover:text-orange-600' }} flex items-center justify-center transition shrink-0">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <span class="font-bold text-[13px] sidebar-text-full">Inspecciones</span>
                </div>
                <span class="text-[10px] {{ $isInspecciones ? 'text-orange-600' : 'text-slate-400 group-hover:text-orange-600' }} arrow-icon" id="arrowInspecciones">
                    <i class="fa-solid fa-chevron-down"></i>
                </span>
            </div>
            <!-- Acordeón Normal -->
            <ul class="submenu-transition bg-slate-50/70 rounded-xl mt-1 mx-1 p-1 border border-slate-100 space-y-0.5">
                @php $actInspRegistrar = request()->routeIs('SST.aprendiz.inspecciones.registrar') || request()->routeIs('SST.aprendiz.inspecciones.realizar'); @endphp
                <li>
                    <a href="{{ route('SST.aprendiz.inspecciones.registrar') }}" 
                       class="flex items-center pl-7 pr-3 py-1.5 rounded-lg transition font-medium {{ $actInspRegistrar ? 'text-orange-600 bg-white shadow-xs font-bold' : 'text-slate-600 hover:text-orange-600 hover:bg-white hover:shadow-xs' }}">
                        <span class="text-[6px] mr-2.5 {{ $actInspRegistrar ? 'text-orange-500 scale-125' : 'text-slate-400' }}"><i class="fa-solid fa-circle"></i></span> 
                        Registrar Inspección
                    </a>
                </li>

                @php $actInspSeguimiento = request()->routeIs('SST.aprendiz.inspecciones.seguimiento') || request()->routeIs('SST.aprendiz.inspecciones.historial'); @endphp
                <li>
                    <a href="{{ route('SST.aprendiz.inspecciones.seguimiento') }}" class="flex items-center pl-7 pr-3 py-1.5 rounded-lg transition font-medium {{ $actInspSeguimiento ? 'text-orange-600 bg-white shadow-xs font-bold' : 'text-slate-600 hover:text-orange-600 hover:bg-white hover:shadow-xs' }}">
                        <span class="text-[6px] mr-2.5 {{ $actInspSeguimiento ? 'text-orange-500 scale-125' : 'text-slate-400' }}"><i class="fa-solid fa-circle"></i></span> 
                        Seguimiento
                    </a>
                </li>
            </ul>
            <!-- Tarjeta Flotante (Flyout Mini) -->
            <div class="sidebar-flyout">
                <div class="px-3 py-2 border-b border-slate-100 flex items-center justify-between">
                    <span class="font-extrabold text-xs text-slate-800 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                        <span>Inspecciones</span>
                    </span>
                </div>
                <div class="py-1 space-y-0.5">
                    <a href="{{ route('SST.aprendiz.inspecciones.registrar') }}" 
                       class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $actInspRegistrar ? 'bg-orange-50 text-orange-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-orange-600' }}">
                        <span>Registrar Inspección</span>
                        @if($actInspRegistrar)<span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>@endif
                    </a>
                    <a href="{{ route('SST.aprendiz.inspecciones.seguimiento') }}" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $actInspSeguimiento ? 'bg-orange-50 text-orange-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-orange-600' }}">
                        <span>Seguimiento</span>
                        @if($actInspSeguimiento)<span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>@endif
                    </a>
                </div>
            </div>
        </div>

        <!-- ACTIVIDADES SST -->
        @php
            $isActividades = request()->routeIs('SST.aprendiz.actividades_sst.*');
        @endphp
        <div class="flex flex-col menu-wrapper relative {{ $isActividades ? 'open' : '' }}" id="pausasWrapper">
            <div class="menu-btn-item flex items-center justify-between px-3 py-2.5 rounded-xl {{ $isActividades ? 'text-orange-600 bg-orange-50/70 font-bold' : 'text-slate-700 hover:text-orange-600 hover:bg-slate-50' }} transition cursor-pointer group" id="btnTogglePausas">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-xl {{ $isActividades ? 'bg-orange-100 text-orange-600' : 'bg-slate-100 text-slate-500 group-hover:bg-orange-50 group-hover:text-orange-600' }} flex items-center justify-center transition shrink-0">
                        <i class="fa-solid fa-person-chalkboard text-xs"></i>
                    </span>
                    <span class="font-bold text-[13px] sidebar-text-full">Actividades SST</span>
                </div>
                <span class="text-[10px] {{ $isActividades ? 'text-orange-600' : 'text-slate-400 group-hover:text-orange-600' }} arrow-icon" id="arrowPausas">
                    <i class="fa-solid fa-chevron-down"></i>
                </span>
            </div>
            <!-- Acordeón Normal -->
            <ul class="submenu-transition bg-slate-50/70 rounded-xl mt-1 mx-1 p-1 border border-slate-100 space-y-0.5">
                @php $actPausas = request()->routeIs('SST.aprendiz.actividades_sst.pausas_activas.*'); @endphp
                <li>
                    <a href="{{ route('SST.aprendiz.actividades_sst.pausas_activas.index') }}" class="flex items-center pl-7 pr-3 py-1.5 rounded-lg transition font-medium {{ $actPausas ? 'text-orange-600 bg-white shadow-xs font-bold' : 'text-slate-600 hover:text-orange-600 hover:bg-white hover:shadow-xs' }}">
                        <span class="text-[6px] mr-2.5 {{ $actPausas ? 'text-orange-500 scale-125' : 'text-slate-400' }}"><i class="fa-solid fa-circle"></i></span> 
                        Pausas Activas
                    </a>
                </li>
                @php $actCharlas = request()->routeIs('SST.aprendiz.actividades_sst.charlas.*'); @endphp
                <li>
                    <a href="{{ route('SST.aprendiz.actividades_sst.charlas.index') }}" class="flex items-center pl-7 pr-3 py-1.5 rounded-lg transition font-medium {{ $actCharlas ? 'text-orange-600 bg-white shadow-xs font-bold' : 'text-slate-600 hover:text-orange-600 hover:bg-white hover:shadow-xs' }}">
                        <span class="text-[6px] mr-2.5 {{ $actCharlas ? 'text-orange-500 scale-125' : 'text-slate-400' }}"><i class="fa-solid fa-circle"></i></span> 
                        Charlas
                    </a>
                </li>
                @php $actSensib = request()->routeIs('SST.aprendiz.actividades_sst.sensibilizacion.*'); @endphp
                <li>
                    <a href="{{ route('SST.aprendiz.actividades_sst.sensibilizacion.index') }}" class="flex items-center pl-7 pr-3 py-1.5 rounded-lg transition font-medium {{ $actSensib ? 'text-orange-600 bg-white shadow-xs font-bold' : 'text-slate-600 hover:text-orange-600 hover:bg-white hover:shadow-xs' }}">
                        <span class="text-[6px] mr-2.5 {{ $actSensib ? 'text-orange-500 scale-125' : 'text-slate-400' }}"><i class="fa-solid fa-circle"></i></span> 
                        Sensibilización
                    </a>
                </li>
            </ul>
            <!-- Tarjeta Flotante (Flyout Mini) -->
            <div class="sidebar-flyout">
                <div class="px-3 py-2 border-b border-slate-100 flex items-center justify-between">
                    <span class="font-extrabold text-xs text-slate-800 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                        <span>Actividades SST</span>
                    </span>
                </div>
                <div class="py-1 space-y-0.5">
                    <a href="{{ route('SST.aprendiz.actividades_sst.pausas_activas.index') }}" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $actPausas ? 'bg-orange-50 text-orange-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-orange-600' }}">
                        <span>Pausas Activas</span>
                        @if($actPausas)<span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>@endif
                    </a>
                    <a href="{{ route('SST.aprendiz.actividades_sst.charlas.index') }}" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $actCharlas ? 'bg-orange-50 text-orange-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-orange-600' }}">
                        <span>Charlas</span>
                        @if($actCharlas)<span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>@endif
                    </a>
                    <a href="{{ route('SST.aprendiz.actividades_sst.sensibilizacion.index') }}" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $actSensib ? 'bg-orange-50 text-orange-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-orange-600' }}">
                        <span>Sensibilización</span>
                        @if($actSensib)<span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>@endif
                    </a>
                </div>
            </div>
        </div>

        <!-- ASISTENCIA -->
        <div class="flex flex-col menu-wrapper relative" id="asistenciaWrapper">
            <div class="menu-btn-item flex items-center justify-between px-3 py-2.5 rounded-xl text-slate-700 hover:text-orange-600 hover:bg-slate-50 transition cursor-pointer group" id="btnToggleAsistencia">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center group-hover:bg-orange-50 group-hover:text-orange-600 transition shrink-0">
                        <i class="fa-solid fa-clipboard-user text-xs"></i>
                    </span>
                    <span class="font-bold text-[13px] sidebar-text-full">Asistencia</span>
                </div>
                <span class="text-[10px] text-slate-400 group-hover:text-orange-600 arrow-icon" id="arrowAsistencia">
                    <i class="fa-solid fa-chevron-down"></i>
                </span>
            </div>
            <ul class="submenu-transition bg-slate-50/70 rounded-xl mt-1 mx-1 p-1 border border-slate-100 space-y-0.5">
                <li><a href="{{ route('SST.aprendiz.asistencia.index') }}" class="flex items-center pl-7 pr-3 py-1.5 text-slate-600 rounded-lg hover:text-orange-600 hover:bg-white hover:shadow-xs transition font-medium"><span class="text-[6px] mr-2.5 text-slate-400"><i class="fa-solid fa-circle"></i></span> Control de Asistencias</a></li>
            </ul>
            <div class="sidebar-flyout">
                <div class="px-3 py-2 border-b border-slate-100">
                    <span class="font-extrabold text-xs text-slate-800">Asistencia</span>
                </div>
                <div class="py-1">
                    <a href="{{ route('SST.aprendiz.asistencia.index') }}" class="flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-orange-600 transition">Control de Asistencias</a>
                </div>
            </div>
        </div>

    </nav>

    <!-- Footer Sidebar -->
    <div class="p-3.5 border-t border-slate-100 bg-slate-50/50 text-center sidebar-text-full">
        <div class="text-[11px] font-bold text-slate-600">SG-SST v2.5</div>
        <div class="text-[10px] text-slate-400">Ambiente Aprendiz Seguro</div>
    </div>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebarMenu');

        const toggleMenu = (btnId, wrapperId) => {
            const btn = document.getElementById(btnId);
            const wrapper = document.getElementById(wrapperId);
            if (btn && wrapper) {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    if (sidebar && sidebar.classList.contains('sidebar-mini')) {
                        const isOpen = wrapper.classList.contains('flyout-open');
                        document.querySelectorAll('.menu-wrapper').forEach(w => w.classList.remove('flyout-open'));
                        if (!isOpen) {
                            wrapper.classList.add('flyout-open');
                        }
                    } else {
                        wrapper.classList.toggle('open');
                    }
                });
            }
        };

        toggleMenu('btnToggleDefAprendiz', 'defAprendizWrapper');
        toggleMenu('btnToggleTiposEventos', 'tiposEventosWrapper');
        toggleMenu('btnToggleEmergencias', 'emergenciasWrapper');
        toggleMenu('btnToggleInspecciones', 'inspeccionesWrapper');
        toggleMenu('btnTogglePausas', 'pausasWrapper');
        toggleMenu('btnToggleAsistencia', 'asistenciaWrapper');

        // Cerrar menú flotante al hacer clic afuera
        document.addEventListener('click', function(e) {
            if (sidebar && sidebar.classList.contains('sidebar-mini')) {
                if (!sidebar.contains(e.target)) {
                    document.querySelectorAll('.menu-wrapper').forEach(w => w.classList.remove('flyout-open'));
                }
            }
        });
    });
</script>

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
        /* Estilo de submódulos idéntico a la referencia */
    /* Estilo de submódulos idéntico a la referencia con movimiento interactivo */
    .submenu-item {
        color: #ffffff !important;
        text-decoration: none;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        border-left: 3px solid transparent;
        background-color: transparent;
    }
    .submenu-item:hover {
        background-color: rgba(255, 255, 255, 0.08) !important;
        color: #ffffff !important;
        transform: translateX(4px);
    }
    .submenu-item.active-sub {
        background-color: #1E4E70 !important;
        border-left: 3px solid #62E31D !important;
        color: #ffffff !important;
        font-weight: 700 !important;
        transform: translateX(3px);
    }
    .submenu-item .sub-dot {
        color: rgba(255, 255, 255, 0.45);
        transition: color 0.15s ease;
    }
    .submenu-item.active-sub .sub-dot {
        color: #ffffff !important;
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
        background: rgba(255, 255, 255, 0.15);
        border-radius: 9999px;
    }
    #sidebarMenu nav::-webkit-scrollbar-thumb:hover {
        background: #39A900;
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
        background: #0F3247;
        border-radius: 1rem;
        border: 1px solid #1A4B69;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5), 0 8px 10px -6px rgba(0, 0, 0, 0.4);
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

<!-- SIDEBAR APRENDIZ SG-SST (Estilo Ejecutivo Dirección más claro: Azul Petróleo #0A2333 / #0F3247, Verde SENA #39A900 y Neón #62E31D) -->
<aside id="sidebarMenu" class="w-[270px] bg-gradient-to-b from-[#0A2333] via-[#0F3247] to-[#0A2333] text-slate-200 flex flex-col h-screen fixed left-0 top-0 z-40 shadow-2xl border-r border-[#1A4B69] select-none">
    
    <!-- Logo & Header -->
    <div class="flex flex-col items-center pt-5 pb-4 px-3 border-b border-[#1A4B69] shrink-0">
        <!-- Logo Grande -->
        <div class="logo-full flex flex-col items-center">
            <div class="w-14 h-14 bg-white/10 backdrop-blur-sm rounded-2xl p-1.5 border border-white/20 flex items-center justify-center mb-2 shadow-lg overflow-hidden">
                <img src="{{ asset('img/logodesst.jpeg') }}" alt="Logo SENA SST" class="max-w-full max-h-full object-contain rounded-xl">
            </div>
            <div class="text-[11px] text-center font-extrabold uppercase tracking-widest text-white leading-tight sidebar-text-full">
                Portal Aprendiz <span class="text-[#62E31D]">SST</span>
            </div>
            <div class="inline-flex items-center gap-1.5 mt-1.5 px-2.5 py-0.5 rounded-full bg-[#1A4B69] border border-[#39A900]/40 text-[10px] font-bold text-[#62E31D] sidebar-text-full">
                <span class="w-1.5 h-1.5 rounded-full bg-[#62E31D] animate-pulse"></span>
                La Angostura
            </div>
        </div>

        <!-- Logo Mini -->
        <div class="logo-mini hidden w-11 h-11 bg-white/10 backdrop-blur-sm rounded-xl p-1 border border-white/20 items-center justify-center shadow-md overflow-hidden cursor-pointer" title="SG-SST • La Angostura">
            <img src="{{ asset('img/logodesst.jpeg') }}" alt="Logo SST" class="max-w-full max-h-full object-contain rounded-lg">
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="py-3 flex-grow overflow-y-auto space-y-1.5 px-2.5 text-xs font-semibold">

        <!-- DEFINICIONES (Enlace Único Directo al Glosario Completo) -->
        @php
            $isDefiniciones = request()->routeIs('SST.aprendiz.definiciones*');
        @endphp
        <div class="relative menu-wrapper">
            <a href="{{ route('SST.aprendiz.definiciones') }}" 
               class="menu-btn-item flex items-center justify-between px-3 py-2.5 rounded-xl {{ $isDefiniciones ? 'bg-white/10 text-white font-bold border border-white/10 shadow-xs' : 'text-white hover:text-white hover:bg-white/10' }} transition group cursor-pointer"
               title="Definiciones SST">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-xl {{ $isDefiniciones ? 'bg-[#39A900] text-white shadow-md shadow-[#39A900]/30 border border-[#62E31D]/40' : 'bg-white/10 text-white group-hover:bg-[#39A900] group-hover:text-white border border-white/5' }} flex items-center justify-center transition shrink-0">
                        <i class="fa-solid fa-book text-xs"></i>
                    </span>
                    <span class="font-bold text-[13px] sidebar-text-full">Definiciones</span>
                </div>
            </a>
            <!-- Tarjeta Flotante (Flyout Mini) -->
            <div class="sidebar-flyout">
                <div class="px-3 py-2 border-b border-[#1A4B69]">
                    <a href="{{ route('SST.aprendiz.definiciones') }}" class="font-extrabold text-xs text-white hover:text-[#62E31D] flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-[#62E31D]"></span>
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
            <div class="menu-btn-item flex items-center justify-between px-3 py-2.5 rounded-xl {{ $isTiposEventos ? 'bg-white/10 text-white font-bold border border-white/10 shadow-xs' : 'text-white hover:text-white hover:bg-white/10' }} transition cursor-pointer group" id="btnToggleTiposEventos">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-xl {{ $isTiposEventos ? 'bg-[#39A900] text-white shadow-md shadow-[#39A900]/30 border border-[#62E31D]/40' : 'bg-white/10 text-white group-hover:bg-[#39A900] group-hover:text-white border border-white/5' }} flex items-center justify-center transition shrink-0">
                        <i class="fa-solid fa-list-check text-xs"></i>
                    </span>
                    <span class="font-bold text-[13px] sidebar-text-full">Tipos de Eventos</span>
                </div>
                <span class="text-[10px] {{ $isTiposEventos ? 'text-[#62E31D]' : 'text-white/70 group-hover:text-white' }} arrow-icon" id="arrowTiposEventos">
                    <i class="fa-solid fa-chevron-down"></i>
                </span>
            </div>
            <!-- Acordeón Normal -->
            <ul class="submenu-transition bg-[#071D2B]/95 rounded-xl mt-1 mx-1 p-1.5 border border-[#1A4B69] space-y-0.5">
                @php $actAcc = request()->routeIs('SST.aprendiz.tipos_eventos.accidentes.*'); @endphp
                <li>
                    <a href="{{ route('SST.aprendiz.tipos_eventos.accidentes.index') }}" class="submenu-item flex items-center pl-7 pr-3 py-1.5 rounded-lg text-xs {{ $actAcc ? 'active-sub' : '' }}">
                        <span class="sub-dot text-[6px] mr-2.5"><i class="fa-solid fa-circle"></i></span> 
                        Accidentes
                    </a>
                </li>

                @php $actInc = request()->routeIs('SST.aprendiz.tipos_eventos.incidentes.*'); @endphp
                <li>
                    <a href="{{ route('SST.aprendiz.tipos_eventos.incidentes.index') }}" class="submenu-item flex items-center pl-7 pr-3 py-1.5 rounded-lg text-xs {{ $actInc ? 'active-sub' : '' }}">
                        <span class="sub-dot text-[6px] mr-2.5"><i class="fa-solid fa-circle"></i></span> 
                        Incidentes
                    </a>
                </li>

                @php $actRiesg = request()->routeIs('SST.aprendiz.tipos_eventos.riesgos.*'); @endphp
                <li>
                    <a href="{{ route('SST.aprendiz.tipos_eventos.riesgos.index') }}" class="submenu-item flex items-center pl-7 pr-3 py-1.5 rounded-lg text-xs {{ $actRiesg ? 'active-sub' : '' }}">
                        <span class="sub-dot text-[6px] mr-2.5"><i class="fa-solid fa-circle"></i></span> 
                        Riesgos
                    </a>
                </li>

                @php $actActos = request()->routeIs('SST.aprendiz.tipos_eventos.actos_inseguros.*'); @endphp
                <li>
                    <a href="{{ route('SST.aprendiz.tipos_eventos.actos_inseguros.index') }}" class="submenu-item flex items-center pl-7 pr-3 py-1.5 rounded-lg text-xs {{ $actActos ? 'active-sub' : '' }}">
                        <span class="sub-dot text-[6px] mr-2.5"><i class="fa-solid fa-circle"></i></span> 
                        Actos Inseguros
                    </a>
                </li>

                @php $actCond = request()->routeIs('SST.aprendiz.tipos_eventos.condiciones_inseguras.*'); @endphp
                <li>
                    <a href="{{ route('SST.aprendiz.tipos_eventos.condiciones_inseguras.index') }}" class="submenu-item flex items-center pl-7 pr-3 py-1.5 rounded-lg text-xs {{ $actCond ? 'active-sub' : '' }}">
                        <span class="sub-dot text-[6px] mr-2.5"><i class="fa-solid fa-circle"></i></span> 
                        Condiciones Inseguras
                    </a>
                </li>

                @php $actLes = request()->routeIs('SST.aprendiz.tipos_eventos.lesiones.*'); @endphp
                <li>
                    <a href="{{ route('SST.aprendiz.tipos_eventos.lesiones.index') }}" class="submenu-item flex items-center pl-7 pr-3 py-1.5 rounded-lg text-xs {{ $actLes ? 'active-sub' : '' }}">
                        <span class="sub-dot text-[6px] mr-2.5"><i class="fa-solid fa-circle"></i></span> 
                        Lesiones
                    </a>
                </li>

                @php $actEmerg = request()->routeIs('SST.aprendiz.tipos_eventos.tipos_emergencias.*'); @endphp
                <li>
                    <a href="{{ route('SST.aprendiz.tipos_eventos.tipos_emergencias.index') }}" class="submenu-item flex items-center pl-7 pr-3 py-1.5 rounded-lg text-xs {{ $actEmerg ? 'active-sub' : '' }}">
                        <span class="sub-dot text-[6px] mr-2.5"><i class="fa-solid fa-circle"></i></span> 
                        Condiciones de Emergencias
                    </a>
                </li>
            </ul>
            <!-- Tarjeta Flotante (Flyout Mini) -->
            <div class="sidebar-flyout">
                <div class="px-3 py-2 border-b border-[#1A4B69] flex items-center justify-between">
                    <span class="font-extrabold text-xs text-white flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-[#62E31D]"></span>
                        <span>Tipos de Eventos</span>
                    </span>
                    <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-[#1A4B69] text-[#62E31D] border border-[#39A900]/40">7</span>
                </div>
                <div class="py-1 space-y-0.5">
                    <a href="{{ route('SST.aprendiz.tipos_eventos.accidentes.index') }}" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $actAcc ? 'bg-white/15 text-white font-bold border border-white/20' : 'text-white hover:bg-white/10 hover:text-white' }}">
                        <span>Accidentes</span>
                        @if($actAcc)<span class="w-1.5 h-1.5 rounded-full bg-[#62E31D]"></span>@endif
                    </a>
                    <a href="{{ route('SST.aprendiz.tipos_eventos.incidentes.index') }}" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $actInc ? 'bg-white/15 text-white font-bold border border-white/20' : 'text-white hover:bg-white/10 hover:text-white' }}">
                        <span>Incidentes</span>
                        @if($actInc)<span class="w-1.5 h-1.5 rounded-full bg-[#62E31D]"></span>@endif
                    </a>
                    <a href="{{ route('SST.aprendiz.tipos_eventos.riesgos.index') }}" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $actRiesg ? 'bg-white/15 text-white font-bold border border-white/20' : 'text-white hover:bg-white/10 hover:text-white' }}">
                        <span>Riesgos</span>
                        @if($actRiesg)<span class="w-1.5 h-1.5 rounded-full bg-[#62E31D]"></span>@endif
                    </a>
                    <a href="{{ route('SST.aprendiz.tipos_eventos.actos_inseguros.index') }}" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $actActos ? 'bg-white/15 text-white font-bold border border-white/20' : 'text-white hover:bg-white/10 hover:text-white' }}">
                        <span>Actos Inseguros</span>
                        @if($actActos)<span class="w-1.5 h-1.5 rounded-full bg-[#62E31D]"></span>@endif
                    </a>
                    <a href="{{ route('SST.aprendiz.tipos_eventos.condiciones_inseguras.index') }}" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $actCond ? 'bg-white/15 text-white font-bold border border-white/20' : 'text-white hover:bg-white/10 hover:text-white' }}">
                        <span>Condiciones Inseguras</span>
                        @if($actCond)<span class="w-1.5 h-1.5 rounded-full bg-[#62E31D]"></span>@endif
                    </a>
                    <a href="{{ route('SST.aprendiz.tipos_eventos.lesiones.index') }}" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $actLes ? 'bg-white/15 text-white font-bold border border-white/20' : 'text-white hover:bg-white/10 hover:text-white' }}">
                        <span>Lesiones</span>
                        @if($actLes)<span class="w-1.5 h-1.5 rounded-full bg-[#62E31D]"></span>@endif
                    </a>
                    <a href="{{ route('SST.aprendiz.tipos_eventos.tipos_emergencias.index') }}" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $actEmerg ? 'bg-white/15 text-white font-bold border border-white/20' : 'text-white hover:bg-white/10 hover:text-white' }}">
                        <span>Condiciones de Emergencias</span>
                        @if($actEmerg)<span class="w-1.5 h-1.5 rounded-full bg-[#62E31D]"></span>@endif
                    </a>
                </div>
            </div>
        </div>

        <!-- EMERGENCIAS (Enlace Único Directo) -->
        @php
            $isEmergencias = request()->routeIs('SST.aprendiz.emergencias*');
        @endphp
        <div class="relative menu-wrapper">
            <a href="{{ route('SST.aprendiz.emergencias.contactos') }}" 
               class="menu-btn-item flex items-center justify-between px-3 py-2.5 rounded-xl {{ $isEmergencias ? 'bg-white/10 text-white font-bold border border-white/10 shadow-xs' : 'text-white hover:text-white hover:bg-white/10' }} transition group cursor-pointer"
               title="Emergencias">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-xl {{ $isEmergencias ? 'bg-[#39A900] text-white shadow-md shadow-[#39A900]/30 border border-[#62E31D]/40' : 'bg-white/10 text-white group-hover:bg-[#39A900] group-hover:text-white border border-white/5' }} flex items-center justify-center transition shrink-0">
                        <i class="fa-solid fa-phone text-xs"></i>
                    </span>
                    <span class="font-bold text-[13px] sidebar-text-full">Emergencias</span>
                </div>
            </a>
            <!-- Tarjeta Flotante (Flyout Mini) -->
            <div class="sidebar-flyout">
                <div class="px-3 py-2 border-b border-[#1A4B69]">
                    <a href="{{ route('SST.aprendiz.emergencias.contactos') }}" class="font-extrabold text-xs text-white hover:text-[#62E31D] flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-[#62E31D]"></span>
                        <span>Emergencias</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- INSPECCIONES -->
        @php
            $isInspecciones = request()->routeIs('SST.aprendiz.inspecciones.*');
        @endphp
        <div class="flex flex-col menu-wrapper relative {{ $isInspecciones ? 'open' : '' }}" id="inspeccionesWrapper">
            <div class="menu-btn-item flex items-center justify-between px-3 py-2.5 rounded-xl {{ $isInspecciones ? 'bg-white/10 text-white font-bold border border-white/10 shadow-xs' : 'text-white hover:text-white hover:bg-white/10' }} transition cursor-pointer group" id="btnToggleInspecciones">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-xl {{ $isInspecciones ? 'bg-[#39A900] text-white shadow-md shadow-[#39A900]/30 border border-[#62E31D]/40' : 'bg-white/10 text-white group-hover:bg-[#39A900] group-hover:text-white border border-white/5' }} flex items-center justify-center transition shrink-0">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <span class="font-bold text-[13px] sidebar-text-full">Inspecciones</span>
                </div>
                <span class="text-[10px] {{ $isInspecciones ? 'text-[#62E31D]' : 'text-white/70 group-hover:text-white' }} arrow-icon" id="arrowInspecciones">
                    <i class="fa-solid fa-chevron-down"></i>
                </span>
            </div>
            <!-- Acordeón Normal -->
            <ul class="submenu-transition bg-[#071D2B]/95 rounded-xl mt-1 mx-1 p-1.5 border border-[#1A4B69] space-y-0.5">
                @php $actInspRegistrar = request()->routeIs('SST.aprendiz.inspecciones.registrar') || request()->routeIs('SST.aprendiz.inspecciones.realizar'); @endphp
                <li>
                    <a href="{{ route('SST.aprendiz.inspecciones.registrar') }}" 
                       class="submenu-item flex items-center pl-7 pr-3 py-1.5 rounded-lg text-xs {{ $actInspRegistrar ? 'active-sub' : '' }}">
                        <span class="sub-dot text-[6px] mr-2.5"><i class="fa-solid fa-circle"></i></span> 
                        Registrar Inspección
                    </a>
                </li>

                @php $actInspSeguimiento = request()->routeIs('SST.aprendiz.inspecciones.seguimiento') || request()->routeIs('SST.aprendiz.inspecciones.historial'); @endphp
                <li>
                    <a href="{{ route('SST.aprendiz.inspecciones.seguimiento') }}" class="submenu-item flex items-center pl-7 pr-3 py-1.5 rounded-lg text-xs {{ $actInspSeguimiento ? 'active-sub' : '' }}">
                        <span class="sub-dot text-[6px] mr-2.5"><i class="fa-solid fa-circle"></i></span> 
                        Seguimiento
                    </a>
                </li>
            </ul>
            <!-- Tarjeta Flotante (Flyout Mini) -->
            <div class="sidebar-flyout">
                <div class="px-3 py-2 border-b border-[#1A4B69] flex items-center justify-between">
                    <span class="font-extrabold text-xs text-white flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-[#62E31D]"></span>
                        <span>Inspecciones</span>
                    </span>
                </div>
                <div class="py-1 space-y-0.5">
                    <a href="{{ route('SST.aprendiz.inspecciones.registrar') }}" 
                       class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $actInspRegistrar ? 'bg-white/15 text-white font-bold border border-white/20' : 'text-white hover:bg-white/10 hover:text-white' }}">
                        <span>Registrar Inspección</span>
                        @if($actInspRegistrar)<span class="w-1.5 h-1.5 rounded-full bg-[#62E31D]"></span>@endif
                    </a>
                    <a href="{{ route('SST.aprendiz.inspecciones.seguimiento') }}" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $actInspSeguimiento ? 'bg-white/15 text-white font-bold border border-white/20' : 'text-white hover:bg-white/10 hover:text-white' }}">
                        <span>Seguimiento</span>
                        @if($actInspSeguimiento)<span class="w-1.5 h-1.5 rounded-full bg-[#62E31D]"></span>@endif
                    </a>
                </div>
            </div>
        </div>

        <!-- ACTIVIDADES SST -->
        @php
            $isActividades = request()->routeIs('SST.aprendiz.actividades_sst.*');
        @endphp
        <div class="flex flex-col menu-wrapper relative {{ $isActividades ? 'open' : '' }}" id="pausasWrapper">
            <div class="menu-btn-item flex items-center justify-between px-3 py-2.5 rounded-xl {{ $isActividades ? 'bg-white/10 text-white font-bold border border-white/10 shadow-xs' : 'text-white hover:text-white hover:bg-white/10' }} transition cursor-pointer group" id="btnTogglePausas">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-xl {{ $isActividades ? 'bg-[#39A900] text-white shadow-md shadow-[#39A900]/30 border border-[#62E31D]/40' : 'bg-white/10 text-white group-hover:bg-[#39A900] group-hover:text-white border border-white/5' }} flex items-center justify-center transition shrink-0">
                        <i class="fa-solid fa-person-chalkboard text-xs"></i>
                    </span>
                    <span class="font-bold text-[13px] sidebar-text-full">Actividades SST</span>
                </div>
                <span class="text-[10px] {{ $isActividades ? 'text-[#62E31D]' : 'text-white/70 group-hover:text-white' }} arrow-icon" id="arrowPausas">
                    <i class="fa-solid fa-chevron-down"></i>
                </span>
            </div>
            <!-- Acordeón Normal -->
            <ul class="submenu-transition bg-[#071D2B]/95 rounded-xl mt-1 mx-1 p-1.5 border border-[#1A4B69] space-y-0.5">
                @php $actPausas = request()->routeIs('SST.aprendiz.actividades_sst.pausas_activas.*'); @endphp
                <li>
                    <a href="{{ route('SST.aprendiz.actividades_sst.pausas_activas.index') }}" class="submenu-item flex items-center pl-7 pr-3 py-1.5 rounded-lg text-xs {{ $actPausas ? 'active-sub' : '' }}">
                        <span class="sub-dot text-[6px] mr-2.5"><i class="fa-solid fa-circle"></i></span> 
                        Pausas Activas
                    </a>
                </li>
                @php $actCharlas = request()->routeIs('SST.aprendiz.actividades_sst.charlas.*'); @endphp
                <li>
                    <a href="{{ route('SST.aprendiz.actividades_sst.charlas.index') }}" class="submenu-item flex items-center pl-7 pr-3 py-1.5 rounded-lg text-xs {{ $actCharlas ? 'active-sub' : '' }}">
                        <span class="sub-dot text-[6px] mr-2.5"><i class="fa-solid fa-circle"></i></span> 
                        Charlas
                    </a>
                </li>
                @php $actSensib = request()->routeIs('SST.aprendiz.actividades_sst.sensibilizacion.*'); @endphp
                <li>
                    <a href="{{ route('SST.aprendiz.actividades_sst.sensibilizacion.index') }}" class="submenu-item flex items-center pl-7 pr-3 py-1.5 rounded-lg text-xs {{ $actSensib ? 'active-sub' : '' }}">
                        <span class="sub-dot text-[6px] mr-2.5"><i class="fa-solid fa-circle"></i></span> 
                        Sensibilización
                    </a>
                </li>
            </ul>
            <!-- Tarjeta Flotante (Flyout Mini) -->
            <div class="sidebar-flyout">
                <div class="px-3 py-2 border-b border-[#1A4B69] flex items-center justify-between">
                    <span class="font-extrabold text-xs text-white flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-[#62E31D]"></span>
                        <span>Actividades SST</span>
                    </span>
                </div>
                <div class="py-1 space-y-0.5">
                    <a href="{{ route('SST.aprendiz.actividades_sst.pausas_activas.index') }}" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $actPausas ? 'bg-white/15 text-white font-bold border border-white/20' : 'text-white hover:bg-white/10 hover:text-white' }}">
                        <span>Pausas Activas</span>
                        @if($actPausas)<span class="w-1.5 h-1.5 rounded-full bg-[#62E31D]"></span>@endif
                    </a>
                    <a href="{{ route('SST.aprendiz.actividades_sst.charlas.index') }}" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $actCharlas ? 'bg-white/15 text-white font-bold border border-white/20' : 'text-white hover:bg-white/10 hover:text-white' }}">
                        <span>Charlas</span>
                        @if($actCharlas)<span class="w-1.5 h-1.5 rounded-full bg-[#62E31D]"></span>@endif
                    </a>
                    <a href="{{ route('SST.aprendiz.actividades_sst.sensibilizacion.index') }}" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $actSensib ? 'bg-white/15 text-white font-bold border border-white/20' : 'text-white hover:bg-white/10 hover:text-white' }}">
                        <span>Sensibilización</span>
                        @if($actSensib)<span class="w-1.5 h-1.5 rounded-full bg-[#62E31D]"></span>@endif
                    </a>
                </div>
            </div>
        </div>

        <!-- ASISTENCIA (Enlace Único Directo) -->
        @php
            $isAsistencia = request()->routeIs('SST.aprendiz.asistencia*');
        @endphp
        <div class="relative menu-wrapper">
            <a href="{{ route('SST.aprendiz.asistencia.index') }}" 
               class="menu-btn-item flex items-center justify-between px-3 py-2.5 rounded-xl {{ $isAsistencia ? 'bg-white/10 text-white font-bold border border-white/10 shadow-xs' : 'text-white hover:text-white hover:bg-white/10' }} transition group cursor-pointer"
               title="Asistencia">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-xl {{ $isAsistencia ? 'bg-[#39A900] text-white shadow-md shadow-[#39A900]/30 border border-[#62E31D]/40' : 'bg-white/10 text-white group-hover:bg-[#39A900] group-hover:text-white border border-white/5' }} flex items-center justify-center transition shrink-0">
                        <i class="fa-solid fa-clipboard-user text-xs"></i>
                    </span>
                    <span class="font-bold text-[13px] sidebar-text-full">Asistencia</span>
                </div>
            </a>
            <!-- Tarjeta Flotante (Flyout Mini) -->
            <div class="sidebar-flyout">
                <div class="px-3 py-2 border-b border-[#1A4B69]">
                    <a href="{{ route('SST.aprendiz.asistencia.index') }}" class="font-extrabold text-xs text-white hover:text-[#62E31D] flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-[#62E31D]"></span>
                        <span>Asistencia</span>
                    </a>
                </div>
            </div>
        </div>

    </nav>

    <!-- Footer Sidebar -->
    <div class="p-3.5 border-t border-[#1A4B69] bg-[#0A2333] text-center sidebar-text-full shrink-0">
        <div class="text-[11px] font-bold text-slate-200">SG-SST <span class="text-[#62E31D]">v2.5</span></div>
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

        toggleMenu('btnToggleTiposEventos', 'tiposEventosWrapper');
        toggleMenu('btnToggleInspecciones', 'inspeccionesWrapper');
        toggleMenu('btnTogglePausas', 'pausasWrapper');

        // Mover instantáneamente el efecto activo al hacer clic en cualquier submódulo
        document.querySelectorAll('.submenu-item').forEach(function(item) {
            item.addEventListener('click', function() {
                document.querySelectorAll('.submenu-item').forEach(function(el) {
                    el.classList.remove('active-sub');
                });
                this.classList.add('active-sub');
            });
        });

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

<style>
    /* Transición para acordeón normal (Sidebar expandido) */
    .submenu-transition {
        max-height: 0;
        overflow: hidden;
        border: none !important;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
        margin-top: 0 !important;
        opacity: 0;
        pointer-events: none;
        transition: max-height 0.3s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.2s ease;
    }
    .menu-wrapper.open .submenu-transition {
        max-height: 480px;
        border: 1px solid #1A4B69 !important;
        padding-top: 0.375rem !important;
        padding-bottom: 0.375rem !important;
        margin-top: 0.25rem !important;
        opacity: 1;
        pointer-events: auto;
    }
    .arrow-icon {
        transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .menu-wrapper.open .arrow-icon {
        transform: rotate(180deg);
    }

    /* Estilos del Sidebar */
    #sidebarMenu {
        transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1), left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .logo-full, .logo-mini {
        background-color: transparent !important;
        box-shadow: none !important;
        text-decoration: none !important;
        outline: none !important;
    }
    .logo-full:hover, .logo-full:active, .logo-full:focus,
    .logo-mini:hover, .logo-mini:active, .logo-mini:focus {
        background-color: transparent !important;
        box-shadow: none !important;
        outline: none !important;
    }
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

    /* MODO MINI SIDEBAR (COMPACTO) */
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
        display: none;
    }
    #sidebarMenu.sidebar-mini .sidebar-flyout {
        display: block;
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

<!-- SIDEBAR ADMIN SG-SST (Estilo Ejecutivo Dirección más claro: Azul Petróleo #0A2333 / #0F3247, Verde SENA #39A900 y Neón #62E31D) -->
<aside id="sidebarMenu" class="w-[270px] bg-gradient-to-b from-[#0A2333] via-[#0F3247] to-[#0A2333] text-slate-200 flex flex-col h-screen fixed left-0 top-0 z-40 shadow-2xl border-r border-[#1A4B69] select-none">
    
    <!-- Logo & Header -->
    <div class="flex flex-col items-center pt-5 pb-4 px-3 border-b border-[#1A4B69] shrink-0">
        <!-- Logo Grande -->
        <a href="{{ route('SST.admin.dashboard') }}" class="logo-full flex flex-col items-center hover:opacity-95 transition group cursor-pointer" title="Ir al Panel de Gráficas y Métricas">
            <div class="w-14 h-14 bg-white/10 backdrop-blur-sm rounded-2xl p-1.5 border border-white/20 flex items-center justify-center mb-2 shadow-lg overflow-hidden group-hover:scale-105 group-hover:border-[#62E31D]/50 transition-all duration-200">
                <img src="{{ asset('img/logodesst.jpeg') }}" alt="Logo SENA SST" class="max-w-full max-h-full object-contain rounded-xl">
            </div>
            <div class="text-[11px] text-center font-extrabold uppercase tracking-widest text-white leading-tight sidebar-text-full group-hover:text-[#62E31D] transition-colors">
                Sistema de Gestión <span class="text-[#62E31D]">SST</span>
            </div>
            <div class="inline-flex items-center gap-1.5 mt-1.5 px-2.5 py-0.5 rounded-full bg-[#1A4B69] border border-[#39A900]/40 text-[10px] font-bold text-[#62E31D] sidebar-text-full group-hover:border-[#62E31D] transition-colors">
                <span class="w-1.5 h-1.5 rounded-full bg-[#62E31D] animate-pulse"></span>
                La Angostura
            </div>
        </a>

        <!-- Logo Mini (Modo Compacto) -->
        <a href="{{ route('SST.admin.dashboard') }}" class="logo-mini hidden w-11 h-11 bg-white/10 backdrop-blur-sm rounded-xl p-1 border border-white/20 items-center justify-center shadow-md overflow-hidden cursor-pointer hover:scale-105 hover:border-[#62E31D]/50 transition-all duration-200" title="SG-SST • Ir a Gráficas">
            <img src="{{ asset('img/logodesst.jpeg') }}" alt="Logo SST" class="max-w-full max-h-full object-contain rounded-lg">
        </a>
    </div>

    <!-- Navigation Menu -->
    <nav class="py-3 flex-grow overflow-y-auto space-y-1.5 px-2.5 text-xs font-semibold">


        <!-- 2. TIPOS DE EVENTOS -->
        @php
            $isTiposEventos = request()->routeIs('SST.admin.tipos_eventos.*');
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
                @php $actEvAcc = request()->routeIs('SST.admin.tipos_eventos.accidentes*'); @endphp
                <li><a href="{{ route('SST.admin.tipos_eventos.accidentes.index') }}" class="submenu-item flex items-center pl-7 pr-3 py-1.5 rounded-lg text-xs {{ $actEvAcc ? 'active-sub' : '' }}"><span class="sub-dot text-[6px] mr-2.5"><i class="fa-solid fa-circle"></i></span> Accidentes</a></li>
                @php $actEvInc = request()->routeIs('SST.admin.tipos_eventos.incidentes*'); @endphp
                <li><a href="{{ route('SST.admin.tipos_eventos.incidentes.index') }}" class="submenu-item flex items-center pl-7 pr-3 py-1.5 rounded-lg text-xs {{ $actEvInc ? 'active-sub' : '' }}"><span class="sub-dot text-[6px] mr-2.5"><i class="fa-solid fa-circle"></i></span> Incidentes</a></li>
                @php $actEvRie = request()->routeIs('SST.admin.tipos_eventos.riesgos*'); @endphp
                <li><a href="{{ route('SST.admin.tipos_eventos.riesgos.index') }}" class="submenu-item flex items-center pl-7 pr-3 py-1.5 rounded-lg text-xs {{ $actEvRie ? 'active-sub' : '' }}"><span class="sub-dot text-[6px] mr-2.5"><i class="fa-solid fa-circle"></i></span> Riesgos</a></li>
                @php $actEvAct = request()->routeIs('SST.admin.tipos_eventos.actos_inseguros*'); @endphp
                <li><a href="{{ route('SST.admin.tipos_eventos.actos_inseguros.index') }}" class="submenu-item flex items-center pl-7 pr-3 py-1.5 rounded-lg text-xs {{ $actEvAct ? 'active-sub' : '' }}"><span class="sub-dot text-[6px] mr-2.5"><i class="fa-solid fa-circle"></i></span> Actos Inseguros</a></li>
                @php $actEvCond = request()->routeIs('SST.admin.tipos_eventos.condiciones_inseguras*'); @endphp
                <li><a href="{{ route('SST.admin.tipos_eventos.condiciones_inseguras.index') }}" class="submenu-item flex items-center pl-7 pr-3 py-1.5 rounded-lg text-xs {{ $actEvCond ? 'active-sub' : '' }}"><span class="sub-dot text-[6px] mr-2.5"><i class="fa-solid fa-circle"></i></span> Condiciones Inseguras</a></li>
                @php $actEvLes = request()->routeIs('SST.admin.tipos_eventos.lesiones*'); @endphp
                <li><a href="{{ route('SST.admin.tipos_eventos.lesiones.index') }}" class="submenu-item flex items-center pl-7 pr-3 py-1.5 rounded-lg text-xs {{ $actEvLes ? 'active-sub' : '' }}"><span class="sub-dot text-[6px] mr-2.5"><i class="fa-solid fa-circle"></i></span> Lesiones</a></li>
                @php $actEvEme = request()->routeIs('SST.admin.tipos_eventos.tipos_emergencias*'); @endphp
                <li><a href="{{ route('SST.admin.tipos_eventos.tipos_emergencias.index') }}" class="submenu-item flex items-center pl-7 pr-3 py-1.5 rounded-lg text-xs {{ $actEvEme ? 'active-sub' : '' }}"><span class="sub-dot text-[6px] mr-2.5"><i class="fa-solid fa-circle"></i></span> Condiciones de Emergencias</a></li>
            </ul>
            <!-- Tarjeta Flotante (Flyout Mini) -->
            <div class="sidebar-flyout">
                <div class="px-3 py-2 border-b border-[#1A4B69] flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-[#62E31D]"></span>
                    <span class="font-extrabold text-xs text-white">Tipos de Eventos</span>
                </div>
                <div class="py-1 space-y-0.5">
                    <a href="{{ route('SST.admin.tipos_eventos.accidentes.index') }}" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $actEvAcc ? 'bg-white/15 text-white font-bold border border-white/20' : 'text-white hover:bg-white/10 hover:text-white' }}"><span>Accidentes</span> @if($actEvAcc)<span class="w-1.5 h-1.5 rounded-full bg-[#62E31D]"></span>@endif</a>
                    <a href="{{ route('SST.admin.tipos_eventos.incidentes.index') }}" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $actEvInc ? 'bg-white/15 text-white font-bold border border-white/20' : 'text-white hover:bg-white/10 hover:text-white' }}"><span>Incidentes</span> @if($actEvInc)<span class="w-1.5 h-1.5 rounded-full bg-[#62E31D]"></span>@endif</a>
                    <a href="{{ route('SST.admin.tipos_eventos.riesgos.index') }}" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $actEvRie ? 'bg-white/15 text-white font-bold border border-white/20' : 'text-white hover:bg-white/10 hover:text-white' }}"><span>Riesgos</span> @if($actEvRie)<span class="w-1.5 h-1.5 rounded-full bg-[#62E31D]"></span>@endif</a>
                    <a href="{{ route('SST.admin.tipos_eventos.actos_inseguros.index') }}" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $actEvAct ? 'bg-white/15 text-white font-bold border border-white/20' : 'text-white hover:bg-white/10 hover:text-white' }}"><span>Actos Inseguros</span> @if($actEvAct)<span class="w-1.5 h-1.5 rounded-full bg-[#62E31D]"></span>@endif</a>
                    <a href="{{ route('SST.admin.tipos_eventos.condiciones_inseguras.index') }}" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $actEvCond ? 'bg-white/15 text-white font-bold border border-white/20' : 'text-white hover:bg-white/10 hover:text-white' }}"><span>Condiciones Inseguras</span> @if($actEvCond)<span class="w-1.5 h-1.5 rounded-full bg-[#62E31D]"></span>@endif</a>
                    <a href="{{ route('SST.admin.tipos_eventos.lesiones.index') }}" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $actEvLes ? 'bg-white/15 text-white font-bold border border-white/20' : 'text-white hover:bg-white/10 hover:text-white' }}"><span>Lesiones</span> @if($actEvLes)<span class="w-1.5 h-1.5 rounded-full bg-[#62E31D]"></span>@endif</a>
                    <a href="{{ route('SST.admin.tipos_eventos.tipos_emergencias.index') }}" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $actEvEme ? 'bg-white/15 text-white font-bold border border-white/20' : 'text-white hover:bg-white/10 hover:text-white' }}"><span>Condiciones Emergencias</span> @if($actEvEme)<span class="w-1.5 h-1.5 rounded-full bg-[#62E31D]"></span>@endif</a>
                </div>
            </div>
        </div>

        <!-- 3. INFORMACIÓN BÁSICA -->
        @php
            $isInfoBasica = request()->routeIs('SST.admin.informacion_basica.*');
        @endphp
        <div class="flex flex-col menu-wrapper relative {{ $isInfoBasica ? 'open' : '' }}" id="informacionBasicaWrapper">
            <div class="menu-btn-item flex items-center justify-between px-3 py-2.5 rounded-xl {{ $isInfoBasica ? 'bg-white/10 text-white font-bold border border-white/10 shadow-xs' : 'text-white hover:text-white hover:bg-white/10' }} transition cursor-pointer group" id="btnToggleInformacionBasica">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-xl {{ $isInfoBasica ? 'bg-[#39A900] text-white shadow-md shadow-[#39A900]/30 border border-[#62E31D]/40' : 'bg-white/10 text-white group-hover:bg-[#39A900] group-hover:text-white border border-white/5' }} flex items-center justify-center transition shrink-0">
                        <i class="fa-solid fa-circle-info text-xs"></i>
                    </span>
                    <span class="font-bold text-[13px] sidebar-text-full">Información Básica</span>
                </div>
                <span class="text-[10px] {{ $isInfoBasica ? 'text-[#62E31D]' : 'text-white/70 group-hover:text-white' }} arrow-icon" id="arrowInformacionBasica">
                    <i class="fa-solid fa-chevron-down"></i>
                </span>
            </div>
            <!-- Acordeón Normal -->
            <ul class="submenu-transition bg-[#071D2B]/95 rounded-xl mt-1 mx-1 p-1.5 border border-[#1A4B69] space-y-0.5">
                @php $actInfCont = request()->routeIs('SST.admin.informacion_basica.contacto_emergencia*'); @endphp
                <li><a href="{{ route('SST.admin.informacion_basica.contacto_emergencia.index') }}" class="submenu-item flex items-center pl-7 pr-3 py-1.5 rounded-lg text-xs {{ $actInfCont ? 'active-sub' : '' }}"><span class="sub-dot text-[6px] mr-2.5"><i class="fa-solid fa-circle"></i></span> Contactos de Emergencia</a></li>
                @php $actInfLug = request()->routeIs('SST.admin.informacion_basica.lugar_informacion*'); @endphp
                <li><a href="{{ route('SST.admin.informacion_basica.lugar_informacion.index') }}" class="submenu-item flex items-center pl-7 pr-3 py-1.5 rounded-lg text-xs {{ $actInfLug ? 'active-sub' : '' }}"><span class="sub-dot text-[6px] mr-2.5"><i class="fa-solid fa-circle"></i></span> Lugares e Instalaciones</a></li>
                @php $actInfResp = request()->routeIs('SST.admin.informacion_basica.respuesta_evento*'); @endphp
                <li><a href="{{ route('SST.admin.informacion_basica.respuesta_eventos.index') }}" class="submenu-item flex items-center pl-7 pr-3 py-1.5 rounded-lg text-xs {{ $actInfResp ? 'active-sub' : '' }}"><span class="sub-dot text-[6px] mr-2.5"><i class="fa-solid fa-circle"></i></span> Respuesta a Eventos</a></li>
            </ul>
            <!-- Tarjeta Flotante (Flyout Mini) -->
            <div class="sidebar-flyout">
                <div class="px-3 py-2 border-b border-[#1A4B69] flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-[#62E31D]"></span>
                    <span class="font-extrabold text-xs text-white">Información Básica</span>
                </div>
                <div class="py-1 space-y-0.5">
                    <a href="{{ route('SST.admin.informacion_basica.contacto_emergencia.index') }}" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $actInfCont ? 'bg-white/15 text-white font-bold border border-white/20' : 'text-white hover:bg-white/10 hover:text-white' }}"><span>Contactos Emergencia</span> @if($actInfCont)<span class="w-1.5 h-1.5 rounded-full bg-[#62E31D]"></span>@endif</a>
                    <a href="{{ route('SST.admin.informacion_basica.lugar_informacion.index') }}" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $actInfLug ? 'bg-white/15 text-white font-bold border border-white/20' : 'text-white hover:bg-white/10 hover:text-white' }}"><span>Lugares e Instalaciones</span> @if($actInfLug)<span class="w-1.5 h-1.5 rounded-full bg-[#62E31D]"></span>@endif</a>
                    <a href="{{ route('SST.admin.informacion_basica.respuesta_eventos.index') }}" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $actInfResp ? 'bg-white/15 text-white font-bold border border-white/20' : 'text-white hover:bg-white/10 hover:text-white' }}"><span>Respuesta a Eventos</span> @if($actInfResp)<span class="w-1.5 h-1.5 rounded-full bg-[#62E31D]"></span>@endif</a>
                </div>
            </div>
        </div>

        <!-- 4. INSPECCIONES (Acceso Directo) -->
        @php
            $isInspecciones = request()->routeIs('SST.admin.inspecciones.*');
        @endphp
        <div class="relative menu-wrapper">
            <a href="{{ route('SST.admin.inspecciones.historial') }}" 
               class="menu-btn-item flex items-center justify-between px-3 py-2.5 rounded-xl {{ $isInspecciones ? 'bg-white/10 text-white font-bold border border-white/10 shadow-xs' : 'text-white hover:text-white hover:bg-white/10' }} transition group cursor-pointer"
               title="Inspecciones">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-xl {{ $isInspecciones ? 'bg-[#39A900] text-white shadow-md shadow-[#39A900]/30 border border-[#62E31D]/40' : 'bg-white/10 text-white group-hover:bg-[#39A900] group-hover:text-white border border-white/5' }} flex items-center justify-center transition shrink-0">
                        <i class="fa-solid fa-magnifying-glass-chart text-xs"></i>
                    </span>
                    <span class="font-bold text-[13px] sidebar-text-full">Inspecciones</span>
                </div>
            </a>
            <!-- Tarjeta Flotante (Flyout Mini) -->
            <div class="sidebar-flyout">
                <div class="px-3 py-2 border-b border-[#1A4B69]">
                    <a href="{{ route('SST.admin.inspecciones.historial') }}" class="font-extrabold text-xs text-white hover:text-[#62E31D] flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-[#62E31D]"></span>
                        <span>Inspecciones</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- 5. ACTIVIDADES SST -->
        @php
            $isPausas = request()->routeIs('SST.admin.actividades_sst.*') || request()->routeIs('SST.admin.pausas_activas.*') || request()->routeIs('SST.admin.charlas.*') || request()->routeIs('SST.admin.sensibilizacion.*') || request()->routeIs('SST.admin.cronograma.*');
        @endphp
        <div class="flex flex-col menu-wrapper relative {{ $isPausas ? 'open' : '' }}" id="pausasWrapper">
            <div class="menu-btn-item flex items-center justify-between px-3 py-2.5 rounded-xl {{ $isPausas ? 'bg-white/10 text-white font-bold border border-white/10 shadow-xs' : 'text-white hover:text-white hover:bg-white/10' }} transition cursor-pointer group" id="btnTogglePausas">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-xl {{ $isPausas ? 'bg-[#39A900] text-white shadow-md shadow-[#39A900]/30 border border-[#62E31D]/40' : 'bg-white/10 text-white group-hover:bg-[#39A900] group-hover:text-white border border-white/5' }} flex items-center justify-center transition shrink-0">
                        <i class="fa-solid fa-person-running text-xs"></i>
                    </span>
                    <span class="font-bold text-[13px] sidebar-text-full">Actividades SST</span>
                </div>
                <span class="text-[10px] {{ $isPausas ? 'text-[#62E31D]' : 'text-white/70 group-hover:text-white' }} arrow-icon" id="arrowPausas">
                    <i class="fa-solid fa-chevron-down"></i>
                </span>
            </div>
            <!-- Acordeón Normal -->
            <ul class="submenu-transition bg-[#071D2B]/95 rounded-xl mt-1 mx-1 p-1.5 border border-[#1A4B69] space-y-0.5">
                @php $actPausas = request()->routeIs('SST.admin.pausas_activas.*'); @endphp
                <li><a href="{{ route('SST.admin.pausas_activas.index') }}" class="submenu-item flex items-center pl-7 pr-3 py-1.5 rounded-lg text-xs {{ $actPausas ? 'active-sub' : '' }}"><span class="sub-dot text-[6px] mr-2.5"><i class="fa-solid fa-circle"></i></span> Pausas Activas</a></li>
                @php $actCharlas = request()->routeIs('SST.admin.charlas.*'); @endphp
                <li><a href="{{ route('SST.admin.charlas.index') }}" class="submenu-item flex items-center pl-7 pr-3 py-1.5 rounded-lg text-xs {{ $actCharlas ? 'active-sub' : '' }}"><span class="sub-dot text-[6px] mr-2.5"><i class="fa-solid fa-circle"></i></span> Charlas</a></li>
                @php $actSens = request()->routeIs('SST.admin.sensibilizacion.*'); @endphp
                <li><a href="{{ route('SST.admin.sensibilizacion.index') }}" class="submenu-item flex items-center pl-7 pr-3 py-1.5 rounded-lg text-xs {{ $actSens ? 'active-sub' : '' }}"><span class="sub-dot text-[6px] mr-2.5"><i class="fa-solid fa-circle"></i></span> Sensibilización</a></li>
                @php $actCron = request()->routeIs('SST.admin.cronograma.*'); @endphp
                <li><a href="{{ route('SST.admin.cronograma.calendario') }}" class="submenu-item flex items-center pl-7 pr-3 py-1.5 rounded-lg text-xs {{ $actCron ? 'active-sub' : '' }}"><span class="sub-dot text-[6px] mr-2.5"><i class="fa-solid fa-circle"></i></span> Calendario</a></li>
            </ul>
            <!-- Tarjeta Flotante (Flyout Mini) -->
            <div class="sidebar-flyout">
                <div class="px-3 py-2 border-b border-[#1A4B69] flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-[#62E31D]"></span>
                    <span class="font-extrabold text-xs text-white">Actividades SST</span>
                </div>
                <div class="py-1 space-y-0.5">
                    <a href="{{ route('SST.admin.pausas_activas.index') }}" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $actPausas ? 'bg-white/15 text-white font-bold border border-white/20' : 'text-white hover:bg-white/10 hover:text-white' }}"><span>Pausas Activas</span> @if($actPausas)<span class="w-1.5 h-1.5 rounded-full bg-[#62E31D]"></span>@endif</a>
                    <a href="{{ route('SST.admin.charlas.index') }}" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $actCharlas ? 'bg-white/15 text-white font-bold border border-white/20' : 'text-white hover:bg-white/10 hover:text-white' }}"><span>Charlas</span> @if($actCharlas)<span class="w-1.5 h-1.5 rounded-full bg-[#62E31D]"></span>@endif</a>
                    <a href="{{ route('SST.admin.sensibilizacion.index') }}" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $actSens ? 'bg-white/15 text-white font-bold border border-white/20' : 'text-white hover:bg-white/10 hover:text-white' }}"><span>Sensibilización</span> @if($actSens)<span class="w-1.5 h-1.5 rounded-full bg-[#62E31D]"></span>@endif</a>
                    <a href="{{ route('SST.admin.cronograma.calendario') }}" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $actCron ? 'bg-white/15 text-white font-bold border border-white/20' : 'text-white hover:bg-white/10 hover:text-white' }}"><span>Calendario</span> @if($actCron)<span class="w-1.5 h-1.5 rounded-full bg-[#62E31D]"></span>@endif</a>
                </div>
            </div>
        </div>


        <!-- 7. GESTIÓN USUARIOS -->
        @php
            $isUsuarios = request()->routeIs('SST.admin.usuarios.*');
        @endphp
        <div class="flex flex-col menu-wrapper relative {{ $isUsuarios ? 'open' : '' }}" id="usuariosWrapper">
            <div class="menu-btn-item flex items-center justify-between px-3 py-2.5 rounded-xl {{ $isUsuarios ? 'bg-white/10 text-white font-bold border border-white/10 shadow-xs' : 'text-white hover:text-white hover:bg-white/10' }} transition cursor-pointer group" id="btnToggleUsuarios">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-xl {{ $isUsuarios ? 'bg-[#39A900] text-white shadow-md shadow-[#39A900]/30 border border-[#62E31D]/40' : 'bg-white/10 text-white group-hover:bg-[#39A900] group-hover:text-white border border-white/5' }} flex items-center justify-center transition shrink-0">
                        <i class="fa-solid fa-users-gear text-xs"></i>
                    </span>
                    <span class="font-bold text-[13px] sidebar-text-full">Usuarios</span>
                </div>
                <span class="text-[10px] {{ $isUsuarios ? 'text-[#62E31D]' : 'text-white/70 group-hover:text-white' }} arrow-icon" id="arrowUsuarios">
                    <i class="fa-solid fa-chevron-down"></i>
                </span>
            </div>
            <!-- Acordeón Normal -->
            <ul class="submenu-transition bg-[#071D2B]/95 rounded-xl mt-1 mx-1 p-1.5 border border-[#1A4B69] space-y-0.5">
                @php $actUserReg = request()->routeIs('SST.admin.usuarios.index'); @endphp
                <li><a href="{{ route('SST.admin.usuarios.index') }}" class="submenu-item flex items-center pl-7 pr-3 py-1.5 rounded-lg text-xs {{ $actUserReg ? 'active-sub' : '' }}"><span class="sub-dot text-[6px] mr-2.5"><i class="fa-solid fa-circle"></i></span> Registrar Usuario</a></li>
            </ul>
            <!-- Tarjeta Flotante (Flyout Mini) -->
            <div class="sidebar-flyout">
                <div class="px-3 py-2 border-b border-[#1A4B69] flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-[#62E31D]"></span>
                    <span class="font-extrabold text-xs text-white">Usuarios</span>
                </div>
                <div class="py-1 space-y-0.5">
                    <a href="{{ route('SST.admin.usuarios.index') }}" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $actUserReg ? 'bg-white/15 text-white font-bold border border-white/20' : 'text-white hover:bg-white/10 hover:text-white' }}"><span>Registrar Usuario</span> @if($actUserReg)<span class="w-1.5 h-1.5 rounded-full bg-[#62E31D]"></span>@endif</a>
                </div>
            </div>
        </div>

    </nav>

    <!-- Footer Sidebar -->
    <div class="p-3.5 border-t border-[#1A4B69] bg-[#0A2333] text-center sidebar-text-full shrink-0">
        <div class="text-[11px] font-bold text-slate-200">SG-SST <span class="text-[#62E31D]">v2.5</span></div>
        <div class="text-[10px] text-slate-400">Ambiente Administrativo Seguro</div>
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
                        // En modo mini: abrir/cerrar tarjeta flotante por clic
                        const isOpen = wrapper.classList.contains('flyout-open');
                        document.querySelectorAll('.menu-wrapper').forEach(w => w.classList.remove('flyout-open'));
                        if (!isOpen) {
                            wrapper.classList.add('flyout-open');
                        }
                    } else {
                        // En modo expandido: abrir/cerrar acordeón hacia abajo
                        wrapper.classList.toggle('open');
                    }
                });
            }
        };

        toggleMenu('btnToggleTiposEventos', 'tiposEventosWrapper');
        toggleMenu('btnToggleInformacionBasica', 'informacionBasicaWrapper');
        toggleMenu('btnTogglePausas', 'pausasWrapper');
        toggleMenu('btnToggleUsuarios', 'usuariosWrapper');

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

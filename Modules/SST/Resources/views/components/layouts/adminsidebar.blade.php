<style>
    /* Transición para acordeón normal (Sidebar expandido) */
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

    /* Estilos del Sidebar */
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

    /* MODO MINI SIDEBAR (COMPACTO) */
    #sidebarMenu.sidebar-mini {
        width: 76px !important;
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
        left: 68px;
        top: 0;
        width: 224px;
        background: #ffffff;
        border-radius: 1rem;
        border: 1px solid rgba(226, 232, 240, 0.95);
        box-shadow: 0 14px 30px -4px rgba(15, 23, 42, 0.12), 0 4px 10px -2px rgba(0, 0, 0, 0.05);
        padding: 0.5rem;
        z-index: 60;
        opacity: 0;
        visibility: hidden;
        transform: translateX(8px);
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        pointer-events: none;
    }
    #sidebarMenu.sidebar-mini .menu-wrapper:hover .sidebar-flyout {
        opacity: 1;
        visibility: visible;
        transform: translateX(0);
        pointer-events: auto;
    }
</style>

<!-- SIDEBAR ADMIN SG-SST -->
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

        <!-- Logo Mini (Modo Compacto) -->
        <div class="logo-mini hidden w-11 h-11 bg-slate-50 rounded-xl p-1 border border-slate-200/80 items-center justify-center shadow-xs overflow-hidden" title="SG-SST • La Angostura">
            <img src="{{ asset('img/logodesst.jpeg') }}" alt="Logo SST" class="max-w-full max-h-full object-contain rounded-lg">
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="py-3 flex-grow overflow-y-auto space-y-1 px-2.5 text-xs font-semibold">

        <!-- 1. DEFINICIONES -->
        <div class="flex flex-col menu-wrapper relative" id="definicionesWrapper">
            <div class="menu-btn-item flex items-center justify-between px-3 py-2.5 rounded-xl text-slate-700 hover:text-orange-600 hover:bg-slate-50 transition cursor-pointer group" id="btnToggleDefiniciones">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center group-hover:bg-orange-50 group-hover:text-orange-600 transition shrink-0">
                        <i class="fa-solid fa-book text-xs"></i>
                    </span>
                    <span class="font-bold text-[13px] sidebar-text-full">Definiciones</span>
                </div>
                <span class="text-[10px] text-slate-400 group-hover:text-orange-600 arrow-icon" id="arrowDefiniciones">
                    <i class="fa-solid fa-chevron-down"></i>
                </span>
            </div>
            <!-- Acordeón Normal -->
            <ul class="submenu-transition bg-slate-50/70 rounded-xl mt-1 mx-1 p-1 border border-slate-100 space-y-0.5">
                <li><a href="#" class="flex items-center pl-7 pr-3 py-1.5 text-slate-600 rounded-lg hover:text-orange-600 hover:bg-white hover:shadow-xs transition font-medium"><span class="text-[6px] mr-2.5 text-slate-400"><i class="fa-solid fa-circle"></i></span> Accidentes</a></li>
                <li><a href="#" class="flex items-center pl-7 pr-3 py-1.5 text-slate-600 rounded-lg hover:text-orange-600 hover:bg-white hover:shadow-xs transition font-medium"><span class="text-[6px] mr-2.5 text-slate-400"><i class="fa-solid fa-circle"></i></span> Incidentes</a></li>
                <li><a href="#" class="flex items-center pl-7 pr-3 py-1.5 text-slate-600 rounded-lg hover:text-orange-600 hover:bg-white hover:shadow-xs transition font-medium"><span class="text-[6px] mr-2.5 text-slate-400"><i class="fa-solid fa-circle"></i></span> Riesgos</a></li>
                <li><a href="#" class="flex items-center pl-7 pr-3 py-1.5 text-slate-600 rounded-lg hover:text-orange-600 hover:bg-white hover:shadow-xs transition font-medium"><span class="text-[6px] mr-2.5 text-slate-400"><i class="fa-solid fa-circle"></i></span> Actos Inseguros</a></li>
                <li><a href="#" class="flex items-center pl-7 pr-3 py-1.5 text-slate-600 rounded-lg hover:text-orange-600 hover:bg-white hover:shadow-xs transition font-medium"><span class="text-[6px] mr-2.5 text-slate-400"><i class="fa-solid fa-circle"></i></span> Lesiones</a></li>
                <li><a href="#" class="flex items-center pl-7 pr-3 py-1.5 text-slate-600 rounded-lg hover:text-orange-600 hover:bg-white hover:shadow-xs transition font-medium"><span class="text-[6px] mr-2.5 text-slate-400"><i class="fa-solid fa-circle"></i></span> Tipos de Emergencias</a></li>
            </ul>
            <!-- Tarjeta Flotante (Flyout Mini) -->
            <div class="sidebar-flyout">
                <div class="px-3 py-2 border-b border-slate-100 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                    <span class="font-extrabold text-xs text-slate-800">Definiciones</span>
                </div>
                <div class="py-1 space-y-0.5">
                    <a href="#" class="flex items-center px-3 py-1.5 text-slate-600 rounded-lg hover:text-orange-600 hover:bg-orange-50 transition text-xs font-medium">Accidentes</a>
                    <a href="#" class="flex items-center px-3 py-1.5 text-slate-600 rounded-lg hover:text-orange-600 hover:bg-orange-50 transition text-xs font-medium">Incidentes</a>
                    <a href="#" class="flex items-center px-3 py-1.5 text-slate-600 rounded-lg hover:text-orange-600 hover:bg-orange-50 transition text-xs font-medium">Riesgos</a>
                    <a href="#" class="flex items-center px-3 py-1.5 text-slate-600 rounded-lg hover:text-orange-600 hover:bg-orange-50 transition text-xs font-medium">Actos Inseguros</a>
                    <a href="#" class="flex items-center px-3 py-1.5 text-slate-600 rounded-lg hover:text-orange-600 hover:bg-orange-50 transition text-xs font-medium">Lesiones</a>
                    <a href="#" class="flex items-center px-3 py-1.5 text-slate-600 rounded-lg hover:text-orange-600 hover:bg-orange-50 transition text-xs font-medium">Tipos de Emergencias</a>
                </div>
            </div>
        </div>

        <!-- 2. TIPOS DE EVENTOS -->
        @php
            $isTiposEventos = request()->routeIs('SST.admin.tipos_eventos.*');
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
                @php $actAcc = request()->routeIs('SST.admin.tipos_eventos.accidentes.*'); @endphp
                <li>
                    <a href="{{ route('SST.admin.tipos_eventos.accidentes.index') }}" class="flex items-center pl-7 pr-3 py-1.5 rounded-lg transition font-medium {{ $actAcc ? 'text-orange-600 bg-white shadow-xs font-bold' : 'text-slate-600 hover:text-orange-600 hover:bg-white hover:shadow-xs' }}">
                        <span class="text-[6px] mr-2.5 {{ $actAcc ? 'text-orange-500 scale-125' : 'text-slate-400' }}"><i class="fa-solid fa-circle"></i></span> 
                        Accidentes
                    </a>
                </li>

                @php $actInc = request()->routeIs('SST.admin.tipos_eventos.incidentes.*'); @endphp
                <li>
                    <a href="{{ route('SST.admin.tipos_eventos.incidentes.index') }}" class="flex items-center pl-7 pr-3 py-1.5 rounded-lg transition font-medium {{ $actInc ? 'text-orange-600 bg-white shadow-xs font-bold' : 'text-slate-600 hover:text-orange-600 hover:bg-white hover:shadow-xs' }}">
                        <span class="text-[6px] mr-2.5 {{ $actInc ? 'text-orange-500 scale-125' : 'text-slate-400' }}"><i class="fa-solid fa-circle"></i></span> 
                        Incidentes
                    </a>
                </li>

                @php $actRiesg = request()->routeIs('SST.admin.tipos_eventos.riesgos.*'); @endphp
                <li>
                    <a href="{{ route('SST.admin.tipos_eventos.riesgos.index') }}" class="flex items-center pl-7 pr-3 py-1.5 rounded-lg transition font-medium {{ $actRiesg ? 'text-orange-600 bg-white shadow-xs font-bold' : 'text-slate-600 hover:text-orange-600 hover:bg-white hover:shadow-xs' }}">
                        <span class="text-[6px] mr-2.5 {{ $actRiesg ? 'text-orange-500 scale-125' : 'text-slate-400' }}"><i class="fa-solid fa-circle"></i></span> 
                        Riesgos
                    </a>
                </li>

                @php $actActos = request()->routeIs('SST.admin.tipos_eventos.actos_inseguros.*'); @endphp
                <li>
                    <a href="{{ route('SST.admin.tipos_eventos.actos_inseguros.index') }}" class="flex items-center pl-7 pr-3 py-1.5 rounded-lg transition font-medium {{ $actActos ? 'text-orange-600 bg-white shadow-xs font-bold' : 'text-slate-600 hover:text-orange-600 hover:bg-white hover:shadow-xs' }}">
                        <span class="text-[6px] mr-2.5 {{ $actActos ? 'text-orange-500 scale-125' : 'text-slate-400' }}"><i class="fa-solid fa-circle"></i></span> 
                        Actos Inseguros
                    </a>
                </li>

                @php $actLes = request()->routeIs('SST.admin.tipos_eventos.lesiones.*'); @endphp
                <li>
                    <a href="{{ route('SST.admin.tipos_eventos.lesiones.index') }}" class="flex items-center pl-7 pr-3 py-1.5 rounded-lg transition font-medium {{ $actLes ? 'text-orange-600 bg-white shadow-xs font-bold' : 'text-slate-600 hover:text-orange-600 hover:bg-white hover:shadow-xs' }}">
                        <span class="text-[6px] mr-2.5 {{ $actLes ? 'text-orange-500 scale-125' : 'text-slate-400' }}"><i class="fa-solid fa-circle"></i></span> 
                        Lesiones
                    </a>
                </li>

                @php $actEmerg = request()->routeIs('SST.admin.tipos_eventos.tipos_emergencias.*'); @endphp
                <li>
                    <a href="{{ route('SST.admin.tipos_eventos.tipos_emergencias.index') }}" class="flex items-center pl-7 pr-3 py-1.5 rounded-lg transition font-medium {{ $actEmerg ? 'text-orange-600 bg-white shadow-xs font-bold' : 'text-slate-600 hover:text-orange-600 hover:bg-white hover:shadow-xs' }}">
                        <span class="text-[6px] mr-2.5 {{ $actEmerg ? 'text-orange-500 scale-125' : 'text-slate-400' }}"><i class="fa-solid fa-circle"></i></span> 
                        Tipos de Emergencias
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
                    <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-orange-100 text-orange-700">6</span>
                </div>
                <div class="py-1 space-y-0.5">
                    <a href="{{ route('SST.admin.tipos_eventos.accidentes.index') }}" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $actAcc ? 'bg-orange-50 text-orange-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-orange-600' }}">
                        <span>Accidentes</span>
                        @if($actAcc)<span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>@endif
                    </a>
                    <a href="{{ route('SST.admin.tipos_eventos.incidentes.index') }}" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $actInc ? 'bg-orange-50 text-orange-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-orange-600' }}">
                        <span>Incidentes</span>
                        @if($actInc)<span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>@endif
                    </a>
                    <a href="{{ route('SST.admin.tipos_eventos.riesgos.index') }}" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $actRiesg ? 'bg-orange-50 text-orange-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-orange-600' }}">
                        <span>Riesgos</span>
                        @if($actRiesg)<span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>@endif
                    </a>
                    <a href="{{ route('SST.admin.tipos_eventos.actos_inseguros.index') }}" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $actActos ? 'bg-orange-50 text-orange-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-orange-600' }}">
                        <span>Actos Inseguros</span>
                        @if($actActos)<span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>@endif
                    </a>
                    <a href="{{ route('SST.admin.tipos_eventos.lesiones.index') }}" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $actLes ? 'bg-orange-50 text-orange-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-orange-600' }}">
                        <span>Lesiones</span>
                        @if($actLes)<span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>@endif
                    </a>
                    <a href="{{ route('SST.admin.tipos_eventos.tipos_emergencias.index') }}" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $actEmerg ? 'bg-orange-50 text-orange-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-orange-600' }}">
                        <span>Tipos de Emergencias</span>
                        @if($actEmerg)<span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>@endif
                    </a>
                </div>
            </div>
        </div>

        <!-- 3. INFORMACIÓN BÁSICA -->
        @php
            $isInfoBasica = request()->routeIs('SST.admin.informacion_basica.*');
        @endphp
        <div class="flex flex-col menu-wrapper relative {{ $isInfoBasica ? 'open' : '' }}" id="informacionBasicaWrapper">
            <div class="menu-btn-item flex items-center justify-between px-3 py-2.5 rounded-xl {{ $isInfoBasica ? 'text-orange-600 bg-orange-50/70 font-bold' : 'text-slate-700 hover:text-orange-600 hover:bg-slate-50' }} transition cursor-pointer group" id="btnToggleInformacionBasica">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-xl {{ $isInfoBasica ? 'bg-orange-100 text-orange-600' : 'bg-slate-100 text-slate-500 flex items-center justify-center group-hover:bg-orange-50 group-hover:text-orange-600' }} flex items-center justify-center transition shrink-0">
                        <i class="fa-solid fa-info-circle text-xs"></i>
                    </span>
                    <span class="font-bold text-[13px] sidebar-text-full">Información Básica</span>
                </div>
                <span class="text-[10px] {{ $isInfoBasica ? 'text-orange-600' : 'text-slate-400 group-hover:text-orange-600' }} arrow-icon" id="arrowInformacionBasica">
                    <i class="fa-solid fa-chevron-down"></i>
                </span>
            </div>
            <!-- Acordeón Normal -->
            <ul class="submenu-transition bg-slate-50/70 rounded-xl mt-1 mx-1 p-1 border border-slate-100 space-y-0.5">
                @php $actResp = request()->routeIs('SST.admin.informacion_basica.respuesta_eventos.*'); @endphp
                <li>
                    <a href="{{ route('SST.admin.informacion_basica.respuesta_eventos.index') }}" class="flex items-center pl-7 pr-3 py-1.5 rounded-lg transition font-medium {{ $actResp ? 'text-orange-600 bg-white shadow-xs font-bold' : 'text-slate-600 hover:text-orange-600 hover:bg-white hover:shadow-xs' }}">
                        <span class="text-[6px] mr-2.5 {{ $actResp ? 'text-orange-500 scale-125' : 'text-slate-400' }}"><i class="fa-solid fa-circle"></i></span> 
                        Respuesta de Eventos
                    </a>
                </li>

                @php $actLugar = request()->routeIs('SST.admin.informacion_basica.lugar_informacion.*'); @endphp
                <li>
                    <a href="{{ route('SST.admin.informacion_basica.lugar_informacion.index') }}" class="flex items-center pl-7 pr-3 py-1.5 rounded-lg transition font-medium {{ $actLugar ? 'text-orange-600 bg-white shadow-xs font-bold' : 'text-slate-600 hover:text-orange-600 hover:bg-white hover:shadow-xs' }}">
                        <span class="text-[6px] mr-2.5 {{ $actLugar ? 'text-orange-500 scale-125' : 'text-slate-400' }}"><i class="fa-solid fa-circle"></i></span> 
                        Lugar de Información
                    </a>
                </li>

                @php $actContacto = request()->routeIs('SST.admin.informacion_basica.contacto_emergencia.*'); @endphp
                <li>
                    <a href="{{ route('SST.admin.informacion_basica.contacto_emergencia.index') }}" class="flex items-center pl-7 pr-3 py-1.5 rounded-lg transition font-medium {{ $actContacto ? 'text-orange-600 bg-white shadow-xs font-bold' : 'text-slate-600 hover:text-orange-600 hover:bg-white hover:shadow-xs' }}">
                        <span class="text-[6px] mr-2.5 {{ $actContacto ? 'text-orange-500 scale-125' : 'text-slate-400' }}"><i class="fa-solid fa-circle"></i></span> 
                        Contacto de Emergencia
                    </a>
                </li>
            </ul>
            <!-- Tarjeta Flotante (Flyout Mini) -->
            <div class="sidebar-flyout">
                <div class="px-3 py-2 border-b border-slate-100 flex items-center justify-between">
                    <span class="font-extrabold text-xs text-slate-800 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                        <span>Información Básica</span>
                    </span>
                    <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-orange-100 text-orange-700">3</span>
                </div>
                <div class="py-1 space-y-0.5">
                    <a href="{{ route('SST.admin.informacion_basica.respuesta_eventos.index') }}" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $actResp ? 'bg-orange-50 text-orange-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-orange-600' }}">
                        <span>Respuesta de Eventos</span>
                        @if($actResp)<span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>@endif
                    </a>
                    <a href="{{ route('SST.admin.informacion_basica.lugar_informacion.index') }}" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $actLugar ? 'bg-orange-50 text-orange-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-orange-600' }}">
                        <span>Lugar de Información</span>
                        @if($actLugar)<span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>@endif
                    </a>
                    <a href="{{ route('SST.admin.informacion_basica.contacto_emergencia.index') }}" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $actContacto ? 'bg-orange-50 text-orange-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-orange-600' }}">
                        <span>Contacto de Emergencia</span>
                        @if($actContacto)<span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>@endif
                    </a>
                </div>
            </div>
        </div>

        <!-- 4. INSPECCIONES -->
        @php
            $isInspecciones = request()->routeIs('SST.admin.inspecciones.*');
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
                @php $actInspRealizar = request()->routeIs('SST.admin.inspecciones.realizar'); @endphp
                <li>
                    <a href="{{ route('SST.admin.inspecciones.realizar') }}" class="flex items-center pl-7 pr-3 py-1.5 rounded-lg transition font-medium {{ $actInspRealizar ? 'text-orange-600 bg-white shadow-xs font-bold' : 'text-slate-600 hover:text-orange-600 hover:bg-white hover:shadow-xs' }}">
                        <span class="text-[6px] mr-2.5 {{ $actInspRealizar ? 'text-orange-500 scale-125' : 'text-slate-400' }}"><i class="fa-solid fa-circle"></i></span> 
                        Realizar Inspección
                    </a>
                </li>

                @php $actInspHistorial = request()->routeIs('SST.admin.inspecciones.historial'); @endphp
                <li>
                    <a href="{{ route('SST.admin.inspecciones.historial') }}" class="flex items-center pl-7 pr-3 py-1.5 rounded-lg transition font-medium {{ $actInspHistorial ? 'text-orange-600 bg-white shadow-xs font-bold' : 'text-slate-600 hover:text-orange-600 hover:bg-white hover:shadow-xs' }}">
                        <span class="text-[6px] mr-2.5 {{ $actInspHistorial ? 'text-orange-500 scale-125' : 'text-slate-400' }}"><i class="fa-solid fa-circle"></i></span> 
                        Historial
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
                    <a href="{{ route('SST.admin.inspecciones.realizar') }}" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $actInspRealizar ? 'bg-orange-50 text-orange-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-orange-600' }}">
                        <span>Realizar Inspección</span>
                        @if($actInspRealizar)<span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>@endif
                    </a>
                    <a href="{{ route('SST.admin.inspecciones.historial') }}" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $actInspHistorial ? 'bg-orange-50 text-orange-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-orange-600' }}">
                        <span>Historial</span>
                        @if($actInspHistorial)<span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>@endif
                    </a>
                </div>
            </div>
        </div>

        <!-- 5. PAUSAS ACTIVAS -->
        <div class="flex flex-col menu-wrapper relative" id="pausasWrapper">
            <div class="menu-btn-item flex items-center justify-between px-3 py-2.5 rounded-xl text-slate-700 hover:text-orange-600 hover:bg-slate-50 transition cursor-pointer group" id="btnTogglePausas">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center group-hover:bg-orange-50 group-hover:text-orange-600 transition shrink-0">
                        <i class="fa-solid fa-spa text-xs"></i>
                    </span>
                    <span class="font-bold text-[13px] sidebar-text-full">Pausas Activas</span>
                </div>
                <span class="text-[10px] text-slate-400 group-hover:text-orange-600 arrow-icon" id="arrowPausas">
                    <i class="fa-solid fa-chevron-down"></i>
                </span>
            </div>
            <!-- Acordeón Normal -->
            <ul class="submenu-transition bg-slate-50/70 rounded-xl mt-1 mx-1 p-1 border border-slate-100">
                <li><a href="#" class="flex items-center pl-7 pr-3 py-1.5 text-slate-600 rounded-lg hover:text-orange-600 hover:bg-white hover:shadow-xs transition font-medium"><span class="text-[6px] mr-2.5 text-slate-400"><i class="fa-solid fa-circle"></i></span> Programadas</a></li>
            </ul>
            <!-- Tarjeta Flotante (Flyout Mini) -->
            <div class="sidebar-flyout">
                <div class="px-3 py-2 border-b border-slate-100">
                    <span class="font-extrabold text-xs text-slate-800 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                        <span>Pausas Activas</span>
                    </span>
                </div>
                <div class="py-1">
                    <a href="#" class="flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-orange-600 transition">Programadas</a>
                </div>
            </div>
        </div>

        <!-- 6. CRONOGRAMA SST -->
        @php
            $isCronograma = request()->routeIs('SST.admin.cronograma.*');
        @endphp
        <div class="flex flex-col menu-wrapper relative {{ $isCronograma ? 'open' : '' }}" id="cronogramaWrapper">
            <div class="menu-btn-item flex items-center justify-between px-3 py-2.5 rounded-xl {{ $isCronograma ? 'text-orange-600 bg-orange-50/70 font-bold' : 'text-slate-700 hover:text-orange-600 hover:bg-slate-50' }} transition cursor-pointer group" id="btnToggleCronograma">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-xl {{ $isCronograma ? 'bg-orange-100 text-orange-600' : 'bg-slate-100 text-slate-500 group-hover:bg-orange-50 group-hover:text-orange-600' }} flex items-center justify-center transition shrink-0">
                        <i class="fa-solid fa-calendar-days text-xs"></i>
                    </span>
                    <span class="font-bold text-[13px] sidebar-text-full">Cronograma SST</span>
                </div>
                <span class="text-[10px] {{ $isCronograma ? 'text-orange-600' : 'text-slate-400 group-hover:text-orange-600' }} arrow-icon" id="arrowCronograma">
                    <i class="fa-solid fa-chevron-down"></i>
                </span>
            </div>
            <!-- Acordeón Normal -->
            <ul class="submenu-transition bg-slate-50/70 rounded-xl mt-1 mx-1 p-1 border border-slate-100">
                @php $actCal = request()->routeIs('SST.admin.cronograma.calendario'); @endphp
                <li>
                    <a href="{{ route('SST.admin.cronograma.calendario') }}" class="flex items-center pl-7 pr-3 py-1.5 rounded-lg transition font-medium {{ $actCal ? 'text-orange-600 bg-white shadow-xs font-bold' : 'text-slate-600 hover:text-orange-600 hover:bg-white hover:shadow-xs' }}">
                        <span class="text-[6px] mr-2.5 {{ $actCal ? 'text-orange-500 scale-125' : 'text-slate-400' }}"><i class="fa-solid fa-circle"></i></span> 
                        Calendario
                    </a>
                </li>
            </ul>
            <!-- Tarjeta Flotante (Flyout Mini) -->
            <div class="sidebar-flyout">
                <div class="px-3 py-2 border-b border-slate-100">
                    <span class="font-extrabold text-xs text-slate-800 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                        <span>Cronograma SST</span>
                    </span>
                </div>
                <div class="py-1">
                    <a href="{{ route('SST.admin.cronograma.calendario') }}" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $actCal ? 'bg-orange-50 text-orange-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-orange-600' }}">
                        <span>Calendario</span>
                        @if($actCal)<span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>@endif
                    </a>
                </div>
            </div>
        </div>

        <!-- 7. INDICADORES SST -->
        <div class="flex flex-col menu-wrapper relative" id="indicadoresWrapper">
            <div class="menu-btn-item flex items-center justify-between px-3 py-2.5 rounded-xl text-slate-700 hover:text-orange-600 hover:bg-slate-50 transition cursor-pointer group" id="btnToggleIndicadores">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center group-hover:bg-orange-50 group-hover:text-orange-600 transition shrink-0">
                        <i class="fa-solid fa-chart-line text-xs"></i>
                    </span>
                    <span class="font-bold text-[13px] sidebar-text-full">Indicadores SST</span>
                </div>
                <span class="text-[10px] text-slate-400 group-hover:text-orange-600 arrow-icon" id="arrowIndicadores">
                    <i class="fa-solid fa-chevron-down"></i>
                </span>
            </div>
            <!-- Acordeón Normal -->
            <ul class="submenu-transition bg-slate-50/70 rounded-xl mt-1 mx-1 p-1 border border-slate-100">
                <li><a href="#" class="flex items-center pl-7 pr-3 py-1.5 text-slate-600 rounded-lg hover:text-orange-600 hover:bg-white hover:shadow-xs transition font-medium"><span class="text-[6px] mr-2.5 text-slate-400"><i class="fa-solid fa-circle"></i></span> Ver Indicadores</a></li>
            </ul>
            <!-- Tarjeta Flotante (Flyout Mini) -->
            <div class="sidebar-flyout">
                <div class="px-3 py-2 border-b border-slate-100">
                    <span class="font-extrabold text-xs text-slate-800 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                        <span>Indicadores SST</span>
                    </span>
                </div>
                <div class="py-1">
                    <a href="#" class="flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-orange-600 transition">Ver Indicadores</a>
                </div>
            </div>
        </div>

        <!-- 8. USUARIOS -->
        <div class="flex flex-col menu-wrapper relative" id="usuariosWrapper">
            <div class="menu-btn-item flex items-center justify-between px-3 py-2.5 rounded-xl text-slate-700 hover:text-orange-600 hover:bg-slate-50 transition cursor-pointer group" id="btnToggleUsuarios">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center group-hover:bg-orange-50 group-hover:text-orange-600 transition shrink-0">
                        <i class="fa-solid fa-users text-xs"></i>
                    </span>
                    <span class="font-bold text-[13px] sidebar-text-full">Usuarios</span>
                </div>
                <span class="text-[10px] text-slate-400 group-hover:text-orange-600 arrow-icon" id="arrowUsuarios">
                    <i class="fa-solid fa-chevron-down"></i>
                </span>
            </div>
            <!-- Acordeón Normal -->
            <ul class="submenu-transition bg-slate-50/70 rounded-xl mt-1 mx-1 p-1 border border-slate-100 space-y-0.5">
                <li><a href="#" class="flex items-center pl-7 pr-3 py-1.5 text-slate-600 rounded-lg hover:text-orange-600 hover:bg-white hover:shadow-xs transition font-medium"><span class="text-[6px] mr-2.5 text-slate-400"><i class="fa-solid fa-circle"></i></span> Registrar Usuario</a></li>
            </ul>
            <!-- Tarjeta Flotante (Flyout Mini) -->
            <div class="sidebar-flyout">
                <div class="px-3 py-2 border-b border-slate-100">
                    <span class="font-extrabold text-xs text-slate-800 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                        <span>Usuarios</span>
                    </span>
                </div>
                <div class="py-1">
                    <a href="#" class="flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-orange-600 transition">Registrar Usuario</a>
                </div>
            </div>
        </div>

    </nav>

    <!-- Footer Sidebar -->
    <div class="p-3.5 border-t border-slate-100 bg-slate-50/50 text-center sidebar-text-full">
        <div class="text-[11px] font-bold text-slate-600">SG-SST v2.5</div>
        <div class="text-[10px] text-slate-400">Ambiente Administrativo Seguro</div>
    </div>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleMenu = (btnId, wrapperId) => {
            const btn = document.getElementById(btnId);
            const wrapper = document.getElementById(wrapperId);
            if (btn && wrapper) {
                btn.addEventListener('click', () => {
                    const sidebar = document.getElementById('sidebarMenu');
                    // Solo activar acordeón normal si NO está en modo mini
                    if (sidebar && !sidebar.classList.contains('sidebar-mini')) {
                        wrapper.classList.toggle('open');
                    }
                });
            }
        };

        toggleMenu('btnToggleDefiniciones', 'definicionesWrapper');
        toggleMenu('btnToggleTiposEventos', 'tiposEventosWrapper');
        toggleMenu('btnToggleInformacionBasica', 'informacionBasicaWrapper');
        toggleMenu('btnToggleInspecciones', 'inspeccionesWrapper');
        toggleMenu('btnTogglePausas', 'pausasWrapper');
        toggleMenu('btnToggleCronograma', 'cronogramaWrapper');
        toggleMenu('btnToggleIndicadores', 'indicadoresWrapper');
        toggleMenu('btnToggleUsuarios', 'usuariosWrapper');
    });
</script>

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

        <!-- DEFINICIONES -->
        <div class="flex flex-col menu-wrapper relative" id="defAprendizWrapper">
            <a href="#" class="menu-btn-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-700 hover:text-orange-600 hover:bg-slate-50 transition group">
                <span class="w-8 h-8 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center group-hover:bg-orange-50 group-hover:text-orange-600 transition shrink-0">
                    <i class="fa-solid fa-book text-xs"></i>
                </span>
                <span class="font-bold text-[13px] sidebar-text-full">Definiciones</span>
            </a>
            <div class="sidebar-flyout">
                <div class="px-3 py-2 border-b border-slate-100">
                    <span class="font-extrabold text-xs text-slate-800">Definiciones SST</span>
                </div>
                <div class="py-1">
                    <a href="#" class="flex items-center px-3 py-1.5 text-slate-600 hover:bg-slate-50 hover:text-orange-600 rounded-lg text-xs font-semibold transition">Ver Glosario y Conceptos</a>
                </div>
            </div>
        </div>

        <!-- TIPOS DE EVENTOS -->
        <div class="flex flex-col menu-wrapper relative" id="tiposEventosWrapper">
            <div class="menu-btn-item flex items-center justify-between px-3 py-2.5 rounded-xl text-slate-700 hover:text-orange-600 hover:bg-slate-50 transition cursor-pointer group" id="btnToggleTiposEventos">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center group-hover:bg-orange-50 group-hover:text-orange-600 transition shrink-0">
                        <i class="fa-solid fa-list-check text-xs"></i>
                    </span>
                    <span class="font-bold text-[13px] sidebar-text-full">Tipos de Eventos</span>
                </div>
                <span class="text-[10px] text-slate-400 group-hover:text-orange-600 arrow-icon" id="arrowTiposEventos">
                    <i class="fa-solid fa-chevron-down"></i>
                </span>
            </div>
            <ul class="submenu-transition bg-slate-50/70 rounded-xl mt-1 mx-1 p-1 border border-slate-100 space-y-0.5">
                <li><a href="#" class="flex items-center pl-7 pr-3 py-1.5 text-slate-600 rounded-lg hover:text-orange-600 hover:bg-white hover:shadow-xs transition font-medium"><span class="text-[6px] mr-2.5 text-slate-400"><i class="fa-solid fa-circle"></i></span> Accidentes</a></li>
                <li><a href="#" class="flex items-center pl-7 pr-3 py-1.5 text-slate-600 rounded-lg hover:text-orange-600 hover:bg-white hover:shadow-xs transition font-medium"><span class="text-[6px] mr-2.5 text-slate-400"><i class="fa-solid fa-circle"></i></span> Incidentes</a></li>
                <li><a href="#" class="flex items-center pl-7 pr-3 py-1.5 text-slate-600 rounded-lg hover:text-orange-600 hover:bg-white hover:shadow-xs transition font-medium"><span class="text-[6px] mr-2.5 text-slate-400"><i class="fa-solid fa-circle"></i></span> Riesgos</a></li>
                <li><a href="#" class="flex items-center pl-7 pr-3 py-1.5 text-slate-600 rounded-lg hover:text-orange-600 hover:bg-white hover:shadow-xs transition font-medium"><span class="text-[6px] mr-2.5 text-slate-400"><i class="fa-solid fa-circle"></i></span> Actos Inseguros</a></li>
                <li><a href="#" class="flex items-center pl-7 pr-3 py-1.5 text-slate-600 rounded-lg hover:text-orange-600 hover:bg-white hover:shadow-xs transition font-medium"><span class="text-[6px] mr-2.5 text-slate-400"><i class="fa-solid fa-circle"></i></span> Lesiones</a></li>
                <li><a href="#" class="flex items-center pl-7 pr-3 py-1.5 text-slate-600 rounded-lg hover:text-orange-600 hover:bg-white hover:shadow-xs transition font-medium"><span class="text-[6px] mr-2.5 text-slate-400"><i class="fa-solid fa-circle"></i></span> Tipos de Emergencias</a></li>
            </ul>
            <div class="sidebar-flyout">
                <div class="px-3 py-2 border-b border-slate-100 flex items-center justify-between">
                    <span class="font-extrabold text-xs text-slate-800">Tipos de Eventos</span>
                </div>
                <div class="py-1 space-y-0.5">
                    <a href="#" class="flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-orange-600 transition">Accidentes</a>
                    <a href="#" class="flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-orange-600 transition">Incidentes</a>
                    <a href="#" class="flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-orange-600 transition">Riesgos</a>
                    <a href="#" class="flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-orange-600 transition">Actos Inseguros</a>
                    <a href="#" class="flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-orange-600 transition">Lesiones</a>
                    <a href="#" class="flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-orange-600 transition">Tipos de Emergencias</a>
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
                <li><a href="#" class="flex items-center pl-7 pr-3 py-1.5 text-slate-600 rounded-lg hover:text-orange-600 hover:bg-white hover:shadow-xs transition font-medium"><span class="text-[6px] mr-2.5 text-slate-400"><i class="fa-solid fa-circle"></i></span> Contactos</a></li>
            </ul>
            <div class="sidebar-flyout">
                <div class="px-3 py-2 border-b border-slate-100">
                    <span class="font-extrabold text-xs text-slate-800">Emergencias</span>
                </div>
                <div class="py-1">
                    <a href="#" class="flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-orange-600 transition">Contactos de Emergencia</a>
                </div>
            </div>
        </div>

        <!-- TIPOS DE RIESGOS -->
        <div class="flex flex-col menu-wrapper relative" id="tiposRiesgosWrapper">
            <div class="menu-btn-item flex items-center justify-between px-3 py-2.5 rounded-xl text-slate-700 hover:text-orange-600 hover:bg-slate-50 transition cursor-pointer group" id="btnToggleTiposRiesgos">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center group-hover:bg-orange-50 group-hover:text-orange-600 transition shrink-0">
                        <i class="fa-solid fa-triangle-exclamation text-xs"></i>
                    </span>
                    <span class="font-bold text-[13px] sidebar-text-full">Tipos de Riesgos</span>
                </div>
                <span class="text-[10px] text-slate-400 group-hover:text-orange-600 arrow-icon" id="arrowTiposRiesgos">
                    <i class="fa-solid fa-chevron-down"></i>
                </span>
            </div>
            <ul class="submenu-transition bg-slate-50/70 rounded-xl mt-1 mx-1 p-1 border border-slate-100 space-y-0.5">
                <li><a href="#" class="flex items-center pl-7 pr-3 py-1.5 text-slate-600 rounded-lg hover:text-orange-600 hover:bg-white hover:shadow-xs transition font-medium"><span class="text-[6px] mr-2.5 text-slate-400"><i class="fa-solid fa-circle"></i></span> Consultar</a></li>
            </ul>
            <div class="sidebar-flyout">
                <div class="px-3 py-2 border-b border-slate-100">
                    <span class="font-extrabold text-xs text-slate-800">Tipos de Riesgos</span>
                </div>
                <div class="py-1">
                    <a href="#" class="flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-orange-600 transition">Consultar Riesgos</a>
                </div>
            </div>
        </div>

        <!-- PAUSAS ACTIVAS -->
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
            <ul class="submenu-transition bg-slate-50/70 rounded-xl mt-1 mx-1 p-1 border border-slate-100 space-y-0.5">
                <li><a href="#" class="flex items-center pl-7 pr-3 py-1.5 text-slate-600 rounded-lg hover:text-orange-600 hover:bg-white hover:shadow-xs transition font-medium"><span class="text-[6px] mr-2.5 text-slate-400"><i class="fa-solid fa-circle"></i></span> Próximas</a></li>
                <li><a href="#" class="flex items-center pl-7 pr-3 py-1.5 text-slate-600 rounded-lg hover:text-orange-600 hover:bg-white hover:shadow-xs transition font-medium"><span class="text-[6px] mr-2.5 text-slate-400"><i class="fa-solid fa-circle"></i></span> Mi Historial</a></li>
            </ul>
            <div class="sidebar-flyout">
                <div class="px-3 py-2 border-b border-slate-100">
                    <span class="font-extrabold text-xs text-slate-800">Pausas Activas</span>
                </div>
                <div class="py-1 space-y-0.5">
                    <a href="#" class="flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-orange-600 transition">Próximas</a>
                    <a href="#" class="flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-orange-600 transition">Mi Historial</a>
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
                <li><a href="#" class="flex items-center pl-7 pr-3 py-1.5 text-slate-600 rounded-lg hover:text-orange-600 hover:bg-white hover:shadow-xs transition font-medium"><span class="text-[6px] mr-2.5 text-slate-400"><i class="fa-solid fa-circle"></i></span> Registrar Asistencia</a></li>
            </ul>
            <div class="sidebar-flyout">
                <div class="px-3 py-2 border-b border-slate-100">
                    <span class="font-extrabold text-xs text-slate-800">Asistencia</span>
                </div>
                <div class="py-1">
                    <a href="#" class="flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-orange-600 transition">Registrar Asistencia</a>
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
        const toggleMenu = (btnId, wrapperId) => {
            const btn = document.getElementById(btnId);
            const wrapper = document.getElementById(wrapperId);
            if (btn && wrapper) {
                btn.addEventListener('click', () => {
                    const sidebar = document.getElementById('sidebarMenu');
                    if (sidebar && !sidebar.classList.contains('sidebar-mini')) {
                        wrapper.classList.toggle('open');
                    }
                });
            }
        };

        toggleMenu('btnToggleTiposEventos', 'tiposEventosWrapper');
        toggleMenu('btnToggleEmergencias', 'emergenciasWrapper');
        toggleMenu('btnToggleTiposRiesgos', 'tiposRiesgosWrapper');
        toggleMenu('btnTogglePausas', 'pausasWrapper');
        toggleMenu('btnToggleAsistencia', 'asistenciaWrapper');
    });
</script>

<style>
    .submenu-transition {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .menu-wrapper.open .submenu-transition {
        max-height: 420px;
    }
    #sidebarMenu {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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
</style>

<!-- SIDEBAR ADMIN SG-SST -->
<aside id="sidebarMenu" class="w-[270px] bg-white text-slate-600 flex flex-col h-screen fixed left-0 top-0 z-40 shadow-sm border-r border-slate-200/80 select-none">
    
    <!-- Logo & Header -->
    <div class="flex flex-col items-center pt-6 pb-5 px-5 border-b border-slate-100">
        <div class="w-16 h-16 bg-slate-50 rounded-2xl p-2 border border-slate-200/70 flex items-center justify-center mb-2.5 shadow-sm overflow-hidden">
            <img src="{{ asset('img/logodesst.jpeg') }}" alt="Logo SENA SST" class="max-w-full max-h-full object-contain rounded-xl">
        </div>
        <div class="text-[11px] text-center font-extrabold uppercase tracking-widest text-slate-800 leading-tight">
            Sistema de Gestión <span class="text-orange-600">SST</span>
        </div>
        <div class="inline-flex items-center gap-1.5 mt-1.5 px-2.5 py-0.5 rounded-full bg-slate-100 border border-slate-200/80 text-[10px] font-bold text-slate-600">
            <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>
            La Angostura
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="py-4 flex-grow overflow-y-auto space-y-1 px-3 text-xs font-semibold">

        <!-- DEFINICIONES -->
        <div class="flex flex-col menu-wrapper" id="definicionesWrapper">
            <div class="flex items-center justify-between px-3 py-2.5 rounded-xl text-slate-700 hover:text-orange-600 hover:bg-slate-50 transition cursor-pointer group" id="btnToggleDefiniciones">
                <div class="flex items-center gap-3">
                    <span class="w-7 h-7 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center group-hover:bg-orange-50 group-hover:text-orange-600 transition">
                        <i class="fa-solid fa-book text-xs"></i>
                    </span>
                    <span class="font-bold text-[13px]">Definiciones</span>
                </div>
                <span class="text-[10px] text-slate-400 group-hover:text-orange-600 transition-transform duration-200" id="arrowDefiniciones">
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
        </div>

        <!-- TIPOS DE EVENTOS -->
        <div class="flex flex-col menu-wrapper" id="tiposEventosWrapper">
            <div class="flex items-center justify-between px-3 py-2.5 rounded-xl text-slate-700 hover:text-orange-600 hover:bg-slate-50 transition cursor-pointer group" id="btnToggleTiposEventos">
                <div class="flex items-center gap-3">
                    <span class="w-7 h-7 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center group-hover:bg-orange-50 group-hover:text-orange-600 transition">
                        <i class="fa-solid fa-list-check text-xs"></i>
                    </span>
                    <span class="font-bold text-[13px]">Tipos de Eventos</span>
                </div>
                <span class="text-[10px] text-slate-400 group-hover:text-orange-600 transition-transform duration-200" id="arrowTiposEventos">
                    <i class="fa-solid fa-chevron-down"></i>
                </span>
            </div>
            <ul class="submenu-transition bg-slate-50/70 rounded-xl mt-1 mx-1 p-1 border border-slate-100 space-y-0.5">
                <li><a href="{{ route('SST.admin.tipos_eventos.accidentes.index') }}" class="flex items-center pl-7 pr-3 py-1.5 text-slate-600 rounded-lg hover:text-orange-600 hover:bg-white hover:shadow-xs transition font-medium"><span class="text-[6px] mr-2.5 text-slate-400"><i class="fa-solid fa-circle"></i></span> Accidentes</a></li>
                <li><a href="{{ route('SST.admin.tipos_eventos.incidentes.index') }}" class="flex items-center pl-7 pr-3 py-1.5 text-slate-600 rounded-lg hover:text-orange-600 hover:bg-white hover:shadow-xs transition font-medium"><span class="text-[6px] mr-2.5 text-slate-400"><i class="fa-solid fa-circle"></i></span> Incidentes</a></li>
                <li><a href="{{ route('SST.admin.tipos_eventos.riesgos.index') }}" class="flex items-center pl-7 pr-3 py-1.5 text-slate-600 rounded-lg hover:text-orange-600 hover:bg-white hover:shadow-xs transition font-medium"><span class="text-[6px] mr-2.5 text-slate-400"><i class="fa-solid fa-circle"></i></span> Riesgos</a></li>
                <li><a href="{{ route('SST.admin.tipos_eventos.actos_inseguros.index') }}" class="flex items-center pl-7 pr-3 py-1.5 text-slate-600 rounded-lg hover:text-orange-600 hover:bg-white hover:shadow-xs transition font-medium"><span class="text-[6px] mr-2.5 text-slate-400"><i class="fa-solid fa-circle"></i></span> Actos Inseguros</a></li>
                <li><a href="{{ route('SST.admin.tipos_eventos.lesiones.index') }}" class="flex items-center pl-7 pr-3 py-1.5 text-slate-600 rounded-lg hover:text-orange-600 hover:bg-white hover:shadow-xs transition font-medium"><span class="text-[6px] mr-2.5 text-slate-400"><i class="fa-solid fa-circle"></i></span> Lesiones</a></li>
                <li><a href="{{ route('SST.admin.tipos_eventos.tipos_emergencias.index') }}" class="flex items-center pl-7 pr-3 py-1.5 text-slate-600 rounded-lg hover:text-orange-600 hover:bg-white hover:shadow-xs transition font-medium"><span class="text-[6px] mr-2.5 text-slate-400"><i class="fa-solid fa-circle"></i></span> Tipos de Emergencias</a></li>
            </ul>
        </div>

        <!-- INFORMACIÓN BÁSICA -->
        <div class="flex flex-col menu-wrapper" id="informacionBasicaWrapper">
            <div class="flex items-center justify-between px-3 py-2.5 rounded-xl text-slate-700 hover:text-orange-600 hover:bg-slate-50 transition cursor-pointer group" id="btnToggleInformacionBasica">
                <div class="flex items-center gap-3">
                    <span class="w-7 h-7 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center group-hover:bg-orange-50 group-hover:text-orange-600 transition">
                        <i class="fa-solid fa-info-circle text-xs"></i>
                    </span>
                    <span class="font-bold text-[13px]">Información Básica</span>
                </div>
                <span class="text-[10px] text-slate-400 group-hover:text-orange-600 transition-transform duration-200" id="arrowInformacionBasica">
                    <i class="fa-solid fa-chevron-down"></i>
                </span>
            </div>
            <ul class="submenu-transition bg-slate-50/70 rounded-xl mt-1 mx-1 p-1 border border-slate-100 space-y-0.5">
                <li><a href="#" class="flex items-center pl-7 pr-3 py-1.5 text-slate-600 rounded-lg hover:text-orange-600 hover:bg-white hover:shadow-xs transition font-medium"><span class="text-[6px] mr-2.5 text-slate-400"><i class="fa-solid fa-circle"></i></span> Respuesta de Eventos</a></li>
                <li><a href="#" class="flex items-center pl-7 pr-3 py-1.5 text-slate-600 rounded-lg hover:text-orange-600 hover:bg-white hover:shadow-xs transition font-medium"><span class="text-[6px] mr-2.5 text-slate-400"><i class="fa-solid fa-circle"></i></span> Lugar de Información</a></li>
                <li><a href="#" class="flex items-center pl-7 pr-3 py-1.5 text-slate-600 rounded-lg hover:text-orange-600 hover:bg-white hover:shadow-xs transition font-medium"><span class="text-[6px] mr-2.5 text-slate-400"><i class="fa-solid fa-circle"></i></span> Contacto de Emergencia</a></li>
            </ul>
        </div>

        <!-- INSPECCIONES -->
        <div class="flex flex-col menu-wrapper" id="inspeccionesWrapper">
            <div class="flex items-center justify-between px-3 py-2.5 rounded-xl text-slate-700 hover:text-orange-600 hover:bg-slate-50 transition cursor-pointer group" id="btnToggleInspecciones">
                <div class="flex items-center gap-3">
                    <span class="w-7 h-7 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center group-hover:bg-orange-50 group-hover:text-orange-600 transition">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <span class="font-bold text-[13px]">Inspecciones</span>
                </div>
                <span class="text-[10px] text-slate-400 group-hover:text-orange-600 transition-transform duration-200" id="arrowInspecciones">
                    <i class="fa-solid fa-chevron-down"></i>
                </span>
            </div>
            <ul class="submenu-transition bg-slate-50/70 rounded-xl mt-1 mx-1 p-1 border border-slate-100 space-y-0.5">
                <li><a href="#" class="flex items-center pl-7 pr-3 py-1.5 text-slate-600 rounded-lg hover:text-orange-600 hover:bg-white hover:shadow-xs transition font-medium"><span class="text-[6px] mr-2.5 text-slate-400"><i class="fa-solid fa-circle"></i></span> Realizar Inspección</a></li>
                <li><a href="#" class="flex items-center pl-7 pr-3 py-1.5 text-slate-600 rounded-lg hover:text-orange-600 hover:bg-white hover:shadow-xs transition font-medium"><span class="text-[6px] mr-2.5 text-slate-400"><i class="fa-solid fa-circle"></i></span> Historial</a></li>
            </ul>
        </div>

        <!-- PAUSAS ACTIVAS -->
        <div class="flex flex-col menu-wrapper" id="pausasWrapper">
            <div class="flex items-center justify-between px-3 py-2.5 rounded-xl text-slate-700 hover:text-orange-600 hover:bg-slate-50 transition cursor-pointer group" id="btnTogglePausas">
                <div class="flex items-center gap-3">
                    <span class="w-7 h-7 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center group-hover:bg-orange-50 group-hover:text-orange-600 transition">
                        <i class="fa-solid fa-spa text-xs"></i>
                    </span>
                    <span class="font-bold text-[13px]">Pausas Activas</span>
                </div>
                <span class="text-[10px] text-slate-400 group-hover:text-orange-600 transition-transform duration-200" id="arrowPausas">
                    <i class="fa-solid fa-chevron-down"></i>
                </span>
            </div>
            <ul class="submenu-transition bg-slate-50/70 rounded-xl mt-1 mx-1 p-1 border border-slate-100">
                <li><a href="#" class="flex items-center pl-7 pr-3 py-1.5 text-slate-600 rounded-lg hover:text-orange-600 hover:bg-white hover:shadow-xs transition font-medium"><span class="text-[6px] mr-2.5 text-slate-400"><i class="fa-solid fa-circle"></i></span> Programadas</a></li>
            </ul>
        </div>

        <!-- CRONOGRAMA SST -->
        <div class="flex flex-col menu-wrapper" id="cronogramaWrapper">
            <div class="flex items-center justify-between px-3 py-2.5 rounded-xl text-slate-700 hover:text-orange-600 hover:bg-slate-50 transition cursor-pointer group" id="btnToggleCronograma">
                <div class="flex items-center gap-3">
                    <span class="w-7 h-7 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center group-hover:bg-orange-50 group-hover:text-orange-600 transition">
                        <i class="fa-solid fa-calendar-days text-xs"></i>
                    </span>
                    <span class="font-bold text-[13px]">Cronograma SST</span>
                </div>
                <span class="text-[10px] text-slate-400 group-hover:text-orange-600 transition-transform duration-200" id="arrowCronograma">
                    <i class="fa-solid fa-chevron-down"></i>
                </span>
            </div>
            <ul class="submenu-transition bg-slate-50/70 rounded-xl mt-1 mx-1 p-1 border border-slate-100">
                <li><a href="#" class="flex items-center pl-7 pr-3 py-1.5 text-slate-600 rounded-lg hover:text-orange-600 hover:bg-white hover:shadow-xs transition font-medium"><span class="text-[6px] mr-2.5 text-slate-400"><i class="fa-solid fa-circle"></i></span> Calendario</a></li>
            </ul>
        </div>

        <!-- INDICADORES SST -->
        <div class="flex flex-col menu-wrapper" id="indicadoresWrapper">
            <div class="flex items-center justify-between px-3 py-2.5 rounded-xl text-slate-700 hover:text-orange-600 hover:bg-slate-50 transition cursor-pointer group" id="btnToggleIndicadores">
                <div class="flex items-center gap-3">
                    <span class="w-7 h-7 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center group-hover:bg-orange-50 group-hover:text-orange-600 transition">
                        <i class="fa-solid fa-chart-line text-xs"></i>
                    </span>
                    <span class="font-bold text-[13px]">Indicadores SST</span>
                </div>
                <span class="text-[10px] text-slate-400 group-hover:text-orange-600 transition-transform duration-200" id="arrowIndicadores">
                    <i class="fa-solid fa-chevron-down"></i>
                </span>
            </div>
            <ul class="submenu-transition bg-slate-50/70 rounded-xl mt-1 mx-1 p-1 border border-slate-100">
                <li><a href="#" class="flex items-center pl-7 pr-3 py-1.5 text-slate-600 rounded-lg hover:text-orange-600 hover:bg-white hover:shadow-xs transition font-medium"><span class="text-[6px] mr-2.5 text-slate-400"><i class="fa-solid fa-circle"></i></span> Ver Indicadores</a></li>
            </ul>
        </div>

        <!-- USUARIOS -->
        <div class="flex flex-col menu-wrapper" id="usuariosWrapper">
            <div class="flex items-center justify-between px-3 py-2.5 rounded-xl text-slate-700 hover:text-orange-600 hover:bg-slate-50 transition cursor-pointer group" id="btnToggleUsuarios">
                <div class="flex items-center gap-3">
                    <span class="w-7 h-7 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center group-hover:bg-orange-50 group-hover:text-orange-600 transition">
                        <i class="fa-solid fa-users text-xs"></i>
                    </span>
                    <span class="font-bold text-[13px]">Usuarios</span>
                </div>
                <span class="text-[10px] text-slate-400 group-hover:text-orange-600 transition-transform duration-200" id="arrowUsuarios">
                    <i class="fa-solid fa-chevron-down"></i>
                </span>
            </div>
            <ul class="submenu-transition bg-slate-50/70 rounded-xl mt-1 mx-1 p-1 border border-slate-100 space-y-0.5">
                <li><a href="#" class="flex items-center pl-7 pr-3 py-1.5 text-slate-600 rounded-lg hover:text-orange-600 hover:bg-white hover:shadow-xs transition font-medium"><span class="text-[6px] mr-2.5 text-slate-400"><i class="fa-solid fa-circle"></i></span> Registrar Usuario</a></li>
            </ul>
        </div>

    </nav>

    <!-- Footer Sidebar -->
    <div class="p-3.5 border-t border-slate-100 bg-slate-50/50 text-center">
        <div class="text-[11px] font-bold text-slate-600">SG-SST v2.5</div>
        <div class="text-[10px] text-slate-400">Ambiente Administrativo Seguro</div>
    </div>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleMenu = (btnId, wrapperId, arrowId) => {
            const btn = document.getElementById(btnId);
            const wrapper = document.getElementById(wrapperId);
            const arrow = document.getElementById(arrowId);
            if (btn && wrapper) {
                btn.addEventListener('click', () => {
                    const isOpen = wrapper.classList.toggle('open');
                    if (arrow) {
                        arrow.style.transform = isOpen ? "rotate(180deg)" : "rotate(0deg)";
                    }
                });
            }
        };

        toggleMenu('btnToggleDefiniciones', 'definicionesWrapper', 'arrowDefiniciones');
        toggleMenu('btnToggleTiposEventos', 'tiposEventosWrapper', 'arrowTiposEventos');
        toggleMenu('btnToggleInformacionBasica', 'informacionBasicaWrapper', 'arrowInformacionBasica');
        toggleMenu('btnToggleInspecciones', 'inspeccionesWrapper', 'arrowInspecciones');
        toggleMenu('btnTogglePausas', 'pausasWrapper', 'arrowPausas');
        toggleMenu('btnToggleCronograma', 'cronogramaWrapper', 'arrowCronograma');
        toggleMenu('btnToggleIndicadores', 'indicadoresWrapper', 'arrowIndicadores');
        toggleMenu('btnToggleUsuarios', 'usuariosWrapper', 'arrowUsuarios');
    });
</script>

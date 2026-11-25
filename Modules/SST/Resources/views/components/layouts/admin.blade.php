<!DOCTYPE html>
<html lang="es" class="notranslate" translate="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google" content="notranslate">
    <title>@yield('title', 'SST • SENA Empresa')</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Chart.js CDN (Para Gráficas de Gestión SST) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Script de Precarga Inmediata del Modo Sidebar (Zero-Flicker) -->
    <script>
        (function() {
            try {
                if (localStorage.getItem('sst_sidebar_mode') === 'mini' && window.innerWidth >= 1024) {
                    document.documentElement.classList.add('sst-mini-sidebar');
                }
            } catch (e) {}
        })();
    </script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* Estilos personalizados para SweetAlert2 en SST */
        .swal2-popup.sst-swal-popup {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            border-radius: 28px !important;
            padding: 1.75rem !important;
            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25) !important;
            border: 1px solid #f1f5f9 !important;
            max-width: 420px !important;
            width: 100% !important;
        }
        .swal2-title.sst-swal-title {
            font-size: 1.2rem !important;
            font-weight: 800 !important;
            color: #1e293b !important;
            letter-spacing: -0.025em !important;
            padding: 0.25rem 0 0 0 !important;
        }
        .swal2-html-container.sst-swal-text {
            font-size: 0.825rem !important;
            color: #64748b !important;
            font-weight: 500 !important;
            line-height: 1.55 !important;
            margin: 0.5rem 0 0 0 !important;
        }
        .sst-swal-actions {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 0.75rem !important;
            padding-top: 1.25rem !important;
            width: 100% !important;
        }
        .sst-swal-confirm-btn {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 0.5rem !important;
            padding: 0.65rem 1.25rem !important;
            border-radius: 1rem !important;
            background-color: #e11d48 !important;
            color: #ffffff !important;
            font-size: 0.8125rem !important;
            font-weight: 700 !important;
            box-shadow: 0 4px 6px -1px rgba(225, 29, 72, 0.25) !important;
            transition: all 0.15s ease !important;
            cursor: pointer !important;
            border: none !important;
        }
        .sst-swal-confirm-btn:hover {
            background-color: #be123c !important;
            transform: translateY(-1px) !important;
        }
        .sst-swal-confirm-btn:active {
            transform: scale(0.96) !important;
        }
        .sst-swal-cancel-btn {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 0.5rem !important;
            padding: 0.65rem 1.25rem !important;
            border-radius: 1rem !important;
            background-color: #f1f5f9 !important;
            color: #475569 !important;
            font-size: 0.8125rem !important;
            font-weight: 700 !important;
            transition: all 0.15s ease !important;
            cursor: pointer !important;
            border: 1px solid #e2e8f0 !important;
        }
        .sst-swal-cancel-btn:hover {
            background-color: #e2e8f0 !important;
            color: #0f172a !important;
        }
        .sst-swal-cancel-btn:active {
            transform: scale(0.96) !important;
        }

        /* Ocultar flecha nativa negra ÚNICAMENTE de inputs con datalist para mantener diseño limpio */
        input[list]:not([type="date"]):not([type="datetime-local"])::-webkit-calendar-picker-indicator {
            display: none !important;
            -webkit-appearance: none !important;
            opacity: 0 !important;
            width: 0 !important;
            height: 0 !important;
        }

        /* Selector de fecha y hora interactivo estilizado */
        input[type="date"]::-webkit-calendar-picker-indicator,
        input[type="datetime-local"]::-webkit-calendar-picker-indicator {
            cursor: pointer;
            opacity: 0.8;
            padding: 2px;
            border-radius: 6px;
            transition: all 0.2s ease;
            filter: invert(48%) sepia(79%) saturate(2476%) hue-rotate(65deg) brightness(98%) contrast(106%);
        }
        input[type="date"]::-webkit-calendar-picker-indicator:hover,
        input[type="datetime-local"]::-webkit-calendar-picker-indicator:hover {
            opacity: 1;
            transform: scale(1.15);
            background-color: rgba(220, 252, 231, 0.5);
        }
        
        #mainContentWrapper {
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        #mainContentWrapper.mini-content {
            margin-left: 76px !important;
        }
        #mainContentWrapper.full-width {
            margin-left: 0 !important;
        }

        /* Soporte Zero-Flicker para Modo Mini en Precarga */
        html.sst-mini-sidebar #sidebarMenu {
            width: 76px !important;
            overflow: visible !important;
            transition: none !important;
        }
        html.sst-mini-sidebar #mainContentWrapper {
            margin-left: 76px !important;
            transition: none !important;
        }
        html.sst-mini-sidebar #sidebarMenu .sidebar-text-full,
        html.sst-mini-sidebar #sidebarMenu .arrow-icon,
        html.sst-mini-sidebar #sidebarMenu .submenu-transition,
        html.sst-mini-sidebar #sidebarMenu .logo-full {
            display: none !important;
        }
        html.sst-mini-sidebar #sidebarMenu .logo-mini {
            display: flex !important;
        }
        html.sst-mini-sidebar #sidebarMenu .menu-btn-item {
            justify-content: center !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
        html.sst-mini-sidebar #sidebarMenu .sidebar-flyout {
            display: block;
        }

        /* Animación suave para el menú desplegable */
        .dropdown-menu {
            transform-origin: top right;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .dropdown-menu.hidden-dropdown {
            opacity: 0;
            transform: scale(0.95) translateY(-8px);
            pointer-events: none;
        }
        .dropdown-menu.show-dropdown {
            opacity: 1;
            transform: scale(1) translateY(0);
            pointer-events: auto;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex relative overflow-x-hidden">

    <!-- Backdrop oscuro para móviles cuando el sidebar esté abierto -->
    <div id="sidebarBackdrop" class="fixed inset-0 bg-slate-900/40 z-30 hidden lg:hidden backdrop-blur-xs transition-opacity"></div>

    <!-- 1. Sidebar -->
    @include('sst::components.layouts.adminsidebar')

    <!-- 2. Contenedor Principal -->
    <div id="mainContentWrapper" class="flex-1 ml-[270px] min-h-screen flex flex-col w-full">
    <script>
        (function() {
            try {
                if (localStorage.getItem('sst_sidebar_mode') === 'mini' && window.innerWidth >= 1024) {
                    var sb = document.getElementById('sidebarMenu');
                    var mc = document.getElementById('mainContentWrapper');
                    if (sb) sb.classList.add('sidebar-mini');
                    if (mc) mc.classList.add('mini-content');
                }
            } catch(e) {}
        })();
    </script>
        
        <!-- Top Navbar del Admin (Estilo Ejecutivo Dirección) -->
        <header class="h-16 bg-gradient-to-r from-[#0A2333] via-[#0F3247] to-[#0A2333] border-b-[3px] border-[#39A900] sticky top-0 z-20 px-4 sm:px-6 flex items-center justify-between shadow-lg text-white">
            
            <!-- Botón Hamburguesa, Acceso a Gráficas y Título -->
            <div class="flex items-center gap-2.5 sm:gap-3">
                <button id="toggleSidebarBtn" class="w-10 h-10 rounded-xl bg-white/10 text-white hover:text-[#62E31D] hover:bg-white/20 flex items-center justify-center transition focus:outline-none border border-white/15 cursor-pointer shadow-xs" title="Contraer / Expandir Menú">
                    <i class="fa-solid fa-bars text-base"></i>
                </button>

                <a href="{{ route('SST.admin.dashboard') }}" class="w-10 h-10 rounded-xl bg-white/10 text-white hover:text-[#62E31D] hover:bg-white/20 flex items-center justify-center transition border border-white/15 cursor-pointer shadow-xs group" title="Ver Gráficas y Métricas">
                    <i class="fa-solid fa-chart-pie text-sm group-hover:scale-110 transition-transform"></i>
                </a>

                <h2 class="font-extrabold text-white text-sm sm:text-base tracking-tight flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-[#62E31D] animate-pulse"></span>
                    @yield('title', 'Panel Administrativo • SST')
                </h2>
            </div>
            
            <!-- Menú de Usuario con Dropdown Desplegable -->
            <div class="relative">
                <!-- Botón de Perfil interactivo -->
                <button id="userMenuBtn" class="flex items-center gap-3 p-1.5 sm:px-3 sm:py-2 rounded-2xl hover:bg-white/10 text-white transition focus:outline-none cursor-pointer border border-transparent hover:border-white/15">
                    
                    <!-- Avatar con Iniciales (Circular Verde) -->
                    <div class="w-9 h-9 rounded-full bg-[#39A900] text-white flex items-center justify-center text-xs font-black shadow-md ring-2 ring-[#62E31D]/40">
                        {{ Auth::user()->initials ?? 'OP' }}
                    </div>

                    <!-- Datos del Usuario -->
                    <div class="text-left hidden sm:block leading-tight">
                        <div class="text-xs font-bold text-white flex items-center gap-1.5 uppercase">
                            {{ Auth::user()->full_name ?? Auth::user()->name ?? 'Administrador SST' }}
                            <i class="fa-solid fa-chevron-down text-[9px] text-slate-300 transition-transform duration-200" id="userMenuChevron"></i>
                        </div>
                        <span class="inline-block text-[10px] text-[#62E31D] font-bold">Administrador SST</span>
                    </div>
                </button>

                <!-- Menú Desplegable (Dropdown) -->
                <div id="userDropdown" class="dropdown-menu hidden-dropdown absolute right-0 mt-2 w-64 bg-[#0F3247] rounded-2xl shadow-2xl border border-[#1A4B69] py-2 z-50 text-slate-200 divide-y divide-[#1A4B69]">
                    
                    <!-- Encabezado del Dropdown -->
                    <div class="px-4 py-3 bg-[#0A2333] rounded-t-2xl">
                        <p class="text-xs font-bold text-white truncate">{{ Auth::user()->full_name ?? 'Administrador SST' }}</p>
                        <p class="text-[11px] text-slate-400 truncate">{{ Auth::user()->email ?? 'admin.sst@sena.edu.co' }}</p>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold bg-[#1A4B69] text-[#62E31D] border border-[#39A900]/40 mt-1.5">
                            <i class="fa-solid fa-shield-halved text-[8px]"></i> Administrador SST
                        </span>
                    </div>

                    <!-- Opciones de Navegación -->
                    <div class="py-1">
                        <a href="{{ route('SST.admin.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-slate-200 hover:bg-white/10 hover:text-[#62E31D] transition">
                            <i class="fa-solid fa-chart-pie text-[#62E31D]"></i>
                            <span>Ver Gráficas y Métricas</span>
                        </a>

                        <a href="{{ route('SST.welcome') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-slate-200 hover:bg-white/10 hover:text-[#62E31D] transition">
                            <i class="fa-solid fa-house text-[#39A900]"></i>
                            <span>Inicio SST</span>
                        </a>

                        <a href="{{ route('home') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-slate-200 hover:bg-white/10 hover:text-[#62E31D] transition">
                            <i class="fa-solid fa-grid-horizontal text-[#39A900]"></i>
                            <span>Portal General ERP</span>
                        </a>
                    </div>

                    <!-- Botón Cerrar Sesión -->
                    <div class="pt-1">
                        <a href="{{ route('logout', ['redirect' => route('SST.welcome')]) }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-rose-400 hover:bg-rose-950/40 hover:text-rose-300 transition">
                            <i class="fa-solid fa-power-off text-rose-500"></i>
                            <span>Cerrar Sesión</span>
                        </a>
                    </div>

                </div>
            </div>

        </header>

        <!-- 3. Contenido inyectado desde cada vista -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            @yield('content')
        </main>
    </div>

    <!-- Barra de progreso para carga dinámica SPA -->
    <div id="spaProgressBar" class="fixed top-0 left-0 h-1 bg-gradient-to-r from-[#0A2333] via-[#39A900] to-[#62E31D] z-50 transition-all duration-300 w-0 opacity-0 pointer-events-none"></div>

    <!-- Scripts de Interacción y SPA -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // --- 1. Toggle Sidebar (Abrir / Mini / Cerrar) ---
            const toggleBtn = document.getElementById('toggleSidebarBtn');
            const sidebar = document.getElementById('sidebarMenu');
            const mainContent = document.getElementById('mainContentWrapper');
            const backdrop = document.getElementById('sidebarBackdrop');

            // Restaurar estado guardado
            const savedMode = localStorage.getItem('sst_sidebar_mode');
            if (savedMode === 'mini' && window.innerWidth >= 1024 && sidebar && mainContent) {
                sidebar.classList.add('sidebar-mini');
                mainContent.classList.add('mini-content');
            }

            if (toggleBtn && sidebar && mainContent) {
                toggleBtn.addEventListener('click', function () {
                    if (window.innerWidth >= 1024) {
                        // En escritorio: alternar modo mini / completo
                        document.documentElement.classList.remove('sst-mini-sidebar');
                        const isMini = sidebar.classList.toggle('sidebar-mini');
                        mainContent.classList.toggle('mini-content', isMini);
                        localStorage.setItem('sst_sidebar_mode', isMini ? 'mini' : 'expanded');
                    } else {
                        // En móvil: ocultar / mostrar completamente
                        const isHidden = sidebar.classList.toggle('sidebar-hidden');
                        mainContent.classList.toggle('full-width', isHidden);
                        if (backdrop) {
                            backdrop.classList.toggle('hidden', isHidden);
                        }
                    }
                });

                if (backdrop) {
                    backdrop.addEventListener('click', function () {
                        sidebar.classList.add('sidebar-hidden');
                        mainContent.classList.add('full-width');
                        backdrop.classList.add('hidden');
                    });
                }
            }

            // --- 2. Dropdown de Usuario ---
            const userMenuBtn = document.getElementById('userMenuBtn');
            const userDropdown = document.getElementById('userDropdown');
            const userChevron = document.getElementById('userMenuChevron');

            if (userMenuBtn && userDropdown) {
                userMenuBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    const isShown = userDropdown.classList.contains('show-dropdown');
                    
                    if (isShown) {
                        userDropdown.classList.remove('show-dropdown');
                        userDropdown.classList.add('hidden-dropdown');
                        if (userChevron) userChevron.style.transform = 'rotate(0deg)';
                    } else {
                        userDropdown.classList.remove('hidden-dropdown');
                        userDropdown.classList.add('show-dropdown');
                        if (userChevron) userChevron.style.transform = 'rotate(180deg)';
                    }
                });

                document.addEventListener('click', function (e) {
                    if (!userMenuBtn.contains(e.target) && !userDropdown.contains(e.target)) {
                        userDropdown.classList.remove('show-dropdown');
                        userDropdown.classList.add('hidden-dropdown');
                        if (userChevron) userChevron.style.transform = 'rotate(0deg)';
                    }
                });
            }

            // --- 3. NAVEGACIÓN DINÁMICA SPA (SIN RECARGAR LA PÁGINA) ---
            const progressBar = document.getElementById('spaProgressBar');
            const mainEl = document.querySelector('main');
            const headerTitle = document.querySelector('header h2');

            function startProgress() {
                if (!progressBar) return;
                progressBar.style.width = '0%';
                progressBar.style.opacity = '1';
                setTimeout(() => { progressBar.style.width = '45%'; }, 50);
                setTimeout(() => { progressBar.style.width = '75%'; }, 180);
            }

            function finishProgress() {
                if (!progressBar) return;
                progressBar.style.width = '100%';
                setTimeout(() => {
                    progressBar.style.opacity = '0';
                    setTimeout(() => { progressBar.style.width = '0%'; }, 300);
                }, 150);
            }

            function executeScripts(container) {
                const scripts = container.querySelectorAll('script');
                scripts.forEach(oldScript => {
                    const newScript = document.createElement('script');
                    Array.from(oldScript.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
                    newScript.appendChild(document.createTextNode(oldScript.innerHTML));
                    oldScript.parentNode.replaceChild(newScript, oldScript);
                });
            }

            function updateSidebarActive(targetUrl) {
                const urlPath = new URL(targetUrl, window.location.origin).pathname.toLowerCase().replace(/\/+$/, '');
                
                // Cerrar flyouts abiertos
                document.querySelectorAll('.menu-wrapper').forEach(w => w.classList.remove('flyout-open'));

                // El logo NUNCA debe tener clases activas ni fondo blanco
                document.querySelectorAll('.logo-full, .logo-mini').forEach(logo => {
                    logo.classList.remove('bg-white', 'text-[#39A900]', 'shadow-xs', 'font-bold');
                });

                document.querySelectorAll('#sidebarMenu a').forEach(a => {
                    // Ignorar enlaces del logo o del header
                    if (a.classList.contains('logo-full') || a.classList.contains('logo-mini') || a.closest('.logo-full') || a.closest('.logo-mini')) {
                        a.classList.remove('bg-white', 'text-[#39A900]', 'shadow-xs', 'font-bold');
                        return;
                    }

                    const href = a.getAttribute('href');
                    if (!href || href === '#' || href.startsWith('javascript:')) return;
                    
                    const linkPath = new URL(href, window.location.origin).pathname.toLowerCase().replace(/\/+$/, '');
                    const isMatch = (urlPath === linkPath) || (urlPath.startsWith(linkPath) && linkPath !== '/sst' && linkPath !== '/sst/admin/dashboard');

                    if (a.closest('.sidebar-flyout')) {
                        // Elemento dentro de tarjeta flotante
                        if (isMatch) {
                            a.classList.add('bg-white/15', 'text-white', 'font-bold');
                        } else {
                            a.classList.remove('bg-white/15', 'text-white', 'font-bold');
                        }
                    } else if (a.classList.contains('submenu-item')) {
                        // Elemento de lista en submenú normal
                        if (isMatch) {
                            a.classList.add('active-sub');
                        } else {
                            a.classList.remove('active-sub');
                        }
                    }
                });
            }

            async function loadSpaPage(url, push = true) {
                try {
                    startProgress();
                    const response = await fetch(url, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });

                    if (!response.ok) {
                        window.location.href = url; // Fallback a navegación normal si hay error
                        return;
                    }

                    const htmlText = await response.text();
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(htmlText, 'text/html');

                    // 1. Actualizar título de pestaña
                    if (doc.title) {
                        document.title = doc.title;
                    }

                    // 2. Actualizar título de cabecera si existe
                    const newHeaderTitle = doc.querySelector('header h2');
                    if (headerTitle && newHeaderTitle) {
                        headerTitle.innerHTML = newHeaderTitle.innerHTML;
                    }

                    // 3. Reemplazar contenido del main
                    const newMain = doc.querySelector('main');
                    if (mainEl && newMain) {
                        mainEl.style.opacity = '0.3';
                        mainEl.style.transition = 'opacity 0.15s ease';
                        
                        setTimeout(() => {
                            mainEl.innerHTML = newMain.innerHTML;
                            executeScripts(mainEl);
                            initSSTAutoDismissAlerts(mainEl);
                            if (typeof window.initSSTCharts === 'function') {
                                setTimeout(window.initSSTCharts, 50);
                            }
                            mainEl.style.opacity = '1';
                            window.scrollTo({ top: 0, behavior: 'smooth' });
                        }, 100);
                    } else {
                        window.location.href = url;
                        return;
                    }

                    // 4. Actualizar Sidebar
                    updateSidebarActive(url);

                    // 5. Historial de navegador (Back/Forward)
                    if (push) {
                        window.history.pushState({ spa: true, url }, '', url);
                    }

                    finishProgress();

                } catch (error) {
                    console.error('Error cargando página SPA:', error);
                    window.location.href = url;
                }
            }

            // Interceptar clics en enlaces del sidebar
            document.addEventListener('click', function(e) {
                const link = e.target.closest('#sidebarMenu a, header a.spa-link');
                if (!link) return;

                const href = link.getAttribute('href');
                if (!href || href === '#' || href.startsWith('javascript:') || link.target === '_blank') {
                    return;
                }

                // Asegurarse de que sea una ruta interna del módulo SST Admin
                if (href.includes('/Sst/admin') || href.includes('/sst/admin')) {
                    e.preventDefault();
                    loadSpaPage(href, true);
                }
            });

            // Soporte para botones Atrás/Adelante del navegador
            window.addEventListener('popstate', function(e) {
                if (window.location.pathname.includes('/Sst/admin') || window.location.pathname.includes('/sst/admin')) {
                    loadSpaPage(window.location.href, false);
                }
            });

            // Inicializar autodismiss de alertas flash (3 segundos)
            initSSTAutoDismissAlerts(document);

            // Abrir calendario automáticamente al hacer clic en inputs de fecha/hora
            document.addEventListener('click', function (e) {
                const target = e.target;
                if (target && (target.type === 'date' || target.type === 'datetime-local')) {
                    if (typeof target.showPicker === 'function') {
                        try {
                            target.showPicker();
                        } catch (err) {}
                    }
                }
            // Inicializar Selects Buscables con filtro por inicial
            setupSSTSearchableSelects(document);
        });

        // =========================================================================
        // SST • SELECTS BUSCABLES CON FILTRADO POR INICIAL
        // =========================================================================
        function setupSSTSearchableSelects(container) {
            const root = container || document;
            const selects = root.querySelectorAll ? root.querySelectorAll('select.sst-searchable') : [];

            selects.forEach(select => {
                if (select.dataset.searchableInit === 'true') return;
                select.dataset.searchableInit = 'true';

                const isRequired = select.hasAttribute('required');
                if (isRequired) {
                    select.removeAttribute('required');
                }

                select.style.position = 'absolute';
                select.style.opacity = '0';
                select.style.pointerEvents = 'none';
                select.style.height = '0';
                select.style.width = '0';
                select.tabIndex = -1;

                const wrapper = document.createElement('div');
                wrapper.className = 'relative sst-searchable-wrapper w-full';
                select.parentNode.insertBefore(wrapper, select);
                wrapper.appendChild(select);

                const inputContainer = document.createElement('div');
                inputContainer.className = 'relative w-full';

                const visibleInput = document.createElement('input');
                visibleInput.type = 'text';
                visibleInput.autocomplete = 'off';
                if (isRequired) visibleInput.required = true;
                visibleInput.placeholder = select.options[0] ? select.options[0].text : 'Seleccione o escriba...';
                visibleInput.className = 'w-full pl-3.5 pr-8 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#39A900]/20 focus:border-[#39A900] transition shadow-2xs cursor-pointer';

                const chevron = document.createElement('div');
                chevron.className = 'absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400 text-xs transition-transform duration-200';
                chevron.innerHTML = '<i class="fa-solid fa-chevron-down text-[10px]"></i>';

                inputContainer.appendChild(visibleInput);
                inputContainer.appendChild(chevron);
                wrapper.appendChild(inputContainer);

                const list = document.createElement('div');
                list.className = 'sst-dropdown-list hidden absolute left-0 right-0 top-full mt-1.5 bg-white border border-slate-200 rounded-2xl shadow-xl z-50 max-h-52 overflow-y-auto p-1.5 space-y-0.5 animate-in fade-in zoom-in-95 duration-150';
                wrapper.appendChild(list);

                function buildList(filterQuery = '') {
                    list.innerHTML = '';
                    const q = (filterQuery || '').trim().toLowerCase();
                    let matchCount = 0;

                    Array.from(select.options).forEach((opt, idx) => {
                        if (idx === 0 && !opt.value) return;
                        const text = opt.text.trim();
                        const lowerText = text.toLowerCase();
                        const words = lowerText.split(/\s+/);

                        // Filtro por primera letra o inicio de palabra
                        const matches = !q || lowerText.startsWith(q) || words.some(w => w.startsWith(q));

                        if (matches) {
                            matchCount++;
                            const item = document.createElement('div');
                            const isSelected = select.value === opt.value;
                            item.className = 'px-3 py-2 text-xs font-semibold rounded-xl cursor-pointer transition flex items-center justify-between ' + 
                                            (isSelected ? 'bg-emerald-50 text-[#39A900] font-bold' : 'text-slate-700 hover:bg-slate-50 hover:text-[#39A900]');
                            item.textContent = text;
                            if (isSelected) {
                                item.innerHTML += '<i class="fa-solid fa-check text-[#39A900] text-xs"></i>';
                            }
                            item.addEventListener('mousedown', (e) => {
                                e.preventDefault();
                                select.value = opt.value;
                                visibleInput.value = text;
                                visibleInput.setCustomValidity('');
                                select.dispatchEvent(new Event('change', { bubbles: true }));
                                closeList();
                            });
                            list.appendChild(item);
                        }
                    });

                    if (matchCount === 0) {
                        const emptyMsg = document.createElement('div');
                        emptyMsg.className = 'px-3 py-3 text-center text-xs text-slate-400 font-medium';
                        emptyMsg.textContent = 'No hay opciones que inicien con "' + filterQuery + '"';
                        list.appendChild(emptyMsg);
                    }
                }

                function openList() {
                    closeAllSearchableLists();
                    buildList('');
                    list.classList.remove('hidden');
                    chevron.querySelector('i').classList.add('rotate-180');
                }

                function closeList() {
                    list.classList.add('hidden');
                    chevron.querySelector('i').classList.remove('rotate-180');
                    const currentText = visibleInput.value.trim().toLowerCase();
                    const matchedOpt = Array.from(select.options).find(o => o.text.trim().toLowerCase() === currentText && o.value);
                    if (matchedOpt) {
                        select.value = matchedOpt.value;
                        visibleInput.value = matchedOpt.text.trim();
                        visibleInput.setCustomValidity('');
                    } else if (!select.value) {
                        visibleInput.value = '';
                    } else {
                        const selectedOpt = select.options[select.selectedIndex];
                        visibleInput.value = (selectedOpt && selectedOpt.value) ? selectedOpt.text.trim() : '';
                        visibleInput.setCustomValidity('');
                    }
                }

                visibleInput.addEventListener('focus', () => {
                    openList();
                    visibleInput.select();
                });

                visibleInput.addEventListener('click', () => {
                    if (list.classList.contains('hidden')) {
                        openList();
                    }
                });

                visibleInput.addEventListener('input', () => {
                    if (list.classList.contains('hidden')) {
                        list.classList.remove('hidden');
                        chevron.querySelector('i').classList.add('rotate-180');
                    }
                    buildList(visibleInput.value);
                    if (!visibleInput.value.trim()) {
                        select.value = '';
                    }
                });

                visibleInput.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape') {
                        closeList();
                        visibleInput.blur();
                    } else if (e.key === 'Enter') {
                        const firstMatch = list.querySelector('.dropdown-item, [class*="cursor-pointer"]');
                        if (firstMatch && !list.classList.contains('hidden')) {
                            e.preventDefault();
                            firstMatch.click();
                        }
                    }
                });

                select.addEventListener('change', () => {
                    const selectedOpt = select.options[select.selectedIndex];
                    visibleInput.value = (selectedOpt && selectedOpt.value) ? selectedOpt.text.trim() : '';
                    if (visibleInput.value) visibleInput.setCustomValidity('');
                });

                // Si se resetea el formulario padre, limpiar visibleInput
                const parentForm = select.closest('form');
                if (parentForm) {
                    parentForm.addEventListener('reset', () => {
                        setTimeout(() => {
                            select.value = '';
                            visibleInput.value = '';
                            visibleInput.setCustomValidity('');
                        }, 10);
                    });
                }

                // Sincronizar valor inicial si ya viene seleccionado
                if (select.value) {
                    const selectedOpt = select.options[select.selectedIndex];
                    visibleInput.value = (selectedOpt && selectedOpt.value) ? selectedOpt.text.trim() : '';
                }
            });
        }

        function closeAllSearchableLists() {
            document.querySelectorAll('.sst-dropdown-list').forEach(l => l.classList.add('hidden'));
            document.querySelectorAll('.sst-searchable-wrapper i.fa-chevron-down').forEach(i => i.classList.remove('rotate-180'));
        }

        document.addEventListener('click', (e) => {
            if (!e.target.closest('.sst-searchable-wrapper')) {
                closeAllSearchableLists();
            }
        });

        window.setupSSTSearchableSelects = setupSSTSearchableSelects;
        window.closeAllSearchableLists = closeAllSearchableLists;

        // =========================================================================
        // =========================================================================
        // SST • AUTO-DESAPARECER ALERTAS FLASH TRAS 3 SEGUNDOS
        // =========================================================================
        function dismissAlertWithAnimation(alertEl) {
            if (!alertEl || alertEl.dataset.dismissing === 'true') return;
            alertEl.dataset.dismissing = 'true';

            alertEl.style.transition = 'opacity 0.4s ease, transform 0.4s ease, max-height 0.4s ease, margin 0.4s ease, padding 0.4s ease';
            alertEl.style.opacity = '0';
            alertEl.style.transform = 'translateY(-8px)';

            setTimeout(function () {
                alertEl.style.maxHeight = '0';
                alertEl.style.marginTop = '0';
                alertEl.style.marginBottom = '0';
                alertEl.style.paddingTop = '0';
                alertEl.style.paddingBottom = '0';
                setTimeout(function () {
                    if (alertEl.parentElement) {
                        alertEl.remove();
                    }
                }, 400);
            }, 200);
        }

        function initSSTAutoDismissAlerts(container) {
            const root = container || document;
            const elements = root.querySelectorAll ? root.querySelectorAll('.sst-flash-alert, .sst-auto-dismiss, .bg-emerald-50, .bg-rose-50, .bg-amber-50, .bg-sky-50') : [];

            elements.forEach(function (el) {
                const closeBtn = el.querySelector('button[onclick*="remove"]') || el.querySelector('button.close-alert');
                const isAlertBanner = el.classList.contains('sst-flash-alert') || 
                                     el.classList.contains('sst-auto-dismiss') || 
                                     closeBtn !== null ||
                                     (el.classList.contains('p-4') && el.classList.contains('rounded-2xl') && (el.classList.contains('bg-emerald-50') || el.classList.contains('bg-rose-50')));

                if (isAlertBanner && !el.dataset.dismissTimerSet) {
                    el.dataset.dismissTimerSet = 'true';

                    // Desaparecer después de 1 segundo exacto
                    const timer = setTimeout(function () {
                        dismissAlertWithAnimation(el);
                    }, 1000);

                    // Si el usuario da clic en la 'x' manualmente antes de los 3 segundos
                    if (closeBtn) {
                        closeBtn.onclick = function (e) {
                            e.preventDefault();
                            clearTimeout(timer);
                            dismissAlertWithAnimation(el);
                        };
                    }
                }
            });
        }

        // Observer para capturar alertas inyectadas dinámicamente en el DOM
        try {
            const alertObserver = new MutationObserver(function (mutations) {
                mutations.forEach(function (mutation) {
                    mutation.addedNodes.forEach(function (node) {
                        if (node.nodeType === 1) {
                            initSSTAutoDismissAlerts(node);
                        }
                    });
                });
            });
            alertObserver.observe(document.body, { childList: true, subtree: true });
        } catch (e) {}

        // =========================================================================
        // SST • MODAL ELEGANTE DE CONFIRMACIÓN DE ELIMINACIÓN (SWEETALERT2)
        // =========================================================================
        window.sstConfirmDelete = function(options = {}) {
            const title = options.title || '¿Eliminar registro?';
            const text = options.text || 'Esta acción no se puede deshacer y el registro será eliminado permanentemente.';
            const confirmButtonText = options.confirmButtonText || '<i class="fa-solid fa-trash-can text-xs"></i><span>Sí, eliminar</span>';
            const cancelButtonText = options.cancelButtonText || '<span>Cancelar</span>';

            return Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                iconColor: '#e11d48',
                showCancelButton: true,
                confirmButtonText: confirmButtonText,
                cancelButtonText: cancelButtonText,
                reverseButtons: true,
                focusCancel: true,
                buttonsStyling: false,
                backdrop: 'rgba(15, 23, 42, 0.6)',
                customClass: {
                    popup: 'sst-swal-popup',
                    title: 'sst-swal-title',
                    htmlContainer: 'sst-swal-text',
                    actions: 'sst-swal-actions',
                    confirmButton: 'sst-swal-confirm-btn',
                    cancelButton: 'sst-swal-cancel-btn'
                }
            });
        };

        // Interceptor Global de Formularios de Eliminación en SST Admin
        document.addEventListener('submit', function(e) {
            const form = e.target;
            // Si ya fue confirmado o si el formulario está dentro de un modal propio de confirmación, permitir envío directo
            if (form.dataset.confirmed === 'true' || form.closest('#modalDelete, #deleteModal, #modalEliminarOverlay, [id*="modalDelete"], [id*="deleteModal"], [id*="modalEliminar"]')) {
                return;
            }

            const onsubmitAttr = form.getAttribute('onsubmit') || '';
            const hasDeleteMethod = form.querySelector('input[name="_method"][value="DELETE"]') !== null;
            const isDeleteRoute = (form.action || '').toLowerCase().includes('destroy') || (form.action || '').toLowerCase().includes('delete');
            const isDeleteForm = onsubmitAttr.includes('confirm(') || hasDeleteMethod || isDeleteRoute || form.classList.contains('form-eliminar');

            if (isDeleteForm) {
                e.preventDefault();
                e.stopImmediatePropagation();

                let msg = '¿Estás seguro de que deseas eliminar este registro? Esta acción no se puede deshacer.';
                let title = '¿Eliminar registro?';

                const match = onsubmitAttr.match(/confirm\s*\(\s*['"`](.*?)['"`]\s*\)/);
                if (match && match[1]) {
                    msg = match[1];
                }

                if (msg.toLowerCase().includes('pausa') || (form.action || '').includes('pausas')) {
                    title = '¿Eliminar Pausa Activa?';
                } else if (msg.toLowerCase().includes('charla') || (form.action || '').includes('charlas')) {
                    title = '¿Eliminar Charla?';
                } else if (msg.toLowerCase().includes('sensibilización') || (form.action || '').includes('sensibilizacion')) {
                    title = '¿Eliminar Sensibilización?';
                } else if (msg.toLowerCase().includes('inspección') || (form.action || '').includes('inspecciones')) {
                    title = '¿Eliminar Inspección?';
                } else if (msg.toLowerCase().includes('rol') || (form.action || '').includes('remove-role')) {
                    title = '¿Remover Rol?';
                } else if (msg.toLowerCase().includes('actividad') || (form.action || '').includes('cronograma')) {
                    title = '¿Eliminar Actividad?';
                }

                sstConfirmDelete({
                    title: title,
                    text: msg
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.dataset.confirmed = 'true';
                        form.submit();
                    }
                });
            }
        }, true);
    </script>

    @stack('scripts')
</body>
</html>

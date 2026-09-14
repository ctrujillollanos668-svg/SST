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

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        #mainContentWrapper {
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        #mainContentWrapper.mini-content {
            margin-left: 76px !important;
        }
        #mainContentWrapper.full-width {
            margin-left: 0 !important;
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
        
        <!-- Top Navbar del Admin -->
        <header class="h-16 bg-white border-b border-slate-200/80 sticky top-0 z-20 px-4 sm:px-6 flex items-center justify-between shadow-xs">
            
            <!-- Botón Hamburguesa y Título -->
            <div class="flex items-center gap-3">
                <button id="toggleSidebarBtn" class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 hover:text-orange-600 hover:bg-orange-50 flex items-center justify-center transition focus:outline-none border border-slate-200 cursor-pointer" title="Contraer / Expandir Menú">
                    <i class="fa-solid fa-bars text-base"></i>
                </button>

                <h2 class="font-bold text-slate-800 text-sm sm:text-base tracking-tight">
                    @yield('title', 'Panel Administrativo')
                </h2>
            </div>
            
            <!-- Menú de Usuario con Dropdown Desplegable -->
            <div class="relative">
                <!-- Botón de Perfil interactivo -->
                <button id="userMenuBtn" class="flex items-center gap-3 p-1.5 sm:px-3 sm:py-2 rounded-2xl hover:bg-slate-100/80 transition focus:outline-none cursor-pointer border border-transparent hover:border-slate-200">
                    
                    <!-- Avatar con Iniciales -->
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-orange-600 to-amber-500 text-white flex items-center justify-center text-xs font-extrabold shadow-sm">
                        {{ Auth::user()->initials ?? 'AD' }}
                    </div>

                    <!-- Datos del Usuario -->
                    <div class="text-left hidden sm:block leading-tight">
                        <div class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                            {{ Auth::user()->full_name ?? Auth::user()->name ?? 'Administrador SST' }}
                            <i class="fa-solid fa-chevron-down text-[9px] text-slate-400 transition-transform duration-200" id="userMenuChevron"></i>
                        </div>
                        <span class="inline-block text-[10px] text-orange-600 font-semibold">Administrador SST</span>
                    </div>
                </button>

                <!-- Menú Desplegable (Dropdown) -->
                <div id="userDropdown" class="dropdown-menu hidden-dropdown absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50">
                    
                    <!-- Encabezado del Dropdown -->
                    <div class="px-4 py-3 border-b border-slate-100 bg-slate-50/50 rounded-t-2xl">
                        <p class="text-xs font-bold text-slate-800 truncate">{{ Auth::user()->full_name ?? 'Administrador SST' }}</p>
                        <p class="text-[11px] text-slate-500 truncate">{{ Auth::user()->email ?? 'admin.sst@sena.edu.co' }}</p>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold bg-orange-100 text-orange-700 mt-1.5">
                            <i class="fa-solid fa-shield-halved text-[8px]"></i> Administrador SST
                        </span>
                    </div>

                    <!-- Opciones de Navegación -->
                    <div class="py-1">
                        <a href="{{ route('SST.welcome') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-orange-50 hover:text-orange-600 transition">
                            <i class="fa-solid fa-house text-slate-400"></i>
                            <span>Inicio SST</span>
                        </a>

                        <a href="{{ route('home') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-orange-50 hover:text-orange-600 transition">
                            <i class="fa-solid fa-grid-horizontal text-slate-400"></i>
                            <span>Portal General ERP</span>
                        </a>
                    </div>

                    <!-- Botón Cerrar Sesión -->
                    <div class="pt-1 border-t border-slate-100">
                        <a href="{{ route('logout', ['redirect' => route('SST.welcome')]) }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-red-600 hover:bg-red-50 transition">
                            <i class="fa-solid fa-power-off text-red-500"></i>
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

    <!-- Scripts de Interacción -->
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

        });
    </script>

</body>
</html>

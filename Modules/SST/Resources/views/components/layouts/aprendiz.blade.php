<!DOCTYPE html>
<html lang="es" class="notranslate" translate="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google" content="notranslate">
    <title>@yield('title', 'Aprendiz SST • SENA Empresa')</title>

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
            filter: invert(42%) sepia(85%) saturate(1450%) hue-rotate(360deg) brightness(97%) contrast(98%);
        }
        input[type="date"]::-webkit-calendar-picker-indicator:hover,
        input[type="datetime-local"]::-webkit-calendar-picker-indicator:hover {
            opacity: 1;
            transform: scale(1.15);
            background-color: rgba(254, 243, 199, 0.5);
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

        .vista { display: none; }
        .vista.active { display: block; }
        .modal { display: none !important; }
        .modal.active { display: flex !important; }
    </style>
</head>
<body class="bg-[#f4f7f6] text-slate-800 antialiased min-h-screen flex relative overflow-x-hidden">

    <!-- Backdrop oscuro para móviles cuando el sidebar esté abierto -->
    <div id="sidebarBackdrop" class="fixed inset-0 bg-slate-900/40 z-30 hidden lg:hidden backdrop-blur-xs transition-opacity"></div>

    <!-- 1. Sidebar Aprendiz -->
    @include('sst::components.layouts.aprendizsidebar')

    <!-- 2. Contenedor Principal -->
    <div id="mainContentWrapper" class="flex-1 ml-[270px] min-h-screen flex flex-col w-full">
        
        <!-- Top Navbar del Aprendiz (Estilo Ejecutivo Dirección) -->
        <header class="h-16 bg-gradient-to-r from-[#0A2333] via-[#0F3247] to-[#0A2333] border-b-[3px] border-[#39A900] sticky top-0 z-20 px-4 sm:px-6 flex items-center justify-between shadow-lg text-white">
            
            <!-- Botón Hamburguesa, Acceso a Gráficas y Título (Idéntico a Admin) -->
            <div class="flex items-center gap-2.5 sm:gap-3">
                <button id="toggleSidebarBtn" class="w-10 h-10 rounded-xl bg-white/10 text-white hover:text-[#62E31D] hover:bg-white/20 flex items-center justify-center transition focus:outline-none border border-white/15 cursor-pointer shadow-xs" title="Contraer / Expandir Menú">
                    <i class="fa-solid fa-bars text-base"></i>
                </button>

                <a href="{{ route('SST.aprendiz.dashboard') }}" class="w-10 h-10 rounded-xl bg-white/10 text-white hover:text-[#62E31D] hover:bg-white/20 flex items-center justify-center transition border border-white/15 cursor-pointer shadow-xs group" title="Ver Gráficas y Métricas">
                    <i class="fa-solid fa-chart-pie text-sm group-hover:scale-110 transition-transform"></i>
                </a>

                <h2 class="font-extrabold text-white text-sm sm:text-base tracking-tight flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-[#62E31D] animate-pulse"></span>
                    @yield('title', 'Portal Aprendiz • SST')
                </h2>
            </div>
            
            <div class="flex items-center gap-4">
                <!-- Notificaciones -->
                <button class="cursor-pointer text-slate-300 hover:text-[#62E31D] hover:bg-white/10 transition text-base relative p-2 rounded-xl" title="Notificaciones">
                    <i class="fa-regular fa-bell"></i>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-[#62E31D] rounded-full animate-ping"></span>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-[#62E31D] rounded-full"></span>
                </button>

                <span class="h-6 w-px bg-white/20"></span>

                <!-- Menú de Usuario con Dropdown Desplegable -->
                <div class="relative">
                    <!-- Botón de Perfil interactivo -->
                    <button id="userMenuBtn" class="flex items-center gap-3 p-1.5 sm:px-3 sm:py-2 rounded-2xl hover:bg-white/10 text-white transition focus:outline-none cursor-pointer border border-transparent hover:border-white/15">
                        
                        <!-- Avatar con Iniciales -->
                        <div class="w-9 h-9 rounded-full bg-[#39A900] text-white flex items-center justify-center text-xs font-black shadow-md ring-2 ring-[#62E31D]/40">
                            {{ Auth::user()->initials ?? 'AP' }}
                        </div>

                        <!-- Datos del Usuario -->
                        <div class="text-left hidden sm:block leading-tight">
                            <div class="text-xs font-bold text-white flex items-center gap-1.5 uppercase">
                                {{ Auth::user()->full_name ?? Auth::user()->name ?? 'Aprendiz SST' }}
                                <i class="fa-solid fa-chevron-down text-[9px] text-slate-300 transition-transform duration-200" id="userMenuChevron"></i>
                            </div>
                            <span class="inline-block text-[10px] text-[#62E31D] font-bold">Aprendiz SST</span>
                        </div>
                    </button>

                    <!-- Menú Desplegable (Dropdown) -->
                    <div id="userDropdown" class="dropdown-menu hidden-dropdown absolute right-0 mt-2 w-64 bg-[#0F3247] rounded-2xl shadow-2xl border border-[#1A4B69] py-2 z-50 text-slate-200 divide-y divide-[#1A4B69]">
                        
                        <!-- Encabezado del Dropdown -->
                        <div class="px-4 py-3 bg-[#0A2333] rounded-t-2xl">
                            <p class="text-xs font-bold text-white truncate">{{ Auth::user()->full_name ?? 'Aprendiz SST' }}</p>
                            <p class="text-[11px] text-slate-400 truncate">{{ Auth::user()->email ?? 'aprendiz.sst@sena.edu.co' }}</p>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold bg-[#1A4B69] text-[#62E31D] border border-[#39A900]/40 mt-1.5">
                                <i class="fa-solid fa-user-check text-[8px]"></i> Aprendiz SST
                            </span>
                        </div>

                        <!-- Opciones de Navegación -->
                        <div class="py-1">
                            <a href="{{ route('SST.aprendiz.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-slate-200 hover:bg-white/10 hover:text-[#62E31D] transition">
                                <i class="fa-solid fa-chart-pie text-[#39A900]"></i>
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
            </div>

        </header>

        <!-- 3. Contenido inyectado desde cada vista -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            <!-- Mensajes Flash y Errores -->
            @if(session('success'))
                <div class="mb-4 p-4 rounded-2xl bg-emerald-50 border border-emerald-200/80 text-emerald-800 flex items-center justify-between shadow-xs">
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
            @if(session('error') || $errors->any())
                <div class="mb-4 p-4 rounded-2xl bg-rose-50 border border-rose-200/80 text-rose-800 flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center font-bold">
                            <i class="fa-solid fa-circle-exclamation text-sm"></i>
                        </span>
                        <div class="text-xs sm:text-sm font-semibold">
                            {{ session('error') ?? 'Ocurrieron errores al procesar la solicitud:' }}
                            @if($errors->any())
                                <ul class="list-disc list-inside text-xs mt-1 text-rose-700 font-medium">
                                    @foreach($errors->all() as $err)
                                        <li>{{ $err }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700 p-1 cursor-pointer">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>
            @endif

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
                        const isMini = sidebar.classList.toggle('sidebar-mini');
                        mainContent.classList.toggle('mini-content', isMini);
                        localStorage.setItem('sst_sidebar_mode', isMini ? 'mini' : 'expanded');
                    } else {
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

            // --- 3. Abrir calendario automáticamente al hacer clic en inputs de fecha/hora ---
            document.addEventListener('click', function (e) {
                const target = e.target;
                if (target && (target.type === 'date' || target.type === 'datetime-local')) {
                    if (typeof target.showPicker === 'function') {
                        try {
                            target.showPicker();
                        } catch (err) {}
                    }
                }
            });

            // Inicializar autodismiss de alertas flash (3 segundos)
            initSSTAutoDismissAlerts(document);
        });

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

        // Interceptor Global de Formularios de Eliminación en SST Aprendiz
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (form.dataset.confirmed === 'true') {
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

                if (msg.toLowerCase().includes('inspección') || (form.action || '').includes('inspecciones')) {
                    title = '¿Eliminar Inspección?';
                } else if (msg.toLowerCase().includes('reporte') || (form.action || '').includes('eventos')) {
                    title = '¿Eliminar Reporte?';
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

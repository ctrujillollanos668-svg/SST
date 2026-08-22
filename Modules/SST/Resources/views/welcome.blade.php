<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seguridad y Salud en el Trabajo (SST) • SENA Empresa ERP</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        sst: {
                            50: '#fff7ed',
                            100: '#ffedd5',
                            200: '#fed7aa',
                            500: '#f97316',
                            600: '#ea580c', /* Naranja refinado, sobrio */
                            700: '#c2410c',
                        },
                        slate: {
                            850: '#151f32',
                            900: '#0f172a',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        display: ['Outfit', 'sans-serif'],
                    },
                    boxShadow: {
                        'subtle-orange': '0 10px 25px -5px rgba(234, 88, 12, 0.25)',
                        'card-clean': '0 4px 20px -2px rgba(15, 23, 42, 0.05), 0 2px 6px -1px rgba(0, 0, 0, 0.02)',
                        'card-hover': '0 12px 30px -4px rgba(15, 23, 42, 0.08), 0 0 0 1px rgba(234, 88, 12, 0.4)',
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans bg-slate-50 text-slate-800 flex flex-col min-h-screen antialiased selection:bg-sst-600 selection:text-white">

    <!-- Top Navigation Bar (Blanco con Bordes Sutiles y Toque Naranja Elegante) -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-200/90 shadow-sm transition-all duration-200">
        
        <!-- Fina línea superior de acento -->
        <div class="h-1 w-full bg-sst-600"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">

                <!-- Logo & Marca -->
                <a href="#inicio" class="flex items-center gap-3.5 group select-none">
                    <!-- Icono Casco SST en contenedor limpio -->
                    <div class="relative w-11 h-11 rounded-xl bg-orange-50 border border-orange-200/80 p-2 flex items-center justify-center shadow-sm group-hover:bg-sst-600 group-hover:border-sst-600 transition-all duration-300">
                        <svg class="w-6 h-6 text-sst-600 group-hover:text-white transition-colors duration-300" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 14.5C4 9.8 7.58 6 12 6C16.42 6 20 9.8 20 14.5H4Z" fill="currentColor" fill-opacity="0.15" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                            <path d="M12 6V11.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M2.5 14.5C2.5 13.95 2.95 13.5 3.5 13.5H20.5C21.05 13.5 21.5 13.95 21.5 14.5C21.5 15.05 21.05 15.5 20.5 15.5H3.5C2.95 15.5 2.5 15.05 2.5 14.5Z" fill="currentColor"/>
                            <path d="M7 15.5L9.5 18.5H14.5L17 15.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" stroke-dasharray="2 2"/>
                        </svg>
                    </div>

                    <!-- Textos Marca -->
                    <div class="flex flex-col">
                        <div class="flex items-center gap-2">
                            <span class="text-slate-900 font-display font-extrabold text-lg tracking-tight leading-none group-hover:text-sst-600 transition">
                                SENA <span class="text-sst-600">EMPRESA</span>
                            </span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700 uppercase tracking-wider leading-none border border-slate-200">
                                ERP
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 font-medium tracking-normal mt-1 leading-none flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-sst-600"></span>
                            Gestión Integral • <span class="text-slate-700 font-semibold">SG-SST</span>
                        </p>
                    </div>
                </a>

                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex items-center space-x-1 lg:space-x-2">
                    <a href="#inicio" class="px-3.5 py-2 rounded-xl text-sm font-semibold text-slate-700 hover:text-sst-600 hover:bg-slate-100 transition">
                        <i class="fas fa-home me-1.5 text-sst-600"></i> Inicio
                    </a>
                    <a href="#pilares" class="px-3.5 py-2 rounded-xl text-sm font-semibold text-slate-700 hover:text-sst-600 hover:bg-slate-100 transition">
                        <i class="fas fa-shield-heart me-1.5 text-sst-600"></i> Ejes SST
                    </a>
                    <a href="#matrices" class="px-3.5 py-2 rounded-xl text-sm font-semibold text-slate-700 hover:text-sst-600 hover:bg-slate-100 transition">
                        <i class="fas fa-clipboard-list me-1.5 text-sst-600"></i> Matrices & Protocolos
                    </a>
                    <a href="#brigadas" class="px-3.5 py-2 rounded-xl text-sm font-semibold text-slate-700 hover:text-sst-600 hover:bg-slate-100 transition">
                        <i class="fas fa-fire-extinguisher me-1.5 text-sst-600"></i> Brigadas & Emergencias
                    </a>
                </nav>

                <!-- Action Area -->
                <div class="flex items-center gap-3">
                    
                    <!-- Botón Volver al ERP General -->
                    <a href="{{ route('home') }}" class="hidden lg:inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold text-slate-600 border border-slate-300 hover:border-slate-400 hover:text-slate-900 hover:bg-slate-100 transition">
                        <i class="fas fa-arrow-left text-slate-500"></i>
                        <span>Portal ERP</span>
                    </a>

                    <!-- Botón Iniciar Sesión (Redirección a Página de Login) -->
                    <a href="{{ route('login', ['redirect' => route('SST.welcome')]) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-white bg-sst-600 hover:bg-sst-700 shadow-subtle-orange transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0">
                        <i class="fas fa-right-to-bracket"></i>
                        <span>Iniciar Sesión</span>
                    </a>

                    <!-- Mobile Menu Trigger -->
                    <button id="mobileMenuBtn" class="md:hidden p-2 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition">
                        <i class="fas fa-bars text-lg"></i>
                    </button>

                </div>
            </div>
        </div>

        <!-- Mobile Drawer -->
        <div id="mobileMenu" class="hidden md:hidden px-4 pt-2 pb-6 bg-white border-t border-slate-200 space-y-2">
            <a href="#inicio" class="block px-3 py-2 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-sst-600">
                <i class="fas fa-home me-2 text-sst-600"></i> Inicio
            </a>
            <a href="#pilares" class="block px-3 py-2 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-sst-600">
                <i class="fas fa-shield-heart me-2 text-sst-600"></i> Ejes SST
            </a>
            <a href="#matrices" class="block px-3 py-2 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-sst-600">
                <i class="fas fa-clipboard-list me-2 text-sst-600"></i> Matrices & Protocolos
            </a>
            <a href="#brigadas" class="block px-3 py-2 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-sst-600">
                <i class="fas fa-fire-extinguisher me-2 text-sst-600"></i> Brigadas & Emergencias
            </a>
            <div class="pt-2 border-t border-slate-100">
                <a href="#" class="block px-3 py-2 rounded-lg text-sm font-semibold text-slate-600 hover:bg-slate-50">
                    <i class="fas fa-arrow-left me-2 text-slate-500"></i> Volver al Portal ERP
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow" id="inicio">

        <!-- Hero Section (Fondo Gris Pizarra Muy Suave con Textos Carbón y Acento Naranja Puntual) -->
        <section class="bg-gradient-to-b from-slate-100/90 via-slate-50 to-white pt-16 pb-20 border-b border-slate-200/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                    
                    <!-- Left Hero Text & CTA -->
                    <div class="lg:col-span-7 text-left space-y-6">
                        
                        <!-- Badge Sobrio -->
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white border border-slate-200 text-slate-700 shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-sst-600"></span>
                            <i class="fas fa-hard-hat text-sst-600 text-xs"></i>
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-600">Sistema de Gestión SG-SST</span>
                        </div>

                        <!-- Main Title -->
                        <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 tracking-tight leading-tight">
                            Seguridad y <br>
                            <span class="text-sst-600">Salud en el Trabajo</span>
                        </h1>

                        <!-- Description -->
                        <p class="text-slate-600 text-base sm:text-lg leading-relaxed max-w-2xl font-normal">
                            Administración integral de riesgos laborales, inspecciones preventivas, dotación técnica de EPP y cumplimiento normativo (Decreto 1072 & Resolución 0312).
                        </p>

                        <!-- Action Buttons -->
                        <div class="pt-2 flex flex-wrap gap-4 items-center">
                            <a href="#matrices" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl text-sm font-bold text-white bg-sst-600 hover:bg-sst-700 shadow-subtle-orange transition-all duration-200 hover:-translate-y-0.5">
                                <i class="fas fa-chart-pie"></i> Explorar Módulo SST
                            </a>
                            <a href="#pilares" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl text-sm font-bold text-slate-700 border border-slate-300 hover:border-slate-400 hover:bg-white transition bg-white shadow-sm">
                                <i class="fas fa-shield-halved text-sst-600"></i> Ejes y Protocolos
                            </a>
                        </div>

                    </div>

                    <!-- Right Hero: Live Metrics Grid (Tarjetas Blancas Limpias con Bordes Sutiles) -->
                    <div class="lg:col-span-5">
                        <div class="grid grid-cols-2 gap-4">
                            
                            <!-- Card 1: Matrices -->
                            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-card-clean hover:shadow-card-hover transition-all duration-300 text-center">
                                <div class="w-11 h-11 mx-auto mb-3 rounded-xl bg-orange-50 text-sst-600 flex items-center justify-center text-lg">
                                    <i class="fas fa-table-list"></i>
                                </div>
                                <div class="text-3xl font-display font-extrabold text-slate-900 mb-1">
                                    18
                                </div>
                                <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    Matrices GTC 45
                                </div>
                            </div>

                            <!-- Card 2: Inspecciones Activas -->
                            <div class="bg-white rounded-2xl p-6 border-2 border-sst-600 shadow-card-clean hover:shadow-card-hover transition-all duration-300 text-center relative">
                                <div class="w-11 h-11 mx-auto mb-3 rounded-xl bg-sst-600 text-white flex items-center justify-center text-lg shadow-sm">
                                    <i class="fas fa-clipboard-check"></i>
                                </div>
                                <div class="text-3xl font-display font-extrabold text-sst-600 mb-1">
                                    42
                                </div>
                                <div class="text-xs font-bold text-slate-700 uppercase tracking-wider">
                                    Inspecciones OK
                                </div>
                            </div>

                            <!-- Card 3: Días Sin Accidentes -->
                            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-card-clean hover:shadow-card-hover transition-all duration-300 text-center">
                                <div class="w-11 h-11 mx-auto mb-3 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg">
                                    <i class="fas fa-triangle-exclamation"></i>
                                </div>
                                <div class="text-3xl font-display font-extrabold text-amber-600 mb-1">
                                    0
                                </div>
                                <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    Días Sin Accidentes
                                </div>
                            </div>

                            <!-- Card 4: Cumplimiento SG-SST -->
                            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-card-clean hover:shadow-card-hover transition-all duration-300 text-center">
                                <div class="w-11 h-11 mx-auto mb-3 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-lg">
                                    <i class="fas fa-shield-virus"></i>
                                </div>
                                <div class="text-3xl font-display font-extrabold text-sky-600 mb-1">
                                    98.4%
                                </div>
                                <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    Estándares 0312
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- Ejes Estratégicos de SST (Pilares) -->
        <section class="py-20 bg-white" id="pilares">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200 uppercase tracking-wider mb-3">
                        <i class="fas fa-layer-group text-sst-600"></i> Ejes Fundamentales
                    </span>
                    <h2 class="font-display text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                        Pilares de la Gestión en SG-SST
                    </h2>
                    <p class="mt-3 text-slate-600 text-sm sm:text-base">
                        Estructura integral para garantizar la prevención de riesgos laborales y la protección de la salud en todos los ambientes de formación y trabajo.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    
                    <!-- Pilar 1: Identificación de Peligros -->
                    <div class="group bg-slate-50/70 rounded-3xl p-8 border border-slate-200 hover:bg-white hover:border-sst-600 shadow-card-clean hover:shadow-card-hover transition-all duration-300 flex flex-col">
                        <div class="w-14 h-14 rounded-2xl bg-orange-50 text-sst-600 flex items-center justify-center text-2xl mb-6 group-hover:bg-sst-600 group-hover:text-white transition-all duration-300 shadow-sm">
                            <i class="fas fa-magnifying-glass-chart"></i>
                        </div>
                        <h3 class="font-display text-lg font-bold text-slate-900 mb-2">Identificación de Peligros</h3>
                        <p class="text-slate-600 text-sm leading-relaxed flex-grow">
                            Diagnóstico y evaluación sistemática de riesgos bajo metodología GTC 45 para controlar factores físicos, químicos, biológicos y biomecánicos.
                        </p>
                    </div>

                    <!-- Pilar 2: Medicina Preventiva -->
                    <div class="group bg-slate-50/70 rounded-3xl p-8 border border-slate-200 hover:bg-white hover:border-sst-600 shadow-card-clean hover:shadow-card-hover transition-all duration-300 flex flex-col">
                        <div class="w-14 h-14 rounded-2xl bg-orange-50 text-sst-600 flex items-center justify-center text-2xl mb-6 group-hover:bg-sst-600 group-hover:text-white transition-all duration-300 shadow-sm">
                            <i class="fas fa-heart-pulse"></i>
                        </div>
                        <h3 class="font-display text-lg font-bold text-slate-900 mb-2">Medicina Preventiva</h3>
                        <p class="text-slate-600 text-sm leading-relaxed flex-grow">
                            Promoción del autocuidado, exámenes ocupacionales, vigilancia epidemiológica y programas de pausas activas para aprendices y personal.
                        </p>
                    </div>

                    <!-- Pilar 3: Higiene y Seguridad -->
                    <div class="group bg-slate-50/70 rounded-3xl p-8 border border-slate-200 hover:bg-white hover:border-sst-600 shadow-card-clean hover:shadow-card-hover transition-all duration-300 flex flex-col">
                        <div class="w-14 h-14 rounded-2xl bg-orange-50 text-sst-600 flex items-center justify-center text-2xl mb-6 group-hover:bg-sst-600 group-hover:text-white transition-all duration-300 shadow-sm">
                            <i class="fas fa-shield-halved"></i>
                        </div>
                        <h3 class="font-display text-lg font-bold text-slate-900 mb-2">Seguridad e Higiene</h3>
                        <p class="text-slate-600 text-sm leading-relaxed flex-grow">
                            Control de uso de EPP, inspecciones locativas periódicas, señalización preventiva y cumplimiento de normas de seguridad industrial.
                        </p>
                    </div>

                    <!-- Pilar 4: Emergencias y Brigadas -->
                    <div class="group bg-slate-50/70 rounded-3xl p-8 border border-slate-200 hover:bg-white hover:border-sst-600 shadow-card-clean hover:shadow-card-hover transition-all duration-300 flex flex-col">
                        <div class="w-14 h-14 rounded-2xl bg-orange-50 text-sst-600 flex items-center justify-center text-2xl mb-6 group-hover:bg-sst-600 group-hover:text-white transition-all duration-300 shadow-sm">
                            <i class="fas fa-fire-extinguisher"></i>
                        </div>
                        <h3 class="font-display text-lg font-bold text-slate-900 mb-2">Plan de Emergencias</h3>
                        <p class="text-slate-600 text-sm leading-relaxed flex-grow">
                            Brigadas entrenadas en primeros auxilios y conatos, rutas de evacuación señalizadas y cronograma anual de simulacros.
                        </p>
                    </div>

                </div>
            </div>
        </section>

        <!-- Matrices y Protocolos Destacados -->
        <section class="py-20 bg-slate-100/70 border-t border-slate-200" id="matrices">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
                    <div>
                        <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold bg-white text-slate-800 border border-slate-200 uppercase tracking-wider mb-2 shadow-sm">
                            <i class="fas fa-file-shield text-sst-600"></i> Protocolos Registrados
                        </span>
                        <h2 class="font-display text-3xl font-extrabold text-slate-900 tracking-tight">
                            Matrices y Lineamientos de Seguridad
                        </h2>
                    </div>
                    <div>
                        <button type="button" data-open-modal="loginModal" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-sst-600 hover:bg-sst-700 shadow-subtle-orange transition cursor-pointer">
                            <i class="fas fa-arrow-right"></i> Ver Todas las Matrices
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Tarjeta 1: Matriz de Riesgos -->
                    <div class="bg-white rounded-2xl p-6 border-l-4 border-l-sst-600 border border-slate-200 shadow-card-clean hover:shadow-card-hover transition-all duration-300 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <span class="px-2.5 py-1 rounded-lg bg-slate-900 text-white font-mono text-xs font-bold">
                                    SST-MAT-01
                                </span>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Activa
                                </span>
                            </div>
                            <h4 class="font-display text-base font-bold text-slate-900 mb-2">
                                Matriz Integral de Identificación de Peligros y Riesgos (GTC 45)
                            </h4>
                            <p class="text-slate-600 text-xs leading-relaxed mb-4">
                                Evaluación sistemática de riesgos físicos, químicos, biológicos y biomecánicos, con jerarquización de controles de ingeniería.
                            </p>
                        </div>
                        <div class="pt-4 border-t border-slate-100 flex flex-wrap items-center justify-between gap-2 text-[11px] text-slate-500 font-medium">
                            <span class="flex items-center gap-1"><i class="fas fa-tag text-sst-600"></i> Matriz GTC 45</span>
                            <span class="flex items-center gap-1"><i class="fas fa-user-check text-slate-700"></i> Coordinación SST</span>
                            <span class="flex items-center gap-1"><i class="fas fa-calendar-alt text-amber-600"></i> Vigencia: 2026</span>
                        </div>
                    </div>

                    <!-- Tarjeta 2: Protocolo EPP -->
                    <div class="bg-white rounded-2xl p-6 border-l-4 border-l-sst-600 border border-slate-200 shadow-card-clean hover:shadow-card-hover transition-all duration-300 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <span class="px-2.5 py-1 rounded-lg bg-slate-900 text-white font-mono text-xs font-bold">
                                    SST-EPP-02
                                </span>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Activa
                                </span>
                            </div>
                            <h4 class="font-display text-base font-bold text-slate-900 mb-2">
                                Estándar Técnico de Dotación y Uso Obligatorio de EPP
                            </h4>
                            <p class="text-slate-600 text-xs leading-relaxed mb-4">
                                Lineamiento de entrega, verificación de fichas técnicas, uso correcto y reposición de Elementos de Protección Personal.
                            </p>
                        </div>
                        <div class="pt-4 border-t border-slate-100 flex flex-wrap items-center justify-between gap-2 text-[11px] text-slate-500 font-medium">
                            <span class="flex items-center gap-1"><i class="fas fa-tag text-sst-600"></i> Estándar EPP</span>
                            <span class="flex items-center gap-1"><i class="fas fa-user-check text-slate-700"></i> Instructor Líder SST</span>
                            <span class="flex items-center gap-1"><i class="fas fa-calendar-alt text-amber-600"></i> Vigencia: 2026</span>
                        </div>
                    </div>

                    <!-- Tarjeta 3: Investigación de Accidentes -->
                    <div class="bg-white rounded-2xl p-6 border-l-4 border-l-amber-500 border border-slate-200 shadow-card-clean hover:shadow-card-hover transition-all duration-300 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <span class="px-2.5 py-1 rounded-lg bg-slate-900 text-white font-mono text-xs font-bold">
                                    SST-INV-03
                                </span>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    En Revisión
                                </span>
                            </div>
                            <h4 class="font-display text-base font-bold text-slate-900 mb-2">
                                Protocolo de Investigación de Accidentes e Incidentes Laborales
                            </h4>
                            <p class="text-slate-600 text-xs leading-relaxed mb-4">
                                Procedimiento para la determinación de causas básicas e inmediatas (árbol de causas) y reporte normativo ante ARL.
                            </p>
                        </div>
                        <div class="pt-4 border-t border-slate-100 flex flex-wrap items-center justify-between gap-2 text-[11px] text-slate-500 font-medium">
                            <span class="flex items-center gap-1"><i class="fas fa-tag text-sst-600"></i> Investigación AT/IT</span>
                            <span class="flex items-center gap-1"><i class="fas fa-user-check text-slate-700"></i> Comité COPASST</span>
                            <span class="flex items-center gap-1"><i class="fas fa-calendar-alt text-amber-600"></i> Vigencia: 2026</span>
                        </div>
                    </div>

                    <!-- Tarjeta 4: Plan de Emergencias -->
                    <div class="bg-white rounded-2xl p-6 border-l-4 border-l-sst-600 border border-slate-200 shadow-card-clean hover:shadow-card-hover transition-all duration-300 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <span class="px-2.5 py-1 rounded-lg bg-slate-900 text-white font-mono text-xs font-bold">
                                    SST-EMERG-04
                                </span>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Activa
                                </span>
                            </div>
                            <h4 class="font-display text-base font-bold text-slate-900 mb-2">
                                Plan de Prevención, Preparación y Respuesta ante Emergencias
                            </h4>
                            <p class="text-slate-600 text-xs leading-relaxed mb-4">
                                Procedimientos de evacuación, inspección técnica de extintores, botiquines y organización de brigadas integrales.
                            </p>
                        </div>
                        <div class="pt-4 border-t border-slate-100 flex flex-wrap items-center justify-between gap-2 text-[11px] text-slate-500 font-medium">
                            <span class="flex items-center gap-1"><i class="fas fa-tag text-sst-600"></i> Plan Emergencias</span>
                            <span class="flex items-center gap-1"><i class="fas fa-user-check text-slate-700"></i> Brigada General</span>
                            <span class="flex items-center gap-1"><i class="fas fa-calendar-alt text-amber-600"></i> Vigencia: 2026</span>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- CTA Call to Action Banner (Fondo Carbón Elegante con Botón Naranja Sobrio) -->
        <section class="py-16 bg-white" id="brigadas">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="relative overflow-hidden rounded-3xl bg-slate-900 p-8 sm:p-12 lg:p-14 text-white shadow-xl">
                    
                    <!-- Decorative subtle warm circle -->
                    <div class="absolute -right-16 -bottom-16 w-72 h-72 bg-sst-600/15 rounded-full blur-3xl pointer-events-none"></div>

                    <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                        <div class="lg:col-span-8 space-y-4">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-white/10 text-slate-200 border border-white/15 backdrop-blur-sm">
                                <i class="fas fa-shield-halved text-sst-500"></i> Control de Acceso y Gestión Operativa
                            </span>
                            <h3 class="font-display text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                                Portal de Seguridad y Salud en el Trabajo
                            </h3>
                            <p class="text-slate-300 text-sm sm:text-base leading-relaxed max-w-2xl font-normal">
                                Los administradores, instructores técnicos, brigadistas y miembros del COPASST pueden gestionar inspecciones, reportar actos inseguros y actualizar las matrices normativas.
                            </p>
                        </div>
                        
                        <div class="lg:col-span-4 lg:text-right">
                            <button type="button" data-open-modal="loginModal" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl text-sm font-bold text-white bg-sst-600 hover:bg-sst-700 shadow-subtle-orange transition-all duration-200 hover:-translate-y-0.5 cursor-pointer">
                                <i class="fas fa-gauge-high"></i> Entrar al Dashboard
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 text-xs py-8 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-center sm:text-left">
                    <p class="text-slate-400 font-medium">
                        SENA Empresa &copy; 2026 • Centro de Formación Agroindustrial <strong class="text-slate-200">"La Angostura"</strong> (Campoalegre - Huila)
                    </p>
                </div>
                <div>
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-800 border border-slate-700 text-[11px] text-slate-300 font-medium">
                        <span class="w-1.5 h-1.5 rounded-full bg-sst-600"></span>
                        Módulo de SG-SST • Procesos de Apoyo ERP
                    </span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts de Interacción -->
    <script>
        // Drawer móvil
        document.getElementById('mobileMenuBtn')?.addEventListener('click', function() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        });
    </script>
</body>
</html>

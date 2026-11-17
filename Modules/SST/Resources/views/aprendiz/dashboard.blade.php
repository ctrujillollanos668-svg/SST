@extends('sst::components.layouts.aprendiz')

@section('title', 'Portal Aprendiz • SST')

@section('content')
    <div class="space-y-6">
        <!-- Header Banner (Estilo Dirección Ejecutivo) -->
        <div class="bg-gradient-to-r from-[#00131E] via-[#001A29] to-[#002D44] border-l-4 border-l-[#39A900] rounded-3xl p-6 sm:p-8 text-white shadow-lg relative overflow-hidden">
            <div class="relative z-10 max-w-2xl">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 border border-white/15 text-xs font-bold text-[#62E31D] mb-3">
                    <span class="w-2 h-2 rounded-full bg-[#62E31D] animate-pulse"></span>
                    Ambiente Aprendiz Seguro • SG-SST
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight mb-2">¡Bienvenido(a), <span class="text-[#62E31D]">{{ Auth::user()->person->first_name ?? Auth::user()->nickname ?? 'Aprendiz' }}</span>!</h1>
                <p class="text-white/80 text-sm leading-relaxed">
                    Plataforma de Seguridad y Salud en el Trabajo. Accede al control de asistencia, actividades SST, consulta de riesgos y protocolos de emergencia de SENA Empresa.
                </p>
            </div>
            <div class="absolute right-0 bottom-0 opacity-10 transform translate-x-10 translate-y-10 pointer-events-none text-[#62E31D]">
                <i class="fa-solid fa-shield-halved text-[220px]"></i>
            </div>
        </div>

        <!-- Módulos / Accesos Rápidos Grid -->
        <div>
            <h2 class="text-lg font-bold text-[#001A29] mb-4 flex items-center gap-2">
                <i class="fa-solid fa-layer-group text-[#39A900]"></i>
                Módulos y Servicios
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                
                <!-- Card 1: Asistencia -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs hover:shadow-md transition-all hover:border-[#39A900]/40 group flex flex-col justify-between border-t-2 border-t-[#39A900]">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-[#39A900] flex items-center justify-center mb-4 text-xl group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-clipboard-user"></i>
                        </div>
                        <h3 class="font-bold text-slate-800 text-base mb-1 group-hover:text-[#39A900] transition">Asistencia</h3>
                        <p class="text-slate-500 text-xs leading-relaxed">
                            Registra tu asistencia laboral diaria y consulta tu historial de turnos y actividades.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-[11px] font-semibold text-[#39A900]">Módulo Activo</span>
                        <a href="{{ route('SST.aprendiz.asistencia.index') }}" class="text-xs font-bold text-[#39A900] hover:text-[#2d8500] flex items-center gap-1">
                            Ingresar <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>

                <!-- Card 2: Actividades SST -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs hover:shadow-md transition-all hover:border-[#39A900]/40 group flex flex-col justify-between border-t-2 border-t-[#39A900]">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-[#39A900] flex items-center justify-center mb-4 text-xl group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-person-chalkboard"></i>
                        </div>
                        <h3 class="font-bold text-slate-800 text-base mb-1 group-hover:text-[#39A900] transition">Actividades SST</h3>
                        <p class="text-slate-500 text-xs leading-relaxed">
                            Revisa las jornadas de pausas activas, charlas y actividades programadas para tu bienestar.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-[11px] font-semibold text-[#39A900]">Bienestar y Salud</span>
                        <a href="{{ route('SST.aprendiz.actividades_sst.pausas_activas.index') }}" class="text-xs font-bold text-[#39A900] hover:text-[#2d8500] flex items-center gap-1">
                            Ver próximas <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>

                <!-- Card 3: Emergencias -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs hover:shadow-md transition-all hover:border-[#39A900]/40 group flex flex-col justify-between border-t-2 border-t-rose-500">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center mb-4 text-xl group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <h3 class="font-bold text-slate-800 text-base mb-1 group-hover:text-rose-600 transition">Emergencias</h3>
                        <p class="text-slate-500 text-xs leading-relaxed">
                            Directorio y líneas de atención inmediata para primeros auxilios y brigada del centro.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-[11px] font-semibold text-rose-600">Líneas Rápidas</span>
                        <a href="{{ route('SST.aprendiz.emergencias.contactos') }}" class="text-xs font-bold text-rose-600 hover:text-rose-700 flex items-center gap-1">
                            Contactos <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>

                <!-- Card 4: Inspecciones SST -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs hover:shadow-md transition-all hover:border-[#39A900]/40 group flex flex-col justify-between border-t-2 border-t-[#001A29]">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-[#001A29]/10 text-[#001A29] flex items-center justify-center mb-4 text-xl group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-clipboard-check"></i>
                        </div>
                        <h3 class="font-bold text-slate-800 text-base mb-1 group-hover:text-[#001A29] transition">Inspecciones SST</h3>
                        <p class="text-slate-500 text-xs leading-relaxed">
                            Reporta actos y condiciones inseguras en ambientes de formación y consulta las que tienen seguimiento.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                        <a href="{{ route('SST.aprendiz.inspecciones.seguimiento') }}" class="text-[11px] font-bold text-slate-600 hover:text-[#39A900] flex items-center gap-1">
                            <i class="fa-regular fa-clock text-[10px]"></i> Seguimiento
                        </a>
                        <a href="{{ route('SST.aprendiz.inspecciones.registrar') }}" class="text-xs font-bold text-[#39A900] hover:text-[#2d8500] flex items-center gap-1">
                            Registrar <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>

                <!-- Card 5: Tipos de Eventos -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs hover:shadow-md transition-all hover:border-[#39A900]/40 group flex flex-col justify-between border-t-2 border-t-[#39A900]">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-[#39A900] flex items-center justify-center mb-4 text-xl group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-list-check"></i>
                        </div>
                        <h3 class="font-bold text-slate-800 text-base mb-1 group-hover:text-[#39A900] transition">Tipos de Eventos</h3>
                        <p class="text-slate-500 text-xs leading-relaxed">
                            Guía informativa sobre incidentes, actos inseguros, lesiones y protocolos de actuación.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-[11px] font-semibold text-[#39A900]">Protocolos</span>
                        <a href="{{ route('SST.aprendiz.tipos_eventos.accidentes.index') }}" class="text-xs font-bold text-[#39A900] hover:text-[#2d8500] flex items-center gap-1">
                            Ver detalles <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>

                <!-- Card 6: Definiciones -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs hover:shadow-md transition-all hover:border-[#39A900]/40 group flex flex-col justify-between border-t-2 border-t-[#001A29]">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-[#001A29]/10 text-[#001A29] flex items-center justify-center mb-4 text-xl group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-book"></i>
                        </div>
                        <h3 class="font-bold text-slate-800 text-base mb-1 group-hover:text-[#001A29] transition">Glosario y Definiciones</h3>
                        <p class="text-slate-500 text-xs leading-relaxed">
                            Términos normativos y conceptos clave del Sistema de Gestión de Seguridad y Salud en el Trabajo.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-[11px] font-semibold text-[#001A29]">Normativa</span>
                        <a href="{{ route('SST.aprendiz.definiciones') }}" class="text-xs font-bold text-[#001A29] hover:text-[#39A900] flex items-center gap-1">
                            Explorar <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

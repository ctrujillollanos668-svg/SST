@extends('sst::components.layouts.admin')

@section('title', 'Panel Administrativo • SST')

@section('content')
    <div class="bg-gradient-to-r from-[#00131E] via-[#001A29] to-[#002D44] p-6 sm:p-8 rounded-2xl border-l-4 border-l-[#39A900] text-white shadow-lg flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-white/10 text-[#62E31D] flex items-center justify-center border border-white/20 shadow-inner shrink-0">
                <i class="fa-solid fa-shield-halved text-2xl"></i>
            </div>
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/15 text-xs font-bold text-[#62E31D] mb-2">
                    <span class="w-2 h-2 rounded-full bg-[#62E31D] animate-pulse"></span> Sistema de Gestión SG-SST
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">¡Bienvenido al Panel de Administración <span class="text-[#62E31D]">SST</span>!</h1>
                <p class="text-white/80 text-sm font-normal mt-1">Supervisión, control operativo y gestión integral de seguridad y salud en el trabajo para SENA Empresa.</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('SST.admin.cronograma.calendario') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#39A900] hover:bg-[#2d8500] text-white text-xs font-bold shadow-md shadow-[#39A900]/30 transition border border-[#62E31D]/40">
                <i class="fa-solid fa-calendar-days"></i> Ver Cronograma
            </a>
        </div>
    </div>
@endsection

@extends('sst::components.layouts.aprendiz')

@section('title', 'Contactos de Emergencia • Aprendiz SST')

@section('content')

<div class="max-w-5xl mx-auto space-y-6">

    {{-- Encabezado --}}
    <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-5">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center border border-orange-200/80 shadow-xs text-2xl shrink-0">
                <i class="fa-solid fa-phone-volume"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Contactos de Emergencia</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-orange-100/70 text-orange-700 border border-orange-200/60">
                        SST
                    </span>
                </div>
                <p class="text-sm text-slate-500 font-medium mt-0.5">
                    Números y personas clave ante una emergencia en tu lugar de formación.
                </p>
            </div>
        </div>

        {{-- Contador de contactos --}}
        <div class="flex items-center gap-2 bg-orange-50 border border-orange-200/60 rounded-2xl px-4 py-3">
            <i class="fa-solid fa-address-book text-orange-500 text-lg"></i>
            <div>
                <p class="text-[11px] font-semibold text-orange-500 uppercase tracking-wide">Total Contactos</p>
                <p class="text-xl font-extrabold text-orange-700 leading-tight">{{ $contactos->count() }}</p>
            </div>
        </div>
    </div>

    {{-- Lista de contactos --}}
    @if($contactos->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($contactos as $contacto)
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md hover:border-orange-300 transition-all p-5 flex flex-col gap-3">

                    {{-- Icono y nombre --}}
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center border border-orange-200/60 text-lg shrink-0">
                            <i class="fa-solid fa-user-shield"></i>
                        </div>
                        <div class="min-w-0">
                            <h2 class="text-sm font-extrabold text-slate-800 truncate">{{ $contacto->nombre }}</h2>
                            @if($contacto->descripcion)
                                <p class="text-[11px] text-slate-400 font-medium truncate">{{ $contacto->descripcion }}</p>
                            @endif
                        </div>
                    </div>

                    <hr class="border-slate-100">

                    {{-- Teléfono --}}
                    <a href="tel:{{ $contacto->telefono }}"
                       class="flex items-center gap-2.5 group cursor-pointer">
                        <div class="w-9 h-9 rounded-xl bg-green-50 text-green-600 flex items-center justify-center border border-green-200/60 text-sm shrink-0 group-hover:bg-green-100 transition">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wide">Teléfono</p>
                            <p class="text-sm font-bold text-green-700 group-hover:text-green-800 transition">{{ $contacto->telefono }}</p>
                        </div>
                        <div class="ml-auto opacity-0 group-hover:opacity-100 transition text-green-500">
                            <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                        </div>
                    </a>

                </div>
            @endforeach
        </div>

    @else
        {{-- Estado vacío --}}
        <div class="bg-white rounded-2xl border border-dashed border-slate-200 p-12 text-center">
            <div class="w-16 h-16 mx-auto rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center text-3xl mb-4">
                <i class="fa-regular fa-address-book"></i>
            </div>
            <h3 class="text-base font-bold text-slate-700">Sin contactos de emergencia registrados</h3>
            <p class="text-sm text-slate-400 mt-1">El administrador aún no ha registrado contactos de emergencia.</p>
        </div>
    @endif

</div>

@endsection

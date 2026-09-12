<?php

use Illuminate\Support\Facades\Route;
use Modules\SST\Http\Controllers\SSTController;
use Modules\SST\Http\Controllers\Admin\TiposEventos\AccidentesController;
use Modules\SST\Http\Controllers\Admin\TiposEventos\IncidentesController;
use Modules\SST\Http\Controllers\Admin\TiposEventos\RiegosController;
use Modules\SST\Http\Controllers\Admin\TiposEventos\ActosInsegurosController;
use Modules\SST\Http\Controllers\Admin\TiposEventos\LesionesController;
use Modules\SST\Http\Controllers\Admin\TiposEventos\TiposEmergenciasController;

Route::prefix('Sst')->name('SST.')->group(function () {
    // 1. Landing pública / Bienvenida
    Route::get('/', [SSTController::class, 'welcome'])->name('welcome');
    Route::get('/index', [SSTController::class, 'welcome'])->name('index');

    // 2. Redirección Inteligente
    Route::get('/dashboard', [SSTController::class, 'dashboard'])->name('dashboard');

    // 3. Ruta para el Administrador
    Route::get('/admin/dashboard', [SSTController::class, 'adminDashboard'])->name('admin.dashboard');

    // Rutas de Administración - Tipos de Eventos
    Route::prefix('admin/tipos-eventos')->name('admin.tipos_eventos.')->group(function () {
        Route::resource('accidentes', AccidentesController::class)->names('accidentes');
        Route::resource('incidentes', IncidentesController::class)->names('incidentes');
        Route::resource('riesgos', RiegosController::class)->names('riesgos');
        Route::resource('actos-inseguros', ActosInsegurosController::class)->names('actos_inseguros');
        Route::resource('lesiones', LesionesController::class)->names('lesiones');
        Route::resource('tipos-emergencias', TiposEmergenciasController::class)->names('tipos_emergencias');
    });

    // 4. Ruta para el Aprendiz SST
    Route::get('/aprendiz/dashboard', [SSTController::class, 'aprendizDashboard'])->name('aprendiz.dashboard');
    // Compatibilidad
    Route::get('/funcionario/dashboard', [SSTController::class, 'aprendizDashboard'])->name('funcionario.dashboard');
});

<?php

use Illuminate\Support\Facades\Route;
use Modules\SST\Http\Controllers\SSTController;
use Modules\SST\Http\Controllers\Admin\TiposEventos\AccidentesController;
use Modules\SST\Http\Controllers\Admin\TiposEventos\IncidentesController;
use Modules\SST\Http\Controllers\Admin\TiposEventos\RiegosController;
use Modules\SST\Http\Controllers\Admin\TiposEventos\ActosInsegurosController;
use Modules\SST\Http\Controllers\Admin\TiposEventos\LesionesController;
use Modules\SST\Http\Controllers\Admin\TiposEventos\TiposEmergenciasController;
use Modules\SST\Http\Controllers\Admin\Inspecciones\InspeccionesController;
use Modules\SST\Http\Controllers\Admin\Informacion_basica\LugarInformacionController;
use Modules\SST\Http\Controllers\Admin\Informacion_basica\RespuestaEventoController;
use Modules\SST\Http\Controllers\Admin\Informacion_basica\ContactoEmergenciaController;

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

    // Rutas de Administración - Información Básica
    Route::prefix('admin/informacion-basica')->name('admin.informacion_basica.')->group(function () {
        Route::resource('respuesta-eventos', RespuestaEventoController::class)->names('respuesta_eventos');
        Route::resource('lugar-informacion', LugarInformacionController::class)->names('lugar_informacion');
        Route::resource('contacto-emergencia', ContactoEmergenciaController::class)->names('contacto_emergencia');
    });

    // Rutas de Administración - Inspecciones
    Route::prefix('admin/inspecciones')->name('admin.inspecciones.')->group(function () {
        Route::get('/realizar', [InspeccionesController::class, 'realizar'])->name('realizar');
        Route::get('/historial', [InspeccionesController::class, 'historial'])->name('historial');
    });

    // Rutas de Administración - Cronograma SST
    Route::prefix('admin/cronograma')->name('admin.cronograma.')->group(function () {
        Route::get('/calendario', [\Modules\SST\Http\Controllers\Admin\Cronograma\CalendarioController::class, 'index'])->name('calendario');
    });

    // 4. Ruta para el Aprendiz SST
    Route::get('/aprendiz/dashboard', [SSTController::class, 'aprendizDashboard'])->name('aprendiz.dashboard');
    // Compatibilidad
    Route::get('/funcionario/dashboard', [SSTController::class, 'aprendizDashboard'])->name('funcionario.dashboard');
});

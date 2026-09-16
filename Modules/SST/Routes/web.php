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
use Modules\SST\Http\Controllers\Admin\AdminController;
use Modules\SST\Http\Controllers\Aprendiz\AprendizController;
use Modules\SST\Http\Controllers\Aprendiz\Definiciones\DefinicionesController as AprendizDefinicionesController;

Route::prefix('Sst')->name('SST.')->group(function () {
    // 1. Landing pública / Bienvenida
    Route::get('/', [SSTController::class, 'welcome'])->name('welcome');
    Route::get('/index', [SSTController::class, 'welcome'])->name('index');

    // 2. Redirección Inteligente
    Route::get('/dashboard', [SSTController::class, 'dashboard'])->name('dashboard');

    // 3. Ruta para el Administrador
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

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
        Route::post('/realizar', [InspeccionesController::class, 'store'])->name('store');
        Route::get('/historial', [InspeccionesController::class, 'historial'])->name('historial');
    });

    // Rutas de Administración - Cronograma SST
    Route::prefix('admin/cronograma')->name('admin.cronograma.')->group(function () {
        Route::get('/calendario', [\Modules\SST\Http\Controllers\Admin\Cronograma\CalendarioController::class, 'index'])->name('calendario');
        Route::post('/calendario', [\Modules\SST\Http\Controllers\Admin\Cronograma\CalendarioController::class, 'store'])->name('calendario.store');
        Route::post('/calendario/tipo-actividad', [\Modules\SST\Http\Controllers\Admin\Cronograma\CalendarioController::class, 'storeTipoActividad'])->name('calendario.tipo_actividad.store');
        Route::delete('/calendario/tipo-actividad/{id}', [\Modules\SST\Http\Controllers\Admin\Cronograma\CalendarioController::class, 'destroyTipoActividad'])->name('calendario.tipo_actividad.destroy');
        Route::put('/calendario/{id}', [\Modules\SST\Http\Controllers\Admin\Cronograma\CalendarioController::class, 'update'])->name('calendario.update');
        Route::delete('/calendario/{id}', [\Modules\SST\Http\Controllers\Admin\Cronograma\CalendarioController::class, 'destroy'])->name('calendario.destroy');
    });

    // Rutas de Administración - Pausas Activas
    Route::prefix('admin/pausas-activas')->name('admin.pausas_activas.')->group(function () {
        Route::get('/programadas', [\Modules\SST\Http\Controllers\Admin\PausasActivas\PausasActivasController::class, 'index'])->name('index');
        Route::post('/programadas', [\Modules\SST\Http\Controllers\Admin\PausasActivas\PausasActivasController::class, 'store'])->name('store');
        Route::post('/asistencia', [\Modules\SST\Http\Controllers\Admin\PausasActivas\PausasActivasController::class, 'storeAsistencia'])->name('asistencia');
    });

    // Rutas de Administración - Indicadores SST
    Route::prefix('admin/indicadores')->name('admin.indicadores.')->group(function () {
        Route::get('/ver-indicadores', [\Modules\SST\Http\Controllers\Admin\Indicadores\IndicadoresController::class, 'index'])->name('index');
        Route::post('/store', [\Modules\SST\Http\Controllers\Admin\Indicadores\IndicadoresController::class, 'store'])->name('store');
        Route::put('/update/{id}', [\Modules\SST\Http\Controllers\Admin\Indicadores\IndicadoresController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [\Modules\SST\Http\Controllers\Admin\Indicadores\IndicadoresController::class, 'destroy'])->name('destroy');
    });

    // Rutas de Administración - Gestión de Usuarios SST
    Route::prefix('admin/usuarios')->name('admin.usuarios.')->group(function () {
        Route::get('/registrar-usuario', [\Modules\SST\Http\Controllers\Admin\Usuarios\UsuarioController::class, 'index'])->name('index');
        Route::get('/search', [\Modules\SST\Http\Controllers\Admin\Usuarios\UsuarioController::class, 'search'])->name('search');
        Route::post('/assign-role', [\Modules\SST\Http\Controllers\Admin\Usuarios\UsuarioController::class, 'assignRole'])->name('assign_role');
        Route::delete('/remove-role/{user}/{role}', [\Modules\SST\Http\Controllers\Admin\Usuarios\UsuarioController::class, 'removeRole'])->name('remove_role');
    });

    // 4. Rutas para el Aprendiz SST
    Route::prefix('aprendiz')->name('aprendiz.')->group(function () {
        Route::get('/dashboard', [AprendizController::class, 'dashboard'])->name('dashboard');
        Route::get('/definiciones', [AprendizDefinicionesController::class, 'index'])->name('definiciones');
    });

    // Compatibilidad
    Route::get('/funcionario/dashboard', [AprendizController::class, 'dashboard'])->name('funcionario.dashboard');
});

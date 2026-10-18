<?php

use Illuminate\Support\Facades\Route;
use Modules\SST\Http\Controllers\SSTController;
use Modules\SST\Http\Controllers\Admin\TiposEventos\AccidentesController;
use Modules\SST\Http\Controllers\Admin\TiposEventos\IncidentesController;
use Modules\SST\Http\Controllers\Admin\TiposEventos\RiegosController;
use Modules\SST\Http\Controllers\Admin\TiposEventos\ActosInsegurosController;
use Modules\SST\Http\Controllers\Admin\TiposEventos\CondicionesInsegurasController;
use Modules\SST\Http\Controllers\Admin\TiposEventos\LesionesController;
use Modules\SST\Http\Controllers\Admin\TiposEventos\TiposEmergenciasController;
use Modules\SST\Http\Controllers\Admin\Inspecciones\InspeccionesController;
use Modules\SST\Http\Controllers\Admin\Informacion_basica\LugarInformacionController;
use Modules\SST\Http\Controllers\Admin\Informacion_basica\RespuestaEventoController;
use Modules\SST\Http\Controllers\Admin\Informacion_basica\ContactoEmergenciaController;
use Modules\SST\Http\Controllers\Admin\AdminController;
use Modules\SST\Http\Controllers\Admin\Definiciones\DefinicionesController as AdminDefinicionesController;
use Modules\SST\Http\Controllers\Aprendiz\AprendizController;
use Modules\SST\Http\Controllers\Aprendiz\Definiciones\DefinicionesController as AprendizDefinicionesController;
use Modules\SST\Http\Controllers\Aprendiz\TiposEventos\AccidentesController as AprendizAccidentesController;
use Modules\SST\Http\Controllers\Aprendiz\TiposEventos\IncidentesController as AprendizIncidentesController;
use Modules\SST\Http\Controllers\Aprendiz\TiposEventos\RiesgosController as AprendizRiesgosController;
use Modules\SST\Http\Controllers\Aprendiz\TiposEventos\ActosInsegurosController as AprendizActosInsegurosController;
use Modules\SST\Http\Controllers\Aprendiz\TiposEventos\CondicionesInsegurasController as AprendizCondicionesInsegurasController;
use Modules\SST\Http\Controllers\Aprendiz\TiposEventos\LesionesController as AprendizLesionesController;
use Modules\SST\Http\Controllers\Aprendiz\TiposEventos\TiposEmergenciasController as AprendizTiposEmergenciasController;
use Modules\SST\Http\Controllers\Aprendiz\Emergencias\ContactoEmergenciaController as AprendizContactoEmergenciaController;
use Modules\SST\Http\Controllers\Aprendiz\Inspecciones\InspeccionesController as AprendizInspeccionesController;

Route::prefix('Sst')->name('SST.')->group(function () {
    // 1. Landing pública / Bienvenida
    Route::get('/', [SSTController::class, 'welcome'])->name('welcome');
    Route::get('/index', [SSTController::class, 'welcome'])->name('index');

    // 2. Redirección Inteligente
    Route::get('/dashboard', [SSTController::class, 'dashboard'])->name('dashboard');

    // 3. Ruta para el Administrador
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Rutas de Administración - Definiciones SST (Glosario para Aprendices)
    Route::prefix('admin/definiciones')->name('admin.definiciones.')->group(function () {
        Route::get('/accidentes', [AdminDefinicionesController::class, 'accidentes'])->name('accidentes');
        Route::get('/incidentes', [AdminDefinicionesController::class, 'incidentes'])->name('incidentes');
        Route::get('/riesgos', [AdminDefinicionesController::class, 'riesgos'])->name('riesgos');
        Route::get('/actos-inseguros', [AdminDefinicionesController::class, 'actosInseguros'])->name('actos_inseguros');
        Route::get('/condiciones-inseguras', [AdminDefinicionesController::class, 'condicionesInseguras'])->name('condiciones_inseguras');
        Route::get('/lesiones', [AdminDefinicionesController::class, 'lesiones'])->name('lesiones');
        Route::get('/tipos-emergencias', [AdminDefinicionesController::class, 'tiposEmergencias'])->name('tipos_emergencias');
        Route::post('/store', [AdminDefinicionesController::class, 'store'])->name('store');
        Route::put('/update/{id}', [AdminDefinicionesController::class, 'update'])->name('update');
        Route::patch('/toggle-estado/{id}', [AdminDefinicionesController::class, 'toggleEstado'])->name('toggle_estado');
        Route::delete('/destroy/{id}', [AdminDefinicionesController::class, 'destroy'])->name('destroy');
    });

    // Rutas de Administración - Tipos de Eventos
    Route::prefix('admin/tipos-eventos')->name('admin.tipos_eventos.')->group(function () {
        Route::resource('accidentes', AccidentesController::class)->names('accidentes');
        Route::resource('incidentes', IncidentesController::class)->names('incidentes');
        Route::resource('riesgos', RiegosController::class)->names('riesgos');
        Route::resource('actos-inseguros', ActosInsegurosController::class)->names('actos_inseguros');
        Route::resource('condiciones-inseguras', CondicionesInsegurasController::class)->names('condiciones_inseguras');
        Route::resource('lesiones', LesionesController::class)->names('lesiones');
        Route::resource('tipos-emergencias', TiposEmergenciasController::class)->names('tipos_emergencias');
    });

    // Rutas de Administración - Información Básica
    Route::prefix('admin/informacion-basica')->name('admin.informacion_basica.')->group(function () {
        Route::post('respuesta-eventos/{id}/atender', [RespuestaEventoController::class, 'atender'])->name('respuesta_eventos.atender');
        Route::resource('respuesta-eventos', RespuestaEventoController::class)->names('respuesta_eventos');
        Route::resource('lugar-informacion', LugarInformacionController::class)->names('lugar_informacion');
        Route::resource('contacto-emergencia', ContactoEmergenciaController::class)->names('contacto_emergencia');
    });

    // Rutas de Administración - Inspecciones
    Route::prefix('admin/inspecciones')->name('admin.inspecciones.')->group(function () {
        Route::get('/historial', [InspeccionesController::class, 'historial'])->name('historial');
        Route::post('/historial/{id}/atender', [InspeccionesController::class, 'atender'])->name('atender');
        Route::redirect('/realizar', '/Sst/admin/inspecciones/historial')->name('realizar');
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

    // Rutas de Administración - Actividades SST
    Route::prefix('admin/pausas-activas')->name('admin.pausas_activas.')->group(function () {
        Route::get('/programadas', [\Modules\SST\Http\Controllers\Admin\ActividadesSST\PausasActivasController::class, 'index'])->name('index');
        Route::post('/programadas', [\Modules\SST\Http\Controllers\Admin\ActividadesSST\PausasActivasController::class, 'store'])->name('store');
        Route::put('/{id}', [\Modules\SST\Http\Controllers\Admin\ActividadesSST\PausasActivasController::class, 'update'])->name('update');
        Route::delete('/{id}', [\Modules\SST\Http\Controllers\Admin\ActividadesSST\PausasActivasController::class, 'destroy'])->name('destroy');
        Route::post('/asistencia', [\Modules\SST\Http\Controllers\Admin\ActividadesSST\PausasActivasController::class, 'storeAsistencia'])->name('asistencia');
    });

    Route::prefix('admin/charlas')->name('admin.charlas.')->group(function () {
        Route::get('/', [\Modules\SST\Http\Controllers\Admin\ActividadesSST\CharlasController::class, 'index'])->name('index');
        Route::post('/', [\Modules\SST\Http\Controllers\Admin\ActividadesSST\CharlasController::class, 'store'])->name('store');
        Route::put('/{id}', [\Modules\SST\Http\Controllers\Admin\ActividadesSST\CharlasController::class, 'update'])->name('update');
        Route::delete('/{id}', [\Modules\SST\Http\Controllers\Admin\ActividadesSST\CharlasController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('admin/sensibilizacion')->name('admin.sensibilizacion.')->group(function () {
        Route::get('/', [\Modules\SST\Http\Controllers\Admin\ActividadesSST\SensibilizacionController::class, 'index'])->name('index');
        Route::post('/', [\Modules\SST\Http\Controllers\Admin\ActividadesSST\SensibilizacionController::class, 'store'])->name('store');
        Route::put('/{id}', [\Modules\SST\Http\Controllers\Admin\ActividadesSST\SensibilizacionController::class, 'update'])->name('update');
        Route::delete('/{id}', [\Modules\SST\Http\Controllers\Admin\ActividadesSST\SensibilizacionController::class, 'destroy'])->name('destroy');
    });

    // Rutas de Administración - Actividades SST (Grupo Unificado)
    Route::prefix('admin/actividades-sst')->name('admin.actividades_sst.')->group(function () {
        Route::get('/pausas-activas', [\Modules\SST\Http\Controllers\Admin\ActividadesSST\PausasActivasController::class, 'index'])->name('pausas_activas.index');
        Route::get('/charlas', [\Modules\SST\Http\Controllers\Admin\ActividadesSST\CharlasController::class, 'index'])->name('charlas.index');
        Route::get('/sensibilizacion', [\Modules\SST\Http\Controllers\Admin\ActividadesSST\SensibilizacionController::class, 'index'])->name('sensibilizacion.index');
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
        Route::put('/update-credentials/{id}', [\Modules\SST\Http\Controllers\Admin\Usuarios\UsuarioController::class, 'updateCredentials'])->name('update_credentials');
    });

    // 4. Rutas para el Aprendiz SST
    Route::prefix('aprendiz')->name('aprendiz.')->group(function () {
        Route::get('/dashboard', [AprendizController::class, 'dashboard'])->name('dashboard');
        Route::get('/definiciones', [AprendizDefinicionesController::class, 'index'])->name('definiciones');
        Route::put('/cambiar-clave', [\Modules\SST\Http\Controllers\Admin\Usuarios\UsuarioController::class, 'updateMyPassword'])->name('cambiar_clave');

        // Tipos de Eventos para Aprendiz
        Route::prefix('tipos-eventos')->name('tipos_eventos.')->group(function () {
            Route::get('/accidentes', [AprendizAccidentesController::class, 'index'])->name('accidentes.index');
            Route::post('/accidentes', [AprendizAccidentesController::class, 'store'])->name('accidentes.store');
            Route::put('/accidentes/{id}', [AprendizAccidentesController::class, 'update'])->name('accidentes.update');
            Route::delete('/accidentes/{id}', [AprendizAccidentesController::class, 'destroy'])->name('accidentes.destroy');

            Route::get('/incidentes', [AprendizIncidentesController::class, 'index'])->name('incidentes.index');
            Route::post('/incidentes', [AprendizIncidentesController::class, 'store'])->name('incidentes.store');
            Route::put('/incidentes/{id}', [AprendizIncidentesController::class, 'update'])->name('incidentes.update');
            Route::delete('/incidentes/{id}', [AprendizIncidentesController::class, 'destroy'])->name('incidentes.destroy');

            Route::get('/riesgos', [AprendizRiesgosController::class, 'index'])->name('riesgos.index');
            Route::post('/riesgos', [AprendizRiesgosController::class, 'store'])->name('riesgos.store');
            Route::put('/riesgos/{id}', [AprendizRiesgosController::class, 'update'])->name('riesgos.update');
            Route::delete('/riesgos/{id}', [AprendizRiesgosController::class, 'destroy'])->name('riesgos.destroy');

            Route::get('/actos-inseguros', [AprendizActosInsegurosController::class, 'index'])->name('actos_inseguros.index');
            Route::post('/actos-inseguros', [AprendizActosInsegurosController::class, 'store'])->name('actos_inseguros.store');
            Route::put('/actos-inseguros/{id}', [AprendizActosInsegurosController::class, 'update'])->name('actos_inseguros.update');
            Route::delete('/actos-inseguros/{id}', [AprendizActosInsegurosController::class, 'destroy'])->name('actos_inseguros.destroy');

            Route::get('/condiciones-inseguras', [AprendizCondicionesInsegurasController::class, 'index'])->name('condiciones_inseguras.index');
            Route::post('/condiciones-inseguras', [AprendizCondicionesInsegurasController::class, 'store'])->name('condiciones_inseguras.store');
            Route::put('/condiciones-inseguras/{id}', [AprendizCondicionesInsegurasController::class, 'update'])->name('condiciones_inseguras.update');
            Route::delete('/condiciones-inseguras/{id}', [AprendizCondicionesInsegurasController::class, 'destroy'])->name('condiciones_inseguras.destroy');

            Route::get('/lesiones', [AprendizLesionesController::class, 'index'])->name('lesiones.index');
            Route::post('/lesiones', [AprendizLesionesController::class, 'store'])->name('lesiones.store');
            Route::put('/lesiones/{id}', [AprendizLesionesController::class, 'update'])->name('lesiones.update');
            Route::delete('/lesiones/{id}', [AprendizLesionesController::class, 'destroy'])->name('lesiones.destroy');

            Route::get('/tipos-emergencias', [AprendizTiposEmergenciasController::class, 'index'])->name('tipos_emergencias.index');
            Route::post('/tipos-emergencias', [AprendizTiposEmergenciasController::class, 'store'])->name('tipos_emergencias.store');
            Route::put('/tipos-emergencias/{id}', [AprendizTiposEmergenciasController::class, 'update'])->name('tipos_emergencias.update');
            Route::delete('/tipos-emergencias/{id}', [AprendizTiposEmergenciasController::class, 'destroy'])->name('tipos_emergencias.destroy');
        });

        // Contactos de Emergencia para Aprendiz
        Route::get('/emergencias/contactos', [AprendizContactoEmergenciaController::class, 'index'])->name('emergencias.contactos');

        // Inspecciones para Aprendiz
        Route::prefix('inspecciones')->name('inspecciones.')->group(function () {
            Route::get('/registrar', [AprendizInspeccionesController::class, 'registrar'])->name('registrar');
            Route::post('/registrar', [AprendizInspeccionesController::class, 'store'])->name('store');
            Route::get('/seguimiento', [AprendizInspeccionesController::class, 'seguimiento'])->name('seguimiento');
            Route::put('/actualizar/{id}', [AprendizInspeccionesController::class, 'update'])->name('update');
            Route::delete('/eliminar/{id}', [AprendizInspeccionesController::class, 'destroy'])->name('destroy');

            // Alias de compatibilidad
            Route::get('/realizar', [AprendizInspeccionesController::class, 'realizar'])->name('realizar');
            Route::get('/historial', [AprendizInspeccionesController::class, 'historial'])->name('historial');
        });

        // Actividades SST para Aprendiz
        Route::prefix('actividades-sst')->name('actividades_sst.')->group(function () {
            Route::get('/aprendices-por-ficha', [\Modules\SST\Http\Controllers\Aprendiz\ActividadesSST\CharlasController::class, 'getAprendicesPorFicha'])->name('aprendices_por_ficha');

            Route::get('/pausas-activas', [\Modules\SST\Http\Controllers\Aprendiz\ActividadesSST\PausasActivasController::class, 'index'])->name('pausas_activas.index');
            Route::post('/pausas-activas/{id}/asistencia', [\Modules\SST\Http\Controllers\Aprendiz\ActividadesSST\PausasActivasController::class, 'asistencia'])->name('pausas_activas.asistencia');

            Route::get('/charlas', [\Modules\SST\Http\Controllers\Aprendiz\ActividadesSST\CharlasController::class, 'index'])->name('charlas.index');
            Route::post('/charlas/{id}/asistencia', [\Modules\SST\Http\Controllers\Aprendiz\ActividadesSST\CharlasController::class, 'asistencia'])->name('charlas.asistencia');

            Route::get('/sensibilizacion', [\Modules\SST\Http\Controllers\Aprendiz\ActividadesSST\SensibilizacionController::class, 'index'])->name('sensibilizacion.index');
            Route::post('/sensibilizacion/{id}/asistencia', [\Modules\SST\Http\Controllers\Aprendiz\ActividadesSST\SensibilizacionController::class, 'asistencia'])->name('sensibilizacion.asistencia');
        });

        // Módulo Central de Control de Asistencias SST
        Route::prefix('asistencia')->name('asistencia.')->group(function () {
            Route::get('/', [\Modules\SST\Http\Controllers\Aprendiz\Asistencia\AsistenciaController::class, 'index'])->name('index');
            Route::get('/{id}', [\Modules\SST\Http\Controllers\Aprendiz\Asistencia\AsistenciaController::class, 'show'])->name('show');
        });
    });

    // Compatibilidad
    Route::get('/funcionario/dashboard', [AprendizController::class, 'dashboard'])->name('funcionario.dashboard');
});

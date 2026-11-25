<?php

use Illuminate\Support\Facades\Route;
use Modules\SST\Http\Controllers\SSTController;
use Modules\SST\Http\Controllers\Admin\TiposEventos\AccidentesController;
use Modules\SST\Http\Controllers\Admin\TiposEventos\IncidentesController;
use Modules\SST\Http\Controllers\Admin\TiposEventos\RiesgosController;
use Modules\SST\Http\Controllers\Admin\TiposEventos\ActosInsegurosController;
use Modules\SST\Http\Controllers\Admin\TiposEventos\CondicionesInsegurasController;
use Modules\SST\Http\Controllers\Admin\TiposEventos\LesionesController;
use Modules\SST\Http\Controllers\Admin\TiposEventos\CondicionesEmergenciasController;
use Modules\SST\Http\Controllers\Admin\Inspecciones\HistorialController;
use Modules\SST\Http\Controllers\Admin\Informacion_basica\RespuestaEventoController;
use Modules\SST\Http\Controllers\Admin\Informacion_basica\ContactoEmergenciaController;
use Modules\SST\Http\Controllers\Admin\AdminController;
use Modules\SST\Http\Controllers\Aprendiz\AprendizController;
use Modules\SST\Http\Controllers\Aprendiz\TiposEventos\AccidentesController as AprendizAccidentesController;
use Modules\SST\Http\Controllers\Aprendiz\TiposEventos\IncidentesController as AprendizIncidentesController;
use Modules\SST\Http\Controllers\Aprendiz\TiposEventos\RiesgosController as AprendizRiesgosController;
use Modules\SST\Http\Controllers\Aprendiz\TiposEventos\ActosInsegurosController as AprendizActosInsegurosController;
use Modules\SST\Http\Controllers\Aprendiz\TiposEventos\CondicionesInsegurasController as AprendizCondicionesInsegurasController;
use Modules\SST\Http\Controllers\Aprendiz\TiposEventos\LesionesController as AprendizLesionesController;
use Modules\SST\Http\Controllers\Aprendiz\TiposEventos\CondicionesEmergenciasController as AprendizCondicionesEmergenciasController;
use Modules\SST\Http\Controllers\Aprendiz\Emergencias\ContactoEmergenciaController as AprendizContactoEmergenciaController;
use Modules\SST\Http\Controllers\Aprendiz\Inspecciones\RegistrarInspeccionController;
use Modules\SST\Http\Controllers\Aprendiz\Inspecciones\SeguimientoController;

Route::prefix('Sst')->name('SST.')->group(function () {
    // 1. Landing pública / Bienvenida
    Route::get('/', [SSTController::class, 'welcome'])->name('welcome');
    Route::get('/index', [SSTController::class, 'welcome'])->name('index');

    // 2. Redirección Inteligente
    Route::get('/dashboard', [SSTController::class, 'dashboard'])->name('dashboard');

    // 3. Rutas de Administración SST (Protegidas exclusivamente para sst.admin y superadmin)
    Route::middleware([\Modules\SST\Http\Middleware\SSTAdminMiddleware::class])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        // Rutas de Administración - Tipos de Eventos
        Route::prefix('admin/tipos-eventos')->name('admin.tipos_eventos.')->group(function () {
            Route::resource('accidentes', AccidentesController::class)->names('accidentes')->only(['index', 'store', 'update', 'destroy']);
            Route::resource('incidentes', IncidentesController::class)->names('incidentes')->only(['index', 'store', 'update', 'destroy']);
            Route::resource('riesgos', RiesgosController::class)->names('riesgos')->only(['index', 'store', 'update', 'destroy']);
            Route::resource('actos-inseguros', ActosInsegurosController::class)->names('actos_inseguros')->only(['index', 'store', 'update', 'destroy']);
            Route::resource('condiciones-inseguras', CondicionesInsegurasController::class)->names('condiciones_inseguras')->only(['index', 'store', 'update', 'destroy']);
            Route::resource('lesiones', LesionesController::class)->names('lesiones')->only(['index', 'store', 'update', 'destroy']);
            Route::resource('tipos-emergencias', CondicionesEmergenciasController::class)->names('tipos_emergencias')->only(['index', 'store', 'update', 'destroy']);
        });

        // Rutas de Administración - Información Básica
        Route::prefix('admin/informacion-basica')->name('admin.informacion_basica.')->group(function () {
            Route::post('respuesta-eventos/{id}/atender', [RespuestaEventoController::class, 'atender'])->name('respuesta_eventos.atender');
            Route::post('respuesta-eventos/{id}/finalizar', [RespuestaEventoController::class, 'finalizar'])->name('respuesta_eventos.finalizar');
            Route::resource('respuesta-eventos', RespuestaEventoController::class)->names('respuesta_eventos')->only(['index', 'store', 'update', 'destroy']);
            Route::resource('contacto-emergencia', ContactoEmergenciaController::class)->names('contacto_emergencia')->only(['index', 'store', 'update', 'destroy']);
        });

        // Rutas de Administración - Inspecciones
        Route::prefix('admin/inspecciones')->name('admin.inspecciones.')->group(function () {
            Route::get('/historial', [HistorialController::class, 'historial'])->name('historial');
            Route::post('/historial/{id}/atender', [HistorialController::class, 'atender'])->name('atender');
            Route::redirect('/realizar', '/Sst/admin/inspecciones/historial')->name('realizar');
        });

        // Rutas de Administración - Actividades SST (Calendario)
        Route::prefix('admin/cronograma')->name('admin.cronograma.')->group(function () {
            Route::get('/calendario', [\Modules\SST\Http\Controllers\Admin\ActividadesSST\CalendarioController::class, 'index'])->name('calendario');
            Route::post('/calendario', [\Modules\SST\Http\Controllers\Admin\ActividadesSST\CalendarioController::class, 'store'])->name('calendario.store');
            Route::post('/calendario/tipo-actividad', [\Modules\SST\Http\Controllers\Admin\ActividadesSST\CalendarioController::class, 'storeTipoActividad'])->name('calendario.tipo_actividad.store');
            Route::delete('/calendario/tipo-actividad/{id}', [\Modules\SST\Http\Controllers\Admin\ActividadesSST\CalendarioController::class, 'destroyTipoActividad'])->name('calendario.tipo_actividad.destroy');
            Route::put('/calendario/{id}', [\Modules\SST\Http\Controllers\Admin\ActividadesSST\CalendarioController::class, 'update'])->name('calendario.update');
            Route::delete('/calendario/{id}', [\Modules\SST\Http\Controllers\Admin\ActividadesSST\CalendarioController::class, 'destroy'])->name('calendario.destroy');
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
            Route::get('/calendario', [\Modules\SST\Http\Controllers\Admin\ActividadesSST\CalendarioController::class, 'index'])->name('calendario.index');
        });

        // Compatibilidad de rutas anteriores de Indicadores hacia el Dashboard Principal
        Route::prefix('admin/indicadores')->name('admin.indicadores.')->group(function () {
            Route::get('/ver-indicadores', function () {
                return redirect()->route('SST.admin.dashboard');
            })->name('index');
        });

        // Rutas de Administración - Gestión de Usuarios SST
        Route::prefix('admin/usuarios')->name('admin.usuarios.')->group(function () {
            Route::get('/registrar-usuario', [\Modules\SST\Http\Controllers\Admin\Usuarios\RegistrarUsuarioController::class, 'index'])->name('index');
            Route::get('/search', [\Modules\SST\Http\Controllers\Admin\Usuarios\RegistrarUsuarioController::class, 'search'])->name('search');
            Route::post('/assign-role', [\Modules\SST\Http\Controllers\Admin\Usuarios\RegistrarUsuarioController::class, 'assignRole'])->name('assign_role');
            Route::delete('/remove-role/{user}/{role}', [\Modules\SST\Http\Controllers\Admin\Usuarios\RegistrarUsuarioController::class, 'removeRole'])->name('remove_role');
            Route::put('/update-credentials/{id}', [\Modules\SST\Http\Controllers\Admin\Usuarios\RegistrarUsuarioController::class, 'updateCredentials'])->name('update_credentials');
        });
    });

    // 4. Rutas para el Aprendiz SST (Protegidas para sst.aprendiz, sst.admin y superadmin)
    Route::middleware([\Modules\SST\Http\Middleware\SSTAprendizMiddleware::class])->group(function () {
        Route::prefix('aprendiz')->name('aprendiz.')->group(function () {
        Route::get('/dashboard', [AprendizController::class, 'dashboard'])->name('dashboard');
        Route::put('/cambiar-clave', [\Modules\SST\Http\Controllers\Admin\Usuarios\RegistrarUsuarioController::class, 'updateMyPassword'])->name('cambiar_clave');

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

            Route::get('/tipos-emergencias', [AprendizCondicionesEmergenciasController::class, 'index'])->name('tipos_emergencias.index');
            Route::post('/tipos-emergencias', [AprendizCondicionesEmergenciasController::class, 'store'])->name('tipos_emergencias.store');
            Route::put('/tipos-emergencias/{id}', [AprendizCondicionesEmergenciasController::class, 'update'])->name('tipos_emergencias.update');
            Route::delete('/tipos-emergencias/{id}', [AprendizCondicionesEmergenciasController::class, 'destroy'])->name('tipos_emergencias.destroy');
        });

        // Contactos de Emergencia para Aprendiz
        Route::get('/emergencias/contactos', [AprendizContactoEmergenciaController::class, 'index'])->name('emergencias.contactos');

        // Inspecciones para Aprendiz
        Route::prefix('inspecciones')->name('inspecciones.')->group(function () {
            Route::get('/registrar', [RegistrarInspeccionController::class, 'registrar'])->name('registrar');
            Route::post('/registrar', [RegistrarInspeccionController::class, 'store'])->name('store');
            Route::get('/realizar', [RegistrarInspeccionController::class, 'realizar'])->name('realizar');

            Route::get('/seguimiento', [SeguimientoController::class, 'seguimiento'])->name('seguimiento');
            Route::put('/actualizar/{id}', [SeguimientoController::class, 'update'])->name('update');
            Route::delete('/eliminar/{id}', [SeguimientoController::class, 'destroy'])->name('destroy');
            Route::get('/historial', [SeguimientoController::class, 'historial'])->name('historial');
        });

        // Actividades SST para Aprendiz (Módulo Unificado)
        Route::prefix('actividades-sst')->name('actividades_sst.')->group(function () {
            Route::get('/', [\Modules\SST\Http\Controllers\Aprendiz\ActividadesSST\ActividadesSSTController::class, 'index'])->name('index');
            Route::post('/{id}/asistencia', [\Modules\SST\Http\Controllers\Aprendiz\ActividadesSST\ActividadesSSTController::class, 'asistencia'])->name('asistencia');
            Route::get('/aprendices-por-ficha', [\Modules\SST\Http\Controllers\Aprendiz\ActividadesSST\ActividadesSSTController::class, 'getAprendicesPorFicha'])->name('aprendices_por_ficha');

            Route::get('/pausas-activas', [\Modules\SST\Http\Controllers\Aprendiz\ActividadesSST\PausasActivasController::class, 'index'])->name('pausas_activas.index');
            Route::post('/pausas-activas/{id}/asistencia', [\Modules\SST\Http\Controllers\Aprendiz\ActividadesSST\PausasActivasController::class, 'asistencia'])->name('pausas_activas.asistencia');

            Route::get('/charlas', [\Modules\SST\Http\Controllers\Aprendiz\ActividadesSST\CharlasController::class, 'index'])->name('charlas.index');
            Route::post('/charlas/{id}/asistencia', [\Modules\SST\Http\Controllers\Aprendiz\ActividadesSST\CharlasController::class, 'asistencia'])->name('charlas.asistencia');

            Route::get('/sensibilizacion', [\Modules\SST\Http\Controllers\Aprendiz\ActividadesSST\SensibilizacionController::class, 'index'])->name('sensibilizacion.index');
            Route::post('/sensibilizacion/{id}/asistencia', [\Modules\SST\Http\Controllers\Aprendiz\ActividadesSST\SensibilizacionController::class, 'asistencia'])->name('sensibilizacion.asistencia');
        });

        // Módulo Central de Control de Asistencias SST
        Route::prefix('asistencia')->name('asistencia.')->group(function () {
            Route::get('/', [\Modules\SST\Http\Controllers\Aprendiz\Asistencia\ControlAsistenciasController::class, 'index'])->name('index');
            Route::get('/{id}', [\Modules\SST\Http\Controllers\Aprendiz\Asistencia\ControlAsistenciasController::class, 'show'])->name('show');
        });
    });
});

// Compatibilidad
Route::prefix('Sst')->name('SST.')->middleware([\Modules\SST\Http\Middleware\SSTAprendizMiddleware::class])->group(function () {
    Route::get('/funcionario/dashboard', [AprendizController::class, 'dashboard'])->name('funcionario.dashboard');
});
});


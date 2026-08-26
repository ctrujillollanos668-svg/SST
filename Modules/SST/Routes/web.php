<?php

use Illuminate\Support\Facades\Route;
use Modules\SST\Http\Controllers\SSTController;

Route::prefix('Sst')->name('SST.')->group(function () {
    // 1. Landing pública / Bienvenida
    Route::get('/', [SSTController::class, 'welcome'])->name('welcome');
    Route::get('/index', [SSTController::class, 'welcome'])->name('index');

    // 2. Redirección Inteligente
    Route::get('/dashboard', [SSTController::class, 'dashboard'])->name('dashboard');

    // 3. Ruta para el Administrador
    Route::get('/admin/dashboard', [SSTController::class, 'adminDashboard'])->name('admin.dashboard');

    // 4. Ruta para el Funcionario
    Route::get('/funcionario/dashboard', [SSTController::class, 'funcionarioDashboard'])->name('funcionario.dashboard');
});

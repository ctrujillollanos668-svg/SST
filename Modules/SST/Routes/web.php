<?php

use Illuminate\Support\Facades\Route;
use Modules\SST\Http\Controllers\SSTController;

 
Route::prefix('Sst')->name('SST.')->group(function () {
    Route::get('/', [SSTController::class, 'welcome'])->name('welcome');
});


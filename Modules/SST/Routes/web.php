<?php

use Illuminate\Support\Facades\Route;
use Modules\SST\Http\Controllers\SSTController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('ssts', SSTController::class)->names('sst');
});

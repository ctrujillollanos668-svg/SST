<?php

use Illuminate\Support\Facades\Route;
use Modules\SST\Http\Controllers\SSTController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('ssts', SSTController::class)->names('sst');
});

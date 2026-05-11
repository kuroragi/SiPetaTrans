<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\AssetTypeController;
use App\Http\Controllers\AssetMonitoringController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::resource('assets', AssetController::class);
Route::resource('asset-types', AssetTypeController::class);
Route::put('/asset-types/update-icon', [AssetTypeController::class, 'updateIcon'])->name('asset-types.update-icon');

// Asset Monitoring Routes
Route::prefix('asset-monitoring')->group(function () {
    Route::get('/', [AssetMonitoringController::class, 'index'])->name('asset-monitoring.index');
    Route::get('/{asset}', [AssetMonitoringController::class, 'show'])->name('asset-monitoring.show');
    Route::post('/{asset}/photo', [AssetMonitoringController::class, 'uploadPhoto'])->name('asset-monitoring.upload');
});

// Asset Photo Routes
Route::delete('/asset-photo/{photo}', [AssetMonitoringController::class, 'deletePhoto'])->name('asset-monitoring.delete-photo');

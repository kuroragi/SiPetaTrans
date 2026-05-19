<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\AssetTypeController;
use App\Http\Controllers\AssetMonitoringController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware('guest');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->name('register.post')->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Protected Routes
Route::middleware('auth')->group(function () {
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

    // Management Routes
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
});

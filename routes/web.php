<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\AssetMaintenanceController;
use App\Http\Controllers\AssetTypeController;
use App\Http\Controllers\AssetMonitoringController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DamageReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AssetDepreciationController;
use App\Http\Controllers\TrayekController;
use App\Mail\AssetReportMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware('guest');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->name('register.post')->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::post('/pengaduan-kerusakan', [DamageReportController::class, 'storePublic'])->name('damage-reports.store-public');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Assets
    Route::resource('assets', AssetController::class);

    // Trayeks
    Route::resource('trayeks', TrayekController::class);

    // Asset Depreciation Routes
    Route::prefix('assets/{asset}/depreciations')->name('asset-depreciations.')->group(function () {
        Route::get('/', [AssetDepreciationController::class, 'show'])->name('show');
        Route::post('/', [AssetDepreciationController::class, 'store'])->name('store');
        Route::put('/{depreciation}', [AssetDepreciationController::class, 'update'])->name('update');
        Route::delete('/{depreciation}', [AssetDepreciationController::class, 'destroy'])->name('destroy');
    });

    // Asset Types
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

    // Damage Reports Routes
    Route::prefix('damage-reports')->name('damage-reports.')->group(function () {
        Route::get('/', [DamageReportController::class, 'index'])->name('index');
        Route::get('/{damageReport}', [DamageReportController::class, 'show'])->name('show');
        Route::put('/{damageReport}', [DamageReportController::class, 'update'])->name('update');
    });

    // Users
    Route::resource('users', UserController::class);

    // Roles
    Route::resource('roles', RoleController::class);

    // Permissions
    Route::resource('permissions', PermissionController::class);

    // Asset Maintenance
    Route::resource('asset-maintenance', AssetMaintenanceController::class);

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::post('/print', [ReportController::class, 'print'])->name('print');
    });
});

Route::get('check-permission', function () {
    dd(auth()->user()->getAllPermissions()->pluck('name'));
});
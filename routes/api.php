<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\MaintenanceAlertController;
use App\Http\Controllers\ServiceTypeController;
use Illuminate\Support\Facades\Route;

// Rotas públicas
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Rotas protegidas
Route::middleware('auth:api')->group(function () {
    // Auth
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);

    // Resources
    Route::apiResource('vehicles', VehicleController::class);
    Route::apiResource('maintenances', MaintenanceController::class);
    Route::apiResource('maintenance-alerts', MaintenanceAlertController::class);
    // Route::get('service-types', ServiceTypeController::class);

    // Nested routes
    Route::get('vehicles/{vehicle}/maintenances', [MaintenanceController::class, 'byVehicle']);
    Route::get('vehicles/{vehicle}/alerts', [MaintenanceAlertController::class, 'byVehicle']);
});

<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ItemController;
use Illuminate\Support\Facades\Route;

// Satu-satunya endpoint tanpa token
Route::post('/login', [AuthController::class, 'login']);

// Semua endpoint lain wajib token Sanctum (tanpa token -> 401)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('items', ItemController::class);
});

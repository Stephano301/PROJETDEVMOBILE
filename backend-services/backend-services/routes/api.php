<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ProviderController;

/*
|--------------------------------------------------------------------------
| Routes publiques (sans authentification)
|--------------------------------------------------------------------------
*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// Services et prestataires accessibles sans auth (pour J1)
Route::get('/services',              [ServiceController::class, 'index']);
Route::get('/services/{id}',         [ServiceController::class, 'show']);
Route::get('/providers',             [ProviderController::class, 'index']);
Route::get('/providers/{id}',        [ProviderController::class, 'show']);
Route::get('/providers/{id}/slots',  [ProviderController::class, 'timeSlots']);

/*
|--------------------------------------------------------------------------
| Routes protégées (authentification requise)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me',      [AuthController::class, 'me']);
});
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProviderDashboardController;

/*
|--------------------------------------------------------------------------
| Routes publiques
|--------------------------------------------------------------------------
*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

Route::get('/services',              [ServiceController::class, 'index']);
Route::get('/services/{id}',         [ServiceController::class, 'show']);
Route::get('/providers',             [ProviderController::class, 'index']);
Route::get('/providers/{id}',        [ProviderController::class, 'show']);
Route::get('/providers/{id}/slots',  [ProviderController::class, 'timeSlots']);

/*
|--------------------------------------------------------------------------
| Routes protégées
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me',      [AuthController::class, 'me']);

    // Bookings client
    Route::get('/bookings',               [BookingController::class, 'index']);
    Route::post('/bookings',              [BookingController::class, 'store']);
    Route::patch('/bookings/{id}/cancel', [BookingController::class, 'cancel']);

    // Dashboard prestataire
    Route::get('/provider/dashboard',              [ProviderDashboardController::class, 'dashboard']);
    Route::patch('/provider/bookings/{id}/accept', [ProviderDashboardController::class, 'acceptBooking']);
    Route::patch('/provider/bookings/{id}/refuse', [ProviderDashboardController::class, 'refuseBooking']);
});
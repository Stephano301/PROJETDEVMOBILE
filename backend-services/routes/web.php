<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Models\User;

Route::get('/', function () {
    return view('welcome');
});

// Dashboard Prestataire
Route::get('/dashboard/provider', function () {
    $user = User::where('email', 'jean.rakoto@email.com')->first();
    auth()->login($user);
    return app(DashboardController::class)->providerDashboard();
});

// Dashboard Admin
Route::get('/admin', function () {
    $user = User::where('email', 'admin@test.com')->first();
    auth()->login($user);
    return app(AdminController::class)->dashboard();
})->name('admin.dashboard');

Route::get('/admin/users',    [AdminController::class, 'users'])
    ->name('admin.users');
Route::get('/admin/providers', [AdminController::class, 'providers'])
    ->name('admin.providers');
Route::get('/admin/bookings',  [AdminController::class, 'bookings'])
    ->name('admin.bookings');
Route::patch('/admin/bookings/{id}/status', [AdminController::class, 'updateBookingStatus'])
    ->name('admin.bookings.status');
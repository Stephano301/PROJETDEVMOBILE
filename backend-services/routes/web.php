<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Models\User;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard/provider', function () {
    $user = User::where('email', 'jean.rakoto@email.com')->first();
    auth()->login($user);
    return app(DashboardController::class)->providerDashboard();
});

Route::prefix('admin')->group(function () {
    Route::get('/', function () {
        $user = User::where('email', 'admin@test.com')->first();
        auth()->login($user);
        return app(AdminController::class)->dashboard();
    })->name('admin.dashboard');

    Route::get('/users',      [AdminController::class, 'users'])     ->name('admin.users');
    Route::get('/providers',  [AdminController::class, 'providers']) ->name('admin.providers');
    Route::get('/categories', [AdminController::class, 'categories'])->name('admin.categories');
    Route::get('/payments',   [AdminController::class, 'payments'])  ->name('admin.payments');
    Route::get('/reviews',    [AdminController::class, 'reviews'])   ->name('admin.reviews');

    // Services
    Route::get('/services',         [AdminController::class, 'services'])    ->name('admin.services');
    Route::post('/services',        [AdminController::class, 'storeService'])->name('admin.services.store');
    Route::patch('/services/{id}/toggle', [AdminController::class, 'toggleService'])->name('admin.services.toggle');

    // Bookings
    Route::get('/bookings',         [AdminController::class, 'bookings'])    ->name('admin.bookings');
    Route::post('/bookings',        [AdminController::class, 'storeBooking'])->name('admin.bookings.store');
    Route::patch('/bookings/{id}/status', [AdminController::class, 'updateBookingStatus'])->name('admin.bookings.status');

    Route::patch('/providers/{id}/toggle', [AdminController::class, 'toggleProvider'])->name('admin.providers.toggle');
    Route::post('/providers', [AdminController::class, 'storeProvider'])->name('admin.providers.store');
});
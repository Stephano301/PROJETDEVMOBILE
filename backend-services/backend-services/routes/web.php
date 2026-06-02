<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Models\User;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard/provider', function () {
    // Connexion automatique pour le test
    $user = User::where('email', 'jean.rakoto@email.com')->first();
    auth()->login($user);
    return app(\App\Http\Controllers\DashboardController::class)->providerDashboard();
});
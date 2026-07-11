<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController\AdminAuthController;
use App\Http\Controllers\AdminController\AdminDashboardController;

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware('guest')->group(function () {

    Route::get('/login', [AdminAuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AdminAuthController::class, 'login'])
        ->name('login.submit');

});

Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');

    Route::post('/logout', [AdminAuthController::class, 'logout'])
        ->name('logout');

});
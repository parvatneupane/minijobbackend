<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController\AdminAuthController;
use App\Http\Controllers\AdminController\AdminDashboardController;
use App\Http\Controllers\AdminController\AdminSettingController;
use App\Http\Controllers\AdminController\UserSettingController;
use App\Http\Controllers\AuthController;



//welcome 
Route::get('/welcome', function () {
    return view('welcome');
});







Route::middleware('guest')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
   Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit'); 

});

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('admin.dashboard');

        Route::post('/logout', [AdminAuthController::class, 'logout'])
            ->name('admin.logout');

        // Account
        Route::prefix('account')->group(function () {
            Route::get('/accountsetting', [AdminSettingController::class, 'index']);
            Route::post('/profile', [AdminSettingController::class, 'updateProfile']);
            Route::post('/password', [AdminSettingController::class, 'updatePassword']);
            Route::post('/notifications', [AdminSettingController::class, 'updateNotifications']);
            Route::post('/deactivate', [AdminSettingController::class, 'deactivate']);
            Route::delete('/delete', [AdminSettingController::class, 'destroy']);
        });

        // User Settings
        Route::get('/usersettings', [UserSettingController::class, 'index']);
        Route::post('/usersettings/add', [UserSettingController::class, 'store']);
        Route::put('/usersettings/{user}', [UserSettingController::class, 'update']);
        Route::delete('/usersettings/{user}', [UserSettingController::class, 'destroy']);

        Route::post('/users/{id}/approve', [UserSettingController::class, 'approve']);
        Route::post('/users/{id}/impersonate', [UserSettingController::class, 'loginAs']);
        Route::get('/stop-impersonate', [UserSettingController::class, 'backToAdmin']);
    });



    //user dashboard



Route::middleware(['auth'])->group(function () {

Route::get('/users/dashboard', [App\Http\Controllers\UserDashboardController::class, 'index'])->name('user.dashboard'); 
route::post('/logout', [App\Http\Controllers\UserDashboardController::class, 'logout'])->name('user.logout');   

});

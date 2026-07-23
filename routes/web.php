<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController\AdminAuthController;
use App\Http\Controllers\AdminController\AdminDashboardController;
use App\Http\Controllers\AdminController\AdminSettingController;



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

    Route::get('admin/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');

    Route::post('/logout', [AdminAuthController::class, 'logout'])
        ->name('admin.logout');

     
    });


Route::prefix('admin/account')->group(function () {

    Route::get('/accountsetting', [AdminSettingController::class, 'index'])
        ->name('admin.account.setting');

    Route::post('/profile', [AdminSettingController::class, 'updateProfile'])
        ->name('admin.profile.update');

    Route::post('/password', [AdminSettingController::class, 'updatePassword'])
        ->name('admin.password.update');

    Route::post('/notifications', [AdminSettingController::class, 'updateNotifications'])
        ->name('admin.notifications.update');

    Route::post('/deactivate', [AdminSettingController::class, 'deactivate'])
        ->name('admin.deactivate');

    Route::delete('/delete', [AdminSettingController::class, 'destroy'])
        ->name('admin.delete');
});




use App\Http\Controllers\AdminController\UserSettingController;

Route::middleware(['auth'])->group(function () {

 Route::get(
        '/admin/usersettings',
        [UserSettingController::class,'index']
    );


    Route::post(
        '/admin/usersettings/add',
        [UserSettingController::class,'store']
    );


    Route::put(
        '/admin/usersettings/{user}',
        [UserSettingController::class,'update']
    );


    Route::delete(
        '/admin/usersettings/{user}',
        [UserSettingController::class,'destroy']
    );



    /*
    |--------------------------------------------------------------------------
    | Approve Pending User
    |--------------------------------------------------------------------------
    */


    Route::post(
        '/admin/users/{id}/approve',
        [UserSettingController::class,'approve']
    );

    Route::post(
    '/admin/users/{id}/impersonate',
    [UserSettingController::class,'loginAs']
);


Route::get(
    '/admin/stop-impersonate',
    [UserSettingController::class,'backToAdmin']
);

});




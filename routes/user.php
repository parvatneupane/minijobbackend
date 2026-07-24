Route::prefix('admin')->group(function(){


    Route::get('/verifications',
    [VerificationController::class,'viewIndex'])
    ->name('admin.verifications.index');


    Route::get('/verifications/create',
    [VerificationController::class,'create'])
    ->name('admin.verifications.create');


    Route::post('/verifications',
    [VerificationController::class,'store'])
    ->name('admin.verifications.store');



    Route::get('/verifications/{id}',
    [VerificationController::class,'show'])
    ->name('admin.verifications.show');



    Route::get('/verifications/{id}/edit',
    [VerificationController::class,'edit'])
    ->name('admin.verifications.edit');



    Route::put('/verifications/{id}',
    [VerificationController::class,'update'])
    ->name('admin.verifications.update');



    Route::delete('/verifications/{id}',
    [VerificationController::class,'destroy'])
    ->name('admin.verifications.destroy');

});


<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FreeLancerProfileController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\TaskCategoriesController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\ContractController;

use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController;


/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);




    /*
|--------------------------------------------------------------------------
| Freelancer Profile Routes
|--------------------------------------------------------------------------
*/
// Protected Routes
Route::middleware('auth:sanctum')->group(function () {

Route::post('/logout', [AuthController::class, 'logout']);


Route::get('/freelancer-profiles', [FreeLancerProfileController::class, 'index']);

Route::post('/freelancer-profiles', [FreeLancerProfileController::class, 'store']);

Route::get('/freelancer-profiles/{id}', [FreeLancerProfileController::class, 'show']);

Route::put('/freelancer-profiles/{id}', [FreeLancerProfileController::class, 'update']);

Route::delete('/freelancer-profiles/{id}', [FreeLancerProfileController::class, 'destroy']);

    Route::get('/my-profile',[FreeLancerProfileController::class,'myProfile']);

/*
|--------------------------------------------------------------------------
| Client Profile Routes
|--------------------------------------------------------------------------
*/

Route::get('/verifications', [VerificationController::class, 'index']);

Route::post('/verifications', [VerificationController::class, 'store']);

Route::get('/verifications/{id}', [VerificationController::class, 'show']);

Route::put('/verifications/{id}', [VerificationController::class, 'update']);

Route::delete('/verifications/{id}', [VerificationController::class, 'destroy']);



/*
|--------------------------------------------------------------------------
| Task Category Routes
|--------------------------------------------------------------------------
*/

Route::get('/task-categories', [TaskCategoriesController::class, 'index']);

Route::post('/task-categories', [TaskCategoriesController::class, 'store']);

Route::get('/task-categories/{id}', [TaskCategoriesController::class, 'show']);

Route::put('/task-categories/{id}', [TaskCategoriesController::class, 'update']);

Route::delete('/task-categories/{id}', [TaskCategoriesController::class, 'destroy']);





/*
|--------------------------------------------------------------------------
| Task Routes
|--------------------------------------------------------------------------
*/

Route::get('/tasks', [TaskController::class, 'index']);

Route::post('/tasks', [TaskController::class, 'store']);

Route::get('/tasks/{id}', [TaskController::class, 'show']);

Route::put('/tasks/{id}', [TaskController::class, 'update']);

Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);


/*
|--------------------------------------------------------------------------
| Proposal Routes
|--------------------------------------------------------------------------
*/

Route::get('/proposals', [ProposalController::class, 'index']);

Route::post('/proposals', [ProposalController::class, 'store']);

Route::get('/proposals/{id}', [ProposalController::class, 'show']);

Route::put('/proposals/{id}', [ProposalController::class, 'update']);

Route::delete('/proposals/{id}', [ProposalController::class, 'destroy']);



/*
|--------------------------------------------------------------------------
| Contract Routes
|--------------------------------------------------------------------------
*/

Route::get('/contracts', [ContractController::class, 'index']);

Route::post('/contracts', [ContractController::class, 'store']);

Route::get('/contracts/{id}', [ContractController::class, 'show']);

Route::put('/contracts/{id}', [ContractController::class, 'update']);

Route::delete('/contracts/{id}', [ContractController::class, 'destroy']);


/*
|--------------------------------------------------------------------------
| Chat Routes
|--------------------------------------------------------------------------
*/

 Route::get('/chats', [ChatController::class,'index']);

Route::post('/chats', [ChatController::class,'store']);

Route::get('/chats/{id}', [ChatController::class,'show']);

Route::put('/chats/{id}', [ChatController::class,'update']);

Route::delete('/chats/{id}', [ChatController::class,'destroy']);


/*
|--------------------------------------------------------------------------
| messages Routes
|--------------------------------------------------------------------------
*/

Route::get('/messages', [MessageController::class, 'index']);

Route::post('/messages', [MessageController::class, 'store']);

Route::get('/messages/{id}', [MessageController::class, 'show']);

Route::put('/messages/{id}', [MessageController::class, 'update']);

Route::delete('/messages/{id}', [MessageController::class, 'destroy']);

Route::get('/chats/{chatId}/messages', [MessageController::class, 'chatMessages']);

Route::put('/chats/{chatId}/read', [MessageController::class, 'markAsRead']);


/*
|--------------------------------------------------------------------------
| Submission Routes
|--------------------------------------------------------------------------
*/

Route::get('/submissions', [SubmissionController::class, 'index']);

Route::post('/submissions', [SubmissionController::class, 'store']);

Route::get('/submissions/{id}', [SubmissionController::class, 'show']);

Route::put('/submissions/{id}', [SubmissionController::class, 'update']);

Route::delete('/submissions/{id}', [SubmissionController::class, 'destroy']);


/*
|--------------------------------------------------------------------------
| Payment Routes
|--------------------------------------------------------------------------
*/

Route::get('/payments', [PaymentController::class,'index']);

Route::post('/payments', [PaymentController::class,'store']);

Route::get('/payments/{id}', [PaymentController::class,'show']);

Route::put('/payments/{id}', [PaymentController::class,'update']);

Route::delete('/payments/{id}', [PaymentController::class,'destroy']);

// Release payment to freelancer
Route::put('/payments/{id}/release', [PaymentController::class,'release']);

// Refund payment
Route::put('/payments/{id}/refund', [PaymentController::class,'refund']);


/*
|--------------------------------------------------------------------------
| Review Routes
|--------------------------------------------------------------------------
*/

Route::get('/reviews', [ReviewController::class, 'index']);

Route::post('/reviews', [ReviewController::class, 'store']);

Route::get('/reviews/{id}', [ReviewController::class, 'show']);

Route::put('/reviews/{id}', [ReviewController::class, 'update']);

Route::delete('/reviews/{id}', [ReviewController::class, 'destroy']);

});



















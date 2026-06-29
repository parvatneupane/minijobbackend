<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FreeLancerProfileController;
use App\Http\Controllers\ClientProfileController;
use App\Http\Controllers\TaskCategoryController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\MilestoneController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ChatController;


/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/profile', [AuthController::class, 'profile']);

    Route::post('/logout', [AuthController::class, 'logout']);

});


/*
|--------------------------------------------------------------------------
| Freelancer Profile Routes
|--------------------------------------------------------------------------
*/

Route::get('/freelancer-profiles', [FreeLancerProfileController::class, 'index']);

Route::post('/freelancer-profiles', [FreeLancerProfileController::class, 'store']);

Route::get('/freelancer-profiles/{id}', [FreeLancerProfileController::class, 'show']);

Route::put('/freelancer-profiles/{id}', [FreeLancerProfileController::class, 'update']);

Route::delete('/freelancer-profiles/{id}', [FreeLancerProfileController::class, 'destroy']);


/*
|--------------------------------------------------------------------------
| Client Profile Routes
|--------------------------------------------------------------------------
*/

Route::get('/client-profiles', [ClientProfileController::class, 'index']);

Route::post('/client-profiles', [ClientProfileController::class, 'store']);

Route::get('/client-profiles/{id}', [ClientProfileController::class, 'show']);

Route::put('/client-profiles/{id}', [ClientProfileController::class, 'update']);

Route::delete('/client-profiles/{id}', [ClientProfileController::class, 'destroy']);


/*
|--------------------------------------------------------------------------
| Task Category Routes
|--------------------------------------------------------------------------
*/

Route::get('/task-categories', [TaskCategoryController::class, 'index']);

Route::post('/task-categories', [TaskCategoryController::class, 'store']);

Route::get('/task-categories/{id}', [TaskCategoryController::class, 'show']);

Route::put('/task-categories/{id}', [TaskCategoryController::class, 'update']);

Route::delete('/task-categories/{id}', [TaskCategoryController::class, 'destroy']);


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
| Milestone Routes
|--------------------------------------------------------------------------
*/

Route::get('/milestones', [MilestoneController::class, 'index']);

Route::post('/milestones', [MilestoneController::class, 'store']);

Route::get('/milestones/{id}', [MilestoneController::class, 'show']);

Route::put('/milestones/{id}', [MilestoneController::class, 'update']);

Route::delete('/milestones/{id}', [MilestoneController::class, 'destroy']);


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

Route::get('/payments', [PaymentController::class, 'index']);

Route::post('/payments', [PaymentController::class, 'store']);

Route::get('/payments/{id}', [PaymentController::class, 'show']);

Route::put('/payments/{id}', [PaymentController::class, 'update']);

Route::delete('/payments/{id}', [PaymentController::class, 'destroy']);


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


/*
|--------------------------------------------------------------------------
| Chat Routes
|--------------------------------------------------------------------------
*/

Route::get('/chats', [ChatController::class, 'index']);

Route::post('/chats', [ChatController::class, 'store']);

Route::get('/chats/{id}', [ChatController::class, 'show']);

Route::put('/chats/{id}', [ChatController::class, 'update']);

Route::delete('/chats/{id}', [ChatController::class, 'destroy']);
<?php

use App\Http\Controllers\Admin\ChatController;
use App\Http\Controllers\Admin\ConflictController;
use App\Http\Controllers\Admin\ContractController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FreelancerProfileController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ProposalController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\SubmissionController;
use App\Http\Controllers\Admin\TaskCategoryController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VerificationController;
use App\Http\Controllers\Admin\WithdrawalController;
use App\Http\Controllers\Admin\AdminAuthController;
use Illuminate\Support\Facades\Route;



Route::middleware('guest')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');


});


Route::post('/logout', [AdminAuthController::class, 'logout'])
    ->name('logout');



Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
 
    // Users
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::patch('users/{user}/status', [UserController::class, 'updateStatus'])->name('users.status');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Task categories (full CRUD)
    Route::resource('task-categories', TaskCategoryController::class)->except(['show']);

    // Freelancer profiles
    Route::get('freelancer-profiles', [FreelancerProfileController::class, 'index'])->name('freelancer-profiles.index');
    Route::get('freelancer-profiles/{freelancerProfile}', [FreelancerProfileController::class, 'show'])->name('freelancer-profiles.show');
    Route::patch('freelancer-profiles/{freelancerProfile}/status', [FreelancerProfileController::class, 'updateStatus'])->name('freelancer-profiles.status');

    // Verifications
    Route::get('verifications', [VerificationController::class, 'index'])->name('verifications.index');
    Route::get('verifications/{verification}', [VerificationController::class, 'show'])->name('verifications.show');
    Route::put('verifications/{verification}', [VerificationController::class, 'update'])->name('verifications.update');

    // Tasks
    Route::get('tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
    Route::patch('tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.status');
    Route::delete('tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    // Proposals
    Route::get('proposals', [ProposalController::class, 'index'])->name('proposals.index');
    Route::get('proposals/{proposal}', [ProposalController::class, 'show'])->name('proposals.show');

    // Contracts
    Route::get('contracts', [ContractController::class, 'index'])->name('contracts.index');
    Route::get('contracts/{contract}', [ContractController::class, 'show'])->name('contracts.show');
    Route::patch('contracts/{contract}/status', [ContractController::class, 'updateStatus'])->name('contracts.status');

    // Chats (read-only moderation)
    Route::get('chats', [ChatController::class, 'index'])->name('chats.index');
    Route::get('chats/{chat}', [ChatController::class, 'show'])->name('chats.show');

    // Submissions
    Route::get('submissions', [SubmissionController::class, 'index'])->name('submissions.index');
    Route::get('submissions/{submission}', [SubmissionController::class, 'show'])->name('submissions.show');

    // Payments
    Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
    Route::patch('payments/{payment}/status', [PaymentController::class, 'updateStatus'])->name('payments.status');

    // Withdrawals
    Route::get('withdrawals', [WithdrawalController::class, 'index'])->name('withdrawals.index');
    Route::get('withdrawals/{withdrawal}', [WithdrawalController::class, 'show'])->name('withdrawals.show');
    Route::put('withdrawals/{withdrawal}', [WithdrawalController::class, 'update'])->name('withdrawals.update');

    // Reviews
    Route::get('reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::delete('reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Notifications (broadcast)
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('notifications/create', [NotificationController::class, 'create'])->name('notifications.create');
    Route::post('notifications', [NotificationController::class, 'store'])->name('notifications.store');

    // Conflicts
    Route::get('conflicts', [ConflictController::class, 'index'])->name('conflicts.index');
    Route::get('conflicts/{conflict}', [ConflictController::class, 'show'])->name('conflicts.show');
    Route::put('conflicts/{conflict}', [ConflictController::class, 'update'])->name('conflicts.update');
    Route::post('conflicts/{conflict}/reply', [ConflictController::class, 'reply'])->name('conflicts.reply');
});

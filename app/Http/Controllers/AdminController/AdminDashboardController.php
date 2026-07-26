<?php

namespace App\Http\Controllers\AdminController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserModel;
use App\Models\TaskModel;
use App\Models\ContractModel;
use App\Models\PaymentModel;
use App\Models\VerificationModel;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
{
    $totalUsers = UserModel::count();
    $totalTasks = TaskModel::count();
    $totalContracts = ContractModel::where('status', 'active')->count();
    $totalRevenue = PaymentModel::where('status', 'released')->sum('platform_fee');
    $pendingVerifications = VerificationModel::where('status', 'pending')->count();
    $pendingUsers = UserModel::where('status', 'pending')->count();

    // Recent activities (latest users, tasks, payments)
    $recentUsers = UserModel::latest()->take(5)->get();
    $recentPayments = PaymentModel::with('client', 'freelancer')->latest()->take(5)->get();

    return view('admin.dashboard', compact(
        'totalUsers', 'totalTasks', 'totalContracts',
        'totalRevenue', 'pendingVerifications', 'pendingUsers',
        'recentUsers', 'recentPayments'
    ));
}

    
}
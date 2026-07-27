<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContractModel as Contract;
use App\Models\ConflictModel as Conflict;
use App\Models\PaymentModel as Payment;
use App\Models\TaskModel as Task;
use App\Models\UserModel as User;
use App\Models\VerificationModel as Verification;
use App\Models\WithdrawalModel as Withdrawal;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'clients' => User::where('role', 'client')->count(),
            'freelancers' => User::where('role', 'freelancer')->count(),
            'open_tasks' => Task::where('status', 'open')->count(),
            'active_contracts' => Contract::where('status', 'active')->count(),
            'pending_verifications' => Verification::where('status', 'pending')->count(),
            'pending_withdrawals' => Withdrawal::where('status', 'pending')->count(),
            'open_conflicts' => Conflict::whereIn('status', ['open'])->count(),
            'escrow_amount' => Payment::where('status', 'escrow')->sum('amount'),
            'released_amount' => Payment::where('status', 'released')->sum('freelancer_amount'),
        ];

        $recentUsers = User::latest()->take(5)->get();
        $recentTasks = Task::with('client')->latest()->take(5)->get();
        $recentConflicts = Conflict::with('contract')->latest()->take(5)->get();

        return view('admin.dashboard.index', compact('stats', 'recentUsers', 'recentTasks', 'recentConflicts'));
    }
}

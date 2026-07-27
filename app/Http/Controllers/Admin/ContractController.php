<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContractModel as Contract;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    public function index(Request $request)
    {
        $contracts = Contract::with(['task', 'client', 'freelancer'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.contracts.index', compact('contracts'));
    }

    public function show(Contract $contract)
    {
        $contract->load(['task', 'client', 'freelancer', 'submissions', 'payments', 'review', 'conflicts']);

        return view('admin.contracts.show', compact('contract'));
    }

    public function updateStatus(Request $request, Contract $contract)
    {
        $request->validate(['status' => 'required|in:active,completed,cancelled']);

        $contract->update(['status' => $request->status]);

        return back()->with('success', 'Contract status updated.');
    }
}

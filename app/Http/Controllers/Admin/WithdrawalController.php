<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WithdrawalModel as Withdrawal;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    public function index(Request $request)
    {
        $withdrawals = Withdrawal::with('user')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.withdrawals.index', compact('withdrawals'));
    }

    public function show(Withdrawal $withdrawal)
    {
        $withdrawal->load('user');

        return view('admin.withdrawals.show', compact('withdrawal'));
    }

    public function update(Request $request, Withdrawal $withdrawal)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,approved,completed,rejected',
            'remarks' => 'nullable|string',
        ]);

        if ($data['status'] === 'completed') {
            $data['completed_at'] = now();
        }

        $withdrawal->update($data);

        return redirect()->route('admin.withdrawals.index')->with('success', 'Withdrawal updated.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentModel as Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $payments = Payment::with(['contract.task', 'client', 'freelancer'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.payments.index', compact('payments'));
    }

    public function show(Payment $payment)
    {
        $payment->load(['contract.task', 'client', 'freelancer']);

        return view('admin.payments.show', compact('payment'));
    }

    public function updateStatus(Request $request, Payment $payment)
    {
        $request->validate(['status' => 'required|in:pending,escrow,released,refunded,failed']);

        $data = ['status' => $request->status];
        if ($request->status === 'released') {
            $data['released_at'] = now();
        }

        $payment->update($data);

        return back()->with('success', 'Payment status updated.');
    }
}

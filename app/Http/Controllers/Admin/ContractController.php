<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContractModel as Contract;
use App\Models\NotificationModel as Notification;
use App\Models\PaymentModel as Payment;
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
        $contract->load([
            'task',
            'client',
            'freelancer',
            'submissions',
            'payments',
            'review',
            'conflicts'
        ]);

        return view('admin.contracts.show', compact('contract'));
    }


    public function updateStatus(Request $request, Contract $contract)
    {
        $request->validate([
            'status' => 'required|in:active,completed,cancelled'
        ]);


        $oldStatus = $contract->status;


        $contract->update([
            'status' => $request->status
        ]);


        // Notify client and freelancer
        $users = collect([
            $contract->client,
            $contract->freelancer
        ])->filter();


        foreach ($users as $user) {

            Notification::create([
                'user_id' => $user->id,
                'title' => 'Contract Status Updated',
                'message' => "Your contract status has been changed to {$contract->status}.",
            ]);

        }


        // Release payment only if completed and no active conflict
        if ($oldStatus !== 'completed' && $contract->status === 'completed') {

            $hasConflict = $contract->conflicts()
                ->whereIn('status', ['open', 'in_review'])
                ->exists();


            if (!$hasConflict) {

                $payment = Payment::where('contract_id', $contract->id)
                    ->whereIn('status', ['pending', 'escrow'])
                    ->first();


                if ($payment) {

                    $payment->update([
                        'status' => 'released',
                        'released_at' => now(),
                    ]);


                    foreach ($users as $user) {

                        Notification::create([
                            'user_id' => $user->id,
                            'title' => 'Payment Released',
                            'message' => 'Your contract is completed and payment has been released.',
                        ]);

                    }
                }
            }
        }


        return back()->with('success', 'Contract status updated.');
    }
}

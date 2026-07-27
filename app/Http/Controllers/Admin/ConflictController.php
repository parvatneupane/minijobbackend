<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConflictModel as Conflict;
use App\Models\ConflictReplyModel as ConflictReply;
use App\Models\NotificationModel as Notification;
use App\Models\PaymentModel as Payment;
use Illuminate\Http\Request;

class ConflictController extends Controller
{
    public function index(Request $request)
    {
        $conflicts = Conflict::with(['contract.task', 'raisedByUser', 'againstUser'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.conflicts.index', compact('conflicts'));
    }

    public function show(Conflict $conflict)
    {
        $conflict->load([
            'contract.task',
            'raisedByUser',
            'againstUser',
            'replies'
        ]);

        return view('admin.conflicts.show', compact('conflict'));
    }

   public function update(Request $request, Conflict $conflict)
{
    $data = $request->validate([
        'status' => 'required|in:open,in_review,resolved,rejected',
        'payment_action' => 'nullable|in:release,refund',
        'admin_response' => 'nullable|string',
    ]);

    $conflict->update([
        'status' => $data['status'],
        'admin_response' => $data['admin_response'] ?? null,
    ]);


    /*
    |--------------------------------------------------------------------------
    | Handle Payment Decision Separately
    |--------------------------------------------------------------------------
    */

    if (!empty($data['payment_action'])) {

        $payment = Payment::where('contract_id', $conflict->contract_id)
            ->whereIn('status', ['pending', 'escrow'])
            ->first();


        if ($payment) {

            if ($data['payment_action'] === 'release') {

                $payment->update([
                    'status' => 'released',
                    'released_at' => now(),
                ]);


            } elseif ($data['payment_action'] === 'refund') {

                $payment->update([
                    'status' => 'refunded',
                    'refunded_at' => now(),
                ]);

            }
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Notify Users
    |--------------------------------------------------------------------------
    */

    $users = collect([
        $conflict->raisedByUser,
        $conflict->againstUser,
    ])->filter();


    foreach ($users as $user) {

        Notification::create([
            'user_id' => $user->id,
            'title' => 'Conflict Updated',
            'message' => "Conflict status is now {$conflict->status}.",
        ]);

    }


    return back()->with('success', 'Conflict resolution updated.');
}


    public function reply(Request $request, Conflict $conflict)
    {
        $data = $request->validate([
            'message' => 'required|string',
        ]);

        ConflictReply::create([
            'conflict_id' => $conflict->id,
            'user_id' => $request->user()->id,
            'message' => $data['message'],
        ]);

        // Notify the other users about the new reply
        $users = collect([
            $conflict->raisedByUser,
            $conflict->againstUser,
        ])->filter();

        foreach ($users as $user) {
            if ($user->id != $request->user()->id) {
                Notification::create([
                    'user_id' => $user->id,
                    'title' => 'New Conflict Reply',
                    'message' => 'A new reply has been added to the conflict.',
                ]);
            }
        }

        return back()->with('success', 'Reply posted.');
    }
}

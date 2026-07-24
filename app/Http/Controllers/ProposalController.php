<?php

namespace App\Http\Controllers;
use App\Models\NotificationModel;
use App\Models\ProposalModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProposalController extends Controller
{
    // Get all proposals
    public function index()
    {
        $proposals = ProposalModel::with([
            'task',
            'user'
        ])->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $proposals
        ]);
    }

    // Create proposal
public function store(Request $request)
{
    $validator = Validator::make($request->all(), [

        'task_id'      => 'required|exists:tasks,id',
        'user_id'      => 'required|exists:users,id',
        'description'  => 'required|string',
        'takes_time'   => 'sometimes|required|numeric|min:0',
        'achievement'  => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',

    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors'  => $validator->errors()
        ], 422);
    }

    $achievementPath = null;

    if ($request->hasFile('achievement')) {
        $achievementPath = $request
            ->file('achievement')
            ->store('achievements', 'public');
    }

    // Create proposal
    $proposal = ProposalModel::create([

        'task_id'      => $request->task_id,
        'user_id'      => $request->user_id,
        'description'  => $request->description,
        'takes_time'   => $request->takes_time,
        'achievement'  => $achievementPath,
        'status'       => 'pending',

    ]);

    // Load relationships
    $proposal->load('task', 'user');

    // ==========================
    // Create notification
    // ==========================
    \App\Models\NotificationModel::create([
        'user_id' => $proposal->task->user_id,
        'title' => 'New Proposal Received',
        'message' => $proposal->user->name .
            ' has submitted a proposal for your task "' .
            $proposal->task->title . '".'
    ]);

    // ==========================
    // Send FCM Notification (Optional)
    // ==========================
    try {

        $client = $proposal->task->user;

        if (!empty($client->fcm_token)) {

            app(\App\Services\FCMService::class)->sendNotification(
                $client->fcm_token,
                'New Proposal Received',
                $proposal->user->name . ' has submitted a proposal for your task.'
            );

        }

    } catch (\Exception $e) {

        \Log::error('FCM Error: ' . $e->getMessage());

    }

    return response()->json([
        'success' => true,
        'message' => 'Proposal submitted successfully.',
        'data'    => $proposal->fresh()->load('task', 'user')
    ], 201);
}
    // Show proposal
    public function show($id)
    {
        $proposal = ProposalModel::with([
            'task',
            'user'
        ])->find($id);

        if (!$proposal) {
            return response()->json([
                'success' => false,
                'message' => 'Proposal not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $proposal
        ]);
    }

    // Update proposal
public function update(Request $request, $id)
{
    $proposal = ProposalModel::find($id);

    if (!$proposal) {
        return response()->json([
            'success' => false,
            'message' => 'Proposal not found'
        ], 404);
    }

    $validator = Validator::make($request->all(), [

        'description' => 'sometimes|required|string',
        'takes_time'  => 'sometimes|required|numeric|min:0',
        'status'      => 'sometimes|required|string',
        'achievement' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',

    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors'  => $validator->errors()
        ], 422);
    }

    $data = $request->only([
        'description',
        'takes_time',
        'status'
    ]);

    // Update achievement file
    if ($request->hasFile('achievement')) {

        // Delete old file
        if (
            $proposal->achievement &&
            Storage::disk('public')->exists($proposal->achievement)
        ) {
            Storage::disk('public')->delete($proposal->achievement);
        }

        // Store new file
        $data['achievement'] = $request
            ->file('achievement')
            ->store('achievements', 'public');
    }

    // Update proposal
    $proposal->update($data);

    // Reload relationships
    $proposal->load('task', 'user');

    // ======================================
    // Notification when proposal is accepted
    // ======================================
    if ($request->filled('status') && $request->status == 'accepted') {

        // Save notification to database
        NotificationModel::create([
            'user_id' => $proposal->user_id,
            'title'   => 'Proposal Accepted',
            'message' => 'Congratulations! Your proposal for "' .
                $proposal->task->title .
                '" has been accepted by the client.'
        ]);

        // Send FCM Push Notification
        try {

            $freelancer = $proposal->user;

            if (!empty($freelancer->fcm_token)) {

                app(\App\Services\FCMService::class)->sendNotification(
                    $freelancer->fcm_token,
                    'Proposal Accepted',
                    'Congratulations! Your proposal has been accepted by the client.'
                );

            }

        } catch (\Exception $e) {

            Log::error('FCM Error (Accepted): ' . $e->getMessage());

        }
    }

    // ======================================
    // Notification when proposal is rejected
    // ======================================
    if ($request->filled('status') && $request->status == 'rejected') {

        // Save notification to database
        NotificationModel::create([
            'user_id' => $proposal->user_id,
            'title'   => 'Proposal Rejected',
            'message' => 'Your proposal for "' .
                $proposal->task->title .
                '" has been rejected by the client.'
        ]);

        // Send FCM Push Notification
        try {

            $freelancer = $proposal->user;

            if (!empty($freelancer->fcm_token)) {

                app(\App\Services\FCMService::class)->sendNotification(
                    $freelancer->fcm_token,
                    'Proposal Rejected',
                    'Your proposal has been rejected by the client.'
                );

            }

        } catch (\Exception $e) {

            Log::error('FCM Error (Rejected): ' . $e->getMessage());

        }
    }

    return response()->json([
        'success' => true,
        'message' => 'Proposal updated successfully.',
        'data'    => $proposal->fresh()->load('task', 'user')
    ]);
}

    // Delete proposal
    public function destroy($id)
    {
        $proposal = ProposalModel::find($id);

        if (!$proposal) {
            return response()->json([
                'success' => false,
                'message' => 'Proposal not found'
            ], 404);
        }

        if (
            $proposal->achievement &&
            Storage::disk('public')->exists($proposal->achievement)
        ) {
            Storage::disk('public')->delete($proposal->achievement);
        }

        $proposal->delete();

        return response()->json([
            'success' => true,
            'message' => 'Proposal deleted successfully.'
        ]);
    }
}
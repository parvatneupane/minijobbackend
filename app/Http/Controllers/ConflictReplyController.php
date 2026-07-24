<?php

namespace App\Http\Controllers;

use App\Models\ConflictModel;
use App\Models\ConflictReplyModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ConflictReplyController extends Controller
{
    /**
     * Get all replies
     */
    public function index()
    {
        $replies = ConflictReplyModel::with([
            'user',
            'conflict'
        ])->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $replies
        ]);
    }

    /**
     * Store reply
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'conflict_id' => 'required|exists:conflicts,id',

            'user_id' => 'required|exists:users,id',

            'message' => 'required|string',

            'attachment' => 'nullable|file|max:10240'

        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);

        }

        $attachment = null;

        if ($request->hasFile('attachment')) {

            $attachment = $request->file('attachment')
                ->store('conflict_replies', 'public');

        }

        $reply = ConflictReplyModel::create([

            'conflict_id' => $request->conflict_id,

            'user_id' => $request->user_id,

            'message' => $request->message,

            'attachment' => $attachment

        ]);

        $reply->load([
            'user',
            'conflict.contract'
        ]);

        // ----------------------------------------------------
        // Send Notification
        // ----------------------------------------------------

        $conflict = $reply->conflict;

        $contract = $conflict->contract;

        if ($contract) {

            // Client replied
            if ($request->user_id == $contract->client_id) {

                app(NotificationController::class)
                    ->sendNotificationToUser(

                        $contract->freelancer_id,

                        'Conflict Reply',

                        'The client has replied to your conflict.'

                    );

            }

            // Freelancer replied
            elseif ($request->user_id == $contract->freelancer_id) {

                app(NotificationController::class)
                    ->sendNotificationToUser(

                        $contract->client_id,

                        'Conflict Reply',

                        'The freelancer has replied to your conflict.'

                    );

            }

        }

        return response()->json([

            'success' => true,

            'message' => 'Reply sent successfully.',

            'data' => $reply

        ], 201);
    }

    /**
     * Show single reply
     */
    public function show($id)
    {
        $reply = ConflictReplyModel::with([
            'user',
            'conflict'
        ])->find($id);

        if (!$reply) {

            return response()->json([
                'success' => false,
                'message' => 'Reply not found.'
            ], 404);

        }

        return response()->json([
            'success' => true,
            'data' => $reply
        ]);
    }

    /**
     * Update reply
     */
    public function update(Request $request, $id)
    {
        $reply = ConflictReplyModel::find($id);

        if (!$reply) {

            return response()->json([
                'success' => false,
                'message' => 'Reply not found.'
            ], 404);

        }

        $validator = Validator::make($request->all(), [

            'message' => 'required|string'

        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);

        }

        $reply->update([

            'message' => $request->message

        ]);

        return response()->json([

            'success' => true,

            'message' => 'Reply updated successfully.',

            'data' => $reply

        ]);
    }

    /**
     * Delete reply
     */
    public function destroy($id)
    {
        $reply = ConflictReplyModel::find($id);

        if (!$reply) {

            return response()->json([
                'success' => false,
                'message' => 'Reply not found.'
            ], 404);

        }

        if ($reply->attachment) {

            Storage::disk('public')
                ->delete($reply->attachment);

        }

        $reply->delete();

        return response()->json([

            'success' => true,

            'message' => 'Reply deleted successfully.'

        ]);
    }

    /**
     * Get replies of one conflict
     */
    public function conflictReplies($conflictId)
    {
        $replies = ConflictReplyModel::with('user')
            ->where('conflict_id', $conflictId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([

            'success' => true,

            'data' => $replies

        ]);
    }
}
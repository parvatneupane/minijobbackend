<?php

namespace App\Http\Controllers;

use App\Models\ConflictModel;
use App\Models\ContractModel;
use App\Models\NotificationModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ConflictController extends Controller
{
    /**
     * Get all conflicts
     */
    public function index()
    {
        $conflicts = ConflictModel::with([
            'contract.task',
            'raisedByUser',
            'againstUser'
        ])->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $conflicts
        ]);
    }

    /**
     * Create Conflict
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'contract_id' => 'required|exists:contracts,id',

            'raised_by' => 'required|exists:users,id',

            'title' => 'required|string|max:255',

            'reason' => 'required|string',

            'attachment' => 'nullable|file|max:10240'

        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);

        }

        $contract = ContractModel::find($request->contract_id);

        if (!$contract) {
            return response()->json([
                'success' => false,
                'message' => 'Contract not found.'
            ], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | Determine who raised the conflict
        |--------------------------------------------------------------------------
        */

        if ($contract->client_id == $request->raised_by) {

            $raisedRole = 'client';
            $againstUser = $contract->freelancer_id;

        } elseif ($contract->freelancer_id == $request->raised_by) {

            $raisedRole = 'freelancer';
            $againstUser = $contract->client_id;

        } else {

            return response()->json([
                'success' => false,
                'message' => 'This user does not belong to this contract.'
            ], 403);

        }

        /*
        |--------------------------------------------------------------------------
        | Upload attachment
        |--------------------------------------------------------------------------
        */

        $attachment = null;

        if ($request->hasFile('attachment')) {

            $attachment = $request->file('attachment')
                ->store('conflicts', 'public');

        }

        /*
        |--------------------------------------------------------------------------
        | Save Conflict
        |--------------------------------------------------------------------------
        */


    $conflict = ConflictModel::create([
        'contract_id' => $contract->id,
        'raised_by' => $request->raised_by,
        'against_user' => $againstUser,
        'raised_by_role' => $raisedRole,
        'title' => $request->title,
        'reason' => $request->reason,
        'attachment' => $attachment,
        'status' => 'open'
    ]);


    if ($contract && $contract->task) {
        $contract->task->update(['status' => 'under_review']);
    }


        /*
        |--------------------------------------------------------------------------
        | Notification
        |--------------------------------------------------------------------------
        */

        $sender = UserModel::find($request->raised_by);

        NotificationModel::create([

            'user_id' => $againstUser,

            'title' => 'New Conflict Raised',

            'message' => $sender->name .
                ' has raised a conflict regarding your contract.'

        ]);

        /*
        |--------------------------------------------------------------------------
        | FCM Push
        |--------------------------------------------------------------------------
        */

        try {

            $receiver = UserModel::find($againstUser);

            if (!empty($receiver->fcm_token)) {

                app(\App\Services\FCMService::class)
                    ->sendNotification(

                        $receiver->fcm_token,

                        'New Conflict',

                        $sender->name .
                        ' has raised a conflict regarding your contract.'

                    );

            }

        } catch (\Exception $e) {

            Log::error($e->getMessage());

        }

        return response()->json([

            'success' => true,

            'message' => 'Conflict submitted successfully.',

            'data' => $conflict->load([
                'contract.task',
                'raisedByUser',
                'againstUser'
            ])

        ], 201);
    }

    /**
     * Show Single Conflict
     */
    public function show($id)
    {
        $conflict = ConflictModel::with([
            'contract.task',
            'raisedByUser',
            'againstUser'
        ])->find($id);

        if (!$conflict) {

            return response()->json([
                'success' => false,
                'message' => 'Conflict not found.'
            ], 404);

        }

        return response()->json([
            'success' => true,
            'data' => $conflict
        ]);
    }

    /**
 * Update Conflict (Admin)
 */
public function update(Request $request, $id)
{
    $conflict = ConflictModel::find($id);

    if (!$conflict) {
        return response()->json([
            'success' => false,
            'message' => 'Conflict not found.'
        ], 404);
    }

    $validator = Validator::make($request->all(), [

        'status' => 'sometimes|in:open,in_review,resolved,rejected',

        'admin_response' => 'nullable|string',

        'attachment' => 'nullable|file|max:10240'

    ]);

    if ($validator->fails()) {

        return response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422);

    }

    if ($request->hasFile('attachment')) {

        if (
            $conflict->attachment &&
            Storage::disk('public')->exists($conflict->attachment)
        ) {
            Storage::disk('public')->delete($conflict->attachment);
        }

        $conflict->attachment = $request
            ->file('attachment')
            ->store('conflicts', 'public');
    }

    if ($request->filled('status')) {
        $conflict->status = $request->status;
    }

    if ($request->filled('admin_response')) {
        $conflict->admin_response = $request->admin_response;
    }

    $conflict->save();

    /*
    |--------------------------------------------------------------------------
    | Notify both users
    |--------------------------------------------------------------------------
    */

    $title = "Conflict Updated";

    $message = "The conflict status has been updated to: " .
        ucfirst($conflict->status);

    NotificationModel::create([
        'user_id' => $conflict->raised_by,
        'title' => $title,
        'message' => $message
    ]);

    NotificationModel::create([
        'user_id' => $conflict->against_user,
        'title' => $title,
        'message' => $message
    ]);

    try {

        $user1 = UserModel::find($conflict->raised_by);

        if ($user1 && !empty($user1->fcm_token)) {

            app(\App\Services\FCMService::class)
                ->sendNotification(
                    $user1->fcm_token,
                    $title,
                    $message
                );

        }

        $user2 = UserModel::find($conflict->against_user);

        if ($user2 && !empty($user2->fcm_token)) {

            app(\App\Services\FCMService::class)
                ->sendNotification(
                    $user2->fcm_token,
                    $title,
                    $message
                );

        }

    } catch (\Exception $e) {

        Log::error($e->getMessage());

    }

    return response()->json([
        'success' => true,
        'message' => 'Conflict updated successfully.',
        'data' => $conflict
    ]);
}

/**
 * Delete Conflict
 */
public function destroy($id)
{
    $conflict = ConflictModel::find($id);

    if (!$conflict) {

        return response()->json([
            'success' => false,
            'message' => 'Conflict not found.'
        ], 404);

    }

    if (
        $conflict->attachment &&
        Storage::disk('public')->exists($conflict->attachment)
    ) {
        Storage::disk('public')->delete($conflict->attachment);
    }

    $conflict->delete();

    return response()->json([
        'success' => true,
        'message' => 'Conflict deleted successfully.'
    ]);
}

/**
 * Get conflicts of a user
 */
public function myConflicts($userId)
{
    $conflicts = ConflictModel::with([
        'contract.task',
        'raisedByUser',
        'againstUser'
    ])
    ->where('raised_by', $userId)
    ->orWhere('against_user', $userId)
    ->latest()
    ->get();

    return response()->json([
        'success' => true,
        'data' => $conflicts
    ]);
}
}
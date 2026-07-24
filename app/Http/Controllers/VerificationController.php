<?php

namespace App\Http\Controllers;
use App\Models\NotificationModel;
use App\Models\UserModel;
use App\Services\FCMService;
use Illuminate\Support\Facades\Log;
use App\Models\VerificationModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class VerificationController extends Controller
{
    // Get all verifications
    public function index()
    {
        $verifications = VerificationModel::with('user')->get();

        return response()->json([
            'success' => true,
            'message' => 'Verifications fetched successfully',
            'data' => $verifications
        ]);
    }

    // Create verification
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'user_id' => 'required|exists:users,id|unique:verifications,user_id',

            'full_name' => 'required|string|max:255',

            'citizenship_front' => 'required|image|mimes:jpg,jpeg,png|max:4096',

            'citizenship_back' => 'required|image|mimes:jpg,jpeg,png|max:4096',

            'pan_card' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',

        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);

        }

        $frontPath = $request->file('citizenship_front')
            ->store('verifications', 'public');

        $backPath = $request->file('citizenship_back')
            ->store('verifications', 'public');

        $panPath = null;

        if ($request->hasFile('pan_card')) {

            $panPath = $request->file('pan_card')
                ->store('verifications', 'public');

        }

        $verification = VerificationModel::create([

            'user_id' => $request->user_id,

            'full_name' => $request->full_name,

            'citizenship_front' => $frontPath,

            'citizenship_back' => $backPath,

            'pan_card' => $panPath,

            'status' => 'pending'

        ]);

        return response()->json([

            'success' => true,

            'message' => 'Verification submitted successfully',

            'data' => $verification

        ], 201);
    }

    // Get single verification
public function show($userId)
{
    $verification = VerificationModel::where('user_id', $userId)->first();

    if (!$verification) {
        return response()->json([
            'success' => false,
            'message' => 'Verification not found'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'data' => [
            'id' => $verification->id,
            'user_id' => $verification->user_id,
            'full_name' => $verification->full_name,

            'citizenship_front' => asset('storage/' . $verification->citizenship_front),
            'citizenship_back' => asset('storage/' . $verification->citizenship_back),
            'pan_card' => asset('storage/' . $verification->pan_card),

            'status' => $verification->status,
            'remarks' => $verification->remarks,
            'verified_at' => $verification->verified_at,
            'created_at' => $verification->created_at,
            'updated_at' => $verification->updated_at,
        ]
    ]);
}

    // Update verification
public function update(Request $request, $id)
{
    $verification = VerificationModel::find($id);

    if (!$verification) {
        return response()->json([
            'success' => false,
            'message' => 'Verification not found'
        ], 404);
    }

    // Store old status
    $oldStatus = $verification->status;

    $validator = Validator::make($request->all(), [

        'full_name' => 'nullable|string|max:255',

        'citizenship_front' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',

        'citizenship_back' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',

        'pan_card' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',

        'status' => 'nullable|in:pending,approved,rejected',

        'remarks' => 'nullable|string',

    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422);
    }

    if ($request->filled('full_name')) {
        $verification->full_name = $request->full_name;
    }

    if ($request->hasFile('citizenship_front')) {

        if ($verification->citizenship_front) {
            Storage::disk('public')->delete($verification->citizenship_front);
        }

        $verification->citizenship_front = $request->file('citizenship_front')
            ->store('verifications', 'public');
    }

    if ($request->hasFile('citizenship_back')) {

        if ($verification->citizenship_back) {
            Storage::disk('public')->delete($verification->citizenship_back);
        }

        $verification->citizenship_back = $request->file('citizenship_back')
            ->store('verifications', 'public');
    }

    if ($request->hasFile('pan_card')) {

        if ($verification->pan_card) {
            Storage::disk('public')->delete($verification->pan_card);
        }

        $verification->pan_card = $request->file('pan_card')
            ->store('verifications', 'public');
    }

    if ($request->filled('status')) {

        $verification->status = $request->status;

        if ($request->status == 'approved') {
            $verification->verified_at = now();
        } else {
            $verification->verified_at = null;
        }
    }

    if ($request->filled('remarks')) {
        $verification->remarks = $request->remarks;
    }

    $verification->save();

    /*
    |--------------------------------------------------------------------------
    | Send Notification
    |--------------------------------------------------------------------------
    */
    if ($oldStatus != $verification->status) {

        $title = '';
        $message = '';

        if ($verification->status == 'approved') {

            $title = 'Verification Approved';
            $message = 'Congratulations! Your identity verification has been approved.';

        } elseif ($verification->status == 'rejected') {

            $title = 'Verification Rejected';
            $message = 'Your identity verification has been rejected. Please check the remarks and submit again.';

        }

        if ($title != '') {

            // Save notification to database
            NotificationModel::create([
                'user_id' => $verification->user_id,
                'title' => $title,
                'message' => $message
            ]);

            // Send Push Notification
            try {

                $user = UserModel::find($verification->user_id);

                if ($user && !empty($user->fcm_token)) {

                    app(FCMService::class)->sendNotification(
                        $user->fcm_token,
                        $title,
                        $message
                    );

                }

            } catch (\Exception $e) {

                Log::error('Verification Notification Error: ' . $e->getMessage());

            }
        }
    }

    return response()->json([
        'success' => true,
        'message' => 'Verification updated successfully',
        'data' => $verification
    ]);
}
    // Delete verification
    public function destroy($id)
    {
        $verification = VerificationModel::find($id);

        if (!$verification) {

            return response()->json([
                'success' => false,
                'message' => 'Verification not found'
            ], 404);

        }

        if ($verification->citizenship_front) {
            Storage::disk('public')->delete($verification->citizenship_front);
        }

        if ($verification->citizenship_back) {
            Storage::disk('public')->delete($verification->citizenship_back);
        }

        if ($verification->pan_card) {
            Storage::disk('public')->delete($verification->pan_card);
        }

        $verification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Verification deleted successfully'
        ]);
    }
}
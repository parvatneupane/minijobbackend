<?php

namespace App\Http\Controllers;

use App\Models\SubmissionModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\NotificationModel;
use App\Models\UserModel;
use App\Services\FCMService;
use Illuminate\Support\Facades\Log;

class SubmissionController extends Controller
{
    // Get all submissions
    public function index()
    {
        $submissions = SubmissionModel::with([
            'contract',
            'freelancer'
        ])->latest()->get();

        return response()->json([
            'success'=>true,
            'data'=>$submissions
        ]);
    }

    // Store submission
public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'contract_id' => 'required|exists:contracts,id',
        'freelancer_id' => 'required|exists:users,id',
        'message' => 'nullable|string',
        'attachment' => 'required|file|mimes:zip,pdf,doc,docx,jpg,jpeg,png,apk|max:51200'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422);
    }

    $path = $request->file('attachment')
        ->store('submissions', 'public');

    $submission = SubmissionModel::create([
        'contract_id' => $request->contract_id,
        'freelancer_id' => $request->freelancer_id,
        'message' => $request->message,
        'attachment' => $path,
        'status' => 'submitted',
        'submitted_at' => now()
    ]);

    // Load relationships
    $submission->load([
        'contract.client',
        'freelancer'
    ]);

    // ===========================
    // Save Notification
    // ===========================
    NotificationModel::create([
        'user_id' => $submission->contract->client_id,
        'title' => 'Work Submitted',
        'message' => $submission->freelancer->name .
            ' has submitted the completed work for your project.'
    ]);

    // ===========================
    // Send Push Notification
    // ===========================
    try {

        $client = UserModel::find($submission->contract->client_id);

        if ($client && !empty($client->fcm_token)) {

            app(FCMService::class)->sendNotification(
                $client->fcm_token,
                'Work Submitted',
                $submission->freelancer->name .
                ' has submitted the completed work for your project.'
            );

        }

    } catch (\Exception $e) {

        Log::error('Submission Notification Error: ' . $e->getMessage());

    }

    return response()->json([
        'success' => true,
        'message' => 'Work submitted successfully.',
        'data' => $submission
    ], 201);
}

    // Show single submission
    public function show($id)
    {
        $submission=SubmissionModel::with([
            'contract',
            'freelancer'
        ])->find($id);

        if(!$submission){
            return response()->json([
                'success'=>false,
                'message'=>'Submission not found.'
            ],404);
        }

        return response()->json([
            'success'=>true,
            'data'=>$submission
        ]);
    }

    // Client update (approve/revision)
    public function update(Request $request,$id)
    {
        $submission=SubmissionModel::find($id);

        if(!$submission){
            return response()->json([
                'success'=>false,
                'message'=>'Submission not found.'
            ],404);
        }

        $validator=Validator::make($request->all(),[
            'status'=>'required|in:submitted,revision_requested,approved',
            'client_feedback'=>'nullable|string'
        ]);

        if($validator->fails()){
            return response()->json([
                'success'=>false,
                'errors'=>$validator->errors()
            ],422);
        }

        $submission->status=$request->status;
        $submission->client_feedback=$request->client_feedback;

        if($request->status=="approved"){
            $submission->approved_at=now();
        }

        $submission->save();

        return response()->json([
            'success'=>true,
            'message'=>'Submission updated successfully.',
            'data'=>$submission
        ]);
    }

    // Delete
    public function destroy($id)
    {
        $submission=SubmissionModel::find($id);

        if(!$submission){
            return response()->json([
                'success'=>false,
                'message'=>'Submission not found.'
            ],404);
        }

        if(Storage::disk('public')->exists($submission->attachment)){
            Storage::disk('public')->delete($submission->attachment);
        }

        $submission->delete();

        return response()->json([
            'success'=>true,
            'message'=>'Submission deleted successfully.'
        ]);
    }
}
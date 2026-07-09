<?php

namespace App\Http\Controllers;

use App\Models\SubmissionModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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
        $validator = Validator::make($request->all(),[
            'contract_id'=>'required|exists:contracts,id',
            'freelancer_id'=>'required|exists:users,id',
            'message'=>'nullable|string',
            'attachment'=>'required|file|mimes:zip,pdf,doc,docx,jpg,jpeg,png,apk|max:51200'
        ]);

        if($validator->fails()){
            return response()->json([
                'success'=>false,
                'errors'=>$validator->errors()
            ],422);
        }

        $file=$request->file('attachment');

        $path=$file->store('submissions','public');

        $submission=SubmissionModel::create([
            'contract_id'=>$request->contract_id,
            'freelancer_id'=>$request->freelancer_id,
            'message'=>$request->message,
            'attachment'=>$path,
            'status'=>'submitted',
            'submitted_at'=>now()
        ]);

        return response()->json([
            'success'=>true,
            'message'=>'Work submitted successfully.',
            'data'=>$submission
        ],201);
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
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SubmissionModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubmissionController extends Controller
{
    // Get all submissions
    public function index()
    {
        $submissions = SubmissionModel::with([
            'milestone',
            'freelancer'
        ])->get();

        return response()->json([
            'success' => true,
            'data' => $submissions
        ]);
    }

    // Create submission
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'milestone_id' =>
                'required|exists:milestones,id',

            'free_lancer_id' =>
                'required|exists:free_lancer_profiles,id',

            'submission_file' =>
                'required|string',

            'submission_description' =>
                'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $submission = SubmissionModel::create([
            'milestone_id' => $request->milestone_id,
            'free_lancer_id' => $request->free_lancer_id,
            'submission_file' => $request->submission_file,
            'submission_description' =>
                $request->submission_description,
            'status' => 'pending'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Submission created successfully',
            'data' => $submission
        ], 201);
    }

    // Get single submission
    public function show($id)
    {
        $submission = SubmissionModel::with([
            'milestone',
            'freelancer'
        ])->find($id);

        if (!$submission) {
            return response()->json([
                'success' => false,
                'message' => 'Submission not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $submission
        ]);
    }

    // Update submission
    public function update(Request $request, $id)
    {
        $submission = SubmissionModel::find($id);

        if (!$submission) {
            return response()->json([
                'success' => false,
                'message' => 'Submission not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [

            'submission_file' =>
                'sometimes|string',

            'submission_description' =>
                'sometimes|string',

            'status' =>
                'sometimes|in:pending,approved,rejected'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $submission->update(
            $request->only([
                'submission_file',
                'submission_description',
                'status'
            ])
        );

        return response()->json([
            'success' => true,
            'message' => 'Submission updated successfully',
            'data' => $submission
        ]);
    }

    // Delete submission
    public function destroy($id)
    {
        $submission = SubmissionModel::find($id);

        if (!$submission) {
            return response()->json([
                'success' => false,
                'message' => 'Submission not found'
            ], 404);
        }

        $submission->delete();

        return response()->json([
            'success' => true,
            'message' => 'Submission deleted successfully'
        ]);
    }
}
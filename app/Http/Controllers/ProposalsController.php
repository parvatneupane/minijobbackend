<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ProposalModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProposalController extends Controller
{
    // Get all proposals
    public function index()
    {
        $proposals = ProposalModel::with([
            'task',
            'freelancer'
        ])->get();

        return response()->json([
            'success' => true,
            'data' => $proposals
        ]);
    }

    // Create proposal
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'task_id' =>
                'required|exists:tasks,id',

            'free_lancer_id' =>
                'required|exists:free_lancer_profiles,id',

            'description' =>
                'required|string',

            'proposal_cost' =>
                'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $proposal = ProposalModel::create([
            'task_id' => $request->task_id,
            'free_lancer_id' => $request->free_lancer_id,
            'description' => $request->description,
            'proposal_cost' => $request->proposal_cost,
            'status' => 'pending'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Proposal submitted successfully',
            'data' => $proposal
        ], 201);
    }

    // Get single proposal
    public function show($id)
    {
        $proposal = ProposalModel::with([
            'task',
            'freelancer'
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

            'description' =>
                'sometimes|string',

            'proposal_cost' =>
                'sometimes|integer',

            'status' =>
                'sometimes|in:pending,accepted,rejected'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $proposal->update(
            $request->only([
                'description',
                'proposal_cost',
                'status'
            ])
        );

        return response()->json([
            'success' => true,
            'message' => 'Proposal updated successfully',
            'data' => $proposal
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

        $proposal->delete();

        return response()->json([
            'success' => true,
            'message' => 'Proposal deleted successfully'
        ]);
    }
}
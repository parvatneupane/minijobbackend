<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MilestoneModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MilestoneController extends Controller
{
    // Get all milestones
    public function index()
    {
        $milestones = MilestoneModel::with(
            'contract'
        )->get();

        return response()->json([
            'success' => true,
            'data' => $milestones
        ]);
    }

    // Create milestone
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'contract_id' =>
                'required|exists:contracts,id',

            'milestone_name' =>
                'required|string|max:255',

            'milestone_cost' =>
                'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $milestone = MilestoneModel::create([
            'contract_id' => $request->contract_id,
            'milestone_name' => $request->milestone_name,
            'milestone_cost' => $request->milestone_cost,
            'status' => 'pending'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Milestone created successfully',
            'data' => $milestone
        ], 201);
    }

    // Get single milestone
    public function show($id)
    {
        $milestone = MilestoneModel::with(
            'contract'
        )->find($id);

        if (!$milestone) {
            return response()->json([
                'success' => false,
                'message' => 'Milestone not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $milestone
        ]);
    }

    // Update milestone
    public function update(Request $request, $id)
    {
        $milestone = MilestoneModel::find($id);

        if (!$milestone) {
            return response()->json([
                'success' => false,
                'message' => 'Milestone not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [

            'milestone_name' =>
                'sometimes|string|max:255',

            'milestone_cost' =>
                'sometimes|numeric',

            'status' =>
                'sometimes|in:pending,in_progress,completed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $milestone->update(
            $request->only([
                'milestone_name',
                'milestone_cost',
                'status'
            ])
        );

        return response()->json([
            'success' => true,
            'message' => 'Milestone updated successfully',
            'data' => $milestone
        ]);
    }

    // Delete milestone
    public function destroy($id)
    {
        $milestone = MilestoneModel::find($id);

        if (!$milestone) {
            return response()->json([
                'success' => false,
                'message' => 'Milestone not found'
            ], 404);
        }

        $milestone->delete();

        return response()->json([
            'success' => true,
            'message' => 'Milestone deleted successfully'
        ]);
    }
}
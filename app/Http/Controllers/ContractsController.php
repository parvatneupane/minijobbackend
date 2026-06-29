<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ContractModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContractController extends Controller
{
    // Get all contracts
    public function index()
    {
        $contracts = ContractModel::with('proposal')->get();

        return response()->json([
            'success' => true,
            'data' => $contracts
        ]);
    }

    // Create contract
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'proposal_id' =>
                'required|exists:proposals,id',

            'start_date' =>
                'required|date',

            'end_date' =>
                'nullable|date',

            'total_payment' =>
                'required|numeric',

            'agreement_file' =>
                'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $contract = ContractModel::create([
            'proposal_id' => $request->proposal_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_payment' => $request->total_payment,
            'agreement_file' => $request->agreement_file,
            'status' => 'active'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Contract created successfully',
            'data' => $contract
        ], 201);
    }

    // Get single contract
    public function show($id)
    {
        $contract = ContractModel::with('proposal')
            ->find($id);

        if (!$contract) {
            return response()->json([
                'success' => false,
                'message' => 'Contract not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $contract
        ]);
    }

    // Update contract
    public function update(Request $request, $id)
    {
        $contract = ContractModel::find($id);

        if (!$contract) {
            return response()->json([
                'success' => false,
                'message' => 'Contract not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [

            'start_date' =>
                'sometimes|date',

            'end_date' =>
                'nullable|date',

            'total_payment' =>
                'sometimes|numeric',

            'agreement_file' =>
                'nullable|string',

            'status' =>
                'sometimes|in:active,completed,terminated'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $contract->update(
            $request->only([
                'start_date',
                'end_date',
                'total_payment',
                'agreement_file',
                'status'
            ])
        );

        return response()->json([
            'success' => true,
            'message' => 'Contract updated successfully',
            'data' => $contract
        ]);
    }

    // Delete contract
    public function destroy($id)
    {
        $contract = ContractModel::find($id);

        if (!$contract) {
            return response()->json([
                'success' => false,
                'message' => 'Contract not found'
            ], 404);
        }

        $contract->delete();

        return response()->json([
            'success' => true,
            'message' => 'Contract deleted successfully'
        ]);
    }
}
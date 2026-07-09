<?php

namespace App\Http\Controllers;

use App\Models\ContractModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContractController extends Controller
{
    /**
     * Display all contracts
     */
    public function index()
    {
        $contracts = ContractModel::with([
            'task',
            'proposal',
            'client',
            'freelancer'
        ])->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Contracts fetched successfully.',
            'data' => $contracts
        ]);
    }

    /**
     * Store contract
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'task_id' => 'required|exists:tasks,id',

            'proposal_id' => 'required|exists:proposals,id',

            'client_id' => 'required|exists:users,id',

            'freelancer_id' => 'required|exists:users,id',

            'start_date' => 'required|date',

            'deadline' => 'required|date|after_or_equal:start_date'

        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ],422);

        }

        // prevent duplicate contract for same proposal

        $already = ContractModel::where(
            'proposal_id',
            $request->proposal_id
        )->first();

        if($already){

            return response()->json([
                'success'=>false,
                'message'=>'Contract already exists for this proposal.'
            ],409);

        }

        $contract = ContractModel::create([

            'task_id'=>$request->task_id,

            'proposal_id'=>$request->proposal_id,

            'client_id'=>$request->client_id,

            'freelancer_id'=>$request->freelancer_id,

            'start_date'=>$request->start_date,

            'deadline'=>$request->deadline,

            'status'=>'active'

        ]);

        return response()->json([

            'success'=>true,

            'message'=>'Contract created successfully.',

            'data'=>$contract->load([
                'task',
                'proposal',
                'client',
                'freelancer'
            ])

        ],201);

    }

    /**
     * Show single contract
     */
    public function show($id)
    {
        $contract = ContractModel::with([
            'task',
            'proposal',
            'client',
            'freelancer'
        ])->find($id);

        if(!$contract){

            return response()->json([
                'success'=>false,
                'message'=>'Contract not found.'
            ],404);

        }

        return response()->json([
            'success'=>true,
            'data'=>$contract
        ]);
    }

    /**
     * Update contract
     */
    public function update(Request $request,$id)
    {
        $contract = ContractModel::find($id);

        if(!$contract){

            return response()->json([
                'success'=>false,
                'message'=>'Contract not found.'
            ],404);

        }

        $validator = Validator::make($request->all(),[

            'start_date'=>'sometimes|date',

            'deadline'=>'sometimes|date',

            'status'=>'sometimes|in:active,completed,cancelled'

        ]);

        if($validator->fails()){

            return response()->json([
                'success'=>false,
                'errors'=>$validator->errors()
            ],422);

        }

        $contract->update(

            $request->only([

                'start_date',

                'deadline',

                'status'

            ])

        );

        return response()->json([

            'success'=>true,

            'message'=>'Contract updated successfully.',

            'data'=>$contract->load([
                'task',
                'proposal',
                'client',
                'freelancer'
            ])

        ]);

    }

    /**
     * Delete contract
     */
    public function destroy($id)
    {
        $contract = ContractModel::find($id);

        if(!$contract){

            return response()->json([
                'success'=>false,
                'message'=>'Contract not found.'
            ],404);

        }

        $contract->delete();

        return response()->json([
            'success'=>true,
            'message'=>'Contract deleted successfully.'
        ]);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\ProposalModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProposalController extends Controller
{
    // Get all proposals
    public function index()
    {
        $proposals = ProposalModel::with([
            'task',
            'user'
        ])->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $proposals
        ]);
    }

    // Create proposal
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'task_id' => 'required|exists:tasks,id',

            'user_id' => 'required|exists:users,id',

            'description' => 'required|string',



            'takes_time' => 'required|string|max:100',

            'achievement' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120'

        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ],422);

        }

        $achievementPath = null;

        if ($request->hasFile('achievements')) {

            $achievementPath = $request
                ->file('achievements')
                ->store('achievements','public');

        }

        $proposal = ProposalModel::create([

            'task_id' => $request->task_id,

            'user_id' => $request->user_id,

            'description' => $request->description,


            'takes_time' => $request->takes_time,

            'achievement' => $achievementPath,

            'status' => 'pending'

        ]);

        return response()->json([
            'success'=>true,
            'message'=>'Proposal submitted successfully.',
            'data'=>$proposal->load('task','user')
        ],201);
    }

    // Show proposal
    public function show($id)
    {
        $proposal = ProposalModel::with([
            'task',
            'user'
        ])->find($id);

        if(!$proposal){

            return response()->json([
                'success'=>false,
                'message'=>'Proposal not found'
            ],404);

        }

        return response()->json([
            'success'=>true,
            'data'=>$proposal
        ]);
    }

    // Update proposal
    public function update(Request $request,$id)
    {
        $proposal = ProposalModel::find($id);

        if(!$proposal){

            return response()->json([
                'success'=>false,
                'message'=>'Proposal not found'
            ],404);

        }

        $validator = Validator::make($request->all(), [

            'description'=>'sometimes|required|string',



            'takes_time'=>'sometimes|required|string|max:100',

            'status'=>'sometimes|required|string',

            'achievement'=>'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120'

        ]);

        if($validator->fails()){

            return response()->json([
                'success'=>false,
                'errors'=>$validator->errors()
            ],422);

        }

        $data = $request->only([
            'description',
            
            'takes_time',
            'status'
        ]);

        if($request->hasFile('achievements')){

            if($proposal->achievements &&
                Storage::disk('public')->exists($proposal->achievements)){

                Storage::disk('public')
                    ->delete($proposal->achievements);

            }

            $data['achievements'] = $request
                ->file('achievements')
                ->store('achievements','public');

        }

        $proposal->update($data);

        return response()->json([
            'success'=>true,
            'message'=>'Proposal updated successfully.',
            'data'=>$proposal->load('task','user')
        ]);
    }

    // Delete proposal
    public function destroy($id)
    {
        $proposal = ProposalModel::find($id);

        if(!$proposal){

            return response()->json([
                'success'=>false,
                'message'=>'Proposal not found'
            ],404);

        }

        if($proposal->achievements &&
            Storage::disk('public')->exists($proposal->achievements)){

            Storage::disk('public')
                ->delete($proposal->achievements);

        }

        $proposal->delete();

        return response()->json([
            'success'=>true,
            'message'=>'Proposal deleted successfully.'
        ]);
    }
}
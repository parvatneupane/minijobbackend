<?php

namespace App\Http\Controllers;

use App\Models\TaskModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    // Get all tasks
    public function index()
    {
        $tasks = TaskModel::with([
            'user',
            'category'
        ])->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $tasks
        ]);
    }

    // Create task
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'user_id' => 'required|exists:users,id',

            'category_id' => 'required|exists:task_categories,id',

            'title' => 'required|string|max:255',

            'description' => 'required|string',

            'deadline' => 'required|date',

            'required_skills' => 'required|string',

            'min_experience' => 'required|string',

            'budget' => 'required|numeric|min:0'

        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ],422);

        }

        $task = TaskModel::create([

            'user_id' => $request->user_id,

            'category_id' => $request->category_id,

            'title' => $request->title,

            'description' => $request->description,

            'deadline' => $request->deadline,

            'required_skills' => $request->required_skills,

            'min_experience' => $request->min_experience,

            'budget' => $request->budget,

            'status' => 'open'

        ]);

        return response()->json([
            'success' => true,
            'message' => 'Task created successfully.',
            'data' => $task
        ],201);
    }

    // Show one task
    public function show($id)
    {
        $task = TaskModel::with([
            'user',
            'category'
        ])->find($id);

        if(!$task){

            return response()->json([
                'success'=>false,
                'message'=>'Task not found'
            ],404);

        }

        return response()->json([
            'success'=>true,
            'data'=>$task
        ]);
    }

    // Update task
    public function update(Request $request,$id)
    {

        $task = TaskModel::find($id);

        if(!$task){

            return response()->json([
                'success'=>false,
                'message'=>'Task not found'
            ],404);

        }

        $validator = Validator::make($request->all(),[

            'category_id'=>'sometimes|exists:task_categories,id',

            'title'=>'sometimes|string|max:255',

            'description'=>'sometimes|string',

            'deadline'=>'sometimes|date',

            'required_skills'=>'sometimes|string',

            'min_experience'=>'sometimes|string',

            'budget'=>'sometimes|numeric|min:0',

            'status'=>'sometimes|in:open,in_progress,completed,cancelled'

        ]);

        if($validator->fails()){

            return response()->json([
                'success'=>false,
                'errors'=>$validator->errors()
            ],422);

        }

        $task->update($request->only([

            'category_id',

            'title',

            'description',

            'deadline',

            'required_skills',

            'min_experience',

            'budget',

            'status'

        ]));

        return response()->json([
            'success'=>true,
            'message'=>'Task updated successfully.',
            'data'=>$task
        ]);

    }

    // Delete task
    public function destroy($id)
    {

        $task = TaskModel::find($id);

        if(!$task){

            return response()->json([
                'success'=>false,
                'message'=>'Task not found'
            ],404);

        }

        $task->delete();

        return response()->json([
            'success'=>true,
            'message'=>'Task deleted successfully.'
        ]);

    }
}
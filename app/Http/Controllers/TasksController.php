<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TaskModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    // Get all tasks
    public function index()
    {
        $tasks = TaskModel::with([
            'client',
            'category'
        ])->get();

        return response()->json([
            'success' => true,
            'data' => $tasks
        ]);
    }

    // Create task
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'client_id' =>
                'required|exists:client_profiles,id',

            'category_id' =>
                'required|exists:task_categories,id',

            'title' =>
                'required|string|max:255',

            'description' =>
                'required|string',

            'deadline' =>
                'required|date',

            'required_skills' =>
                'required|string',

            'min_experience' =>
                'required|string',

            'budget' =>
                'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $task = TaskModel::create([
            'client_id' => $request->client_id,
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
            'message' => 'Task created successfully',
            'data' => $task
        ], 201);
    }

    // Get single task
    public function show($id)
    {
        $task = TaskModel::with([
            'client',
            'category'
        ])->find($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $task
        ]);
    }

    // Update task
    public function update(Request $request, $id)
    {
        $task = TaskModel::find($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [

            'title' => 'sometimes|string',

            'description' => 'sometimes|string',

            'deadline' => 'sometimes|date',

            'required_skills' => 'sometimes|string',

            'min_experience' => 'sometimes|string',

            'budget' => 'sometimes|integer',

            'status' =>
                'sometimes|in:open,in_progress,completed,cancelled'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $task->update(
            $request->only([
                'title',
                'description',
                'deadline',
                'required_skills',
                'min_experience',
                'budget',
                'status'
            ])
        );

        return response()->json([
            'success' => true,
            'message' => 'Task updated successfully',
            'data' => $task
        ]);
    }

    // Delete task
    public function destroy($id)
    {
        $task = TaskModel::find($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found'
            ], 404);
        }

        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully'
        ]);
    }
}
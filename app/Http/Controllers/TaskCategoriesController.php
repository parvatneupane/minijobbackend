<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TaskCategoryModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskCategoryController extends Controller
{
    // Get all categories
    public function index()
    {
        $categories = TaskCategoryModel::all();

        return response()->json([
            'success' => true,
            'message' => 'Categories fetched successfully',
            'data' => $categories
        ]);
    }

    // Create category
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'status' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $category = TaskCategoryModel::create([
            'name' => $request->name,
            'status' => $request->status ?? 0
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully',
            'data' => $category
        ], 201);
    }

    // Get single category
    public function show($id)
    {
        $category = TaskCategoryModel::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $category
        ]);
    }

    // Update category
    public function update(Request $request, $id)
    {
        $category = TaskCategoryModel::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'status' => 'sometimes|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $category->update(
            $request->only([
                'name',
                'status'
            ])
        );

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully',
            'data' => $category
        ]);
    }

    // Delete category
    public function destroy($id)
    {
        $category = TaskCategoryModel::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully'
        ]);
    }
}
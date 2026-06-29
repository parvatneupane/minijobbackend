<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ReviewModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    // Get all reviews
    public function index()
    {
        $reviews = ReviewModel::with([
            'contract',
            'reviewer',
            'reviewee'
        ])->get();

        return response()->json([
            'success' => true,
            'data' => $reviews
        ]);
    }

    // Create review
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'contract_id' =>
                'required|exists:contracts,id',

            'reviewer_id' =>
                'required|exists:users,id',

            'reviewee_id' =>
                'required|exists:users,id',

            'rating' =>
                'required|integer|min:1|max:5',

            'comment' =>
                'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $review = ReviewModel::create([
            'contract_id' => $request->contract_id,
            'reviewer_id' => $request->reviewer_id,
            'reviewee_id' => $request->reviewee_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 0
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review created successfully',
            'data' => $review
        ], 201);
    }

    // Get single review
    public function show($id)
    {
        $review = ReviewModel::with([
            'contract',
            'reviewer',
            'reviewee'
        ])->find($id);

        if (!$review) {
            return response()->json([
                'success' => false,
                'message' => 'Review not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $review
        ]);
    }

    // Update review
    public function update(Request $request, $id)
    {
        $review = ReviewModel::find($id);

        if (!$review) {
            return response()->json([
                'success' => false,
                'message' => 'Review not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [

            'rating' =>
                'sometimes|integer|min:1|max:5',

            'comment' =>
                'sometimes|string',

            'status' =>
                'sometimes|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $review->update(
            $request->only([
                'rating',
                'comment',
                'status'
            ])
        );

        return response()->json([
            'success' => true,
            'message' => 'Review updated successfully',
            'data' => $review
        ]);
    }

    // Delete review
    public function destroy($id)
    {
        $review = ReviewModel::find($id);

        if (!$review) {
            return response()->json([
                'success' => false,
                'message' => 'Review not found'
            ], 404);
        }

        $review->delete();

        return response()->json([
            'success' => true,
            'message' => 'Review deleted successfully'
        ]);
    }
}
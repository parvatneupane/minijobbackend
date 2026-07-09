<?php

namespace App\Http\Controllers;

use App\Models\ReviewModel;
use App\Models\FreeLancerProfileModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    /**
     * Get all reviews
     */
    public function index()
    {
        $reviews = ReviewModel::with([
            'contract',
            'task',
            'client',
            'freelancer'
        ])->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $reviews
        ]);
    }

    /**
     * Store Review
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'contract_id' => 'required|exists:contracts,id',

            'task_id' => 'required|exists:tasks,id',

            'client_id' => 'required|exists:users,id',

            'freelancer_id' => 'required|exists:users,id',

            'rating' => 'required|integer|min:1|max:5',

            'review' => 'required|string',

            'recommended' => 'nullable|boolean'

        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);

        }

        // Prevent duplicate review
        $exists = ReviewModel::where('contract_id', $request->contract_id)
            ->where('client_id', $request->client_id)
            ->first();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'You have already reviewed this freelancer.'
            ], 409);
        }

        $review = ReviewModel::create([

            'contract_id' => $request->contract_id,

            'task_id' => $request->task_id,

            'client_id' => $request->client_id,

            'freelancer_id' => $request->freelancer_id,

            'rating' => $request->rating,

            'review' => $request->review,

            'recommended' => $request->recommended ?? false

        ]);

        // Update freelancer average rating
        $avgRating = ReviewModel::where('freelancer_id', $request->freelancer_id)
            ->avg('rating');

        FreeLancerProfileModel::where('user_id', $request->freelancer_id)
            ->update([
                'rating' => round($avgRating, 2)
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Review submitted successfully.',
            'data' => $review
        ], 201);
    }

    /**
     * Show Single Review
     */
    public function show($id)
    {
        $review = ReviewModel::with([
            'contract',
            'task',
            'client',
            'freelancer'
        ])->find($id);

        if (!$review) {

            return response()->json([
                'success' => false,
                'message' => 'Review not found.'
            ],404);

        }

        return response()->json([
            'success' => true,
            'data' => $review
        ]);
    }

    /**
     * Update Review
     */
    public function update(Request $request, $id)
    {
        $review = ReviewModel::find($id);

        if (!$review) {

            return response()->json([
                'success' => false,
                'message' => 'Review not found.'
            ],404);

        }

        $validator = Validator::make($request->all(), [

            'rating' => 'sometimes|integer|min:1|max:5',

            'review' => 'sometimes|string',

            'recommended' => 'sometimes|boolean'

        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ],422);

        }

        $review->update($request->only([

            'rating',

            'review',

            'recommended'

        ]));

        // Recalculate average rating
        $avgRating = ReviewModel::where('freelancer_id', $review->freelancer_id)
            ->avg('rating');

        FreeLancerProfileModel::where('user_id', $review->freelancer_id)
            ->update([
                'rating' => round($avgRating, 2)
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Review updated successfully.',
            'data' => $review
        ]);
    }

    /**
     * Delete Review
     */
    public function destroy($id)
    {
        $review = ReviewModel::find($id);

        if (!$review) {

            return response()->json([
                'success' => false,
                'message' => 'Review not found.'
            ],404);

        }

        $freelancerId = $review->freelancer_id;

        $review->delete();

        // Update average rating after deletion
        $avgRating = ReviewModel::where('freelancer_id', $freelancerId)
            ->avg('rating');

        FreeLancerProfileModel::where('user_id', $freelancerId)
            ->update([
                'rating' => round($avgRating ?? 0, 2)
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Review deleted successfully.'
        ]);
    }
}
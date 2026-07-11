<?php

namespace App\Http\Controllers;

use App\Models\FreeLancerProfileModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FreeLancerProfileController extends Controller
{
    // GET ALL
    public function index()
    {
        $profiles = FreeLancerProfileModel::with('user', 'categories')->get();

        return response()->json([
            'success' => true,
            'message' => 'Freelancer profiles fetched successfully',
            'data' => $profiles
        ]);
    }

    // CREATE PROFILE
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'experience_years' => 'nullable|integer|min:0',
            'hourly_rate' => 'nullable|numeric|min:0',
            'skills' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'availability' => 'nullable|in:available,busy,unavailable',
            'portfolio_url' => 'nullable|url',

            // Categories
            'categories' => 'nullable|array',
            'categories.*' => 'exists:task_categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $userId = auth()->id();

        $profile = FreeLancerProfileModel::where('user_id', $userId)->first();

        // Update existing profile
        if ($profile) {

            $profile->update([
                'title' => $request->title,
                'bio' => $request->bio,
                'experience_years' => $request->experience_years ?? 0,
                'hourly_rate' => $request->hourly_rate ?? 0,
                'skills' => $request->skills,
                'location' => $request->location,
                'availability' => $request->availability ?? 'available',
                'portfolio_url' => $request->portfolio_url,
            ]);

            if ($request->has('categories')) {
                $profile->categories()->sync($request->categories);
            }

            return response()->json([
                'success' => true,
                'message' => 'Freelancer profile updated successfully',
                'data' => $profile->load('user', 'categories')
            ]);
        }

        // Create new profile
        $profile = FreeLancerProfileModel::create([
            'user_id' => $userId,
            'title' => $request->title,
            'bio' => $request->bio,
            'experience_years' => $request->experience_years ?? 0,
            'hourly_rate' => $request->hourly_rate ?? 0,
            'skills' => $request->skills,
            'location' => $request->location,
            'availability' => $request->availability ?? 'available',
            'portfolio_url' => $request->portfolio_url,
            'rating' => 0,
            'completed_jobs' => 0,
            'status' => 'active'
        ]);

        if ($request->has('categories')) {
            $profile->categories()->sync($request->categories);
        }

        return response()->json([
            'success' => true,
            'message' => 'Freelancer profile created successfully',
            'data' => $profile->load('user', 'categories')
        ], 201);
    }

    // SHOW PROFILE
    public function show($id)
    {
        $profile = FreeLancerProfileModel::with('user', 'categories')->find($id);

        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Freelancer profile not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $profile
        ]);
    }

    // UPDATE PROFILE
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'experience_years' => 'nullable|integer|min:0',
            'hourly_rate' => 'nullable|numeric|min:0',
            'skills' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'availability' => 'nullable|in:available,busy,unavailable',
            'portfolio_url' => 'nullable|url',
            'status' => 'nullable|in:inactive,active,blocked',

            'categories' => 'nullable|array',
            'categories.*' => 'exists:task_categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $profile = FreeLancerProfileModel::find($id);

        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Freelancer profile not found'
            ], 404);
        }

        $profile->update([
            'title' => $request->title,
            'bio' => $request->bio,
            'experience_years' => $request->experience_years,
            'hourly_rate' => $request->hourly_rate,
            'skills' => $request->skills,
            'location' => $request->location,
            'availability' => $request->availability,
            'portfolio_url' => $request->portfolio_url,
            'status' => $request->status
        ]);

        if ($request->has('categories')) {
            $profile->categories()->sync($request->categories);
        }

        return response()->json([
            'success' => true,
            'message' => 'Freelancer profile updated successfully',
            'data' => $profile->load('user', 'categories')
        ]);
    }

    // DELETE PROFILE
    public function destroy($id)
    {
        $profile = FreeLancerProfileModel::find($id);

        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Freelancer profile not found'
            ], 404);
        }

        $profile->categories()->detach();

        $profile->delete();

        return response()->json([
            'success' => true,
            'message' => 'Freelancer profile deleted successfully'
        ]);
    }

    // MY PROFILE
    public function myProfile()
    {
        $profile = FreeLancerProfileModel::with('user', 'categories')
            ->where('user_id', auth()->id())
            ->first();

        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Profile not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $profile
        ]);
    }
}
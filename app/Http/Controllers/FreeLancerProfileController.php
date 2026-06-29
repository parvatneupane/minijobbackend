<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\FreeLancerProfileModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FreeLancerProfileController extends Controller
{
    // Get all freelancer profiles
    public function index()
    {
        $profiles = FreeLancerProfileModel::with('user')->get();

        return response()->json([
            'success' => true,
            'message' => 'Profiles fetched successfully',
            'data' => $profiles
        ]);
    }

    // Create profile
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'title' => 'nullable|string|max:255',
            'profile_image' => 'nullable|string',
            'bio' => 'nullable|string',
            'experience_years' => 'nullable|integer',
            'hourly_rate' => 'nullable|numeric',
            'location' => 'nullable|string',
            'skills' => 'nullable|string',
            'availability' =>
                'nullable|in:available,busy,unavailable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $profile = FreeLancerProfileModel::create([
            'user_id' => $request->user_id,
            'title' => $request->title,
            'profile_image' => $request->profile_image,
            'bio' => $request->bio,
            'experience_years' => $request->experience_years ?? 0,
            'hourly_rate' => $request->hourly_rate ?? 0,
            'location' => $request->location,
            'skills' => $request->skills,
            'availability' => $request->availability ?? 'available',
            'status' => 'pending'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Profile created successfully',
            'data' => $profile
        ], 201);
    }

    // Get single profile
    public function show($id)
    {
        $profile = FreeLancerProfileModel::with('user')->find($id);

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

    // Update profile
    public function update(Request $request, $id)
    {
        $profile = FreeLancerProfileModel::find($id);

        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Profile not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string|max:255',
            'profile_image' => 'nullable|string',
            'bio' => 'nullable|string',
            'experience_years' => 'nullable|integer',
            'hourly_rate' => 'nullable|numeric',
            'location' => 'nullable|string',
            'skills' => 'nullable|string',
            'rating' => 'nullable|numeric',
            'availability' =>
                'nullable|in:available,busy,unavailable',
            'status' =>
                'nullable|in:pending,active,blocked',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $profile->update($request->only([
            'title',
            'profile_image',
            'bio',
            'experience_years',
            'hourly_rate',
            'location',
            'skills',
            'rating',
            'availability',
            'status'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => $profile
        ]);
    }

    // Delete profile
    public function destroy($id)
    {
        $profile = FreeLancerProfileModel::find($id);

        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Profile not found'
            ], 404);
        }

        $profile->delete();

        return response()->json([
            'success' => true,
            'message' => 'Profile deleted successfully'
        ]);
    }
}
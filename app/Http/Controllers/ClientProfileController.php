<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ClientProfileModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClientProfileController extends Controller
{
    // Get all profiles
    public function index()
    {
        $profiles = ClientProfileModel::with('user')->get();

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
            'client_name' => 'nullable|string|max:255',
            'profile_image' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $profile = ClientProfileModel::create([
            'user_id' => $request->user_id,
            'client_name' => $request->client_name,
            'profile_image' => $request->profile_image,
            'address' => $request->address,
            'status' => 'pending'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Client profile created successfully',
            'data' => $profile
        ], 201);
    }

    // Get single profile
    public function show($id)
    {
        $profile = ClientProfileModel::with('user')->find($id);

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
        $profile = ClientProfileModel::find($id);

        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Profile not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'client_name' => 'nullable|string|max:255',
            'profile_image' => 'nullable|string',
            'address' => 'nullable|string',
            'status' => 'nullable|in:pending,active,blocked'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $profile->update($request->only([
            'client_name',
            'profile_image',
            'address',
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
        $profile = ClientProfileModel::find($id);

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
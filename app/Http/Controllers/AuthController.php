<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\UserModel;
use App\Models\ClientProfileModel;
use App\Models\FreeLancerProfileModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:client,freelancer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = UserModel::create([
            'name' => $request->name,
            'email' => $request->email,
           
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => 'active'
        ]);

        if ($user->role == 'client') {

            ClientProfileModel::create([
                'user_id' => $user->id,
                'client_name' => $user->name,
                'profile_image' => null,
                'address' => '',
                'status' => 'active'
            ]);

        } else {

            FreeLancerProfileModel::create([
                'user_id' => $user->id,
                'title' => '',
                'profile_image' => null,
                'bio' => '',
                'experience_years' => 0,
                'hourly_rate' => 0,
                'location' => '',
                'skills' => '',
                'rating' => 0,
                'availability' => 'available',
                'status' => 'active'
            ]);

        }

        $token = $user->createToken('mobile')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registration Successful',
            'token' => $token,
            'user' => $user
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = UserModel::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {

            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password'
            ], 401);

        }

        if ($user->status != 'active') {

            return response()->json([
                'success' => false,
                'message' => 'Your account is not active.'
            ], 403);

        }

        $token = $user->createToken('mobile')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login Successful',
            'token' => $token,
            'user' => $user
        ]);
    }

    public function profile(Request $request)
    {
        $user = UserModel::with([
            'clientProfile',
            'freelancerProfile'
        ])->find($request->user()->id);

        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout Successful'
        ]);
    }
}
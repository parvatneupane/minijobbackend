<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{



public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6|confirmed',
                'role' => 'required|in:client,freelancer,admin'
            ]
        );

        if ($validator->fails()) {
            // For web requests, redirect back with errors
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = UserModel::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => 'active'
        ]);

        // For web, redirect to login with success message
        return redirect()
            ->route('login')
            ->with('success', 'Registration successful! Please login.');
    }
    
    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' =>
                    'required|email',

                'password' =>
                    'required'
            ]
        );

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'errors' =>
                    $validator->errors()
            ], 422);
        }

        $user =
            UserModel::where(
                'email',
                $request->email
            )->first();

        if (
            !$user ||
            !Hash::check(
                $request->password,
                $user->password
            )
        ) {

            return response()->json([
                'success' => false,

                'message' =>
                    'Invalid Email or Password'
            ], 401);
        }

        if (
            $user->status !== 'active'
        ) {

            return response()->json([
                'success' => false,

                'message' =>
                    'Your account is not active'
            ], 403);
        }

        $token =
            $user
                ->createToken('mobile')
                ->plainTextToken;

        return response()->json([
            'success' => true,

            'message' =>
                'Login Successful',

            'token' => $token,

            'user' => $user
        ]);
    }

  
    
    public function logout(Request $request)
    {
        $request
            ->user()
            ->currentAccessToken()
            ->delete();

        return response()->json([
            'success' => true,

            'message' =>
                'Logout Successful'
        ]);
    }
}
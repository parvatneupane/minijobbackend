<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    /**
     * Show Login Page
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.login');
    }


    /**
     * Login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);


        // Check login credentials
        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'Invalid email or password.',
            ])->withInput();
        }


        // Regenerate session after login
        $request->session()->regenerate();


        $user = Auth::user();


        // Check account status
        if ($user->status !== 'active') {

            Auth::logout();

            return redirect()->route('login')->withErrors([
                'email' => 'Your account is inactive.',
            ]);
        }


        // Allow only admin users
        if ($user->role !== 'admin') {

            Auth::logout();

            return redirect()->route('login')->withErrors([
                'email' => 'You are not authorized to access the admin panel.',
            ]);
        }


        // Redirect admin to dashboard
        return redirect()->route('admin.dashboard');
    }


    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Destroy session
        $request->session()->invalidate();

        // Generate new CSRF token
        $request->session()->regenerateToken();


        return redirect()->route('login')
            ->with('success', 'You have been logged out successfully.');
    }
}

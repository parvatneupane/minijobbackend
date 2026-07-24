<?php

namespace App\Http\Controllers\AdminController;

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

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'Invalid email or password.',
            ])->withInput();
        }

        $request->session()->regenerate();

        $user = Auth::user();

        // Check account status
        if ($user->status !== 'active') {

            Auth::logout();

            return redirect('/')->withErrors([
                'email' => 'Your account is inactive.',
            ]);
        }

        // Allow only admin users
        if ($user->role !== 'admin') {

            Auth::logout();

            return redirect('/')->withErrors([
                'email' => 'You are not authorized to access the admin panel.',
            ]);
        }

        return redirect()->route('admin.dashboard');
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('welcome');
    }
}

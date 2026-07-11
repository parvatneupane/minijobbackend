<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Not logged in
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Account not active
        if ($user->status !== 'active') {
            Auth::logout();

            return redirect()->route('login')
                ->withErrors([
                    'email' => 'Your account is inactive.',
                ]);
        }

        // Not an admin
        if ($user->role !== 'admin') {
            abort(403, 'Unauthorized Access');
        }

        return $next($request);
    }
}
<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminSettingController extends Controller
{
    public function index()
    {
        return view('admin.account.accountsetting');
    }


    /**
     * Update profile
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        /** @var \App\Models\User $admin */
        $admin = Auth::user();

        if (!$admin) {
            return back()->withErrors([
                'error' => 'User not authenticated.'
            ]);
        }


        $admin->name = $request->full_name;
        $admin->phone = $request->phone;


        if ($request->hasFile('avatar')) {

            $path = $request->file('avatar')
                ->store('avatars', 'public');

            $admin->profile_image = $path;
        }


        $admin->save();


        return back()->with(
            'success',
            'Profile updated successfully.'
        );
    }



    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);


        $admin = Auth::user();


        if (!$admin) {
            return back()->withErrors([
                'error' => 'User not authenticated.'
            ]);
        }


        if (!Hash::check(
            $request->current_password,
            $admin->password
        )) {

            return back()->withErrors([
                'current_password' => 'Current password is incorrect.'
            ]);
        }


        $admin->password = Hash::make(
            $request->new_password
        );

        $admin->save();


        return back()->with(
            'success',
            'Password updated successfully.'
        );
    }



    /**
     * Update notifications
     */
    public function updateNotifications(Request $request)
    {
        $admin = Auth::user();


        if (!$admin) {
            return back()->withErrors([
                'error' => 'User not authenticated.'
            ]);
        }


        // Save notification settings here


        return back()->with(
            'success',
            'Notification preferences updated.'
        );
    }



    /**
     * Deactivate account
     */
    public function deactivate()
    {
        $admin = Auth::user();


        if (!$admin) {
            return back()->withErrors([
                'error' => 'User not authenticated.'
            ]);
        }


        $admin->status = 'inactive';
        $admin->save();


        return back()->with(
            'success',
            'Account deactivated.'
        );
    }



    /**
     * Delete account
     */
    public function destroy()
    {
        $admin = Auth::user();


        if (!$admin) {
            return redirect('welcome')->withErrors([
                'error' => 'User not authenticated.'
            ]);
        }


        Auth::logout();


        $admin->delete();


        return redirect('/')->with(
            'success',
            'Account deleted.'
        );
    }
}

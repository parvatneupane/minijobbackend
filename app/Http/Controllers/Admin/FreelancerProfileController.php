<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FreelancerProfileModel as FreelancerProfile;
use Illuminate\Http\Request;

class FreelancerProfileController extends Controller
{
    public function index(Request $request)
    {
        $profiles = FreelancerProfile::with('user')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.freelancer-profiles.index', compact('profiles'));
    }

    public function show(FreelancerProfile $freelancerProfile)
    {
        $freelancerProfile->load('user', 'taskCategories');

        return view('admin.freelancer-profiles.show', ['profile' => $freelancerProfile]);
    }

    public function updateStatus(Request $request, FreelancerProfile $freelancerProfile)
    {
        $request->validate(['status' => 'required|in:inactive,active,blocked']);

        $freelancerProfile->update(['status' => $request->status]);

        return back()->with('success', 'Profile status updated.');
    }
}

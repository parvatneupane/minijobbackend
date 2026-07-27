<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VerificationModel as Verification;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function index(Request $request)
    {
        $verifications = Verification::with('user')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.verifications.index', compact('verifications'));
    }

    public function show(Verification $verification)
    {
        $verification->load('user');

        return view('admin.verifications.show', compact('verification'));
    }

    public function update(Request $request, Verification $verification)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'remarks' => 'nullable|string',
        ]);

        $data['verified_at'] = in_array($data['status'], ['approved', 'rejected']) ? now() : null;

        $verification->update($data);

        return redirect()->route('admin.verifications.index')->with('success', 'Verification updated.');
    }
}

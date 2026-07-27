<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubmissionModel as Submission;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    public function index(Request $request)
    {
        $submissions = Submission::with(['contract.task', 'freelancer'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.submissions.index', compact('submissions'));
    }

    public function show(Submission $submission)
    {
        $submission->load(['contract.task', 'freelancer']);

        return view('admin.submissions.show', compact('submission'));
    }
}

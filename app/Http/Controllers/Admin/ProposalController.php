<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProposalModel as Proposal;
use Illuminate\Http\Request;

class ProposalController extends Controller
{
    public function index(Request $request)
    {
        $proposals = Proposal::with(['task', 'freelancer'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.proposals.index', compact('proposals'));
    }

    public function show(Proposal $proposal)
    {
        $proposal->load(['task', 'freelancer', 'contract']);

        return view('admin.proposals.show', compact('proposal'));
    }
}

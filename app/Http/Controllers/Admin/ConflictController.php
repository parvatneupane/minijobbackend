<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConflictModel as Conflict;
use App\Models\ConflictReplyModel as ConflictReply;
use Illuminate\Http\Request;

class ConflictController extends Controller
{
    public function index(Request $request)
    {
        $conflicts = Conflict::with(['contract.task', 'raisedBy', 'againstUser'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.conflicts.index', compact('conflicts'));
    }

    public function show(Conflict $conflict)
    {
        $conflict->load(['contract.task', 'raisedBy', 'againstUser', 'replies.user']);

        return view('admin.conflicts.show', compact('conflict'));
    }

    public function update(Request $request, Conflict $conflict)
    {
        $data = $request->validate([
            'status' => 'required|in:open,in_review,resolved,rejected',
            'admin_response' => 'nullable|string',
        ]);

        $conflict->update($data);

        return back()->with('success', 'Conflict updated.');
    }

    public function reply(Request $request, Conflict $conflict)
    {
        $data = $request->validate(['message' => 'required|string']);

        ConflictReply::create([
            'conflict_id' => $conflict->id,
            'user_id' => $request->user()->id,
            'message' => $data['message'],
        ]);

        return back()->with('success', 'Reply posted.');
    }
}

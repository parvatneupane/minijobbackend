@extends('admin.layouts.app')
@section('title', 'Conflict Detail')

@section('content')
<a href="{{ route('admin.conflicts.index') }}" class="text-sm text-indigo-600">← Back</a>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4">
    <div class="bg-white rounded-xl shadow-sm border p-5 md:col-span-2">
        <h2 class="font-semibold text-lg mb-2">{{ $conflict->title }}</h2>
        <p class="text-sm text-gray-500 mb-3">
            Raised by {{ $conflict->raisedByUser->name ?? '—' }} ({{ $conflict->raised_by_role }})
            against {{ $conflict->againstUser->name ?? '—' }}
            &middot; Task: {{ $conflict->contract->task->title ?? '—' }}
        </p>
        <p class="text-sm mb-4">{{ $conflict->reason }}</p>

        @if($conflict->attachment)
            <a href="{{ asset('storage/'.$conflict->attachment) }}" target="_blank" class="text-indigo-600 text-sm">View Attachment →</a>
        @endif

        <h3 class="font-semibold mt-6 mb-3">Reply Thread</h3>
        <div class="space-y-3 mb-4">
            @forelse($conflict->replies as $reply)
                <div class="p-3 rounded-lg bg-gray-50">
                    <p class="text-xs text-gray-500 mb-1">{{ $reply->user->name ?? '—' }} · {{ $reply->created_at->format('M d, H:i') }}</p>
                    <p class="text-sm">{{ $reply->message }}</p>
                </div>
            @empty
                <p class="text-gray-400 text-sm">No replies yet.</p>
            @endforelse
        </div>

        <form action="{{ route('admin.conflicts.reply', $conflict) }}" method="POST" class="flex gap-2">
            @csrf
            <input type="text" name="message" placeholder="Post a reply as admin..." class="border rounded-lg px-3 py-2 text-sm flex-1" required>
            <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm">Reply</button>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border p-5">
    <h3 class="font-semibold mb-4">Dispute Resolution</h3>

    <form method="POST" action="{{ route('admin.conflicts.update', $conflict) }}">
        @csrf
        @method('PUT')

        <div class="space-y-4">

            <div>
                <label class="text-sm text-gray-600">Conflict Status</label>
                <select name="status" class="border rounded-lg w-full px-3 py-2">
                    <option value="open" @selected($conflict->status=='open')>Open</option>
                    <option value="in_review" @selected($conflict->status=='in_review')>In Review</option>
                    <option value="resolved" @selected($conflict->status=='resolved')>Resolved</option>
                    <option value="rejected" @selected($conflict->status=='rejected')>Rejected</option>
                </select>
            </div>

            <div>
                <label class="text-sm text-gray-600">
                    Decision
                </label>

                <select
                    name="payment_action"
                    class="border rounded-lg w-full px-3 py-2">

                    <option value="">Keep Payment in Escrow</option>

                    <option value="release">
                        ✅ Release Payment to Freelancer
                    </option>

                    <option value="refund">
                        ↩ Refund Payment to Client
                    </option>

                </select>
            </div>

            <div>
                <label class="text-sm text-gray-600">
                    Admin Response
                </label>

                <textarea
                    name="admin_response"
                    rows="5"
                    class="border rounded-lg w-full px-3 py-2">{{ $conflict->admin_response }}</textarea>
            </div>

            <button
                class="bg-indigo-600 text-white rounded-lg px-4 py-2 w-full">
                Save Resolution
            </button>

        </div>

    </form>
</div>

</div>
@endsection

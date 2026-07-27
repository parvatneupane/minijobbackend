@extends('admin.layouts.app')
@section('title', 'Review Verification')

@section('content')
<a href="{{ route('admin.verifications.index') }}" class="text-sm text-indigo-600">← Back</a>

<div class="bg-white rounded-xl shadow-sm border p-5 mt-4 max-w-2xl">
    <h2 class="font-semibold mb-4">{{ $verification->full_name }} ({{ $verification->user->email ?? '—' }})</h2>

    <div class="grid grid-cols-2 gap-4 mb-4">
        <div>
            <p class="text-sm text-gray-500 mb-1">Citizenship Front</p>
            <img src="{{ asset('storage/'.$verification->citizenship_front) }}" class="rounded-lg border w-full">
        </div>
        <div>
            <p class="text-sm text-gray-500 mb-1">Citizenship Back</p>
            <img src="{{ asset('storage/'.$verification->citizenship_back) }}" class="rounded-lg border w-full">
        </div>
    </div>

    @if($verification->pan_card)
        <p class="text-sm text-gray-500 mb-1">PAN Card</p>
        <img src="{{ asset('storage/'.$verification->pan_card) }}" class="rounded-lg border w-full mb-4">
    @endif

    <form method="POST" action="{{ route('admin.verifications.update', $verification) }}" class="space-y-4">
        @csrf @method('PUT')
        <div>
            <label class="text-sm text-gray-600">Decision</label>
            <select name="status" class="border rounded-lg px-3 py-2 w-full text-sm">
                <option value="pending" @selected($verification->status=='pending')>Pending</option>
                <option value="approved" @selected($verification->status=='approved')>Approve</option>
                <option value="rejected" @selected($verification->status=='rejected')>Reject</option>
            </select>
        </div>
        <div>
            <label class="text-sm text-gray-600">Remarks</label>
            <textarea name="remarks" rows="3" class="border rounded-lg px-3 py-2 w-full text-sm">{{ $verification->remarks }}</textarea>
        </div>
        <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm">Save Decision</button>
    </form>
</div>
@endsection

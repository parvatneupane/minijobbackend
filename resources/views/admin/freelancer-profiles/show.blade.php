@extends('admin.layouts.app')
@section('title', 'Freelancer Profile')

@section('content')
<a href="{{ route('admin.freelancer-profiles.index') }}" class="text-sm text-indigo-600">← Back</a>

<div class="bg-white rounded-xl shadow-sm border p-5 mt-4 max-w-2xl">
    <h2 class="font-semibold mb-4">{{ $profile->user->name }}</h2>
    <dl class="grid grid-cols-2 gap-4 text-sm">
        <div><dt class="text-gray-500">Email</dt><dd>{{ $profile->user->email }}</dd></div>
        <div><dt class="text-gray-500">Title</dt><dd>{{ $profile->title ?? '—' }}</dd></div>
        <div><dt class="text-gray-500">Experience</dt><dd>{{ $profile->experience_years }} yrs</dd></div>
        <div><dt class="text-gray-500">Hourly Rate</dt><dd>Rs. {{ $profile->hourly_rate }}</dd></div>
        <div><dt class="text-gray-500">Location</dt><dd>{{ $profile->location ?? '—' }}</dd></div>
        <div><dt class="text-gray-500">Availability</dt><dd class="capitalize">{{ $profile->availability }}</dd></div>
        <div><dt class="text-gray-500">Rating</dt><dd>{{ $profile->rating }}</dd></div>
        <div><dt class="text-gray-500">Completed Jobs</dt><dd>{{ $profile->completed_jobs }}</dd></div>
        <div><dt class="text-gray-500">Earned</dt><dd>Rs. {{ number_format($profile->earned_money, 2) }}</dd></div>
        <div><dt class="text-gray-500">Portfolio</dt><dd>{{ $profile->portfolio_url ?? '—' }}</dd></div>
    </dl>

    <div class="mt-4">
        <dt class="text-gray-500 text-sm">Bio</dt>
        <dd class="text-sm mt-1">{{ $profile->bio ?? '—' }}</dd>
    </div>

    <div class="mt-4">
        <dt class="text-gray-500 text-sm">Categories</dt>
        <dd class="text-sm mt-1">{{ $profile->taskCategories->pluck('name')->join(', ') ?: '—' }}</dd>
    </div>

    <form action="{{ route('admin.freelancer-profiles.status', $profile) }}" method="POST" class="mt-6 flex gap-2">
        @csrf @method('PATCH')
        <select name="status" class="border rounded-lg px-2 py-1.5 text-sm">
            @foreach(['inactive','active','blocked'] as $s)
                <option value="{{ $s }}" @selected($profile->status==$s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <button class="bg-indigo-600 text-white px-3 py-1.5 rounded-lg text-sm">Update Status</button>
    </form>
</div>
@endsection

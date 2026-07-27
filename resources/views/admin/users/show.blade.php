@extends('admin.layouts.app')
@section('title', 'User: '.$user->name)

@section('content')
<a href="{{ route('admin.users.index') }}" class="text-sm text-indigo-600">← Back to Users</a>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4">
    <div class="bg-white rounded-xl shadow-sm border p-5 md:col-span-1">
        <h3 class="font-semibold mb-3">Profile</h3>
        @if($user->profileimage)
            <img src="{{ asset('storage/'.$user->profileimage) }}" class="w-20 h-20 rounded-full object-cover mb-3">
        @endif
        <dl class="text-sm space-y-2">
            <div><dt class="text-gray-500">Name</dt><dd>{{ $user->name }}</dd></div>
            <div><dt class="text-gray-500">Email</dt><dd>{{ $user->email }}</dd></div>
            <div><dt class="text-gray-500">Phone</dt><dd>{{ $user->phone ?? '—' }}</dd></div>
            <div><dt class="text-gray-500">Role</dt><dd class="capitalize">{{ $user->role }}</dd></div>
            <div><dt class="text-gray-500">Status</dt><dd class="capitalize">{{ $user->status }}</dd></div>
            <div><dt class="text-gray-500">Joined</dt><dd>{{ $user->created_at->format('M d, Y') }}</dd></div>
        </dl>

        <form action="{{ route('admin.users.status', $user) }}" method="POST" class="mt-4 flex gap-2">
            @csrf @method('PATCH')
            <select name="status" class="border rounded-lg px-2 py-1.5 text-sm flex-1">
                @foreach(['pending','active','blocked'] as $s)
                    <option value="{{ $s }}" @selected($user->status==$s)>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <button class="bg-indigo-600 text-white px-3 py-1.5 rounded-lg text-sm">Update</button>
        </form>
    </div>

    <div class="md:col-span-2 space-y-6">
        @if($user->freelancerProfile)
            <div class="bg-white rounded-xl shadow-sm border p-5">
                <h3 class="font-semibold mb-3">Freelancer Profile</h3>
                <dl class="grid grid-cols-2 gap-3 text-sm">
                    <div><dt class="text-gray-500">Title</dt><dd>{{ $user->freelancerProfile->title ?? '—' }}</dd></div>
                    <div><dt class="text-gray-500">Hourly Rate</dt><dd>Rs. {{ $user->freelancerProfile->hourly_rate }}</dd></div>
                    <div><dt class="text-gray-500">Rating</dt><dd>{{ $user->freelancerProfile->rating }}</dd></div>
                    <div><dt class="text-gray-500">Completed Jobs</dt><dd>{{ $user->freelancerProfile->completed_jobs }}</dd></div>
                    <div><dt class="text-gray-500">Earned</dt><dd>Rs. {{ number_format($user->freelancerProfile->earned_money, 2) }}</dd></div>
                    <div><dt class="text-gray-500">Availability</dt><dd class="capitalize">{{ $user->freelancerProfile->availability }}</dd></div>
                </dl>
            </div>
        @endif

        @if($user->verification)
            <div class="bg-white rounded-xl shadow-sm border p-5">
                <h3 class="font-semibold mb-3">Verification</h3>
                <p class="text-sm">Status:
                    <span class="capitalize font-medium">{{ $user->verification->status }}</span>
                </p>
                <a href="{{ route('admin.verifications.show', $user->verification) }}" class="text-indigo-600 text-sm">Review submission →</a>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border p-5">
            <h3 class="font-semibold mb-3">Posted Tasks ({{ $user->tasks->count() }})</h3>
            <ul class="text-sm space-y-1">
                @forelse($user->tasks as $task)
                    <li class="flex justify-between border-b py-1">
                        <span>{{ $task->title }}</span>
                        <span class="text-gray-400 capitalize">{{ $task->status }}</span>
                    </li>
                @empty
                    <li class="text-gray-400">No tasks posted</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection

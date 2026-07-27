@extends('admin.layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    @php
        $cards = [
            ['Total Users', $stats['total_users'], 'bg-indigo-600'],
            ['Clients', $stats['clients'], 'bg-blue-600'],
            ['Freelancers', $stats['freelancers'], 'bg-cyan-600'],
            ['Open Tasks', $stats['open_tasks'], 'bg-amber-600'],
            ['Active Contracts', $stats['active_contracts'], 'bg-emerald-600'],
            ['Pending Verifications', $stats['pending_verifications'], 'bg-rose-600'],
            ['Pending Withdrawals', $stats['pending_withdrawals'], 'bg-orange-600'],
            ['Open Conflicts', $stats['open_conflicts'], 'bg-red-600'],
        ];
    @endphp
    @foreach($cards as [$label, $value, $color])
        <div class="{{ $color }} text-white rounded-xl p-5 shadow-sm">
            <p class="text-sm opacity-80">{{ $label }}</p>
            <p class="text-3xl font-bold mt-1">{{ $value }}</p>
        </div>
    @endforeach
</div>

<div class="grid grid-cols-2 gap-4 mb-8">
    <div class="bg-white rounded-xl p-5 shadow-sm border">
        <p class="text-sm text-gray-500">Funds in Escrow</p>
        <p class="text-2xl font-bold mt-1">Rs. {{ number_format($stats['escrow_amount'], 2) }}</p>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border">
        <p class="text-sm text-gray-500">Total Released to Freelancers</p>
        <p class="text-2xl font-bold mt-1">Rs. {{ number_format($stats['released_amount'], 2) }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white rounded-xl shadow-sm border p-5">
        <h3 class="font-semibold mb-3">Recent Users</h3>
        <ul class="space-y-2 text-sm">
            @foreach($recentUsers as $user)
                <li class="flex justify-between border-b pb-1">
                    <span>{{ $user->name }}</span>
                    <span class="text-gray-400">{{ $user->role }}</span>
                </li>
            @endforeach
        </ul>
        <a href="{{ route('admin.users.index') }}" class="text-indigo-600 text-sm mt-3 inline-block">View all →</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border p-5">
        <h3 class="font-semibold mb-3">Recent Tasks</h3>
        <ul class="space-y-2 text-sm">
            @foreach($recentTasks as $task)
                <li class="flex justify-between border-b pb-1">
                    <span class="truncate">{{ $task->title }}</span>
                    <span class="text-gray-400">{{ $task->client->name ?? '—' }}</span>
                </li>
            @endforeach
        </ul>
        <a href="{{ route('admin.tasks.index') }}" class="text-indigo-600 text-sm mt-3 inline-block">View all →</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border p-5">
        <h3 class="font-semibold mb-3">Recent Conflicts</h3>
        <ul class="space-y-2 text-sm">
            @forelse($recentConflicts as $conflict)
                <li class="flex justify-between border-b pb-1">
                    <span class="truncate">{{ $conflict->title }}</span>
                    <span class="text-gray-400">{{ $conflict->status }}</span>
                </li>
            @empty
                <li class="text-gray-400">No conflicts</li>
            @endforelse
        </ul>
        <a href="{{ route('admin.conflicts.index') }}" class="text-indigo-600 text-sm mt-3 inline-block">View all →</a>
    </div>
</div>
@endsection

@extends('admin.layouts.app')
@section('title', 'Proposal Detail')

@section('content')
<a href="{{ route('admin.proposals.index') }}" class="text-sm text-indigo-600">← Back</a>

<div class="bg-white rounded-xl shadow-sm border p-5 mt-4 max-w-2xl">
    <h2 class="font-semibold mb-2">{{ $proposal->task->title ?? '—' }}</h2>
    <p class="text-sm text-gray-500 mb-4">by {{ $proposal->freelancer->name ?? '—' }}</p>
    <p class="text-sm mb-4">{{ $proposal->description }}</p>
    <dl class="grid grid-cols-2 gap-3 text-sm">
        <div><dt class="text-gray-500">Takes Time</dt><dd>{{ $proposal->takes_time }} days</dd></div>
        <div><dt class="text-gray-500">Achievement</dt><dd>{{ $proposal->achievement ?? '—' }}</dd></div>
        <div><dt class="text-gray-500">Status</dt><dd class="capitalize">{{ $proposal->status }}</dd></div>
    </dl>

    @if($proposal->contract)
        <div class="mt-4">
            <a href="{{ route('admin.contracts.show', $proposal->contract) }}" class="text-indigo-600 text-sm">View resulting contract →</a>
        </div>
    @endif
</div>
@endsection

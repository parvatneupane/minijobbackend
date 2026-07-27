@extends('admin.layouts.app')
@section('title', 'Contract Detail')

@section('content')
<a href="{{ route('admin.contracts.index') }}" class="text-sm text-indigo-600">← Back</a>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
    <div class="bg-white rounded-xl shadow-sm border p-5">
        <h2 class="font-semibold mb-3">{{ $contract->task->title ?? '—' }}</h2>
        <dl class="grid grid-cols-2 gap-3 text-sm">
            <div><dt class="text-gray-500">Client</dt><dd>{{ $contract->client->name ?? '—' }}</dd></div>
            <div><dt class="text-gray-500">Freelancer</dt><dd>{{ $contract->freelancer->name ?? '—' }}</dd></div>
            <div><dt class="text-gray-500">Start Date</dt><dd>{{ $contract->start_date->format('M d, Y') }}</dd></div>
            <div><dt class="text-gray-500">Deadline</dt><dd>{{ $contract->deadline->format('M d, Y') }}</dd></div>
        </dl>

        <form action="{{ route('admin.contracts.status', $contract) }}" method="POST" class="mt-4 flex gap-2">
            @csrf @method('PATCH')
            <select name="status" class="border rounded-lg px-2 py-1.5 text-sm">
                @foreach(['active','completed','cancelled'] as $s)
                    <option value="{{ $s }}" @selected($contract->status==$s)>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <button class="bg-indigo-600 text-white px-3 py-1.5 rounded-lg text-sm">Update Status</button>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border p-5">
        <h3 class="font-semibold mb-3">Payments</h3>
        <ul class="text-sm space-y-2 mb-4">
            @forelse($contract->payments as $payment)
                <li class="border-b pb-1 flex justify-between">
                    <span>Rs. {{ $payment->amount }}</span>
                    <span class="text-gray-400 capitalize">{{ $payment->status }}</span>
                </li>
            @empty
                <li class="text-gray-400">No payments yet</li>
            @endforelse
        </ul>

        <h3 class="font-semibold mb-3">Submissions</h3>
        <ul class="text-sm space-y-2">
            @forelse($contract->submissions as $submission)
                <li class="border-b pb-1 flex justify-between">
                    <span>{{ $submission->submitted_at?->format('M d, Y') ?? 'Draft' }}</span>
                    <span class="text-gray-400 capitalize">{{ $submission->status }}</span>
                </li>
            @empty
                <li class="text-gray-400">No submissions yet</li>
            @endforelse
        </ul>

        @if($contract->conflicts->count())
            <h3 class="font-semibold mt-4 mb-3 text-red-600">Conflicts ({{ $contract->conflicts->count() }})</h3>
            <ul class="text-sm space-y-2">
                @foreach($contract->conflicts as $conflict)
                    <li class="border-b pb-1">
                        <a href="{{ route('admin.conflicts.show', $conflict) }}" class="text-indigo-600">{{ $conflict->title }}</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
@endsection

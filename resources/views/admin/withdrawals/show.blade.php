@extends('admin.layouts.app')
@section('title', 'Withdrawal Detail')

@section('content')
<a href="{{ route('admin.withdrawals.index') }}" class="text-sm text-indigo-600">← Back</a>

<div class="bg-white rounded-xl shadow-sm border p-5 mt-4 max-w-2xl">
    <h2 class="font-semibold mb-4">{{ $withdrawal->user->name ?? '—' }}</h2>
    <dl class="grid grid-cols-2 gap-3 text-sm mb-4">
        <div><dt class="text-gray-500">Amount</dt><dd>Rs. {{ $withdrawal->amount }}</dd></div>
        <div><dt class="text-gray-500">Method</dt><dd>{{ $withdrawal->payment_method }}</dd></div>
        <div><dt class="text-gray-500">Account Name</dt><dd>{{ $withdrawal->account_name }}</dd></div>
        <div><dt class="text-gray-500">Account Number</dt><dd>{{ $withdrawal->account_number }}</dd></div>
        <div><dt class="text-gray-500">Requested</dt><dd>{{ $withdrawal->requested_at?->format('M d, Y') ?? $withdrawal->created_at->format('M d, Y') }}</dd></div>
    </dl>

    <form method="POST" action="{{ route('admin.withdrawals.update', $withdrawal) }}" class="space-y-4">
        @csrf @method('PUT')
        <div>
            <label class="text-sm text-gray-600">Status</label>
            <select name="status" class="border rounded-lg px-3 py-2 w-full text-sm">
                @foreach(['pending','approved','completed','rejected'] as $s)
                    <option value="{{ $s }}" @selected($withdrawal->status==$s)>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-sm text-gray-600">Remarks</label>
            <textarea name="remarks" rows="3" class="border rounded-lg px-3 py-2 w-full text-sm">{{ $withdrawal->remarks }}</textarea>
        </div>
        <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm">Save</button>
    </form>
</div>
@endsection

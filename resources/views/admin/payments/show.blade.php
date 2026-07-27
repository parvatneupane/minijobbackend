@extends('admin.layouts.app')
@section('title', 'Payment Detail')

@section('content')
<a href="{{ route('admin.payments.index') }}" class="text-sm text-indigo-600">← Back</a>

<div class="bg-white rounded-xl shadow-sm border p-5 mt-4 max-w-2xl">
    <h2 class="font-semibold mb-4">{{ $payment->contract->task->title ?? '—' }}</h2>
    <dl class="grid grid-cols-2 gap-3 text-sm">
        <div><dt class="text-gray-500">Client</dt><dd>{{ $payment->client->name ?? '—' }}</dd></div>
        <div><dt class="text-gray-500">Freelancer</dt><dd>{{ $payment->freelancer->name ?? '—' }}</dd></div>
        <div><dt class="text-gray-500">Amount</dt><dd>Rs. {{ $payment->amount }}</dd></div>
        <div><dt class="text-gray-500">Platform Fee</dt><dd>Rs. {{ $payment->platform_fee }}</dd></div>
        <div><dt class="text-gray-500">Freelancer Gets</dt><dd>Rs. {{ $payment->freelancer_amount }}</dd></div>
        <div><dt class="text-gray-500">Method</dt><dd>{{ $payment->payment_method }}</dd></div>
        <div><dt class="text-gray-500">Transaction ID</dt><dd>{{ $payment->transaction_id ?? '—' }}</dd></div>
        <div><dt class="text-gray-500">Paid At</dt><dd>{{ $payment->paid_at?->format('M d, Y H:i') ?? '—' }}</dd></div>
    </dl>

    <form action="{{ route('admin.payments.status', $payment) }}" method="POST" class="mt-6 flex gap-2">
        @csrf @method('PATCH')
        <select name="status" class="border rounded-lg px-2 py-1.5 text-sm">
            @foreach(['pending','escrow','released','refunded','failed'] as $s)
                <option value="{{ $s }}" @selected($payment->status==$s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <button class="bg-indigo-600 text-white px-3 py-1.5 rounded-lg text-sm">Update Status</button>
    </form>
</div>
@endsection

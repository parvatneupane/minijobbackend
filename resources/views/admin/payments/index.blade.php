@extends('admin.layouts.app')
@section('title', 'Payments')

@section('content')
<div class="bg-white rounded-xl shadow-sm border p-5">
    <form method="GET" class="flex gap-3 mb-5">
        <select name="status" class="border rounded-lg px-3 py-2 text-sm">
            <option value="">All Status</option>
            @foreach(['pending','escrow','released','refunded','failed'] as $s)
                <option value="{{ $s }}" @selected(request('status')==$s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm">Filter</button>
    </form>

    <table class="w-full text-sm">
        <thead>
            <tr class="text-left border-b text-gray-500">
                <th class="py-2">Task</th>
                <th>Client</th>
                <th>Freelancer</th>
                <th>Amount</th>
                <th>Fee</th>
                <th>Status</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $payment)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-2">{{ $payment->contract->task->title ?? '—' }}</td>
                    <td>{{ $payment->client->name ?? '—' }}</td>
                    <td>{{ $payment->freelancer->name ?? '—' }}</td>
                    <td>Rs. {{ $payment->amount }}</td>
                    <td>Rs. {{ $payment->platform_fee }}</td>
                    <td>
                        <span class="px-2 py-1 rounded-full text-xs
                            {{ $payment->status == 'released' ? 'bg-green-100 text-green-700' :
                               ($payment->status == 'failed' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </td>
                    <td class="text-right">
                        <a href="{{ route('admin.payments.show', $payment) }}" class="text-indigo-600">View</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">{{ $payments->links() }}</div>
</div>
@endsection

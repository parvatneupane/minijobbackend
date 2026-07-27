@extends('admin.layouts.app')
@section('title', 'Withdrawals')

@section('content')
<div class="bg-white rounded-xl shadow-sm border p-5">
    <form method="GET" class="flex gap-3 mb-5">
        <select name="status" class="border rounded-lg px-3 py-2 text-sm">
            <option value="">All Status</option>
            @foreach(['pending','approved','completed','rejected'] as $s)
                <option value="{{ $s }}" @selected(request('status')==$s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm">Filter</button>
    </form>

    <table class="w-full text-sm">
        <thead>
            <tr class="text-left border-b text-gray-500">
                <th class="py-2">User</th>
                <th>Amount</th>
                <th>Method</th>
                <th>Account</th>
                <th>Status</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($withdrawals as $w)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-2">{{ $w->user->name ?? '—' }}</td>
                    <td>Rs. {{ $w->amount }}</td>
                    <td>{{ $w->payment_method }}</td>
                    <td>{{ $w->account_name }} ({{ $w->account_number }})</td>
                    <td>
                        <span class="px-2 py-1 rounded-full text-xs
                            {{ $w->status == 'completed' ? 'bg-green-100 text-green-700' :
                               ($w->status == 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                            {{ ucfirst($w->status) }}
                        </span>
                    </td>
                    <td class="text-right">
                        <a href="{{ route('admin.withdrawals.show', $w) }}" class="text-indigo-600">Review</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">{{ $withdrawals->links() }}</div>
</div>
@endsection

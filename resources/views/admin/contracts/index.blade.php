@extends('admin.layouts.app')
@section('title', 'Contracts')

@section('content')
<div class="bg-white rounded-xl shadow-sm border p-5">
    <form method="GET" class="flex gap-3 mb-5">
        <select name="status" class="border rounded-lg px-3 py-2 text-sm">
            <option value="">All Status</option>
            @foreach(['active','completed','cancelled'] as $s)
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
                <th>Deadline</th>
                <th>Status</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($contracts as $contract)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-2 truncate max-w-xs">{{ $contract->task->title ?? '—' }}</td>
                    <td>{{ $contract->client->name ?? '—' }}</td>
                    <td>{{ $contract->freelancer->name ?? '—' }}</td>
                    <td>{{ $contract->deadline->format('M d, Y') }}</td>
                    <td class="capitalize">{{ $contract->status }}</td>
                    <td class="text-right">
                        <a href="{{ route('admin.contracts.show', $contract) }}" class="text-indigo-600">View</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">{{ $contracts->links() }}</div>
</div>
@endsection

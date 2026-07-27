@extends('admin.layouts.app')
@section('title', 'Proposals')

@section('content')
<div class="bg-white rounded-xl shadow-sm border p-5">
    <form method="GET" class="flex gap-3 mb-5">
        <select name="status" class="border rounded-lg px-3 py-2 text-sm">
            <option value="">All Status</option>
            @foreach(['pending','accepted','rejected'] as $s)
                <option value="{{ $s }}" @selected(request('status')==$s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm">Filter</button>
    </form>

    <table class="w-full text-sm">
        <thead>
            <tr class="text-left border-b text-gray-500">
                <th class="py-2">Task</th>
                <th>Freelancer</th>
                <th>Takes (days)</th>
                <th>Status</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($proposals as $proposal)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-2 truncate max-w-xs">{{ $proposal->task->title ?? '—' }}</td>
                    <td>{{ $proposal->freelancer->name ?? '—' }}</td>
                    <td>{{ $proposal->takes_time }}</td>
                    <td class="capitalize">{{ $proposal->status }}</td>
                    <td class="text-right">
                        <a href="{{ route('admin.proposals.show', $proposal) }}" class="text-indigo-600">View</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">{{ $proposals->links() }}</div>
</div>
@endsection

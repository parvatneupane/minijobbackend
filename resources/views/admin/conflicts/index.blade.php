@extends('admin.layouts.app')
@section('title', 'Conflicts')

@section('content')
<div class="bg-white rounded-xl shadow-sm border p-5">
    <form method="GET" class="flex gap-3 mb-5">
        <select name="status" class="border rounded-lg px-3 py-2 text-sm">
            <option value="">All Status</option>
            @foreach(['open','in_review','resolved','rejected'] as $s)
                <option value="{{ $s }}" @selected(request('status')==$s)>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
            @endforeach
        </select>
        <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm">Filter</button>
    </form>

    <table class="w-full text-sm">
        <thead>
            <tr class="text-left border-b text-gray-500">
                <th class="py-2">Title</th>
                <th>Raised By</th>
                <th>Against</th>
                <th>Task</th>
                <th>Status</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($conflicts as $conflict)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-2 truncate max-w-xs">{{ $conflict->title }}</td>
                    <td>{{ $conflict->raisedBy->name ?? '—' }} <span class="text-gray-400">({{ $conflict->raised_by_role }})</span></td>
                    <td>{{ $conflict->againstUser->name ?? '—' }}</td>
                    <td>{{ $conflict->contract->task->title ?? '—' }}</td>
                    <td>
                        <span class="px-2 py-1 rounded-full text-xs
                            {{ $conflict->status == 'resolved' ? 'bg-green-100 text-green-700' :
                               ($conflict->status == 'rejected' ? 'bg-gray-100 text-gray-600' : 'bg-red-100 text-red-700') }}">
                            {{ ucfirst(str_replace('_',' ',$conflict->status)) }}
                        </span>
                    </td>
                    <td class="text-right">
                        <a href="{{ route('admin.conflicts.show', $conflict) }}" class="text-indigo-600">Review</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">{{ $conflicts->links() }}</div>
</div>
@endsection

@extends('admin.layouts.app')
@section('title', 'Tasks')

@section('content')
<div class="bg-white rounded-xl shadow-sm border p-5">
    <form method="GET" class="flex gap-3 mb-5">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search title..." class="border rounded-lg px-3 py-2 text-sm w-64">
        <select name="status" class="border rounded-lg px-3 py-2 text-sm">
            <option value="">All Status</option>
            @foreach(['open','in_progress','completed','cancelled'] as $s)
                <option value="{{ $s }}" @selected(request('status')==$s)>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
            @endforeach
        </select>
        <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm">Filter</button>
    </form>

    <table class="w-full text-sm">
        <thead>
            <tr class="text-left border-b text-gray-500">
                <th class="py-2">Title</th>
                <th>Client</th>
                <th>Category</th>
                <th>Budget</th>
                <th>Deadline</th>
                <th>Status</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tasks as $task)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-2 truncate max-w-xs">{{ $task->title }}</td>
                    <td>{{ $task->client->name ?? '—' }}</td>
                    <td>{{ $task->category->name ?? '—' }}</td>
                    <td>Rs. {{ $task->budget }}</td>
                    <td>{{ $task->deadline->format('M d, Y') }}</td>
                    <td>
                        <span class="px-2 py-1 rounded-full text-xs bg-gray-100 capitalize">{{ str_replace('_',' ',$task->status) }}</span>
                    </td>
                    <td class="text-right">
                        <a href="{{ route('admin.tasks.show', $task) }}" class="text-indigo-600">View</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">{{ $tasks->links() }}</div>
</div>
@endsection

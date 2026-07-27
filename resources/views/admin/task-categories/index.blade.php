@extends('admin.layouts.app')
@section('title', 'Task Categories')

@section('content')
<div class="bg-white rounded-xl shadow-sm border p-5">
    <div class="flex justify-between mb-5">
        <h2 class="font-semibold">All Categories</h2>
        <a href="{{ route('admin.task-categories.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm">+ Add Category</a>
    </div>

    <table class="w-full text-sm">
        <thead>
            <tr class="text-left border-b text-gray-500">
                <th class="py-2">Name</th>
                <th>Tasks</th>
                <th>Status</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-2">{{ $category->name }}</td>
                    <td>{{ $category->tasks_count }}</td>
                    <td>
                        <span class="px-2 py-1 rounded-full text-xs {{ $category->status ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ $category->status ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="text-right space-x-2">
                        <a href="{{ route('admin.task-categories.edit', $category) }}" class="text-indigo-600">Edit</a>
                        <form action="{{ route('admin.task-categories.destroy', $category) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button class="text-red-600" onclick="return confirm('Delete this category?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">{{ $categories->links() }}</div>
</div>
@endsection

@extends('admin.layouts.app')
@section('title', 'Edit Category')

@section('content')
<div class="bg-white rounded-xl shadow-sm border p-5 max-w-lg">
    <form method="POST" action="{{ route('admin.task-categories.update', $category) }}" class="space-y-4">
        @csrf @method('PUT')
        <div>
            <label class="text-sm text-gray-600">Name</label>
            <input type="text" name="name" value="{{ $category->name }}" class="border rounded-lg px-3 py-2 w-full text-sm" required>
        </div>
        <div>
            <label class="text-sm text-gray-600">Status</label>
            <select name="status" class="border rounded-lg px-3 py-2 w-full text-sm">
                <option value="1" @selected($category->status==1)>Active</option>
                <option value="0" @selected($category->status==0)>Inactive</option>
            </select>
        </div>
        <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm">Update Category</button>
        <a href="{{ route('admin.task-categories.index') }}" class="text-sm text-gray-500 ml-2">Cancel</a>
    </form>
</div>
@endsection

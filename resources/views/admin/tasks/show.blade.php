@extends('admin.layouts.app')
@section('title', 'Task Detail')

@section('content')
<a href="{{ route('admin.tasks.index') }}" class="text-sm text-indigo-600">← Back</a>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4">
    <div class="bg-white rounded-xl shadow-sm border p-5 md:col-span-2">
        <h2 class="font-semibold text-lg mb-2">{{ $task->title }}</h2>
        <p class="text-sm text-gray-600 mb-4">{{ $task->description }}</p>
        <dl class="grid grid-cols-2 gap-3 text-sm">
            <div><dt class="text-gray-500">Client</dt><dd>{{ $task->client->name ?? '—' }}</dd></div>
            <div><dt class="text-gray-500">Category</dt><dd>{{ $task->category->name ?? '—' }}</dd></div>
            <div><dt class="text-gray-500">Budget</dt><dd>Rs. {{ $task->budget }}</dd></div>
            <div><dt class="text-gray-500">Deadline</dt><dd>{{ $task->deadline->format('M d, Y') }}</dd></div>
            <div><dt class="text-gray-500">Required Skills</dt><dd>{{ $task->required_skills }}</dd></div>
            <div><dt class="text-gray-500">Min Experience</dt><dd>{{ $task->min_experience }}</dd></div>
        </dl>

        <form action="{{ route('admin.tasks.status', $task) }}" method="POST" class="mt-4 flex gap-2">
            @csrf @method('PATCH')
            <select name="status" class="border rounded-lg px-2 py-1.5 text-sm">
                @foreach(['open','in_progress','completed','cancelled'] as $s)
                    <option value="{{ $s }}" @selected($task->status==$s)>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                @endforeach
            </select>
            <button class="bg-indigo-600 text-white px-3 py-1.5 rounded-lg text-sm">Update Status</button>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border p-5">
        <h3 class="font-semibold mb-3">Proposals ({{ $task->proposals->count() }})</h3>
        <ul class="text-sm space-y-2">
            @forelse($task->proposals as $proposal)
                <li class="border-b pb-1 flex justify-between">
                    <span>{{ $proposal->freelancer->name ?? '—' }}</span>
                    <span class="text-gray-400 capitalize">{{ $proposal->status }}</span>
                </li>
            @empty
                <li class="text-gray-400">No proposals yet</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection

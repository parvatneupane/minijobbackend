@extends('admin.layouts.app')
@section('title', 'Submissions')

@section('content')
<div class="bg-white rounded-xl shadow-sm border p-5">
    <form method="GET" class="flex gap-3 mb-5">
        <select name="status" class="border rounded-lg px-3 py-2 text-sm">
            <option value="">All Status</option>
            @foreach(['submitted','revision_requested','approved'] as $s)
                <option value="{{ $s }}" @selected(request('status')==$s)>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
            @endforeach
        </select>
        <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm">Filter</button>
    </form>

    <table class="w-full text-sm">
        <thead>
            <tr class="text-left border-b text-gray-500">
                <th class="py-2">Task</th>
                <th>Freelancer</th>
                <th>Submitted</th>
                <th>Status</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($submissions as $submission)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-2">{{ $submission->contract->task->title ?? '—' }}</td>
                    <td>{{ $submission->freelancer->name ?? '—' }}</td>
                    <td>{{ $submission->submitted_at?->format('M d, Y') ?? '—' }}</td>
                    <td class="capitalize">{{ str_replace('_',' ',$submission->status) }}</td>
                    <td class="text-right">
                        <a href="{{ route('admin.submissions.show', $submission) }}" class="text-indigo-600">View</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">{{ $submissions->links() }}</div>
</div>
@endsection

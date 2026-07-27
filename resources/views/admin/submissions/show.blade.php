@extends('admin.layouts.app')
@section('title', 'Submission Detail')

@section('content')
<a href="{{ route('admin.submissions.index') }}" class="text-sm text-indigo-600">← Back</a>

<div class="bg-white rounded-xl shadow-sm border p-5 mt-4 max-w-2xl">
    <h2 class="font-semibold mb-2">{{ $submission->contract->task->title ?? '—' }}</h2>
    <p class="text-sm text-gray-500 mb-4">by {{ $submission->freelancer->name ?? '—' }}</p>
    <p class="text-sm mb-4">{{ $submission->message ?? '—' }}</p>

    @if($submission->attachment)
        <a href="{{ asset('storage/'.$submission->attachment) }}" target="_blank" class="text-indigo-600 text-sm">View Attachment →</a>
    @endif

    <dl class="grid grid-cols-2 gap-3 text-sm mt-4">
        <div><dt class="text-gray-500">Status</dt><dd class="capitalize">{{ str_replace('_',' ',$submission->status) }}</dd></div>
        <div><dt class="text-gray-500">Client Feedback</dt><dd>{{ $submission->client_feedback ?? '—' }}</dd></div>
    </dl>
</div>
@endsection

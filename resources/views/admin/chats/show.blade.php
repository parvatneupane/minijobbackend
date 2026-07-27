@extends('admin.layouts.app')
@section('title', 'Chat Log')

@section('content')
<a href="{{ route('admin.chats.index') }}" class="text-sm text-indigo-600">← Back</a>

<div class="bg-white rounded-xl shadow-sm border p-5 mt-4 max-w-2xl">
    <h2 class="font-semibold mb-4">
        {{ $chat->contract->client->name ?? '—' }} ↔ {{ $chat->contract->freelancer->name ?? '—' }}
        <span class="text-gray-400 text-sm font-normal">({{ $chat->contract->task->title ?? '—' }})</span>
    </h2>

    <div class="space-y-3 max-h-[500px] overflow-y-auto">
        @forelse($messages as $message)
            <div class="p-3 rounded-lg {{ $message->sender_id == $chat->contract->client_id ? 'bg-blue-50' : 'bg-gray-100' }}">
                <p class="text-xs text-gray-500 mb-1">{{ $message->sender->name ?? '—' }} · {{ $message->created_at->format('M d, H:i') }}</p>
                <p class="text-sm">{{ $message->message ?? '['.$message->message_type.' attachment]' }}</p>
            </div>
        @empty
            <p class="text-gray-400 text-sm">No messages yet.</p>
        @endforelse
    </div>
</div>
@endsection

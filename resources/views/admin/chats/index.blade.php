@extends('admin.layouts.app')
@section('title', 'Chats')

@section('content')
<div class="bg-white rounded-xl shadow-sm border p-5">
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left border-b text-gray-500">
                <th class="py-2">Contract</th>
                <th>Client</th>
                <th>Freelancer</th>
                <th>Last Message</th>
                <th>Time</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($chats as $chat)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-2">{{ $chat->contract->task->title ?? '—' }}</td>
                    <td>{{ $chat->contract->client->name ?? '—' }}</td>
                    <td>{{ $chat->contract->freelancer->name ?? '—' }}</td>
                    <td class="truncate max-w-xs">{{ $chat->last_message ?? '—' }}</td>
                    <td>{{ $chat->last_message_time?->diffForHumans() ?? '—' }}</td>
                    <td class="text-right">
                        <a href="{{ route('admin.chats.show', $chat) }}" class="text-indigo-600">View</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">{{ $chats->links() }}</div>
</div>
@endsection

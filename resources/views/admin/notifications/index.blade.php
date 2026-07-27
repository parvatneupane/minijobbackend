@extends('admin.layouts.app')
@section('title', 'Notifications')

@section('content')
<div class="bg-white rounded-xl shadow-sm border p-5">
    <div class="flex justify-between mb-5">
        <h2 class="font-semibold">Sent Notifications</h2>
        <a href="{{ route('admin.notifications.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm">+ Send Notification</a>
    </div>

    <table class="w-full text-sm">
        <thead>
            <tr class="text-left border-b text-gray-500">
                <th class="py-2">User</th>
                <th>Title</th>
                <th>Message</th>
                <th>Sent</th>
            </tr>
        </thead>
        <tbody>
            @foreach($notifications as $n)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-2">{{ $n->user->name ?? '—' }}</td>
                    <td>{{ $n->title }}</td>
                    <td class="truncate max-w-xs">{{ $n->message }}</td>
                    <td>{{ $n->created_at->diffForHumans() }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">{{ $notifications->links() }}</div>
</div>
@endsection

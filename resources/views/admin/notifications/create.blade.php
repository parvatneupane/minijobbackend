@extends('admin.layouts.app')
@section('title', 'Send Notification')

@section('content')
<div class="bg-white rounded-xl shadow-sm border p-5 max-w-lg">
    <form method="POST" action="{{ route('admin.notifications.store') }}" class="space-y-4">
        @csrf
        <div>
            <label class="text-sm text-gray-600">Send To</label>
            <select name="target" id="target" class="border rounded-lg px-3 py-2 w-full text-sm" onchange="document.getElementById('userSelect').classList.toggle('hidden', this.value !== 'single')">
                <option value="all">All Users</option>
                <option value="client">All Clients</option>
                <option value="freelancer">All Freelancers</option>
                <option value="single">Specific User</option>
            </select>
        </div>
        <div id="userSelect" class="hidden">
            <label class="text-sm text-gray-600">User</label>
            <select name="user_id" class="border rounded-lg px-3 py-2 w-full text-sm">
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role }})</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-sm text-gray-600">Title</label>
            <input type="text" name="title" value="{{ old('title') }}" class="border rounded-lg px-3 py-2 w-full text-sm" required>
        </div>
        <div>
            <label class="text-sm text-gray-600">Message</label>
            <textarea name="message" rows="4" class="border rounded-lg px-3 py-2 w-full text-sm" required>{{ old('message') }}</textarea>
        </div>
        <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm">Send</button>
        <a href="{{ route('admin.notifications.index') }}" class="text-sm text-gray-500 ml-2">Cancel</a>
    </form>
</div>
@endsection

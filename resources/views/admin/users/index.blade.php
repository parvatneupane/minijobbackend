@extends('admin.layouts.app')
@section('title', 'Users')

@section('content')
<div class="bg-white rounded-xl shadow-sm border p-5">
    <form method="GET" class="flex flex-wrap gap-3 mb-5">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or email..."
               class="border rounded-lg px-3 py-2 text-sm w-64">
        <select name="role" class="border rounded-lg px-3 py-2 text-sm">
            <option value="">All Roles</option>
            @foreach(['client','freelancer','admin'] as $role)
                <option value="{{ $role }}" @selected(request('role')==$role)>{{ ucfirst($role) }}</option>
            @endforeach
        </select>
        <select name="status" class="border rounded-lg px-3 py-2 text-sm">
            <option value="">All Status</option>
            @foreach(['pending','active','blocked'] as $status)
                <option value="{{ $status }}" @selected(request('status')==$status)>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
        <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm">Filter</button>
        <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-500 self-center">Reset</a>
    </form>

    <table class="w-full text-sm">
        <thead>
            <tr class="text-left border-b text-gray-500">
                <th class="py-2">Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Joined</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-2">{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td><span class="capitalize">{{ $user->role }}</span></td>
                    <td>
                        <span class="px-2 py-1 rounded-full text-xs
                            {{ $user->status == 'active' ? 'bg-green-100 text-green-700' :
                               ($user->status == 'blocked' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </td>
                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                    <td class="text-right space-x-2">
                        <a href="{{ route('admin.users.show', $user) }}" class="text-indigo-600">View</a>
                        @if($user->status !== 'blocked')
                            <form action="{{ route('admin.users.status', $user) }}" method="POST" class="inline">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="blocked">
                                <button class="text-red-600" onclick="return confirm('Block this user?')">Block</button>
                            </form>
                        @else
                            <form action="{{ route('admin.users.status', $user) }}" method="POST" class="inline">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="active">
                                <button class="text-green-600">Unblock</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">{{ $users->links() }}</div>
</div>
@endsection

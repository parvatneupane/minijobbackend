@extends('admin.layouts.app')
@section('title', 'Freelancer Profiles')

@section('content')
<div class="bg-white rounded-xl shadow-sm border p-5">
    <form method="GET" class="flex gap-3 mb-5">
        <select name="status" class="border rounded-lg px-3 py-2 text-sm">
            <option value="">All Status</option>
            @foreach(['inactive','active','blocked'] as $s)
                <option value="{{ $s }}" @selected(request('status')==$s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm">Filter</button>
    </form>

    <table class="w-full text-sm">
        <thead>
            <tr class="text-left border-b text-gray-500">
                <th class="py-2">Freelancer</th>
                <th>Title</th>
                <th>Rate</th>
                <th>Rating</th>
                <th>Jobs</th>
                <th>Status</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($profiles as $profile)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-2">{{ $profile->user->name ?? '—' }}</td>
                    <td>{{ $profile->title ?? '—' }}</td>
                    <td>Rs. {{ $profile->hourly_rate }}</td>
                    <td>{{ $profile->rating }}</td>
                    <td>{{ $profile->completed_jobs }}</td>
                    <td class="capitalize">{{ $profile->status }}</td>
                    <td class="text-right">
                        <a href="{{ route('admin.freelancer-profiles.show', $profile) }}" class="text-indigo-600">View</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">{{ $profiles->links() }}</div>
</div>
@endsection

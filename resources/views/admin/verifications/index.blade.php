@extends('admin.layouts.app')
@section('title', 'Verifications')

@section('content')
<div class="bg-white rounded-xl shadow-sm border p-5">
    <form method="GET" class="flex gap-3 mb-5">
        <select name="status" class="border rounded-lg px-3 py-2 text-sm">
            <option value="">All Status</option>
            @foreach(['pending','approved','rejected'] as $s)
                <option value="{{ $s }}" @selected(request('status')==$s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm">Filter</button>
    </form>

    <table class="w-full text-sm">
        <thead>
            <tr class="text-left border-b text-gray-500">
                <th class="py-2">User</th>
                <th>Full Name</th>
                <th>Status</th>
                <th>Submitted</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($verifications as $v)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-2">{{ $v->user->name ?? '—' }}</td>
                    <td>{{ $v->full_name }}</td>
                    <td>
                        <span class="px-2 py-1 rounded-full text-xs
                            {{ $v->status == 'approved' ? 'bg-green-100 text-green-700' :
                               ($v->status == 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                            {{ ucfirst($v->status) }}
                        </span>
                    </td>
                    <td>{{ $v->created_at->format('M d, Y') }}</td>
                    <td class="text-right">
                        <a href="{{ route('admin.verifications.show', $v) }}" class="text-indigo-600">Review</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">{{ $verifications->links() }}</div>
</div>
@endsection

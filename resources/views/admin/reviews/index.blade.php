@extends('admin.layouts.app')
@section('title', 'Reviews')

@section('content')
<div class="bg-white rounded-xl shadow-sm border p-5">
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left border-b text-gray-500">
                <th class="py-2">Task</th>
                <th>Client</th>
                <th>Freelancer</th>
                <th>Rating</th>
                <th>Review</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reviews as $review)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-2">{{ $review->task->title ?? '—' }}</td>
                    <td>{{ $review->client->name ?? '—' }}</td>
                    <td>{{ $review->freelancer->name ?? '—' }}</td>
                    <td>{{ $review->rating }} / 5</td>
                    <td class="truncate max-w-xs">{{ $review->review ?? '—' }}</td>
                    <td class="text-right">
                        <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button class="text-red-600" onclick="return confirm('Remove this review?')">Remove</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">{{ $reviews->links() }}</div>
</div>
@endsection

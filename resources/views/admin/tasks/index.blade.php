@extends('layouts.app')

@section('content')

<div class="container">

    <h2>Tasks</h2>

    <a href="{{ route('tasks.create') }}" class="btn btn-primary mb-3">
        Create Task
    </a>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif


    <table class="table table-bordered">

        <thead>
            <tr>
                <th>Title</th>
                <th>User</th>
                <th>Category</th>
                <th>Deadline</th>
                <th>Budget</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>


        <tbody>

        @foreach($tasks as $task)

            <tr>

                <td>
                    {{ $task->title }}
                </td>

                <td>
                    {{ $task->user->name ?? 'N/A' }}
                </td>

                <td>
                    {{ $task->category->name ?? 'N/A' }}
                </td>

                <td>
                    {{ $task->deadline }}
                </td>

                <td>
                    {{ $task->budget }}
                </td>

                <td>
                    {{ $task->status }}
                </td>

                <td>

                    <a href="{{ route('tasks.show',$task->id) }}"
                       class="btn btn-info btn-sm">
                        View
                    </a>


                    <a href="{{ route('tasks.edit',$task->id) }}"
                       class="btn btn-warning btn-sm">
                        Edit
                    </a>


                    <form action="{{ route('tasks.destroy',$task->id) }}"
                          method="POST"
                          style="display:inline">

                        @csrf
                        @method('DELETE')

                        <button class="btn btn-danger btn-sm">
                            Delete
                        </button>

                    </form>

                </td>

            </tr>

        @endforeach


        </tbody>

    </table>


</div>

@endsection

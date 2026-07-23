@extends('layouts.app')

@section('content')

<div class="container">

<h2>Create Task</h2>


<form action="{{ route('tasks.store') }}" method="POST">

@csrf


<input class="form-control mb-2"
name="title"
placeholder="Title">


<textarea class="form-control mb-2"
name="description"
placeholder="Description"></textarea>


<input class="form-control mb-2"
name="deadline"
type="date">


<input class="form-control mb-2"
name="required_skills"
placeholder="Required Skills">


<input class="form-control mb-2"
name="min_experience"
placeholder="Minimum Experience">


<input class="form-control mb-2"
name="budget"
placeholder="Budget">


<select name="category_id" class="form-control mb-2">

<option>Select Category</option>

@foreach($categories as $category)

<option value="{{ $category->id }}">
{{ $category->name }}
</option>

@endforeach

</select>



<button class="btn btn-success">
Save Task
</button>


</form>


</div>

@endsection

@extends('layouts.adminlayouts')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/user-setting.css') }}">
@endpush


@section('content')


<meta name="csrf-token" content="{{csrf_token()}}">



@if(session('success'))

<div class="alert alert-success">
{{session('success')}}
</div>

@endif



@if(session('error'))

<div class="alert alert-danger">
{{session('error')}}
</div>

@endif





<div class="card header-card">


<div class="page-header">


<h2>
User Accounts
</h2>


<button 
id="toggleFormBtn"
class="btn btn-black">


{{isset($editUser)?'Close Form':'Add New User'}}


</button>


</div>


</div>







<div class="card"
id="userFormCard">



<h3>

{{isset($editUser)
?'Edit User'
:'Create User'}}

</h3>




<form method="POST"

@if(isset($editUser))

action="/admin/usersettings/{{$editUser->id}}"

@else

action="/admin/usersettings/add"

@endif


>


@csrf


@if(isset($editUser))

@method('PUT')

@endif





<div class="form-grid">



<div>

<label>Name</label>

<input

type="text"

name="name"

required

value="{{old('name',$editUser->name ?? '')}}"

>

</div>




<div>

<label>Email</label>

<input

type="email"

name="email"

required

value="{{old('email',$editUser->email ?? '')}}"

>

</div>





<div>

<label>Password</label>

<input

type="password"

name="password"

placeholder="Leave empty to keep old password"

>

</div>





<div>

<label>Role</label>


<select name="role">


@foreach($roles as $role)

<option

value="{{$role}}"

@if(($editUser->role ?? '')==$role)

selected

@endif

>

{{ucfirst($role)}}


</option>


@endforeach


</select>


</div>






<div>

<label>Status</label>


<select name="status">


<option value="active">

Active

</option>



<option value="pending">

Pending

</option>



<option value="blocked">

Blocked

</option>


</select>


</div>



</div>





<br>


<button class="btn btn-primary">


Save User


</button>



</form>



</div>










<div class="card">


<h3>
Search Users
</h3>


<form method="GET"
action="/admin/usersettings">


<div class="filter-box">



<input

id="searchUser"

name="search"

placeholder="Search name or email"

value="{{request('search')}}"

>



<select name="role">


<option value="">

All Roles

</option>


@foreach($roles as $role)


<option value="{{$role}}">


{{$role}}


</option>


@endforeach



</select>




<button class="btn btn-black">

Filter

</button>



</div>



</form>


</div>









<div class="card">


<h3>
Approved Users
</h3>




<div class="table-wrapper">


<table>


<thead>

<tr>


<th>
ID
</th>


<th>
Name
</th>


<th>
Email
</th>


<th>
Role
</th>


<th>
Action
</th>


</tr>


</thead>





<tbody>



@foreach($users as $user)


<tr>


<td>
{{$user->id}}
</td>



<td>
{{$user->name}}
</td>



<td>
{{$user->email}}
</td>



<td>


<span class="role-badge">

{{$user->role}}

</span>


</td>





<td>



<a

class="btn btn-black"

href="/admin/usersettings?edit_id={{$user->id}}"

>

Edit

</a>





<form

style="display:inline"

method="POST"

action="/admin/usersettings/{{$user->id}}"

>


@csrf

@method('DELETE')


<button

class="btn btn-danger delete-btn"

>


Delete


</button>


</form>






@if($user->role!='admin')


<form method="POST"
action="/admin/users/{{$user->id}}/impersonate"
style="display:inline">

@csrf

<button class="btn btn-warning">

Login As

</button>

</form>



@endif




</td>


</tr>



@endforeach




</tbody>


</table>



</div>



</div>









<div class="card">


<h3>
Pending Approvals
</h3>




@forelse($pendingUsers as $user)



<div class="pending-item">


<div>


<strong>
{{$user->name}}
</strong>


<br>


{{$user->email}}


</div>




<button

class="btn btn-success approve-user"

data-id="{{$user->id}}"

>

Approve

</button>



</div>




@empty


<p>
No pending users
</p>



@endforelse



</div>







<div id="toast"></div>





@endsection





@push('scripts')

<script src="{{ asset('js/user-settings.js') }}"></script>

@endpush

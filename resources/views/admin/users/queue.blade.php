@extends('layouts.app')

@section('content')

<!-- Header -->
@include('partials._header')
<!-- END Header -->

<div class="container">
    <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
            @include('partials._sidebar')
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        	<div class="row">
        		<h2>Attending Users</h2>
				@if($attendingUsers->count())
					<div class="col-sm-10 col-sm-offset-1">
						<table id="mytable" class="table table-bordred table-striped">
						    <thead>
						        <tr>
						            <th>Employee</th>
						       		<th>User</th>
						        </tr>
						    </thead>
						    <tbody>
						        @foreach($attendingUsers as $attendingUser)
						        	<tr>
							            <td>{{ $attendingUser->employee->fullname }}</td>
							            <td>{{ $attendingUser->user->fullname }}</td>
							        </tr>
						        @endforeach
						    </tbody>
						</table>
					</div>
				@else
					<div class="alert alert-info">
						There are no current attendings.
					</div>
				@endif
			</div>

			<div class="row">
				<h2>Queue</h2>
				@if($users->count())
					<div class="col-sm-10 col-sm-offset-1">
						<table id="mytable" class="table table-bordred table-striped">
						    <thead>
						        <tr>
						            <th>Name</th>
						            <th>Email</th>
						            <th>Mobile</th>
						        </tr>
						    </thead>
						    <tbody>
						        @foreach($users as $user)
						        	<tr>
							            <td>{{ $user->fullname }}</td>
							            <td>{{ $user->email }}</td>
							            <td>{{ $user->mobile }}</td>
							        </tr>
						        @endforeach
						    </tbody>
						</table>
					</div>
				@else
					<div class="alert alert-info">
						There are no users to show.
					</div>
				@endif
			</div>
        </div>
    </div>
</div>
@endsection

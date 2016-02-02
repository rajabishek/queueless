@extends('layouts.app')

@section('content')

<!-- Header -->
@include('partials._header')
<!-- END Header -->

@section('scripts')
@parent
<script src="//js.pusher.com/3.0/pusher.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.16/vue.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue-resource/0.7.0/vue-resource.js"></script>
<script src="/js/queue.js"></script>
@endsection

<div class="container">
    <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
            @include('partials._sidebar')
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        	<div class="row">
        		<h2>Attending Users</h2>
				<div class="col-sm-10 col-sm-offset-1" v-if="attendingUsers.length > 0">
					<table id="mytable" class="table table-bordred table-striped">
					    <thead>
					        <tr>
					            <th>Employee</th>
					       		<th>User</th>
					        </tr>
					    </thead>
					    <tbody>
					        <tr v-for="attendingUser in attendingUsers">
					            <td>@{{ attendingUser.employee.fullname }}</td>
					            <td>@{{ attendingUser.user.fullname }}</td>
					        </tr>
					    </tbody>
					</table>
				</div>
			
				<div class="alert alert-info" v-else>
					There are no current attendings.
				</div>
			</div>

			<div class="row">
				<h2>Queue</h2>
				<div class="col-sm-10 col-sm-offset-1" v-if="users.length > 0">
					<table id="mytable" class="table table-bordred table-striped">
					    <thead>
					        <tr>
					            <th>Name</th>
					            <th>Email</th>
					            <th>Mobile</th>
					        </tr>
					    </thead>
					    <tbody>
					        <tr v-for="user in users">
					            <td>@{{ user.fullname }}</td>
					            <td>@{{ user.email }}</td>
					            <td>@{{ user.mobile }}</td>
					        </tr>
					    </tbody>
					</table>
				</div>
				<div class="alert alert-info" v-else>
					There are no users to show.
				</div>
			</div>
        </div>
    </div>
</div>
@endsection

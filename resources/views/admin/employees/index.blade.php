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
				@if($employees->count())
					<div class="col-sm-10 col-sm-offset-1">
						<h2>Employees</h2>
						<table id="mytable" class="table table-bordred table-striped">
						    <thead>
						        <tr>
						            <th>Name</th>
						            <th>Email</th>
						            <th>Designation</th>
						            <th>Edit</th>
						            <th>Delete</th>
						        </tr>
						    </thead>
						    <tbody>
						        @foreach($employees as $employee)
						        	<tr>
							            <td><a href="{{ route('admin.employees.show',[$domain,$employee->id]) }}">{{ $employee->fullname }}</a></td>
							            <td>{{ $employee->email }}</td>
							            <td>{{ $employee->designation }}</td>
							            <td>
							                <p><a class="btn btn-primary btn-xs" href="{{ route('admin.employees.edit',[$domain,$employee->id]) }}"><span class="glyphicon glyphicon-pencil"></span></a></p>
							            </td>
							            <td>
							                <p><button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#delete" data-placement="top" rel="tooltip"><span class="glyphicon glyphicon-trash"></span></button></p>
							            </td>
							        </tr>
						        @endforeach
						    </tbody>
						</table>
					</div>
				@else
					<div class="alert alert-info">
						There are no employees to show. We suggest you to add employees in your organisation.
					</div>
					<a class="btn btn-sm btn-success" href="{{ route('admin.employees.create',$domain) }}">Add Employee</a>
				@endif
			</div>
        </div>
    </div>
</div>
@endsection

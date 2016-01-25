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
				<div class="col-md-8">
					
					@include('flash::message')

					<div class="panel panel-info">
					    <div class="panel-heading">
					        <h3 class="panel-title">{{ $employee->fullname }}</h3>
					    </div>
					    <div class="panel-body">
					        <div class="row">
					            <div class="col-md-3 col-lg-3" align="center"> <img alt="User Pic" src="{{ $employee->photo_css }}" class="img-circle img-responsive"></div>
					            <div class=" col-md-9 col-lg-9 ">
					                <table class="table table-user-information">
					                    <tbody>
					                        <tr>
					                            <td>Name:</td>
					                            <td>{{ $employee->fullname }}</td>
					                        </tr>
					                        <tr>
					                            <td>Designation:</td>
					                            <td>{{ $employee->designation }}</td>
					                        </tr>
					                        <tr>
					                            <td>Hire date:</td>
					                            <td>{{ $employee->created_at }}</td>
					                        </tr>
					                        @if(isset($employee->mobile))
					                        	<tr>
						                            <td>Mobile</td>
						                            <td>{{ $employee->mobile }}</td>
						                        </tr>
					                        @endif
					                        @if(isset($employee->address))
					                        	<tr>
						                            <td>Home Address</td>
						                            <td>{{ $employee->address }}</td>
						                        </tr>
					                        @endif
					                        <tr>
					                            <td>Email</td>
					                            <td><a href="mailto:{{ $employee->email }}">{{ $employee->email }}</a></td>
					                        </tr>
					                    </tbody>
					                </table>
					                <a href="#" class="btn btn-sm btn-success">My Sales Performance</a>
					                <a href="#" class="btn btn-sm btn-info">Team Sales Performance</a>
					            </div>
					        </div>
					    </div>
					    <div class="panel-footer">
					        <a data-original-title="Broadcast Message" data-toggle="tooltip" type="button" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-envelope"></i></a>
					        <span class="pull-right">
					        <a href="{{ route('admin.employees.edit',[$domain,$employee->id]) }}" class="btn btn-sm btn-warning"><i class="glyphicon glyphicon-edit"></i></a>
					        <a data-original-title="Remove this user" data-toggle="tooltip" type="button" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-remove"></i></a>
					        </span>
					    </div>
					</div>
				</div>
			</div>
        </div>
    </div>
</div>
@endsection
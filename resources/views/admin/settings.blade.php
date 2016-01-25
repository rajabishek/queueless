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
			
			@include('flash::message')
			
			<div class="row">
				<div class="col-md-7">
					<div class="panel panel-info">
					    <div class="panel-heading">
					        <h3 class="panel-title">Settings</h3>
					    </div>
					    <div class="panel-body">
	                    	{{ Form::model($employee,['route' => ['admin.settings.store', $domain],'role'=>'form']) }}
	                            <fieldset>
	                                {{ Form::token() }}
	                                <div class="form-group {{set_error('fullname', $errors)}}">
	                                    {{ Form::label('fullname','Fullname') }}
	                                    {{ Form::text('fullname',null,['class'=>'form-control input-sm']) }}
	                                    {!! get_error('fullname', $errors) !!}
	                                </div>
	                                <div class="form-group {{set_error('email', $errors)}}">
	                                    {{ Form::label('email','Email Address') }}
	                                    {{ Form::email(null,$employee->email,['class'=>'form-control input-sm','disabled']) }}
	                                    {!! get_error('email', $errors) !!}
	                                </div>
	                                <div class="form-group {{set_error('mobile', $errors)}}">
	                                    {{ Form::label('mobile','Mobile') }}
	                                    {{ Form::text('mobile',null,['class'=>'form-control input-sm']) }}
	                                    {!! get_error('mobile', $errors) !!}
	                                </div>
	                                <div class="form-group {{set_error('address', $errors)}}">
	                                    {{ Form::label('address','Address') }}
	                                    {{ Form::textarea('address',null,['class'=>'form-control input-sm','rows'=>3]) }}
	                                    {!! get_error('address', $errors) !!}
	                                </div>
	                                <div class="form-group">
	                                    {{ Form::submit('Save',['class'=>'btn btn-sm btn-success']) }}
	                                </div>
	                            </fieldset>
	                        {{ Form::close() }}
					    </div>
					</div>
				</div>
				<div class="col-md-5">
					<div class="panel panel-info">
					    <div class="panel-heading">
					        <h3 class="panel-title">Change Password</h3>
					    </div>
					    <div class="panel-body">
					    	{{ Form::open(['route' => ['admin.settings.changePassword', $domain],'role'=>'form']) }}
		                        <fieldset>
		                            {{ Form::token() }}
		                            <div class="form-group {{set_error('old_password', $errors)}}">
		                                {{ Form::label('old_password','Current Password') }}
		                                {{ Form::password('old_password',['class'=>'form-control input-sm']) }}
		                                {!! get_error('old_password', $errors) !!}
		                            </div>
		                            <div class="form-group {{set_error('password', $errors)}}">
		                                {{ Form::label('password','New Password') }}
		                                {{ Form::password('password',['class'=>'form-control input-sm']) }}
		                                {!! get_error('password', $errors) !!}
		                            </div>
		                            <div class="form-group {{set_error('password_confirmation', $errors)}}">
		                                {{ Form::label('password_confirmation','Confirm Password') }}
		                                {{ Form::password('password_confirmation',['class'=>'form-control input-sm']) }}
		                                {!! get_error('password_confirmation', $errors) !!}
		                            </div>
		                            <div class="form-group">
		                                {{ Form::submit('Change',['class'=>'btn btn-sm btn-success']) }}
		                            </div>
		                        </fieldset>
		                    {{ Form::close() }}
					    </div>
					 </div>
				</div>
			</div>
        </div>
    </div>
</div>
@endsection
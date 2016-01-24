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
        	<h2>Add an employee</h2> <br />
            {{ Form::open(['route' => 'auth.postRegister','role'=>'form']) }}
                <fieldset>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 {{set_error('fullname', $errors)}}">
                                {{ Form::label('fullname','Fullname') }}
                                {{ Form::text('fullname',null,['class'=>'form-control input-sm']) }}
                                {!! get_error('fullname', $errors) !!}
                            </div>
                            <div class="col-xs-12 col-sm-6 {{set_error('email', $errors)}}">
                                {{ Form::label('email','Email Address') }}
                                {{ Form::email('email',null,['class'=>'form-control input-sm']) }}
                                {!! get_error('email', $errors) !!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 {{set_error('password', $errors)}}">
                                {{ Form::label('password','Password') }}
                                {{ Form::password('password',['class'=>'form-control input-sm']) }}
                                {!! get_error('password', $errors) !!}
                            </div>
                            <div class="col-xs-12 col-sm-6 {{set_error('password_confirmation', $errors)}}">
                                {{ Form::label('password_confirmation','Confirm Password') }}
                                {{ Form::password('password_confirmation',['class'=>'form-control input-sm']) }}
                                {!! get_error('password_confirmation', $errors) !!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                        	<div class="col-xs-12 col-sm-6 {{set_error('address', $errors)}}">
                                {{ Form::label('address','Address') }}
                                {{ Form::textarea('address',null,['class'=>'form-control input-sm','rows'=>3]) }}
                                {!! get_error('address', $errors) !!}
                            </div>
                            <div class="col-xs-12 col-sm-6 {{set_error('mobile', $errors)}}">
                                {{ Form::label('mobile','Mobile Number') }}
                                {{ Form::text('mobile',null,['class'=>'form-control input-sm']) }}
                                {!! get_error('mobile', $errors) !!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-12 col-xs-6">
                                {{ Form::submit('Add Employee',['class'=>'btn btn-sm btn-success']) }}
                            </div>
                        </div>
                    </div>
                </fieldset>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection

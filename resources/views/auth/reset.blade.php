@extends('layouts.app')

@section('content')

<!-- Header -->
@include('partials._header')
<!-- END Header -->

<div class="container">
    <div class="row">
        <div class="col-md-8">
            @include('partials._banner')
        </div>
        <div class="col-md-4">
            <div class="row">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">Reset Password</h3>
                        <a href="{{ route('auth.getLogin',$domain) }}" style="color:white; float:right; font-size: 85%; position: relative; top:-18px">Sign In</a>
                    </div>
                    <div class="panel-body">
                        <h3 align="center">{{ $organisation->name }}</h3>
                        <p class="well">Choose a new password for this account. This password will replace the old one.</p>
                        
                        @include('flash::message')

                        @include('partials._errormessage', ['errors' => $errors, 'field' => 'token'])
                        
                        {{ Form::open(['route' => ['password.postReset', $domain],'role'=>'form']) }}
                            <fieldset>
                                {{ Form::hidden('token',$token) }}
                                {{ Form::token() }}
                                <div class="form-group {{set_error('email', $errors)}}">
                                    {{ Form::label('email','Email Address') }}
                                    {{ Form::email('email',null,['class'=>'form-control input-sm']) }}
                                    {!! get_error('email', $errors) !!}
                                </div>
                                <div class="form-group {{set_error('password', $errors)}}">
                                    {{ Form::label('password','Password') }}
                                    {{ Form::password('password',['class'=>'form-control input-sm']) }}
                                    {!! get_error('password', $errors) !!}
                                </div>
                                <div class="form-group {{set_error('password_confirmation', $errors)}}">
                                    {{ Form::label('password_confirmation','Confirm Password') }}
                                    {{ Form::password('password_confirmation',['class'=>'form-control input-sm']) }}
                                    {!! get_error('password_confirmation', $errors) !!}
                                </div>
                                <div class="form-group">
                                    {{ Form::submit('Reset Password',['class'=>'btn btn-sm btn-info']) }}
                                </div>
                            </fieldset>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

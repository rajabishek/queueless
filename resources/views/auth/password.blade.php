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
                        <h3 class="panel-title">Password Reminder</h3>
                        <a href="{{ route('auth.getLogin',$domain) }}" style="color:white; float:right; font-size: 85%; position: relative; top:-18px">Sign In</a>
                    </div>
                    <div class="panel-body">
                        <h3 align="center">{{ $organisation->name }}</h3>
                        <p class="well">Please provide your account’s email and we will send you your password.</p>
                                                
                        @include('flash::message')

                        {{ Form::open(['route' => ['password.postEmail', $domain],'role'=>'form']) }}
                            <fieldset>
                                {{ Form::token() }}
                                <div class="form-group {{set_error('email', $errors)}}">
                                    {{ Form::label('email','Email Address') }}
                                    {{ Form::email('email',null,['class'=>'form-control input-sm']) }}
                                    {!! get_error('email', $errors) !!}
                                </div>
                                <div class="form-group">
                                    {{ Form::submit('Send Email',['class'=>'btn btn-sm btn-info']) }}
                                </div>
                                <hr style="margin-top:10px;margin-bottom:10px;">
                                <div class="form-group">
                                    <div style="font-size:85%">
                                        Don't have an account! 
                                        <a href="{{ route('auth.getRegister') }}">Sign Up</a>
                                    </div>
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

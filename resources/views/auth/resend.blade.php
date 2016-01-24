@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <br />
        <div class="col-sm-4 col-sm-offset-4">
            <div class="row">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">Account Locked</h3>
                        {{-- <a href="{{ route('password.getEmail',$domain) }}" style="color:white; float:right; font-size: 85%; position: relative; top:-18px">Forgot Your Password?</a> --}}
                    </div>
                    <div class="panel-body">
                        
                        <div class="alert alert-warning">
                            The admin needs to confirm their email address before proceeding.
                        </div>
                        
                        @include('flash::message')

                        {{ Form::open(['route'=>'auth.verification.postResend','role'=>'form']) }}
                            <fieldset>
                                {{ Form::token() }}
                                <div class="form-group {{set_error('email', $errors)}}">
                                    {{ Form::label('email','Email Address (Admin)') }}
                                    {{ Form::email('email',null,['class'=>'form-control input-sm']) }}
                                    {!! get_error('email', $errors) !!}
                                </div>
                                <div class="form-group {{set_error('domain', $errors)}}">
                                    {{ Form::label('domain','Provide the company URL') }}
                                    {{ Form::text('domain',null,['class'=>'form-control input-sm','size'=>20,'style'=>'padding-right: 120px']) }}
                                    <span class="append">.queueless.com</span>
                                    {!! get_error('domain', $errors) !!}
                                </div>
                                <div class="form-group">
                                    {{ Form::submit('Resend',['class'=>'btn btn-sm btn-info']) }}
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

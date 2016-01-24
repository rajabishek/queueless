@extends('layouts.app')

@section('content')

<!-- Header -->
@include('partials._header')
<!-- END Header -->

<div class="container">
    <div class="row">
        <div class="col-md-6">
            @include('partials._banner')
        </div>
        <div class="col-md-6">
            <div class="row">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <h3 class="panel-title">Sign Up</h3>
                    </div>
                    <div class="panel-body">
                        
                        @include('flash::message')

                        {{ Form::open(['route' => 'auth.postRegister','role'=>'form']) }}
                            <fieldset>
                                {{ Form::token() }}
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 {{set_error('email', $errors)}}">
                                            {{ Form::label('email','Email Address (Admin)') }}
                                            {{ Form::email('email',null,['class'=>'form-control input-sm']) }}
                                            {!! get_error('email', $errors) !!}
                                        </div>
                                        <div class="col-xs-12 col-sm-6 {{set_error('fullname', $errors)}}">
                                            {{ Form::label('fullname','Fullname') }}
                                            {{ Form::text('fullname',null,['class'=>'form-control input-sm']) }}
                                            {!! get_error('fullname', $errors) !!}
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
                                        <div class="col-xs-12 col-sm-6 {{set_error('name', $errors)}}">
                                            {{ Form::label('name','Organisation Name') }}
                                            {{ Form::text('name',null,['class'=>'form-control input-sm']) }}
                                            {!! get_error('name', $errors) !!}
                                        </div>
                                        <div class="col-xs-12 col-sm-6 {{set_error('domain', $errors)}}">
                                            {{ Form::label('domain','Choose a company URL') }}
                                            {{ Form::text('domain',null,['class'=>'form-control input-sm','size'=>20,'style'=>'padding-right: 120px']) }}
                                             <span class="append">.queueless.com</span>
                                            {!! get_error('domain', $errors) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xs-12 col-xs-6">
                                            {{ Form::submit('Register',['class'=>'btn btn-sm btn-success']) }}
                                        </div>
                                    </div>
                                </div>
                            {{ Form::close() }}
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

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

                        <form role="form" method="POST" action="{{ route('auth.getRegister') }}">
                            <fieldset>
                                {!! csrf_field() !!}

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 {{set_error('email', $errors)}}">
                                            <label for="email">Email Address ( Admin )</label>
                                            <input type="email" class="form-control input-sm" name="email" value="{{ old('email') }}" id="email">
                                            {!! get_error('email', $errors) !!}
                                        </div>
                                        <div class="col-xs-12 col-sm-6 {{set_error('fullname', $errors)}}">
                                            <label for="fullname">Fullname</label>
                                            <input type="text" class="form-control input-sm" name="fullname" value="{{ old('fullname') }}" id="fullname">
                                            {!! get_error('fullname', $errors) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 {{set_error('password', $errors)}}">
                                            <label for="password">Password</label>
                                            <input type="password" class="form-control input-sm" name="password">
                                            {!! get_error('password', $errors) !!}
                                        </div>
                                        <div class="col-xs-12 col-sm-6 {{set_error('password_confirmation', $errors)}}">
                                            <label for="password_confirmation">Confirm Password</label>
                                            <input type="password" class="form-control input-sm" name="password_confirmation">
                                            {!! get_error('password_confirmation', $errors) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 {{set_error('name', $errors)}}">
                                            <label for="name">Organisation Name</label>
                                            <input type="text" class="form-control input-sm" name="name" value="{{ old('name') }}" id="name">
                                            {!! get_error('name', $errors) !!}
                                        </div>
                                        <div class="col-xs-12 col-sm-6 {{set_error('domain', $errors)}}">
                                            <label for="domain">Choose a company URL</label>
                                             <input type="text" class="form-control input-sm" name="domain" value="{{ old('domain') }}" id="domain" size="20" style="padding-right: 120px">
                                             <span class="append">.queueless.com</span>
                                            {!! get_error('domain', $errors) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xs-12 col-xs-6">
                                            <button type="submit" class="btn btn-sm btn-success">Register</button>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

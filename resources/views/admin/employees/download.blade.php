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
					        <h3 class="panel-title">Export as Excel file</h3>
					    </div>
					    <div class="panel-body">
					        <h2><i class="fa fa-file-excel-o"></i> Excel</h2>
					        <p>Employee data will will be downloaded as an excel sheet.</p>
							<div class="row">
								<div class="col-sm-6">
									{{ Form::open(['route' => ['admin.employees.postDownload',$domain]]) }}
										{{ Form::hidden('format','excel') }}
										<div class="form-group {{set_error('orderby', $errors)}}">
		                                    {{ Form::label('orderby','Order By') }}
		                                    {{ Form::select('orderby',$orderByList,null,['class'=>'form-control input-sm']) }}
		                                    {!! get_error('orderby', $errors) !!}
		                                </div>
		                                <div class="form-group {{set_error('ordertype', $errors)}}">
		                                    {{ Form::label('ordertype','Order Type') }}
		                                    {{ Form::select('ordertype',$orderTypeList,null,['class'=>'form-control input-sm']) }}
		                                    {!! get_error('ordertype', $errors) !!}
		                                </div>
		                                <div class="form-group">
		                                    {{ Form::submit('Download',['class'=>'btn btn-sm btn-success']) }}
		                                </div>
							        {{ Form::close() }}
								</div>
							</div>
					    </div>
					</div>
				</div>
			</div>
        </div>
    </div>
</div>
@endsection
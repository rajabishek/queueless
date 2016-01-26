@if($errors->has($field))
    <div class="alert alert-danger alert-dismissable">
	    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	    {{ $errors->first($field) }}
	</div>
@endif
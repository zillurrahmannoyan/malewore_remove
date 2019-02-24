 @extends('backend.layouts.admin')

@section('title')
Locations
@stop

@section('content')

<div class="row">
	<div class="col-md-4">

		<div class="portlet light bordered">
		    <div class="portlet-title">
		        <div class="caption">
		            <span class="caption-subject font-dark sbold uppercase">Add Location</span>
		        </div>
		    </div>
		    <div class="portlet-body form">
		        <form class="form-horizontal" role="form" method="post">

		        {!! csrf_field() !!}
		        <input type="hidden" name="op" value="1">   
		        <input type="hidden" name="parent" value=" {{ Input::get('parent', 0) }}">   

				<div class="form-body">

					<div class="form-group">
						<div class="col-md-12">
							<label>Name</label>
							<input name="name" class="form-control" value="{{ Input::old('name', @$info->name) }}">		
						    <!-- START error message -->
						    {!! $errors->first('name','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
						    <!-- END error message -->
						</div>
					</div>

					<div class="form-group">
						<div class="col-md-12">
							<label>Slug</label>
							<input name="slug" class="form-control" value="{{ Input::old('slug', @$info->slug) }}">			
						    <!-- START error message -->
						    {!! $errors->first('slug','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
						    <!-- END error message -->
						</div>
					</div>
					
				</div>

				@if( Input::get('id') )
				<button type="submit" class="btn btn-primary btn-block">Update Location</button>
				<a href="{{ URL::route('backend.locations.index') }}" class="btn btn-default btn-block">Cancel</a>				
				@else
				<button type="submit" class="btn btn-primary btn-block">Add Location</button>
				@endif

				</form>
			</div>
		</div>

	</div>

	<div class="col-md-8">
		@include('backend.locations.tpl.list')
	</div>
</div>

@stop


@section('top_style')
@stop


@section('bottom_style')
@stop

@section('bottom_plugin_script')
@stop

@section('bottom_script')
<script>
$(document).on('keyup', '[name="name"]', function(){
	var title = $('[name="name"]').val();
	title = title.replace(/\s+/g, '-').toLowerCase();
	$('[name="slug"]').val(title)
});	
</script>
@stop
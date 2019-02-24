 @extends('backend.layouts.admin')

@section('title')
<a href="{{ URL::route('backend.sds.index') }}">All SDS</a> | Request an SDS
@stop

@section('content')
<form method="post">
{{ csrf_field() }}
<div class="col-md-6 col-centered">
    <p>
    Are you looking for an SDS not listed?<br>
    Please use this request form and our team will notify you upon completion.
    </p>
    <div class="form-group">
    <input type="text" name="name" class="form-control" placeholder="Name" value="{{ Input::old('name') }}">
    {!! $errors->first('name','<span class="help-block"><span class="text-danger">:message</span></span>') !!}
    </div>
    <div class="form-group">
    <input type="email" name="email" class="form-control" placeholder="Email Address" value="{{ Input::old('email') }}">
    {!! $errors->first('email','<span class="help-block"><span class="text-danger">:message</span></span>') !!}
    </div>
    <div class="form-group">
    <textarea name="message" class="form-control" rows="5" placeholder="Desired SDS">{{ Input::old('message') }}</textarea>
    {!! $errors->first('message','<span class="help-block"><span class="text-danger">:message</span></span>') !!}
    </div>

     <div class="form-actions">
        <button type="submit" class="btn blue" name="request" value="1">Submit <i class="m-icon-swapright m-icon-white"></i>
    </div>    
</div>

</div>

</form>



@stop


@section('top_style')
<style>
.col-centered {
    float: none;
    margin: 0 auto;
}
</style>
@stop


@section('bottom_style')
@stop

@section('bottom_plugin_script')
@stop

@section('bottom_script')
@stop
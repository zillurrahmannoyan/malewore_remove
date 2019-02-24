 @extends('backend.layouts.admin')

@section('title')
My Profile
@stop

@section('content')

<div class="row">
    <div class="col-sm-12">
        <div class="portlet box">

            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject font-red-sunglo bold uppercase">Edit Profile</span>
                </div>
            </div>

            <div class="portlet-body form">
                <form action="{{ URL::route('backend.users.profile') }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="row">
                        <div class="col-sm-6">

                            <div class="form-group">
                                <label for="textfield" class="control-label col-md-4">First Name</label>
                                <div class="col-md-8">
                                    <input type="text" name="fname" placeholder="First Name" class="form-control" value="{{ Input::old('fname', $info->fname) }}">
                                    <!-- START error message -->
                                    {!! $errors->first('fname','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                                    <!-- END error message -->
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="textfield" class="control-label col-md-4">Last Name</label>
                                <div class="col-md-8">
                                    <input type="text" name="lname" placeholder="Last Name" class="form-control" value="{{ Input::old('lname', $info->lname) }}">
                                    <!-- START error message -->
                                    {!! $errors->first('lname','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                                    <!-- END error message -->
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="textfield" class="control-label col-md-4">Email</label>
                                <div class="col-md-8">
                                    <input type="text" name="email" placeholder="Email" class="form-control" value="{{ Input::old('email', $info->email) }}">
                                    <!-- START error message -->
                                    {!! $errors->first('email','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                                    <!-- END error message -->
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="textfield" class="control-label col-md-4">Username</label>
                                <div class="col-md-8">
                                    <input type="text" name="username" placeholder="Username" class="form-control" value="{{ Input::old('username', $info->username) }}">
                                    <!-- START error message -->
                                    {!! $errors->first('username','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                                    <!-- END error message -->
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="textfield" class="control-label col-sm-4"></label>
                                <div class="col-sm-8"> 
                                <div class="check-line">
                                    {!! Form::checkbox('change_password', '1', Input::old('change_password'), array("class" => "icheck-me", "data-skin" => "minimal", "id" => "change_password")) !!}                                 
                                    <label class="inline" for="change_password">Change Password</label>
                                </div>
                                </div>
                            </div>

                            <div class="change_password" style="{{ Input::old('change_password') ? '' : 'display:none' }}">
	                            <div class="form-group">
	                                <label for="textfield" class="control-label col-md-4">Old Password</label>
	                                <div class="col-md-8">
	                                    <input type="password" name="old_password" placeholder="Old Password" class="form-control" value="{{ Input::old('old_password') }}">
	                                    <!-- START error message -->
	                                    {!! $errors->first('old_password','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
	                                    <!-- END error message -->
	                                </div>
	                            </div>

	                            <div class="form-group">
	                                <label for="textfield" class="control-label col-md-4">New Password</label>
	                                <div class="col-md-8">
	                                    <input type="password" name="new_password" placeholder="New Password" class="form-control" value="{{ Input::old('new_password') }}">
	                                    <!-- START error message -->
	                                    {!! $errors->first('new_password','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
	                                    <!-- END error message -->
	                                </div>
	                            </div>

	                            <div class="form-group">
	                                <label for="textfield" class="control-label col-md-4">Confirm Password</label>
	                                <div class="col-md-8">
	                                    <input type="password" name="new_password_confirmation" placeholder="Confirm Password" class="form-control" value="{{ Input::old('new_password_confirmation') }}">
	                                    <!-- START error message -->
	                                    {!! $errors->first('new_password_confirmation','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
	                                    <!-- END error message -->
	                                </div>
	                            </div>
                            </div>

                        </div>
    
                   <div class="col-sm-6">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="max-width: 200px;">
                                    <img src="{{ @hasPhoto(Auth::user()->photo) }}" alt=""/>
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px;">
                                </div>
                                <div>
                                    <span class="btn default btn-file">
                                    <span class="fileinput-new">
                                    Select image </span>
                                    <span class="fileinput-exists">
                                    Change </span>
                                    <input type="file" name="upload">
                                    </span>
                                    <a href="#" class="btn red fileinput-exists" data-dismiss="fileinput">
                                    Remove </a>
                                </div>
                            </div>
                            <!-- START error message -->
                            {!! $errors->first('upload','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                            <!-- END error message -->
                        </div>
                    </div>
              </div>         
      
                      
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-actions">
                                <input type="hidden" name="op" value="1">
                                <button type="submit" class="btn btn-primary">Update Account</button>                          
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@stop


@section('top_style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') }}"/>
@stop

@section('bottom_style')
@stop

@section('bottom_plugin_script')
<script src="{{ asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') }}"></script>
@stop


@section('bottom_script')
<script>
$('#change_password').on('click', function(){
	if( $(this).is(':checked') ) {
		$('.change_password').show();
	} else {
		$('.change_password').hide();
	}
});
</script>
@stop

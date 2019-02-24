 @extends('backend.layouts.admin')

@section('title')
Users
@stop

@section('sub-title')
<a href="{{ URL::route('backend.users.index') }}" class="btn pull-right"><i class="fa fa-chevron-left"></i> Back</a>
<small>Edit User</small>
@stop

@section('breadcrumbs')
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <i class="fa fa-home"></i>
      <a href="{{ URL::route('backend.dashboard.index') }}">Dashboard</a>
      <i class="fa fa-angle-right"></i>
    </li>
    <li>
      <a href="{{ URL::route('backend.users.index') }}">Users</a>
      <i class="fa fa-angle-right"></i>
    </li>
    <li>
      <a href="#">Add User</a>
    </li>
  </ul>
</div>
@stop

@section('page-title')
Users
@stop

@section('content')

<link rel="stylesheet" type="text/css" href="{{ asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') }}"/>


<div class="portlet light bordered">
    <div class="portlet-title">
        <div class="caption">
            <span class="caption-subject font-red-sunglo bold uppercase">Edit User</span>
        </div>
    </div>
    <div class="portlet-body form">

    
                <form action="{{ URL::route('backend.users.update', $info->id) }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="row">
                        <div class="col-sm-6">

                            <div class="form-group">
                                <label for="textfield" class="control-label col-sm-3">First Name</label>
                                <div class="col-sm-9">
                                    <input type="text" name="fname" placeholder="First Name" class="form-control" value="{{ Input::old('fname', $info->fname) }}">
                                    <!-- START error message -->
                                    {!! $errors->first('fname','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                                    <!-- END error message -->
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="textfield" class="control-label col-sm-3">Last Name</label>
                                <div class="col-sm-9">
                                    <input type="text" name="lname" placeholder="Last Name" class="form-control" value="{{ Input::old('lname', $info->lname) }}">
                                    <!-- START error message -->
                                    {!! $errors->first('lname','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                                    <!-- END error message -->
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="textfield" class="control-label col-sm-3">Email</label>
                                <div class="col-sm-9">
                                    <input type="text" name="email" placeholder="Email" class="form-control" value="{{ Input::old('email', $info->email) }}">
                                    <!-- START error message -->
                                    {!! $errors->first('email','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                                    <!-- END error message -->
                                </div>
                            </div>

                        
                            <div class="form-group">
                                <label for="textfield" class="control-label col-sm-3">Username</label>
                                <div class="col-sm-9">
                                    <input type="text" name="username" placeholder="Username" class="form-control" value="{{ Input::old('username', $info->username) }}">
                                    <!-- START error message -->
                                    {!! $errors->first('username','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                                    <!-- END error message -->
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="textfield" class="control-label col-sm-3">Change Password</label>
                                <div class="col-sm-9">
                                    <input type="password" name="password" placeholder="Change Password" class="form-control" value="{{ Input::old('password') }}">
                                    <small>Leave empty if you dont want to change password</small>
                                    <!-- START error message -->
                                    {!! $errors->first('password','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                                    <!-- END error message -->
                                </div>
                            </div>

			                <div class="form-group">
			                    <label for="textfield" class="control-label col-sm-3">Group</label>
			                    <div class="col-md-9">
			                        {!! Form::select('role', user_roles(), Input::old('role', $info->role), 
			                        ["class" => "bs-select form-control bfcode", "data-live-search" => "true"]) !!}
                                    <!-- START error message -->
                                    {!! $errors->first('role','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                                    <!-- END error message -->
			                    </div>
			                </div>

                            <div class="form-group">
                                <label for="textfield" class="control-label col-sm-3"></label>
                                <div class="col-sm-9"> 
                                <div class="check-line">
                                    {!! Form::checkbox('status', '1', Input::old('status', $info->status), array("class" => "icheck-me", "data-skin" => "minimal", "id" => "status")) !!}                                 
                                    <label class="inline" for="status">Activate</label>
                                </div>
                                </div>
                            </div>


                        </div>

                       <div class="col-sm-6">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="max-width: 200px;">
                                            <img src="{{ hasPhoto($info->photo) }}" alt=""/>
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
                                <button type="submit" class="btn btn-primary">Save</button>                          
                                <a href="{{ URL::route('backend.users.index') }}" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>


    </div>
</div>
@stop

@section('top_style')
@stop

@section('bottom_style')
@stop

@section('bottom_plugin_script')
<script src="{{ asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') }}"></script>
@stop


@section('bottom_script')
@stop
 @extends('backend.layouts.admin')

@section('title')
Users
@stop

@section('sub-title')
<a href="{{ URL::route('backend.users.index') }}" class="btn pull-right"><i class="fa fa-chevron-left"></i> Back</a>
<small>Add User</small>
@stop


@section('content')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') }}"/>

<div class="portlet light bordered">
    <div class="portlet-title">
        <div class="caption">
            <span class="caption-subject font-red-sunglo bold uppercase">Add User</span>
        </div>
    </div>
    <div class="portlet-body form">

         <form action="{{ URL::route('backend.users.store') }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
         {!! csrf_field() !!}

            <div class="row">
                <div class="col-sm-6">

                    <div class="form-group">
                        <label for="textfield" class="control-label col-sm-3">First Name</label>
                        <div class="col-sm-9">
                            <input type="text" name="fname" placeholder="First Name" class="form-control" value="{{ Input::old('fname') }}">
                            <!-- START error message -->
                            {!! $errors->first('fname','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                            <!-- END error message -->
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="textfield" class="control-label col-sm-3">Last Name</label>
                        <div class="col-sm-9">
                            <input type="text" name="lname" placeholder="Last Name" class="form-control" value="{{ Input::old('lname') }}">
                            <!-- START error message -->
                            {!! $errors->first('lname','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                            <!-- END error message -->
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="textfield" class="control-label col-sm-3">Email</label>
                        <div class="col-sm-9">
                            <input type="text" name="email" placeholder="Email" class="form-control" value="{{ Input::old('email') }}">
                            <!-- START error message -->
                            {!! $errors->first('email','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                            <!-- END error message -->
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="textfield" class="control-label col-sm-3">Username</label>
                        <div class="col-sm-9">
                            <input type="text" name="username" placeholder="Username" class="form-control" value="{{ Input::old('username') }}">
                            <!-- START error message -->
                            {!! $errors->first('username','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                            <!-- END error message -->
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="textfield" class="control-label col-sm-3">Password</label>
                        <div class="col-sm-9">
                            <input type="password" name="password" placeholder="Password" class="form-control" value="{{ Input::old('password') }}">
                            <!-- START error message -->
                            {!! $errors->first('password','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                            <!-- END error message -->
                        </div>
                    </div>

	                <div class="form-group">
	                    <label for="textfield" class="control-label col-sm-3">Group</label>
	                    <div class="col-md-9">
	                        {!! Form::select('role', user_roles(), Input::old('role'), 
	                        ["class" => "bs-select form-control bfcode", "data-live-search" => "true"]) !!}
                            <!-- START error message -->
                            {!! $errors->first('group_id','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                            <!-- END error message -->

	                    </div>
	                </div>

                    <div class="form-group">
                        <label for="textfield" class="control-label col-sm-3"></label>
                        <div class="col-sm-9"> 
                        <div class="check-line">
                            {!! Form::checkbox('status', '1', true, array("class" => "icheck-me", "data-skin" => "minimal", "id" => "status")) !!}                                 
                            <label class="inline" for="status">Activate</label>
                        </div>
                        </div>
                    </div>

                    
                </div>

               <div class="col-sm-6">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                    <img src="" alt=""/>
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">
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


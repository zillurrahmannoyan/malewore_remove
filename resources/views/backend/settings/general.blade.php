 @extends('backend.layouts.admin')

@section('title')
Settings
@stop

@section('content')


<div class="portlet margin-top-10">
    <div class="portlet-body form">

        <form action="" method="POST" class="form-horizontal" enctype="multipart/form-data">

            {!! csrf_field() !!}


            <div class="form-group">
                <label class="control-label col-md-3">Logo (Small)</label>
                <div class="col-md-9">
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                        <div class="fileinput-new thumbnail">
                            @if( file_exists('uploads/images/logo_small.png') )
                            <img src="{{ asset('uploads/images/logo_small.png') }}" alt="" style="max-height: 30px;"/>
                            @else
                            <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" alt=""/>
                            @endif
                        </div>
                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">
                        </div>
                        <div>
                            <span class="btn default btn-file">
                            <span class="fileinput-new">
                            Select image </span>
                            <span class="fileinput-exists">
                            Change </span>
                            <input type="file" name="logo_small">
                            </span>
                            <a href="#" class="btn red fileinput-exists" data-dismiss="fileinput">
                            Remove </a>
                        </div>
                        <small>Size: 120x25</small>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3">SDS Header</label>
                <div class="col-md-9">
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                        <div class="fileinput-new thumbnail">
                            @if( file_exists('uploads/images/sds-header.png') )
                            <img src="{{ asset('uploads/images/sds-header.png') }}" alt="" style="max-height: 90px;"/>
                            @else
                            <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" alt=""/>
                            @endif
                        </div>
                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-height: 90px;"/>
                        </div>
                        <div>
                            <span class="btn default btn-file">
                            <span class="fileinput-new">
                            Select image </span>
                            <span class="fileinput-exists">
                            Change </span>
                            <input type="file" name="sds_header">
                            </span>
                            <a href="#" class="btn red fileinput-exists" data-dismiss="fileinput">
                            Remove </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3">TDS Header</label>
                <div class="col-md-9">
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                        <div class="fileinput-new thumbnail">
                            @if( file_exists('uploads/images/tds-header.png') )
                            <img src="{{ asset('uploads/images/tds-header.png') }}" alt="" style="max-height: 90px;"/>
                            @else
                            <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" alt=""/>
                            @endif
                        </div>
                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-height: 90px;"/>
                        </div>
                        <div>
                            <span class="btn default btn-file">
                            <span class="fileinput-new">
                            Select image </span>
                            <span class="fileinput-exists">
                            Change </span>
                            <input type="file" name="tds_header">
                            </span>
                            <a href="#" class="btn red fileinput-exists" data-dismiss="fileinput">
                            Remove </a>
                        </div>
                    </div>
                </div>
            </div>            

            <div class="form-group">
                <label for="textfield" class="control-label col-sm-3">Site Name</label>
                <div class="col-sm-6">
                    <input type="text" name="site_name" placeholder="Site Name" class="form-control" value="{{ $site_name }}">
                </div>
            </div>

            <div class="form-group">
                <label for="textfield" class="control-label col-sm-3">Copy Right</label>
                <div class="col-sm-6">
                    <input type="text" name="copy_right" placeholder="Copy Right" class="form-control" value="{{ $copy_right }}">
                </div>
            </div>

            <div class="form-group">
                <label for="textfield" class="control-label col-sm-3">Admin Email</label>
                <div class="col-sm-6">
                    <input type="text" name="admin_email" placeholder="Admin Email" class="form-control" value="{{ $admin_email }}">
                </div>
            </div>               
            <div class="form-group">
                <label for="textfield" class="control-label col-sm-3">Carbon Copy Email</label>
                <div class="col-sm-6">
                    <input type="text" name="carbon_copy_email" placeholder="Carbon Copy Email" class="form-control" value="{{ $carbon_copy_email }}">
                </div>
            </div>      
                

            <div class="form-group">
                <label for="textfield" class="control-label col-sm-3">Timezone</label>
                <div class="col-sm-6">
                    <select name="timezone" class="form-control select2me">
                    @foreach(getTimezones() as $timezone_key => $states)
                        <optgroup label="{{ $timezone_key }}">
                        @foreach($states as $state_k => $state_v)
                        <option value="{{ $state_k }}" {{ selected($state_k, $timezone) }}>{{ $state_v }}</option>
                        @endforeach
                        </optgroup>
                    @endforeach
                    </select>
                    <span class="help-block">Current Time: <code>{{ date('h:i:s A') }}</code></span>
                </div>
            </div>                          
                     

            <input type="hidden" name="op" value="1">
            
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-9">
                        <button type="submit" class="btn btn-primary">Save Settings</button>
                    </div>
                </div>
            </div>

        </form>


    </div>
</div>

@stop




@section('top_style')
<link href="{{ asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-summernote/summernote.css') }}" rel="stylesheet" type="text/css" />
@stop


@section('bottom_style')
@stop

@section('bottom_plugin_script')
 <script src="{{ asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') }}" type="text/javascript"></script>
 <script src="{{ asset('assets/global/plugins/bootstrap-summernote/summernote.min.js') }}" type="text/javascript"></script>
@stop

@section('bottom_script')
<script>


$(document).ready(function() {
    $('.summernote').summernote({
        height: "500px"
    });
});
var postForm = function() {
    var content = $('textarea[name="contract_of_lease"]').html($('#summernote').code());
}

$('.note-editable').on("blur", function(){
   $('textarea[name="contract_of_lease"]').val($('#summernote_1').summernote('code'));
});

$('.printBtn').bind('click',function(e) {
  e.preventDefault();
window.open($(this).attr('href'), "_blank", "toolbar=no, scrollbars=no, resizable=no, top=0, left=0, width=900, height=1000, menubar=0, titlebar=0");
});


</script>
@stop
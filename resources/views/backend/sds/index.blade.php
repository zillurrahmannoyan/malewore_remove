 @extends('backend.layouts.admin')

@section('title')
Safety Data Sheets 

| <a href="{{ URL::route('backend.sds.request') }}">Request an SDS</a>

@stop

@section('content')


@if(Auth::user()->role != 'user')
    <a href="{{ URL::route('backend.sds.index') }}">All ({{ $count_all }})</a>

    @if($count_approve)
    | <a href="{{ URL::route('backend.sds.index', ['status' => 'approve']) }}">Approved ({{ $count_approve }})</a>
    @endif

    @if($count_draft)
    | <a href="{{ URL::route('backend.sds.index', ['status' => 'draft']) }}">Drafted ({{ $count_draft }})</a>
    @endif

    @if($count_pending)
    | <a href="{{ URL::route('backend.sds.index', ['status' => 'pending']) }}">Pending ({{ $count_pending }})</a>
    @endif

    @if($count_trash)
    | <a href="{{ URL::route('backend.sds.index', ['status' => 'trash']) }}">Trashed ({{ $count_trash }})</a>
    @endif
@endif

<form method="get">
<div class="row margin-top-10">
    <div class="col-lg-4 col-md-4 col-sm-12">
        @if(Auth::user()->role != 'user')
        <div class="input-group">
            <div class="input-icon">
                <i class="fa fa-gear fa-fw"></i>
                 {!! Form::select('action', get_status(), '', ["class" => "bs-select form-control"]) !!}
            </div>
            <span class="input-group-btn">
            <button class="btn btn-success" type="submit">Apply</button>
            </span>
        </div>
        @endif
    </div>

    <div class="col-lg-4 col-md-4 col-sm-12 pull-right text-right">
        @if(Input::get('status'))
        <input type="hidden" name="status" value="{{ Input::get('status') }}">
        @endif
        <div class="input-group">
            <div class="input-icon">
                <i class="fa fa-search fa-fw"></i>
                <input type="text" name="s" class="form-control" placeholder="Enter search ..." value="{{ Input::get('s') }}">
            </div>
            <span class="input-group-btn">
            <button class="btn btn-success" type="submit">Go <i class="m-icon-swapright m-icon-white"></i></button>
            </span>
        </div>
        <strong>{{ $count }}</strong> item{{ isPlural($count) }}

    </div>                                          
</div>

<div class="row">
<div class="col-md-12">
<table class="table table-striped table-hover margin-top-20">
    <thead>
        <tr>
            @if(Auth::user()->role != 'user')
            <th width="1%"><input type="checkbox" id="check_all"></th>
            @endif
            <th>Title</th>
            @if(Auth::user()->role != 'user')
            <th>Status</th>
            @endif
            <th>Updated At</th>
            <th>
                @if(Auth::user()->role == 'admin')
                <a href="{{ URL::route('backend.sds.add') }}" class="btn btn-primary btn-sm pull-right"><i class="fa fa-plus"></i> Add New</a>  
                
                @if(Input::get('status') == 'trash')
                <a class="btn btn-default btn-sm pull-right delete"
                data-href="{{ URL::route('backend.sds.empty-trash') }}" 
                data-toggle="modal"
                data-target=".delete-modal" 
                data-title="Confirm Empty Trash"
                data-auth="true"
                data-body="Are you sure you want to <b>Empty Trash</b>?">   
                <i class="fa fa-trash"></i> Empty Trash</a>    
                @endif

                @endif            
            </th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($rows as $row): ?>
        <?php $info = App\LeadDetail::where('lead_id', $row->lead_id)->where('field_number', 1)->first(); ?>    
        <tr>
            @if(Auth::user()->role != 'user')
            <td><input type="checkbox" name="ids[]" value="{{ $row->lead_id }}" class="checkboxes"></td>
            @endif
            <td>
            {{ @$info->value }}
            </td>
            @if(Auth::user()->role != 'user')
            <td>
            {!! post_status($row->status) !!}
            </td>
            @endif                
            <td>{{ date_formatted($row->updated_at) }}</td>
            <td>
                <div class="task-config-btn btn-group pull-right">
                    <a class="btn btn-xs default" href="#" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" aria-expanded="false">
                    Actions <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu pull-right">
                        @if(Auth::user()->role == 'admin')
                        <li><a href="{{ URL::route('backend.sds.edit', $row->lead_id) }}">Edit</a></li>
                        @endif
                        <li><a href="{{ URL::route('backend.sds.pdf-view', [$row->lead_id, $row->token]) }}" download>Download PDF</a></li>
                        <li><a href="{{ URL::route('backend.sds.pdf-view', [$row->lead_id, $row->token]) }}" target="_blank">View PDF</a></li>
                        <li>
                            <a href="#" class="embed" 
                                data-href="" 
                                data-toggle="modal"
                                data-target=".embed-modal"
                                data-embed="{{ URL::route('backend.sds.pdf-view', [$row->lead_id, $row->token]) }}">Embed</a>                    
                        </li>
                        @if(Auth::user()->role == 'admin')
                        <li>
                            <a href="#" class="delete"
                                data-href="{{ URL::route('backend.sds.duplicate', $row->lead_id) }}" 
                                data-toggle="modal"
                                data-target=".delete-modal" 
                                data-title="Confirm Duplicate"
                                data-auth="true"
                                data-body="Are you sure you want to duplicate <b>{{ @$info->value }}</b>?">Duplicate</a>                 
                        </li>
                        <li>
                            <a href="#" class="delete"
                                data-href="{{ URL::route('backend.sds.destroy', $row->lead_id) }}" 
                                data-toggle="modal"
                                data-target=".delete-modal" 
                                data-title="Confirm Delete"
                                data-auth="true"
                                data-body="Are you sure you want to delete <b>{{ @$info->value }}</b>?">Delete</a>                 
                        </li>
                        @endif
                    </ul>
                </div>
            </td>
        </tr>
       <?php endforeach; ?>
        </tr>
    </tbody>
</table>


@if( count($rows) == 0 )
<div class="alert alert-info">No records found!</div>
@endif

<?php parse_str($_SERVER['QUERY_STRING'], $query_vars); ?>
{{ $rows->appends($query_vars)->links() }}


</div>


</form>



@stop


@section('top_style')
@stop


@section('bottom_style')
@stop

@section('bottom_plugin_script')
@stop

@section('bottom_script')
<script>
$(document).on('click change','input[id="check_all"]',function() {
    var checkboxes = $('.checkboxes');
    if ($(this).is(':checked')) {
        checkboxes.prop("checked" , true);
        checkboxes.closest('span').addClass('checked');
    } else {
        checkboxes.prop( "checked" , false );
        checkboxes.closest('span').removeClass('checked');
    }
});
</script>
@stop
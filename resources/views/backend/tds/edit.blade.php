 @extends('backend.layouts.admin')

@section('title')
Technical Data Sheets 
@stop

@section('content')
<div class="portlet light bordered">
    <div class="portlet-title">

        <div class="task-config-btn btn-group pull-right">
            <a class="btn btn-md btn-default" href="#" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" aria-expanded="false">
            Actions <i class="fa fa-angle-down"></i>
            </a>
            <ul class="dropdown-menu pull-right">

                <li><a href="{{ URL::route('backend.tds.pdf-view', [$info->id, $info->token, 'v' => Input::get('v')]) }}" download>Download PDF</a></li>
                <li><a href="{{ URL::route('backend.tds.pdf-view', [$info->id, $info->token, 'v' => Input::get('v')]) }}" target="_blank">View PDF</a></li>
                <li>
                    <a href="#" class="embed" 
                        data-href="" 
                        data-toggle="modal"
                        data-target=".embed-modal"
                        data-embed="{{ URL::route('backend.tds.pdf-view', [$info->id, $info->token, 'v' => Input::get('v')]) }}">Embed</a>                    
                </li>

            </ul>
        </div>

        <div class="caption">
            <span class="caption-subject font-red-sunglo bold uppercase">Edit TDS</span>
        </div>
    </div>
    <div class="portlet-body form">

        <ul class="nav nav-pills">
            <li class="{{ Input::get('v') == 2 ? 'active' : ''  }}">
                <a href="{{ URL::route('backend.tds.edit', [$info->id, 'v' => 2]) }}">TDS ( <b>v.2</b> )</a>
            </li>
            <li class="{{ Input::get('v') == 1 ? 'active' : ''  }}">
                <a href="{{ URL::route('backend.tds.edit', [$info->id, 'v' => 1]) }}">TDS ( <b>v.1</b> )</a>
            </li>
            <li class="{{ Input::get('tab') == 'notes' ? 'active' : ''  }}">
                <a href="{{ URL::route('backend.tds.edit', $info->id) }}?tab=notes">
                Notes <span class="badge badge-danger notes-count">
                {{ $note_count }} </span>
                </a>
            </li>
        </ul>

         @if(Input::get('tab') == 'notes')
             @include('backend.reports.notes')
         @else
            
            @if( $v = Input::get('v') )
            @include('backend.tds.v'.$v.'.edit')
            @endif

        @endif
        
    </div>
</div>
@stop

@section('top_style')
<style type="text/css">
form .title {
    font-size: 18px;
}  
form .sub-title {
    font-size: 16px;
}    
.list-field .remove-list {
    display: none!important;
}

.list-actions {    
    margin: 22px 0;
}

input[type=checkbox]:checked+label,  input[type=radio]:checked+label {
    font-weight: bold;
}


</style>
@stop

@section('bottom_style')
<link href="{{ asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') }}" rel="stylesheet" type="text/css" />
@stop

@section('bottom_plugin_script')
@stop

@section('bottom_script')
<script src="{{ asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') }}" type="text/javascript"></script>

<script type="text/javascript">
$(document).on('click', '.add-list', function(e) {
    e.preventDefault();
    var target = $(this).data('target');
    $(target).append($(target).find('.list-field:eq(0)').html() );

    $('.col-field .row').last().find('input').val(''); 
    $('.col-field .row').last().find('textarea').val(''); 
});  

@foreach(get_tds_forms() as $form)
@if(isset($form['condition']))
$(document).on('blur', '[name=input_{{ $form['condition']['field_id'] }}]', function() {
    var val = $(this).val();
    if(val >= {{ $form['condition']['value'] }}) {
        $('.{{ $form['name'] }}').show();
    } else {
        $('.{{ $form['name'] }} input, .{{ $form['name'] }} textarea').val('');
        $(".{{ $form['name'] }} select option:selected").removeAttr("selected");
        $('.{{ $form['name'] }}').hide();        
    }
});     
@endif
@endforeach


$(document).on('click', '.remove-list', function(e) {
    e.preventDefault();
    var target = $(this).data('target');
    $(this).closest('.row').remove();
});  
</script>
@stop


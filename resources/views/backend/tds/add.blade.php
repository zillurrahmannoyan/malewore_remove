 @extends('backend.layouts.admin')

@section('title')
Technical Data Sheets 
@stop

@section('content')


<div class="portlet light bordered">
    <div class="portlet-title">
        <div class="caption">
            <span class="caption-subject font-red-sunglo bold uppercase">Add TDS</span>
        </div>
    </div>
    <div class="portlet-body form">

        @if( $v = Input::get('v') )
        @include('backend.tds.v'.$v.'.add')
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
</style>
@stop


@section('bottom_style')
<link href="{{ asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') }}" rel="stylesheet" type="text/css" />
@stop

@section('bottom_plugin_script')
<script src="{{ asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') }}" type="text/javascript"></script>
@stop

@section('bottom_script')
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
$(document).on('keyup', '[name=input_{{ $form['condition']['field_id'] }}]', function() {
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


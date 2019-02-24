 <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<html>
<body>
<style>
body{
    line-height: 23px;
    font-family: sans-serif;
    font-size: 14px;
}
.title {
    font-weight: bold;
    font-size: 21px;
    color: #1425a7;
    margin: 0;
    border: 1px solid #9E9E9E;
    border-top: none;
    padding: 10px;
}
.title-2 {
    font-weight: bold;
    font-size: 21px;
    color: #1425a7;
    margin: 0 0 10px 0;
}
.divider {    
    display: none;
/*    margin: 10px 0 5px;
    width: 100%;*/
}

.sub-title {
    background: #f3f3f3;
}   
.text {
    margin-bottom: 15px;    
}
.form-group {
    font-size: 13px;
}
.form-group-2 {
    font-size: 13px;
    border: 1px solid #9E9E9E;
    border-top: 0;
    padding: 10px;
}
.bt {
    border-top: 1px solid #9E9E9E;
}
.label {
    font-weight: bold;
    margin-bottom: 5px;    
    font-size: 14px;
    color: #2d37c3;
}
.container {
    margin: 0 auto;
    width: 700px;
}
.header {
    text-align: center;
    margin-bottom: 20px;
}
.justify {
    text-align: justify;
}


.table, .chem-table-square  {
    border-collapse: collapse;
    font-family: sans-serif;
    font-size: 18px;
}

.table, .table td {
    border: 1px solid black;
}
.blue {
    color: #fff;
    background: #004b98;
}
.red {
    color: #fff;
    background: #ff0000;
}
.yellow {
    background: #fcdc20;
}
.white {
    background: #fff;
}
.chemcount {
    background: #fff;
    text-align: center;
    color: #001727;
    display: block;
    line-height: 30px;
    padding-bottom: 5px;
}
.chem {    
    line-height: 30px;
    padding: 0 10px 5px;
}


.chem-table-square td {
    border: 2px solid black;
    font-weight: bold;
}

.chem-table-square td {
    height: 90px;
    width: 90px;
    text-align: center;    
}
.note { font-size: 11px; }

pre {
    font-family: sans-serif;
    font-size: 14px;
}
.col-3 { width: 30%; float:left; border:1px solid;}
.col-row { 
    display: inline-block;
    border: 1px solid #9E9E9E;
    padding: 10px;
}
.col-1 {
    width: 79%;
    float: left;
}    
.col-2 {
    width: 20%;
    float: left;
}    
.page-break { page-break-before: always; }

</style>




<div class="container">

<table width="100%" border="0">
    <tr>
        <td width="50%"><img src="{{ public_path().'/uploads/images/tds-header.png' }}"></td>
        <td align="right" valign="bottom">
        <?php $detail = App\LeadDetail::where('lead_id', $info->id)->where('field_number', 'LIKE', 1)->first(); ?>    
        <h2 style="color: #1425a7;">{{ @$detail->value }}</h2>      
        </td>
    </tr>
</table>

<br>

<div class="title" style="border-top: 1px solid #9E9E9E;border-bottom:0;"><u>Product Information</u></div>

<div class="col-row">
    <?php $img = App\LeadDetail::where('lead_id', $info->id)->where('field_number', 'LIKE', 31)->first(); ?>    

    <div class="{{ $img->value ? 'col-1' : '' }}">
        <?php $desc = App\LeadDetail::where('lead_id', $info->id)->where('field_number', 'LIKE', 3)->first(); ?>    
        @if( $desc->value )
        <div class="form-group" align="justify">
            <span class="label">Description - </span> {{ $desc->value }}            
        </div>    
        @endif

    </div>

    @if( $img->value )
    <div class="col-2">
        <img src="{{ @$img->value }}" style="width: 100%;margin-left:20px;">      
    </div>    
    @endif
</div>




@foreach(get_tds_v2_forms() as $form)
    @if( in_array($form['name'], range(2, 14)) && $form['name'] != 3 && $form['type'] != 'title' )    

        @if($form['type'] == 'text' || $form['type'] == 'textarea')
            <?php $detail = App\LeadDetail::where('lead_id', $info->id)->where('field_number', 'LIKE', $form['name'])->first(); ?>    
            @if( @$detail->value || $detail->value=="0" )
            <div class="form-group-2" align="justify">
                <span class="label">{{ $form['label'] }} - </span> {{ @$detail->value }}            
            </div>
            @endif
        @endif

            <?php $l = $list = 0; ?>
            @if($form['type'] == 'list_populate')
            <?php 
                $detail = App\LeadDetail::where('lead_id', $info->id)->where('field_number', 'LIKE', $form['name'])->first(); 
                $values = unserialize(@$detail->value);
            ?>

                @if($values)
                    <?php
                        $postArr = array_map('array_filter', $values);
                        $postArr = array_filter( $postArr );
                    ?>    
                    @if($postArr)
                        @foreach($values as $value)
                        <div class="form-group-2">
                            @foreach($form['inputs'] as $input)
                            <?php $val = array_values($value); ?>
                            @if($list != 1)
                            <span class="label">{{ @$val[$list] }} - </span>
                            @else
                            {{ @$val[$list] }}                
                            @endif
                            <?php $list++; ?>
                            @endforeach
                        </div>

                        <?php $list = 0; ?>
                        <?php $l++; ?>
                        @endforeach
                    @endif
                @endif
            @endif

    @endif
@endforeach 



@if( App\LeadDetail::where('lead_id', $info->id)->whereIn('field_number', range(15, 21))->where('value', '!=', '')->count() ) 

    <img src="{{ public_path().'/uploads/images/line.png' }}" class="divider">
    <div class="title"><u>Application Information</u></div>
    @foreach(get_tds_v2_forms() as $form)
        @if( in_array($form['name'], range(9, 15)) && $form['type'] != 'title' )    
        
            @if($form['type'] == 'text' || $form['type'] == 'textarea')
                <?php $detail = App\LeadDetail::where('lead_id', $info->id)->where('field_number', 'LIKE', $form['name'])->first(); ?>    
                @if( @$detail->value || $detail->value=="0" )
                <div class="form-group-2" align="justify">
                    <span class="label">{{ $form['label'] }} - </span> {{ @$detail->value }}            
                </div>
                @endif
            @endif


        @endif
    @endforeach 

@endif



@if( App\LeadDetail::where('lead_id', $info->id)->whereIn('field_number', range(22, 30))->where('value', '!=', '')->count() ) 
<div style="border: 1px solid #9E9E9E;padding:10px;">
    <img src="{{ public_path().'/uploads/images/line.png' }}" class="divider">
    <div class="title-2"><u>Technical Information</u></div>

    <table width="100%"  cellpadding="2">
        <tr>
            <?php $detail = App\LeadDetail::where('lead_id', $info->id)->where('field_number', 'LIKE', 22)->first(); ?>    
            @if( $detail->value  )
            <td>
                <div class="form-group">
                    <span class="label">Tensile Strength - </span> {{ $detail->value }}            
                </div>            
            </td>
            @endif

            <?php $detail = App\LeadDetail::where('lead_id', $info->id)->where('field_number', 'LIKE', 23)->first(); ?>    
            @if( $detail->value )
            <td>
                <div class="form-group">
                    <span class="label">Visosity @ 10rpm' - </span> {{ $detail->value }}            
                </div>            
            </td>
            @endif

            <?php $detail = App\LeadDetail::where('lead_id', $info->id)->where('field_number', 'LIKE', 24)->first(); ?>    
            @if( $detail->value )
            <td>
                <div class="form-group">
                    <span class="label">Solids Content - </span> {{ $detail->value }}            
                </div>            
            </td>
            @endif
        </tr>

        <tr>
            <?php $detail = App\LeadDetail::where('lead_id', $info->id)->where('field_number', 'LIKE', 25)->first(); ?>    
            @if( $detail->value )
            <td>
                <div class="form-group">
                    <span class="label">Elongation - </span> {{ $detail->value }}            
                </div>            
            </td>
            @endif

            <?php $detail = App\LeadDetail::where('lead_id', $info->id)->where('field_number', 'LIKE', 26)->first(); ?>    
            @if( $detail->value )
            <td>
                <div class="form-group">
                    <span class="label">Flash Point - </span> {{ $detail->value }}            
                </div>            
            </td>
            @endif

            <?php $detail = App\LeadDetail::where('lead_id', $info->id)->where('field_number', 'LIKE', 27)->first(); ?>    
            @if( $detail->value )
            <td>
                <div class="form-group">
                    <span class="label">pH - </span> {{ $detail->value }}            
                </div>            
            </td>
            @endif
        </tr>

        <tr>
            <?php $detail = App\LeadDetail::where('lead_id', $info->id)->where('field_number', 'LIKE', 28)->first(); ?>    
            @if( $detail->value )
            <td>
                <div class="form-group">
                    <span class="label">Adhesion - </span> {{ $detail->value }}            
                </div>            
            </td>
            @endif

            <?php $detail = App\LeadDetail::where('lead_id', $info->id)->where('field_number', 'LIKE', 29)->first(); ?>    
            @if( $detail->value )
            <td>
                <div class="form-group">
                    <span class="label">Density - </span> {{ $detail->value }}            
                </div>            
            </td>
            @endif

            <?php $detail = App\LeadDetail::where('lead_id', $info->id)->where('field_number', 'LIKE', 30)->first(); ?>    
            @if( $detail->value )
            <td>
                <div class="form-group">
                    <span class="label">Maximum VOC - </span> {{ $detail->value }}            
                </div>            
            </td>
            @endif

        </tr>    
    </table>
</div>
@endif
<?php $detail = App\LeadDetail::where('lead_id', $info->id)->where('field_number', 'LIKE', 99)->first(); 
    //$data  = json_decode($detail);
?>   
@if( $detail->value )
<div class="row">
    <div class="col col-md-12">
        <pre>
        <?php
            $data = json_decode($detail->value);
            //print_r($data);
        ?>
        </pre>
        <table style="width: 100%;">
            <tr>
            <?php
                foreach($data as $key){
            ?>
                <td>
                <table style="width: 100%;">
                    <tr>
                        <th><?php echo $key->field_name; ?></th>
                    </tr>
                    <?php foreach($key->data as $k_val){ ?>
                    <tr>
                        <td><?php echo $k_val->value; ?></td>
                            
                    </tr>
                    <?php } ?>
                </table>
                </td>
            <?php
                }
            ?>
            </tr>
        </table>
    </div>
</div>
@endif

<br>
<div class="note" align="center">12336 Emerson Dr., Brighton, MI 48116 Phone: 248-587-5600  Fax: 248-587-5606</div>


</div>  



</div> 
</body>
</html>
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
    background: #e3e3e3;
}
.sub-title {
    background: #f3f3f3;
}   
.text {
    margin-bottom: 15px;    
}
.label {
    font-weight: bold;
    display: block;
    margin-bottom: 5px;    
    font-size: 14px;
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
.form-group {
    border: 1px solid #eaeaea;    
    padding: 10px 15px;
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


pre {
    font-family: sans-serif;
    font-size: 14px;
}


.page-break { page-break-before: always; }
</style>




<div class="container">


<img src="{{ public_path().'/uploads/images/tds-header.png' }}">

@foreach(get_tds_forms() as $form)

    @if($form['type'] == 'title')
        <div class="title form-group">{{ $form['label'] }}</div>
    @endif

    @if($form['type'] == 'sub-titlex')
        <div class="sub-title form-group">{{ $form['label'] }}</div>
    @endif

    @if($form['type'] == 'htmlx')
        <div class="form-group">{!! $form['label'] !!}</div>
    @endif

    @if($form['type'] == 'text')
        <?php $detail = App\LeadDetail::where('lead_id', $info->id)->where('field_number', 'LIKE', $form['name'])->first(); ?>    
        @if( @$detail->value || $detail->value=="0" )
        <div class="form-group">
            <div class="label">{{ $form['label'] }}</div>
            {{ @$detail->value }}
        </div>
        @endif
    @endif


    @if($form['type'] == 'select')
        <?php $detail = App\LeadDetail::where('lead_id', $info->id)->where('field_number', 'LIKE', $form['name'])->first(); ?>    
        @if( @$detail->value || $detail->value=="0" )
        <div class="form-group">
            <div class="label">{{ $form['label'] }}</div>
            {{ @$detail->value }}
        </div>
        @endif
    @endif

    @if($form['type'] == 'textarea')
        <?php $detail = App\LeadDetail::where('lead_id', $info->id)->where('field_number', 'LIKE', $form['name'])->first(); ?>    
        @if( @$detail->value || $detail->value=="0" )
        <div class="form-group">
            <div class="label">{{ $form['label'] }}</div>
            <pre>{{ @$detail->value }}</pre>
        </div>
        @endif
    @endif

    @if($form['type'] == 'checkbox')
    <div class="form-group">
    <div class="label">{{ $form['label'] }}</div>
    <ul>
    @foreach($form['inputs'] as $input)
        <?php $detail = App\LeadDetail::where('lead_id', $info->id)->where('field_number', 'LIKE', $input['id'])->first(); ?>    
        @if($detail)
        <li>{!! @$detail->value !!}</li>
        @endif
    @endforeach
    </ul>
    </div>

    @endif

    @if($form['type'] == 'radio')
    <?php $detail = App\LeadDetail::where('lead_id', $info->id)->where('field_number', 'LIKE', $form['name'])->first(); ?>    

    <div class="form-group">
        <div class="label">{{ $form['label'] }}</div>
        {{ @$detail->value }}
    </div>

    @endif

    @if($form['type'] == 'list')
    <div class="form-group">
        <div class="label">{{ $form['label'] }}</div>
        @foreach($form['inputs'] as $input)
        <?php $detail = App\LeadDetail::where('lead_id', $info->id)->where('field_number', 'LIKE', $input['id'])->first(); ?>    
        {{ @$detail->value }} 
        @endforeach
    </div>
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
                <div class="form-group">
                    @foreach($form['inputs'] as $input)
                    <?php $val = array_values($value); ?>
                    @if($list != 1)
                    <strong>{{ @$val[$list] }}</strong><br>
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
@endforeach 





</div>  



</div> 
</body>
</html>
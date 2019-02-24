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


<div class="header"><img src="{{ public_path().'/uploads/images/sds-header.png' }}" width="100%"></div>

@foreach(get_sds_forms() as $form)

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
    <?php $na = 'Not Applicable'; ?>
    <div class="form-group">
    <div class="label">{{ $form['label'] }}</div>
    <ul>
    @foreach($form['inputs'] as $input)
        <?php $detail = App\LeadDetail::where('lead_id', $info->id)->where('field_number', 'LIKE', $input['id'])->first(); ?>    
        @if($detail)
            @if( @$detail->value == 'Non-classified')
            <li>Not Applicable</li>
            @elseif( @$detail->field_number == 21)
            <li>{!! @$detail->value !!}</li>                    
            @else
            <li>{!! @$detail->value !!}</li>        
            @endif
            <?php $na = ''?>    
        @endif
    @endforeach
    @if($na)
        <li>{{ $na }}</li>
    @endif
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
        <div class="form-group">
            
            <div class="label">{{ $form['label'] }}</div>

            <table  width="100%">
                @foreach($values as $value)
                <tr>
                    @foreach($form['inputs'] as $input)
                    <?php $val = array_values($value); ?>
                    <td>
                    @if($l == 0)
                    <label><strong>{{ $input['label'] }}</strong></label><br><br>
                    @endif

                    {{ @$val[$list] }}
                    </td>

                    <?php $list++; ?>
                    @endforeach
                    <?php $list = 0; ?>
                </tr>
                <?php $l++; ?>
                @endforeach
            </table>
        </div>
        @endif
    @endif


@endforeach 


<div style="margin-top: 90px;-webkit-user-select: none; margin-left: auto; margin-right: auto;width:300px" class="page-breakx">    


<table class="table" cellpadding="5">
    <tr>
        <td width="200" class="blue">
            <div class="chem">HEALTH</div>
        </td>
        <td width="50" class="white" align="center">
            <span class="chemcount">
            {{ $a = @App\LeadDetail::where('lead_id', $info->id)->where('field_number', 'LIKE', '162')->first()->value }}  
            @if ($a==null) {{$a="0"}} @endif
            </span>            
        </td>
    </tr>
    <tr>
        <td class="red">
            <div class="chem">FIRE</div>
        </td>
        <td class="white" align="center">
            <span class="chemcount">
            {{ $b = @App\LeadDetail::where('lead_id', $info->id)->where('field_number', 'LIKE', '419')->first()->value }}  
            @if ($b==null) {{$b="0"}} @endif
            </span>            
        </td>
    </tr>
    <tr>
        <td class="yellow"> 
            <div class="chem">REACTIVITY</div>
        </td>
        <td class="white" align="center">
            <span class="chemcount">
            {{ $c = @App\LeadDetail::where('lead_id', $info->id)->where('field_number', 'LIKE', '420')->first()->value }}  
            @if ($c==null) {{$c="0"}} @endif
            </span>            
        </td>
    </tr>
    <tr>
        <td> 
            <div class="chem">PPE</div>
        </td>
        <td class="white" align="center">
            <span class="chemcount">
            {{ $d = @App\LeadDetail::where('lead_id', $info->id)->where('field_number', 'LIKE', '421')->first()->value }}  
            @if ($d==null) {{$d="0"}} @endif
            </span>            
        </td>
    </tr>
</table>



<table class="chem-table-square" rotate="-45">
    <tr>
        <td class="blue">{{ $a }}</td>
        <td class="red">{{ $b }}</td>
    </tr>
    <tr>
        <td><div rotate="45">{{ $d }}</td>
        <td class="yellow">{{ $c }}</td>
    </tr>
</table>

</div>  



</div> 
</body>
</html>
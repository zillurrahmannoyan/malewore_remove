 <form action="{{ URL::route('backend.tds.update', [$info->id, query_vars()]) }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
    {!! csrf_field() !!}
    <?php $checkbox = 1; ?>
    <div class="row">
        <div class="col-sm-12">

            @foreach(get_tds_forms() as $form)

            <?php $detail = App\LeadDetail::where('lead_id', $info->id)->where('field_number', 'LIKE', $form['name'])->first(); ?>  

            <div class="form-group {{ $form['name'] }}" style="{{ (@$detail->value) ? '' : @$form['style'] }}">
                
                @if($form['type'] == 'title')
                <div class="col-md-12">
                    <div class="text-left">
                        <div class="title">{{ $form['label'] }}</div>
                    </div>
                </div>
                @endif

                @if($form['type'] == 'sub-title')
                <div class="col-md-12">
                    <div class="text-left">
                        <div class="sub-title">{{ $form['label'] }}</div>
                    </div>
                </div>
                
                @endif
                @if($form['type'] == 'html')
                <div class="col-md-6">
                    <div class="text-left">
                        {!! $form['label'] !!}
                    </div>
                </div>
                @endif

                @if($form['type'] == 'text')
                <?php $detail = App\LeadDetail::where('lead_id', $info->id)->where('field_number', 'LIKE', $form['name'])->first(); ?>    
                <div class="col-md-6">
                    <label>{{ $form['label'] }}</label>
                    <input type="{{ @$form['input'] }}" name="input_{{ $form['name'] }}" class="form-control" value="{{ Input::old($form['name'], @$detail->value) }}">
                    <small class="help-block">{{ @$form['help'] }}</small>
                </div>
                @endif

                @if($form['type'] == 'select')
                <?php $detail = App\LeadDetail::where('lead_id', $info->id)->where('field_number', 'LIKE', $form['name'])->first(); ?>    
                <div class="col-md-6">
                    <label>{{ $form['label'] }}</label>
                    <select name="input_{{ $form['name'] }}" class="form-control">
                        <option value="">-- Select {{ $form['label'] }} -- </option>
                        @foreach($form['inputs'] as $input)
                        <option value="{{ $input }}" {{ selected(@$detail->value, $input) }}>{{ $input }}</option> 
                        @endforeach
                    </select>
                </div>
                @endif

                @if($form['type'] == 'textarea')
                <?php $detail = App\LeadDetail::where('lead_id', $info->id)->where('field_number', 'LIKE', $form['name'])->first(); ?>    
                <div class="col-md-6">
                    <label>{{ $form['label'] }}</label>
                    <textarea name="input_{{ $form['name'] }}" class="form-control" rows="5">{{ Input::old($form['name'], @$detail->value) }}</textarea>
                </div>
                @endif

                @if($form['type'] == 'checkbox')
                <div class="col-md-12">
                    <div class="text-left">
                        <div class="sub-title">{{ $form['label'] }}</div>
                    </div>
                </div>

                @foreach($form['inputs'] as $input)
                <?php $detail = App\LeadDetail::where('lead_id', $info->id)->where('field_number', 'LIKE', $input['id'])->first(); ?>    

                <div class="col-md-{{ isset($form['col']) ? $form['col'] : 12  }}">
                    <label>
                    <input type="checkbox"  name="input_{{ $input['id'] }}" value="{{ $input['label'] }}"                            
                    {{ checked(@$detail->field_number, $input['id']) }}> {{ $input['label'] }}

                    @if( isset($input['img']) )
                    <br>
                    <img src="{{ asset($input['img']) }}">
                    <br><br>
                    @endif
                    </label>

                </div>
                @endforeach
                @endif

                @if($form['type'] == 'radio')
                <?php $detail = App\LeadDetail::where('lead_id', $info->id)->where('field_number', 'LIKE', $form['name'])->first(); ?>    

                <div class="col-md-12">
                    <div class="text-left">
                        <div class="sub-title">{{ $form['label'] }}</div>
                    </div>
                </div>

                @foreach($form['inputs'] as $input)
                <div class="col-md-12">
                    <label>
                    <input type="radio" name="{{ $form['name'] }}" value="{{ $input['label'] }}" 
                    {{ checked(@$detail->value, $input['label']) }}> {{ $input['label'] }}</label>
                </div>
                @endforeach
                @endif

                @if($form['type'] == 'list')
                <div class="col-md-6">
                    <div class="text-left">
                        <div class="sub-title">{{ $form['label'] }}</div>
                    </div>
                    @foreach($form['inputs'] as $input)
                    <label>{{ $input['label'] }}</label>
                    <input type="text" name="input_{{ $input['id'] }}" class="form-control" value="{{ Input::old($input['label']) }}">
                    @endforeach
                </div>
                @endif


                <?php $l = $list = 0; ?>
                @if($form['type'] == 'list_populate')
                <?php 
                    $detail = App\LeadDetail::where('lead_id', $info->id)->where('field_number', 'LIKE', $form['name'])->first(); 
                    $values = unserialize(@$detail->value);
                ?>    
                <div class="col-md-12">
                    <div class="text-left">
                        <div class="sub-title">{{ $form['label'] }}</div>
                    </div>
                </div>


                @if($values)                         
                <div class="col-md-12 col-field field-{{ $form['name'] }}">
                    @foreach($values as $value)
                    <div class="{{ ($l == 0) ? 'list-field' : '' }}">
                        <div class="row">

                            @foreach($form['inputs'] as $input)
                            <?php $val = array_values($value); ?>
                            <div class="col-md-{{ $form['col'] }}">                            
                            <label>{{ $input['label'] }}</label>
                            
                            @if($input['type'] == 'text')
                            <input type="text" name="input_{{ $form['name'] }}[]" class="form-control" 
                            value="{{ Input::old($input['label'], @$val[$list]) }}">
                            @endif

                            @if($input['type'] == 'textarea')
                            <textarea rows="5" name="input_{{ $form['name'] }}[]" class="form-control">{{ Input::old($input['label'], @$val[$list]) }}</textarea>
                            @endif

                            </div>
                            <?php $list++; ?>
                            @endforeach
                            <?php $list = 0; ?>
                            <?php $l++; ?>

                            @if( @$form['populate']  == true)
                            <div class="list-actions col-md-2">
                            <a href="" data-target=".field-{{ $form['name'] }}" class="add-list btn btn-primary"><i class="fa fa-plus"></i></a>
                            <a href="" data-target=".field-{{ $form['name'] }}"  class="remove-list btn btn-danger"><i class="fa fa-remove"></i></a>
                            </div>
                            @endif
                        </div>    
                    </div>
                    @endforeach
                </div>
                @else
                <div class="col-md-12 col-field field-{{ $form['name'] }}">

                    <div class="list-field">
                        <div class="row">

                            @foreach($form['inputs'] as $input)

                            <div class="col-md-{{ $form['col'] }}">                            
                            <label>{{ $input['label'] }}</label>
                            <input type="text" name="input_{{ $form['name'] }}[]" class="form-control" 
                            value="{{ Input::old($input['label']) }}">
                            </div>
                            <?php $list++; ?>
                            @endforeach
                            <?php $list = 0; ?>
                            <?php $l++; ?>

                            @if( @$form['populate']  == true)
                            <div class="list-actions col-md-2">
                            <a href="" data-target=".field-{{ $form['name'] }}" class="add-list btn btn-primary"><i class="fa fa-plus"></i></a>
                            <a href="" data-target=".field-{{ $form['name'] }}"  class="remove-list btn btn-danger"><i class="fa fa-remove"></i></a>
                            </div>
                            @endif
                        </div>    
                    </div>

                </div>                        
                @endif

                @endif


                <!-- START error message -->
                <div class="col-md-12">
                {!! $errors->first('input_'.$form['name'],'<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                </div>
                <!-- END error message -->

            </div>
            @endforeach 
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 sticky-actions">
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save</button>                          
                <a href="{{ URL::route('backend.tds.index') }}" class="btn btn-default">Cancel</a>
            </div>
        </div>
    </div>
</form>
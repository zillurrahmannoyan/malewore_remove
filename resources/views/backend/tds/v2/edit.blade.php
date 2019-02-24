 <form action="{{ URL::route('backend.tds.update', [$info->id, query_vars()]) }}"  method="POST" class="form-horizontal" enctype="multipart/form-data">
    {!! csrf_field() !!}
    <?php $checkbox = 1; ?>
    <div class="row">

        <div class="col-sm-6">

            @foreach(get_tds_v2_forms() as $form)

            <?php $detail = App\LeadDetail::where('lead_id', $info->id)->where('field_number', 'LIKE', $form['name'])->first(); ?>  

            <div class="form-group {{ $form['name'] }}" data-form-index="{{ $form['name'] }}" style="{{ (@$detail->value) ? '' : @$form['style'] }}">
                
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
                <div class="col-md-12">
                    <div class="text-left">
                        {!! $form['label'] !!}
                    </div>
                </div>
                @endif

                @if($form['type'] == 'text')
                <?php $detail = App\LeadDetail::where('lead_id', $info->id)->where('field_number', 'LIKE', $form['name'])->first(); ?>
                <?php $t_data = App\LeadDetail::where('lead_id', $info->id)->where('field_number', 'LIKE', 100+intval($form['name']))->first(); ?>
                <div class="col-md-12 form-input">
                    <div class="col col-md-8">
                        <label>{{ $form['label'] }}</label>
                    <input type="{{ @$form['input'] }}" name="input_{{ $form['name'] }}" class="form-control" value="{{ Input::old($form['name'], @$detail->value) }}">
                    <small class="help-block">{{ @$form['help'] }}</small>
                    </div>
                    <div class="col col-md-4 list-actions">
                        <a class="btn btn-primary add-table" data-toggle="tooltip" data-placement="top" title="Add Table">
                                <i class="fa fa-plus"></i>
                        </a>
                        <a class="btn btn-danger clear-table" data-toggle="tooltip" data-placement="top" title="Clear Table">
                                <i class="fa fa-trash"></i>
                        </a>
                    </div>
                    <input type="hidden" class="hidden_input" id="input_{{100+intval($form['name']) }}" value="{{ Input::old(100+intval($form['name']), @$t_data->value) }}" name="input_{{ 100+intval($form['name']) }}" />
                    <div class="col col-md-12 table-container" id="input_container_{{100+intval($form['name']) }}">
                    </div>
                </div>
                @endif

                @if($form['type'] == 'select')
                <?php $detail = App\LeadDetail::where('lead_id', $info->id)->where('field_number', 'LIKE', $form['name'])->first(); ?>
                <?php $t_data = App\LeadDetail::where('lead_id', $info->id)->where('field_number', 'LIKE', 100+intval($form['name']))->first(); ?>
                <div class="col-md-12 form-input">
                    <div class="col col-md-8">
                        <label>{{ $form['label'] }}</label>
                    <select name="input_{{ $form['name'] }}" class="form-control">
                        <option value="">-- Select {{ $form['label'] }} -- </option>
                        @foreach($form['inputs'] as $input)
                        <option value="{{ $input }}" {{ selected(@$detail->value, $input) }}>{{ $input }}</option> 
                        @endforeach
                    </select>
                    </div>
                    <div class="col col-md-4 list-actions">
                        <a class="btn btn-primary add-table" data-toggle="tooltip" data-placement="top" title="Add Table">
                                <i class="fa fa-plus"></i>
                        </a>
                        <a class="btn btn-danger clear-table" data-toggle="tooltip" data-placement="top" title="Clear Table">
                                <i class="fa fa-trash"></i>
                        </a>
                    </div>
                    <input type="hidden" class="hidden_input" id="input_{{100+intval($form['name']) }}" value="{{ Input::old(100+intval($form['name']), @$t_data->value) }}" name="input_{{ 100+intval($form['name']) }}" />
                    <div class="col col-md-12 table-container" id="input_container_{{100+intval($form['name']) }}">
                    </div>
                </div>
                @endif

                @if($form['type'] == 'textarea')
                <?php $detail = App\LeadDetail::where('lead_id', $info->id)->where('field_number', 'LIKE', $form['name'])->first(); ?>  
                <?php $t_data = App\LeadDetail::where('lead_id', $info->id)->where('field_number', 'LIKE', 100+intval($form['name']))->first(); ?>
                <div class="col-md-12 form-input">
                    <div class="col col-md-8">
                        <label>{{ $form['label'] }}</label>
                        <textarea name="input_{{ $form['name'] }}" class="form-control" rows="5">{{ Input::old($form['name'], @$detail->value) }}</textarea>
                    </div>
                    <div class="col col-md-4 list-actions">
                        <a class="btn btn-primary add-table" data-toggle="tooltip" data-placement="top" title="Add Table">
                                <i class="fa fa-plus"></i>
                        </a>
                        <a class="btn btn-danger clear-table" data-toggle="tooltip" data-placement="top" title="Clear Table">
                                <i class="fa fa-trash"></i>
                        </a>
                    </div>
                    <input type="hidden" class="hidden_input" id="input_{{100+intval($form['name']) }}" value="{{ Input::old(100+intval($form['name']), @$t_data->value) }}" name="input_{{ 100+intval($form['name']) }}" />
                    <div class="col col-md-12 table-container" id="input_container_{{100+intval($form['name']) }}">
                    </div>
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

        <div class="col-md-6">
            <?php 
            $img = App\LeadDetail::where('lead_id', $info->id)->where('field_number', 'LIKE', 31)->first(); 
            ?>
            <input type="hidden" name="input_31" class="form-control" value="{{ @$img->value }}">

            <div class="form-group">
                <div class="col-md-12">
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                        <div class="fileinput-new thumbnail">

                            @if( @$img->value)
                            <img src="{{ asset($img->value) }}" alt="" style="max-width: 300px;"/>
                            @else
                            <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" alt=""/>
                            @endif

                        </div>
                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 300px;"/>
                        </div>
                        <div>
                            <span class="btn default btn-file">
                            <span class="fileinput-new">
                            Select image </span>
                            <span class="fileinput-exists">
                            Change </span>
                            <input type="file" name="file">
                            </span>
                            <a href="#" class="btn red fileinput-exists" data-dismiss="fileinput">
                            Remove </a>
                        </div>
                    </div>
                </div>
            </div>   

        </div>
        
        

    </div>
    
    <?php 
        $table = App\LeadDetail::where('lead_id', $info->id)->where('field_number', 'LIKE', 99)->first(); 
        if(count($table > 0)){ ?>
    <div class="row">
        <div class="col-md-6">
            <div class="row">
                <div class="col col-md-6">
                    <div class="add_table">
                        <label>Add Table</label>
                        <input class="form-control" data-toggle="tooltip" data-placement="top" title="Number of fields you want on the table" id="fields_num" type="number" value="1" />
                        <input type="hidden" id="table_json" value='<?php echo $table->value; ?>' name="input_99" />
                    </div>
                </div>
                <div class="col col-md-6 list-actions">
                    <a class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Add Table" id="add_table_btn"><i class="fa fa-plus"></i></a>
                    <a class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Clear Table" id="clear_table_btn"><i class="fa fa-trash"></i></a>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="add_table_col_modal" role="dialog">
            <div class="modal-dialog">
            <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h1>Please Enter your field name(s)</h1>
                    </div>
                    <div class="modal-body">
                        <div class="row" style="padding: 5px;">
                            <div class="col col-md-10">
                                <p style="padding: 5px;">Global Row (will make all your fields have equal rows) </p>
                            </div>
                            <div class="col col-md-2">
                                <input type="number" id="global_row" placeholder="Row" value="0" class="form-control" />
                            </div>
                        </div>
                        <hr/>
                        <h3>Fields</h3>
                        <div id="fields_data_container">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="save_btn" type="button" class="btn btn-primary">Proceed</button>
                    </div>
                </div>
            </div>
        </div> 
        
        <div id="list_container" class="col-md-12" style="overflow: auto;">
            @if( @$table->value)
                <?php
                    //used for indexing
                    $index_a = 0; 
                ?>
                <div class="row">
                <?php foreach(json_decode($table->value) as $data){ ?>
                    <div class="col col-md-2">
                        <div class="row">
                            <div class="col col-md-12">
                                <label>Field Name</label>
                                <input type="text" data-field-num="<?php echo $index_a; ?>" style="font-weight: bolder;" value="<?php echo $data->field_name; ?>" disabled="" class="field_name form-control" />
                            </div>
                        </div>
                        <?php 
                            foreach($data->data as $field_data){ ?>
                        <div class="row">
                            <div class="col col-md-12">
                                <input style="margin: 5px 0px;" data-index="<?php echo $field_data->index; ?>" data-field-num="<?php echo $index_a; ?>" placeholder="Data" value="<?php echo $field_data->value; ?>" type="text" class="input_data form-control"/>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                <?php 
                    $index_a++;
                    } 
                ?>
                </div>
            @endif
            <?php } ?>
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


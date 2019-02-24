  <form action="{{ URL::route('backend.tds.store', ['v' => 2]) }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
 {!! csrf_field() !!}

    <div class="row">
        <div class="col-md-8">

            @foreach(get_tds_v2_forms() as $form)

            <div class="form-group {{ $form['name'] }}" data-form-index="{{ $form['name'] }}" style="{{ @$form['style'] }}">
                
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
                <div class="col-md-12 form-input">
                    <div class="col col-md-8">
                        <label>{{ $form['label'] }}</label>
                    <input type="{{ @$form['input'] }}" name="input_{{ $form['name'] }}" class="form-control" value="{{ Input::old('input_'.$form['name']) }}">
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
                    <input type="hidden" id="input_100{{ $form['name'] }}" name="input_100{{ $form['name'] }}" />
                    <div class="col col-md-12 table-container">
                    </div>
                </div>
                @endif

                @if($form['type'] == 'select')
                <div class="col-md-12">
                    <label>{{ $form['label'] }}</label>
                    <select name="input_{{ $form['name'] }}" class="form-control">
                        <option value="">-- Select {{ $form['label'] }} -- </option>
                        @foreach($form['inputs'] as $input)
                        <option value="{{ $input }}" {{ selected(Input::old($form['name']), $input) }}>{{ $input }}</option> 
                        @endforeach
                    </select>
                </div>
                @endif

                @if($form['type'] == 'textarea')
                <div class="col-md-12">
                    <label>{{ $form['label'] }}</label>
                    <textarea name="input_{{ $form['name'] }}" class="form-control" rows="5">{{ Input::old('input_'.$form['name']) }}</textarea>
                </div>
                @endif

                @if($form['type'] == 'checkbox')
                <div class="col-md-12">
                    <div class="text-left">
                        <div class="sub-title">{{ $form['label'] }}</div>
                    </div>
                </div>

                @foreach($form['inputs'] as $input)
                <div class="col-md-{{ isset($form['col']) ? $form['col'] : 12  }}"  style="{{ @$input['style'] }}">
                    <label>
                    <input type="checkbox"  name="input_{{ $input['id'] }}" value="{{ $input['label'] }}@if(isset($input['img']))<br><img src='{{ asset($input['img']) }}'@endif">  {{ $input['label'] }}
                    
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
                <div class="col-md-12">
                    <div class="text-left">
                        <div class="sub-title">{{ $form['label'] }}</div>
                    </div>
                </div>

                @foreach($form['inputs'] as $input)
                <div class="col-md-12">
                    <label>
                    <input type="radio" name="{{ $form['name'] }}" value="{{ $input['label'] }}"> {{ $input['label'] }}</label>
                </div>
                @endforeach
                @endif

                @if($form['type'] == 'list')
                <div class="col-md-12">
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
                <div class="col-md-12">
                    <div class="text-left">
                        <div class="sub-title">{{ $form['label'] }}</div>
                    </div>
                </div>

                <div class="col-md-12 col-field field-{{ $form['name'] }}">

                    <div class="list-field">
                        <div class="row">

                            @foreach($form['inputs'] as $input)

                            <div class="col-md-{{ $form['col'] }}">                            
                            <label>{{ $input['label'] }}</label>
                            

                            @if($input['type'] == 'text')
                            <input type="text" name="input_{{ $form['name'] }}[]" class="form-control" 
                            value="{{ Input::old($input['label']) }}">
                            @endif

                            @if($input['type'] == 'textarea')
                            <textarea rows="5" name="input_{{ $form['name'] }}[]" class="form-control">{{ Input::old($input['label']) }}</textarea>
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

                </div>

                @endif


                <!-- START error message -->
                <div class="col-md-12">
                {!! $errors->first('input_'.$form['name'],'<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                </div>
                <!-- END error message -->

            </div>
            @endforeach 

      </div>         

        <div class="col-md-6 hide">

   
            <div class="form-group">
                <div class="col-md-12">
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                        <div class="fileinput-new thumbnail">
                            <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" alt=""/>
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
    <div class="row">
        <div class="col-md-6">
            <div class="row">
                <div class="col col-md-6">
                    <div class="add_table">
                        <label>Add Table</label>
                        <input class="form-control" data-toggle="tooltip" data-placement="top" title="Number of fields you want on the table" id="fields_num" type="number" value="1" />
                        <input type="hidden" id="table_json" name="input_99" />
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
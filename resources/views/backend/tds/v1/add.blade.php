  <form action="{{ URL::route('backend.tds.store', ['v' => 1]) }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
 {!! csrf_field() !!}

    <div class="row">
        <div class="col-sm-12">


            @foreach(get_tds_forms() as $form)

            <div class="form-group {{ $form['name'] }}" style="{{ @$form['style'] }}">
                
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
                <div class="col-md-6">
                    <label>{{ $form['label'] }}</label>
                    <input type="{{ @$form['input'] }}" name="input_{{ $form['name'] }}" class="form-control" value="{{ Input::old($form['name']) }}">
                    <small class="help-block">{{ @$form['help'] }}</small>
                </div>
                @endif

                @if($form['type'] == 'select')
                <div class="col-md-6">
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
                <div class="col-md-6">
                    <label>{{ $form['label'] }}</label>
                    <textarea name="input_{{ $form['name'] }}" class="form-control" rows="5">{{ Input::old($form['name']) }}</textarea>
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
 <?php 
    $type = Request::segment('1');
    $sheet_id = Request::segment('3'); 
    $notes = DB::table('notes')->where('type', $type)->where('sheet_id', $sheet_id)->orderBy('id', 'DESC')->get(); 
?>
<form action="{{ URL::route('backend.setting.add-note') }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
{!! csrf_field() !!}
<input type="hidden" name="type" value="{{ $type }}">
<input type="hidden" name="sheet_id" value="{{ $sheet_id }}">

<div class="row">
    <div class="col-sm-8">
        <div class="form-group">
            <div class="col-md-12">
                <label for="textfield" class="control-label col-sm-2">{{ $form['label'] }}</label>
                <textarea name="notes" placeholder="Enter your message here ..." rows="5" class="form-control" required></textarea>
            </div>
        </div>    
        <button type="submit" class="btn btn-primary btn-block">Add Note</button>

        <div class="margin-top-20">
        <strong class="badge badge-primary notes_count_2">{{ count($notes) }}</strong> note{{ isPlural(count($notes)) }}
        </div>

        <ul class="media-list margin-top-20">
            @foreach($notes as $note)
            <li class="media">
                <a class="pull-left" href="#">
                <img alt="" class="img-circle" src="{{ @hasPhoto(Auth::user()->photo) }}" width="50" height="50" />
                </a>
                <div class="media-body">

                    @if(Auth::User()->id == $note->user_id)
                    <a href="#" class="delete btn btn-default btn-xs pull-right"
                        data-href="{{ URL::route('backend.setting.delete-note', $note->id) }}" 
                        data-toggle="modal"
                        data-target=".delete-modal" 
                        data-title="Confirm Delete"
                        data-auth="true"
                        data-body="Are you sure you want to delete this note?"><i class="fa fa-remove"></i> Delete</a>  
                    @endif    

                    <h5 class="media-heading">{{ name_formatted($note->user_id, 'f l') }}</h5>
                    <small class="text-muted">{{ xTimeAgo($note->created_at, date('Y-m-d H:i:s')) }}</small>

                    <p>{{ $note->message }}</p> 
                </div>    
            </li>
            @endforeach
        </ul>
    </div>
</div>
</form>



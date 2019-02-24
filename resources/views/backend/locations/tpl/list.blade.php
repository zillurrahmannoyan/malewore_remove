 @if( Input::get('parent') )
<a href="{{ URL::route('backend.locations.index') }}" class="pull-right btn btn-default">Main locations</a>
@endif


<h2>{{ $parent_name }}</h2>


<a href="{{ URL::route('backend.locations.index', query_vars('status=0&page=0&s=0') ) }}">All ({{ $all }})</a> | 
<a href="{{ URL::route('backend.locations.index', query_vars('status=trash&page=0&s=0') ) }}">Trashed ({{ $trashed }})</a>

<div class="margin-top-20"></div>

<form class="form-inline" role="form">
	@if(Input::get('parent'))
	<input type="hidden" name="parent" value="{{ Input::get('parent') }}">
	@endif

	@if(Input::get('status'))
	<input type="hidden" name="status" value="{{ Input::get('status') }}">
	@endif

	<div class="form-group pull-right">
		<input name="s" class="form-control" value="{{ Input::get('s') }}">
		<button class="btn btn-default">Search</button>	
	</div>

	<div class="form-group">
			<select name="action" class="form-control">
			<option value="0">Bulk Actions</option>
			<option value="trash">Move to Trash</option>
		</select>
	</div>
	<div class="form-group">
		<button class="btn btn-default">Apply</button>	
	</div>

</form>

<table class="table table-hover table-striped">
	<thead>
		<tr>
			<th width="1%"><input type="checkbox"></th>
			<th>Name</th>
			<th>Slug</th>
			<th>Count</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($rows as $row)
		<?php $count = App\Location::where('parent', $row->id)->count(); ?>
		<tr>
			<td><input type="checkbox"></td>
			<td>
				<div class="post-title bold">{{ $row->name }}</div>
				<div>

					@if( Input::get('status') == 'trash' )
	                    <a href="#" class="delete btn btn-xs btn-default uppercase"
	                        data-href="{{ URL::route('backend.locations.restore', [$row->id, query_vars()]) }}" 
	                        data-toggle="modal"
	                        data-target=".delete-modal" 
	                        data-title="Confirm Restore"
	                        data-body="Are you sure you want to restore <b>{{ $row->name }}</b>?">Restore</a>
					@else
						<a href="{{ URL::route('backend.locations.index', ['parent' => $row->id]) }}" class="btn btn-default btn-xs uppercase">View</a> 

						<a href="{{ URL::route('backend.locations.index', ['id' => $row->id]) }}&parent={{ $row->id }}" class="btn btn-default btn-xs uppercase">Edit</a> 

	                    @if($count == 0)
	                    <a href="#" class="delete btn btn-xs btn-default uppercase"
	                        data-href="{{ URL::route('backend.locations.delete', [$row->id, query_vars()]) }}" 
	                        data-toggle="modal"
	                        data-target=".delete-modal" 
	                        data-title="Confirm Delete"
	                        data-body="Are you sure you want to delete <b>{{ $row->name }}</b>?">Move to Trash</a>
	                    @else
	                    <button href="#" class="btn btn-xs uppercase disabled">Move to Trash</button>
	                    @endif
                    @endif

				</div>
			</td>
			<td>{{ $row->slug }}</td>
			<td>{{ $count }}</td>
		</tr>
		@endforeach
	</tbody>
	@if($count == 0)
	<tfoot>
	<tr>
		<td colspan="3">
			No catogories found.
		</td>
	</tr>			
	</tfoot>
	@endif
</table>

{{ $rows->appends(['parent' => Input::get('parent')])->links() }}

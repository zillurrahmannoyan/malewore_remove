 @extends('backend.layouts.admin')

@section('title')
Users
@stop

@section('content')

<a href="{{ URL::route('backend.users.add') }}">Add New</a>

@if($all > 0)
| <a href="{{ URL::route('backend.users.index') }}">All ({{ $all }})</a>
@endif

@if($admins > 0)
| <a href="{{ URL::route('backend.users.index', query_vars('role=admin&s=0')) }}">Administrators ({{ $admins }})</a>
@endif

@if($users > 0)
| <a href="{{ URL::route('backend.users.index', query_vars('role=user&s=0')) }}">Users ({{ $users }})</a>
@endif

<form class="form-inline" role="form">

    <div class="form-group pull-right">
        <input name="s" class="form-control" value="{{ Input::get('s') }}">
        <button class="btn btn-default" type="submit">Search</button>   
    </div>

</form>

<table class="table table-hover table-striped">
    <thead>
        <tr>
            <th width="1%"><input type="checkbox" id="check_all"></th>
            <th>Name</th>
            <th>Username</th>
            <th>Email</th>            
            <th>Role</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($rows as $row)
        <tr>
            <td><input type="checkbox" name="ids[]" value="{{ $row->lead_id }}" class="checkboxes"></td>
            <td>
                <div class="post-title bold"><a href="{{ URL::route('backend.users.edit', [$row->id, query_vars()]) }}">{{ name_formatted($row->id, 'f l') }}</a></div>
                <div>
                    <a href="{{ URL::route('backend.users.edit', [$row->id, query_vars()]) }}" class="btn btn-xs btn-default uppercase">Edit</a>
                    @if($row->status == 0)
                    <a href="#" class="delete btn btn-xs btn-default uppercase"
                        data-href="{{ URL::route('backend.users.delete', $row->id) }}" 
                        data-toggle="modal"
                        data-target=".delete-modal" 
                        data-title="Confirm Delete"
                        data-body="Are you sure you want to delete <b>{{ $row->title }}</b>?">Delete</a>
                    @else
                    <button href="#" class="btn btn-xs uppercase disabled">Delete</button>
                    @endif
                </div>
            </td>
            <td>{{ $row->username }}</td>
            <td>{{ $row->email }}</td>
            <td>
                {{ $row->role or user_roles($row->role) }}
            </td>
            <td>
                {{ dateFormat($row->updated_at, 'M d, Y H:i:s') }}<br>
                Last Modified
            </td>
        </tr>
        @endforeach
        @if($count == 0)
        <tfoot>
        <tr>
            <td colspan="3">
                No {{ str_plural(Input::get('type')) }} found.
            </td>
        </tr>           
        </tfoot>
        @endif
    </tbody>
</table>

{{ $rows->appends(['role' => Input::get('role')])->links() }}


@stop


@section('top_style')
@stop


@section('bottom_style')
@stop

@section('bottom_plugin_script')
@stop

@section('bottom_script')
<script>
$(document).on('click change','input[id="check_all"]',function() {
    var checkboxes = $('.checkboxes');
    if ($(this).is(':checked')) {
        checkboxes.prop("checked" , true);
        checkboxes.closest('span').addClass('checked');
    } else {
        checkboxes.prop( "checked" , false );
        checkboxes.closest('span').removeClass('checked');
    }
});
</script>
@stop
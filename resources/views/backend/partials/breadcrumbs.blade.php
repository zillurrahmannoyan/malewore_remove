 <div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <a href="{{ URL::route('backend.dashboard.index') }}">Dashboard</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <?php 
        $cb = count(breadcrumbs()); 
        $cbc = 1;
        ?>
        @foreach(breadcrumbs() as $breadcrumb)
        <li>
            @if($breadcrumb['url'])
            <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['name'] }}</a>
            @else
            <span>{{ $breadcrumb['name'] }}</span>
            @endif

            @if($cbc != $cb)
            <i class="fa fa-angle-right"></i>
            @endif
        </li>
        <?php $cbc++; ?>
        @endforeach
    </ul>
    <div class="page-toolbar">
        <div class="btn-group pull-right">
            <button type="button" class="btn btn-fit-height grey-salt dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true" aria-expanded="false">
            Actions <i class="fa fa-angle-down"></i>
            </button>
            <ul class="dropdown-menu pull-right" role="menu">
                <li>
                <?php $vars = array('room_type=0');?>                        
                <a href="{{ URL::route('backend.rooms.add', query_vars($vars)) }}"><i class="fa fa-plus-circle"></i> Add Room</a>
                </li>
                <li>
                <a href="{{ URL::route('backend.rooms.index', query_vars($vars)) }}"><i class="fa fa-list-ul"></i> Rooms</a>
                </li>
                <li class="divider"></li>
                <li>
                <a href="{{ URL::route('backend.room-types.add', query_vars($vars)) }}"><i class="fa fa-plus-circle"></i> Add Room Type</a>
                </li>
                <li>
                <a href="{{ URL::route('backend.room-types.index', query_vars($vars)) }}"><i class="fa fa-list-ul"></i> All Room Types</a>
                </li>
                <li class="divider"></li>
                <li>
                <a href="{{ URL::route('backend.rooms.availability', 'tower='.Input::get('tower')) }}"><i class="fa fa-th-list"></i> Building</a>

                </li>
            </ul>
        </div>
    </div>
</div>

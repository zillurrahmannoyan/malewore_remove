 @extends('backend.layouts.admin')

@section('title')
Dashboard
@stop

@section('content')
<div class="row">
	<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
		<div class="dashboard-stat blue-madison">
			<div class="visual">
				<i class="fa fa-folder"></i>
			</div>
			<div class="details">
				<div class="number">
					 {{ $count_tds }}
				</div>
				<div class="desc">
					 Technical Data Sheets
				</div>
			</div>
			<a class="more" href="{{ URL::route('backend.tds.index') }}">
			View more <i class="m-icon-swapright m-icon-white"></i>
			</a>
		</div>
	</div>
	<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
		<div class="dashboard-stat red-intense">
			<div class="visual">
				<i class="fa fa-folder"></i>
			</div>
			<div class="details">
				<div class="number">
					 {{ $count_sds }}
				</div>
				<div class="desc">
					 Safety Data Sheets
				</div>
			</div>
			<a class="more" href="{{ URL::route('backend.sds.index') }}">
			View more <i class="m-icon-swapright m-icon-white"></i>
			</a>
		</div>
	</div>

	@if(Auth::user()->role == 'admin')
	<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
		<div class="dashboard-stat green-haze">
			<div class="visual">
				<i class="fa fa-users"></i>
			</div>
			<div class="details">
				<div class="number">
					 {{ $count_users }}
				</div>
				<div class="desc">
					 Users
				</div>
			</div>
			<a class="more" href="{{ URL::route('backend.users.index', ['role' => 'user']) }}">
			View more <i class="m-icon-swapright m-icon-white"></i>
		</div>
	</div>
	@endif

</div>
@stop


@section('top_style')
@stop


@section('bottom_style')
@stop

@section('bottom_plugin_script')
@stop

@section('bottom_script')
@stop
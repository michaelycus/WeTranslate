@extends('layouts.default')

@section('content')

<div class="page-header">
	<?php $status_label = unserialize (VIDEO_STATUS_LABEL); ?>
	<h1><span class="text-light-gray">Manage</span> Users</h1>
</div>


<div class="panel widget-followers">
	<div class="panel-heading">
		<span class="panel-title"><i class="panel-title-icon fa fa-users"></i>Users</span>
		<div class="panel-heading-controls text-primary">
			<strong><i class="panel-title-icon fa fa-shield"></i>Admin</strong>
		</div>
	</div> <!-- / .panel-heading -->
	<div class="panel-body">

		<script>
			init.push(function () {

				$('.switcher-admin').switcher({
					theme: 'square',
					on_state_content: '<span class="fa fa-check"></span>',
					off_state_content: '<span class="fa fa-times"></span>'
				});

				$('#switcher-example-1').switcher();

				$('#switcher-example-2').switcher({
					theme: 'square',
					on_state_content: '<span class="fa fa-check"></span>',
					off_state_content: '<span class="fa fa-times"></span>'
				});

				$('#switcher-example-3').switcher({
					theme: 'modern'
				});
			});
		</script>

		<!-- <div>
			<input type="checkbox" id="switcher-example-1">&nbsp;&nbsp;
			
			<input type="checkbox" id="switcher-example-3">
		</div> -->


	@foreach ($users as $user)
		<div class="follower">
			<img src="{{ $user->photo() }}" alt="" class="follower-avatar">
			<div class="body">
				<div class="follower-controls">
					<!-- <h6 class="text-light-gray text-semibold text-xs" style="margin:20px 0 10px 0;">ADMIN</h6> -->
					<input type="checkbox" class="switcher-admin" checked="checked">
				</div>
				<a href="#" class="follower-name">{{ $user->fullname }}</a><br>
				<a href="#" class="follower-username">{{ $user->email }}</a>
			</div>
		</div>
	@endforeach		
	</div>
</div>



@stop

@section('script')

<script type="text/javascript">
	init.push(function () {
		// Javascript code here
	})
	window.PixelAdmin.start(init);
</script>

   
@stop
@extends('layouts.default')

@section('content')

<div class="page-header">
	<?php 
		$status_label = unserialize (VIDEO_STATUS_LABEL);
		$video_marks = unserialize (VIDEO_MARKS);
	 ?>
	<h1><span class="text-light-gray">Videos / </span>{{ $status_label[$status] }}</h1>
</div> <!-- / .page-header -->

<?php Debugbar::info('teste'. $videos); ?>

@foreach ($videos as $video)

<div class="row" data-panel-id="{{ $video->id }}">

	<div class="col-md-12">

		<div class="panel colourable">
			<div class="panel-heading">
				<span class="panel-title"><a href="{{ URL::route('videos-details', $video->id) }}" target="_blank">{{ $video->title }}</a></span>
				<div class="panel-heading-controls">
					<!-- <span class="label label-tag label-warning">I need some assistence!</span> -->
					<div class="btn-group btn-group-xs">
						<!-- <button class="btn dropdown-toggle" type="button" data-toggle="dropdown"><span class="fa fa-bullhorn"></span>&nbsp;<span class="fa fa-caret-down"></span></button>&nbsp; -->
						<ul class="dropdown-menu dropdown-menu-right">
							@foreach ($video_marks as $key => $mark)
							<!-- <li><a href="{{ $key }}">{{ $mark }}</a></li> -->
							@endforeach
						</ul>			
					</div>

					@if (Auth::user()->auth >= USER_AUTH_ADMIN)
					<div class="btn-group btn-group-xs">
						<button class="btn dropdown-toggle" type="button" data-toggle="dropdown"><span class="fa fa-cog"></span>&nbsp;<span class="fa fa-caret-down"></span></button>
						<ul class="dropdown-menu dropdown-menu-right">							
							<li><a onClick="remove_video({{ $video->id }});"><i class="fa fa-trash-o"></i> Delete video</a></li>
						</ul>
					</div>
					@endif
				</div>
			</div>
			<div class="panel-body">
				<div class="col-md-1 text-center text-lg">					
					{{ '<img src="' . $video->thumbnail . '" class="thumbnail_video">' }}
				</div>				

				<div class="col-md-4 text-center tasks-panel" id="{{ $video->id }}">
				</div>

				<div class="col-md-3">
					<ul class="list-group no-margin">
						<!-- Without left and right borders, extra small horizontal padding -->
						<li class="list-group-item no-border padding-xs-hr">
							{{ gmdate("H:i:s", $video->duration) }} <i class="fa  fa-clock-o pull-right"></i>
						</li> <!-- / .list-group-item -->
						<!-- Without left and right borders, extra small horizontal padding -->
						<li class="list-group-item no-border-hr padding-xs-hr">
							{{ date("d/m/Y", strtotime($video->created_at)) }} <i class="fa  fa-calendar-o pull-right"></i>
						</li> <!-- / .list-group-item -->
						<!-- Without left and right borders, without bottom border, extra small horizontal padding -->
						<li class="list-group-item no-border-hr no-border-b padding-xs-hr">
							{{ $video->comments()->count() }}  comments <i class="fa  fa-comment pull-right"></i>
						</li> <!-- / .list-group-item -->
					</ul>					
				</div>	

				<div class="col-md-2 text-center">					
					<p><a href="{{ $video->original_link }}" target="_blank" class="btn btn-flat btn-block btn-sm btn-labeled btn-danger"><span class="btn-label icon fa fa-youtube-play"></span>Original video</a></p>
					<p><a href="{{ $video->working_link }}" target="_blank" class="btn btn-flat btn-block btn-sm btn-labeled btn-warning"><span class="btn-label icon fa fa-rocket"></span>Translate!</a></p>
					<p><a href="{{ URL::route('videos-details', $video->id) }}" target="_blank" class="btn btn-flat btn-block btn-sm btn-labeled btn-info"><span class="btn-label icon fa  fa-info"></span>Video details</a></p>
				</div>

				<div class="col-md-2 text-center">
					@if ($video->status == VIDEO_STATUS_TRANSLATING)
					<a class="btn btn-sm btn-primary btn-labeled btn-block confirm-move" data-video-id="{{ $video->id }}" data-status="{{ VIDEO_STATUS_SYNCHRONIZING }}">
						<span class="btn-label">Translation<br> completed</span><br><i class="fa fa-arrow-right"></i>
					</a>
					@elseif ($video->status == VIDEO_STATUS_SYNCHRONIZING)
					<a class="btn btn-sm btn-primary btn-labeled btn-block confirm-return" data-video-id="{{ $video->id }}" data-status="{{ VIDEO_STATUS_TRANSLATING }}">
						<span class="btn-label"><i class="fa fa-arrow-left"></i></span>Return
					</a>
					<a class="btn btn-sm btn-primary btn-labeled btn-block confirm-move" data-video-id="{{ $video->id }}" data-status="{{ VIDEO_STATUS_PROOFREADING }}">
						<span class="btn-label">Synchronizing<br> completed<br></span><br><i class="fa fa-arrow-right"></i>
					</a>
					@elseif ($video->status == VIDEO_STATUS_PROOFREADING)
					<a class="btn btn-sm btn-primary btn-labeled btn-block confirm-return" data-video-id="{{ $video->id }}" data-status="{{ VIDEO_STATUS_SYNCHRONIZING }}">
						<span class="btn-label"><i class="fa fa-arrow-left"></i></span>Return
					</a>
						@if (Auth::user()->auth >= USER_AUTH_ADMIN)
						<a class="btn btn-sm btn-success btn-labeled btn-block confirm-move" data-video-id="{{ $video->id }}" data-status="{{ VIDEO_STATUS_FINISHED }}">
							<span class="btn-label">Proofreading<br> completed<br></span><br><i class="fa fa-arrow-right"></i>						
						</a>
						@endif
					@endif
				</div>
			</div>
		</div>
	</div>
</div>

@endforeach 


@stop

@section('script')

<script type="text/javascript">

	function refresh_videos()
	{
		$('div.tasks-panel').empty();

		$('div.tasks-panel').each(function(index, value){
		    var video_id = $(this).attr('id');
		    var current_status = {{ $status }};
		    var url = '<?php echo URL::to('/'); ?>' + '/videos/tasks/' + video_id + '/' + current_status;		
			var div = $(this);

		    $.get(url, function(data) {	                   	    	
	           div.append(data);
	        });	 
		});
	}	

	function setHelp(video_id, status)
	{
		var url = '<?php echo URL::to('/'); ?>' + '/videos/help/' + video_id + '/' + status;
		$.get(url, function(data) {
           refresh_videos();
	    });			
	}

	function setStopHelp(video_id, status)
	{
		var url = '<?php echo URL::to('/'); ?>' + '/videos/stophelp/' + video_id + '/' + status;
		$.get(url, function(data) {
           refresh_videos();
	    });
	}	

	$('.confirm-move').on('click', function () {
		var video_id = $(this).attr('data-video-id');
		var video_status = $(this).attr('data-status');		

		var confirm_message = "Are you sure to move to the next stage?";

		if (video_status=={{ VIDEO_STATUS_FINISHED }})		
			confirm_message += " After this, there is no turning back!";
		
		bootbox.confirm({
			message: confirm_message,
			callback: function(result) {
				if (result)
				{
					var url = '<?php echo URL::to('/'); ?>' + '/videos/move-to/' + video_id + '/' + video_status;
					$.get(url, function(data) {					
					   $.growl.notice({ title: "Well done!", message: "The video was moved to the next stage!" });
			           $("div[data-panel-id='"+video_id+"']").slideUp("slow");
				    });	
				}							
			},
			className: "bootbox-sm"
		});
	});

	$('.confirm-return').on('click', function () {
		var video_id = $(this).attr('data-video-id');
		var video_status = $(this).attr('data-status');		
		bootbox.confirm({
			message: "Are you sure you want to return?",
			callback: function(result) {
				if (result)
				{
					var url = '<?php echo URL::to('/'); ?>' + '/videos/return-to/' + video_id + '/' + video_status;
					$.get(url, function(data) {					
					   $.growl.notice({ title: "Well done!", message: "The video was moved to the previous stage!" });
			           $("div[data-panel-id='"+video_id+"']").slideUp("slow");
				    });	
				}							
			},
			className: "bootbox-sm"
		});
	});

	function remove_video(video_id)
	{
		bootbox.confirm({
			message: "Are you sure you want to remove this video?",
			callback: function(result) {
				if (result)
				{
					var url = '<?php echo URL::to('/'); ?>' + '/videos/remove/' + video_id;
					$.get(url, function(data) {					
					   $.growl.notice({ title: "Ok!", message: "The video was removed!" });
			           $("div[data-panel-id='"+video_id+"']").slideUp("slow");
				    });	
				}							
			},
			className: "bootbox-sm"
		});
	}

	refresh_videos();

	init.push(function () {
		$('a').tooltip();
	});

	window.PixelAdmin.start(init);

</script>
			
@stop
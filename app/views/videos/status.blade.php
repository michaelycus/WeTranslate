@extends('layouts.default')

@section('content')

<div class="page-header">
	<?php $status_label = unserialize (VIDEO_STATUS_LABEL); ?>
	<h1><span class="text-light-gray">Videos / </span>{{ $status_label[$status] }}</h1>
</div> <!-- / .page-header -->


@foreach ($videos as $video)
<?php
	preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#",  $video->original_link, $matches);

	$json = json_decode(file_get_contents("http://gdata.youtube.com/feeds/api/videos/$matches[0]?v=2&alt=jsonc"));
?>	

<div class="row" data-panel-id="{{ $video->id }}">

	<div class="col-md-12">

		<div class="panel colourable">
			<div class="panel-heading">
				<span class="panel-title">{{ $json->data->title }}</span>
				<div class="panel-heading-controls">
					<!-- <span class="label label-tag label-warning">Need proofreading</span> -->
					<div class="btn-group btn-group-xs">
						<button class="btn dropdown-toggle" type="button" data-toggle="dropdown"><span class="fa fa-bullhorn"></span>&nbsp;<span class="fa fa-caret-down"></span></button>
						<ul class="dropdown-menu dropdown-menu-right">
							<li><a href="#">(Coming soon)</a></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="panel-body">
				<div class="col-md-1 text-center text-lg">					
					{{ '<img src="' . $json->data->thumbnail->sqDefault . '">' }}
				</div>				

				<div class="col-md-4 text-center tasks-panel" id="{{ $video->id }}">
				</div>

				<div class="col-md-3">
					<ul class="list-group no-margin">
						<!-- Without left and right borders, extra small horizontal padding -->
						<li class="list-group-item no-border padding-xs-hr">
							{{ gmdate("H:i:s", $json->data->duration) }} <i class="fa  fa-clock-o pull-right"></i>
						</li> <!-- / .list-group-item -->
						<!-- Without left and right borders, extra small horizontal padding -->
						<li class="list-group-item no-border-hr padding-xs-hr">
							{{ date("d/m/Y", strtotime($video->created_at)) }} <i class="fa  fa-calendar-o pull-right"></i>
						</li> <!-- / .list-group-item -->
						<!-- Without left and right borders, without bottom border, extra small horizontal padding -->
						<li class="list-group-item no-border-hr no-border-b padding-xs-hr">
							47 comments <i class="fa  fa-comment pull-right"></i>
						</li> <!-- / .list-group-item -->
					</ul>					
				</div>	

				<div class="col-md-2 text-center">					
					<p><a href="{{ $video->original_link }}" target="_blank" class="btn btn-flat btn-block btn-sm btn-labeled btn-danger"><span class="btn-label icon fa fa-youtube-play"></span>Original video</a></p>
					<p><a href="{{ $video->working_link }}" target="_blank" class="btn btn-flat btn-block btn-sm btn-labeled btn-success"><span class="btn-label icon fa fa-rocket"></span>Translate!</a></p>
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
					<a class="btn btn-sm btn-primary btn-labeled btn-block confirm-move" data-video-id="{{ $video->id }}" data-status="{{ VIDEO_STATUS_FINISHED }}">
						<span class="btn-label">Proofreading<br> completed<br></span><br><i class="fa fa-arrow-right"></i>
					</a>
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
		bootbox.confirm({
			message: "Are you sure?",
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
			message: "Are you sure?",
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

	refresh_videos();

</script>
			
@stop
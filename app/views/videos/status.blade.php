@extends('layouts.default')

@section('content')

<div class="page-header">
	<h1><span class="text-light-gray">UI Elements / </span>Panels</h1>
</div> <!-- / .page-header -->


@foreach ($videos as $video)
<?php
	preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#",  $video->original_link, $matches);

	$json = json_decode(file_get_contents("http://gdata.youtube.com/feeds/api/videos/$matches[0]?v=2&alt=jsonc"));
?>	

<div class="row">

	<div class="col-md-12">

		<div class="panel colourable">
			<div class="panel-heading">
				<span class="panel-title">{{ $json->data->title }}</span>
				<div class="panel-heading-controls">
					<span class="label label-tag label-warning">Need proofreading</span>
				</div>
			</div>
			<div class="panel-body">
				<div class="col-md-2 text-center text-lg">
					
					{{ '<img src="' . $json->data->thumbnail->sqDefault . '">' }}					

				</div>
				
				<div class="col-md-4 text-center text-lg">
					<p><img src="http://wetranslate.dev:8000/assets/demo/avatars/1.jpg" alt="" class="user-list">
					<img src="http://wetranslate.dev:8000/assets/demo/avatars/2.jpg" alt="" class="user-list">
					<img src="http://wetranslate.dev:8000/assets/demo/avatars/3.jpg" alt="" class="user-list"></p>
					<p>
						<button class="btn btn-flat btn-sm btn-labeled btn-success"><span class="btn-label icon fa fa-check-circle"></span>I want to help!</button>	
						{{-- $video->task->user_id --}}
						@if ($video->task)
						{{ $video->task->user_id }}
						@endif
						
					</p>
				</div>	

				<div class="col-md-4">

					<ul class="list-group no-margin">
						<!-- Without left and right borders, extra small horizontal padding -->
						<li class="list-group-item no-border padding-xs-hr">
							00:15:32 <i class="fa  fa-clock-o pull-right"></i>
						</li> <!-- / .list-group-item -->
						<!-- Without left and right borders, extra small horizontal padding -->
						<li class="list-group-item no-border-hr padding-xs-hr">
							12/05/2014 <i class="fa  fa-calendar-o pull-right"></i>
						</li> <!-- / .list-group-item -->
						<!-- Without left and right borders, without bottom border, extra small horizontal padding -->
						<li class="list-group-item no-border-hr no-border-b padding-xs-hr">
							47 comments <i class="fa  fa-comment pull-right"></i>
						</li> <!-- / .list-group-item -->
					</ul>
					
				</div>	

				<div class="col-md-2 text-center">
					
					<p><a href="{{ $video->original_link }}" target="_blank" class="btn btn-flat btn-block btn-sm btn-labeled btn-danger"><span class="btn-label icon fa fa-youtube-play"></span>Original</a></p>
					<p><a href="{{ $video->working_link }}" target="_blank" class="btn btn-flat btn-block btn-sm btn-labeled btn-success"><span class="btn-label icon fa fa-rocket"></span>Let's go!</a></p>
					<p><a href="{{ URL::route('videos-details', $video->id) }}" target="_blank" class="btn btn-flat btn-block btn-sm btn-labeled btn-info"><span class="btn-label icon fa  fa-info"></span>Details</a></p>

				</div>
				
			</div>

		</div>
	</div>

</div>


@endforeach

 

@stop

@section('script')

<script type="text/javascript">
	init.push(function () {
		
		

	})
	window.PixelAdmin.start(init);
</script>
			
@stop

				

@extends('layouts.default')

{{ $theme = "page-profile" }}

@section('content')


<div class="profile-full-name">
	<span class="text-semibold">{{ $video->title }}</span> 
</div>
	<div class="profile-row">
	<div class="left-col">
		<div class="profile-block">
			<div class="panel video-detail-image">
				{{ '<img src="' . $video->thumbnail . '">' }}
			</div><br>
			
		</div>

		<div class="panel panel-transparent">
			<div class="panel-heading">
				<span class="panel-title"></span>
			</div>			
			<div class="tasks-panel">
			</div>			
		</div>

		<div class="panel panel-transparent">
			<div class="panel-heading">
				<span class="panel-title">Details</span>
			</div>
			<div class="list-group">
				<a href="{{ $video->original_link }}" class="list-group-item"><i class="profile-list-icon fa fa-youtube-play" style="color: #e00022"></i> Original location</a>
				<a href="{{ $video->working_link }}" class="list-group-item"><i class="profile-list-icon fa fa-rocket" style="color: #059418"></i> Working location</a>
				<a href="#" class="list-group-item"><i class="profile-list-icon fa fa-clock-o" style="color: #4ab6d5"></i> {{ gmdate("H:i:s", $video->duration) }}</a>
				<a href="#" class="list-group-item"><i class="profile-list-icon fa fa-calendar-o" style="color: #1a7ab9"></i> {{ date("d/m/Y", strtotime($video->created_at)) }}</a>
			</div>
		</div>

	</div>
	<div class="right-col">

		<hr class="profile-content-hr no-grid-gutter-h">
		
		<div class="profile-content">

			<ul id="profile-tabs" class="nav nav-tabs">
				<li class="active">
					<a href="#profile-tabs-board" data-toggle="tab">Comments</a>
				</li>
				<li>
					<a href="#profile-tabs-activity" data-toggle="tab">Timeline</a>
				</li>				
			</ul>

			<div class="tab-content tab-content-bordered panel-padding">
				<div class="widget-article-comments tab-pane panel no-padding no-border fade in active" id="profile-tabs-board">

					<div class="comment">
						<div id="disqus_thread"></div>
						<script type="text/javascript">
							/* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
							var disqus_shortname = 'wetranslate'; // required: replace example with your forum shortname

							/* * * DON'T EDIT BELOW THIS LINE * * */
							(function() {
							var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
							dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
							(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
							})();
						</script>
						<noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
						<a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>						    

					</div>

					<hr class="no-panel-padding-h panel-wide">

				</div> <!-- / .tab-pane -->

				<div class="tab-pane fade" id="profile-tabs-activity">
					<div class="timeline centered">
						<div class="tl-header now bg-primary">Now</div>

					<?php
					$tasks_label = unserialize(TASKS_TYPE_LABEL);
					$img_video_status = unserialize(IMG_VIDEO_STATUS);
					$i = 1;
					?>
					@foreach ($tasks as $task)
						 
						<div class="tl-entry <?php echo ($i%2==0 ? '' : 'left'); $i++;  ?>">
							<div class="tl-time">
								{{ date("d/m/Y H:i", strtotime($task->created_at)) }}
							</div>

							<?php
							$background = '';
							switch ($task->type) {
								case TASK_SUGGESTED_VIDEO: $background = 'bg-info'; break;
								case TASK_IS_TRANSLATING: 
								case TASK_IS_SYNCHRONIZING: 
								case TASK_IS_PROOFREADING: $background = 'bg-warning'; break;
								case TASK_IS_FINISHED: 
								case TASK_APPROVED_VIDEO: $background = 'bg-success'; break;
								case TASK_REJECTED_VIDEO: $background = 'bg-danger'; break;								
								case TASK_ADVANCE_TO_SYNC:
								case TASK_ADVANCE_TO_PROOF: $background = 'bg-primary'; break;
								case TASK_BACK_TO_TRANS:
								case TASK_BACK_TO_SYNC: 								
								case TASK_BACK_TO_PROOF: $background = 'bg-default'; break;									
							}
							?>

							<div class="tl-icon {{ $background }}" style="padding-top: 12px">
								<i class="fa {{ $img_video_status[$task->type] }}"></i>
							</div>

							<div class="panel tl-body">			
							    <img src="{{ $task->user->photo() }}" alt="" class="rounded" style=" width: 20px;height: 20px;margin-top: -2px;">					
								{{ $task->user->name . ' ' . $tasks_label[$task->type] }}
							</div>
						</div>

					@endforeach

					</div>		
				
				</div> <!-- / .tab-pane -->
			</div> <!-- / .tab-content -->
		</div>
	</div>
</div>


@stop

@section('script')

<script type="text/javascript">
	init.push(function () {
		$('#profile-tabs').tabdrop();

		$("#leave-comment-form").expandingInput({
			target: 'textarea',
			hidden_content: '> div',
			placeholder: 'Write message',
			onAfterExpand: function () {
				$('#leave-comment-form textarea').attr('rows', '3').autosize();
			}
		});
	})
	window.PixelAdmin.start(init);

	function refresh_videos()
	{
		$('div.tasks-panel').empty();

		$('div.tasks-panel').each(function(index, value){		    		    
		    var url = '<?php echo URL::to('/'); ?>' + '/videos/detail-tasks/{{ $video->id }}/{{$video->status}}';
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

	refresh_videos();

	$('.timeline').each(function(index, value){
	    $(this).removeClass('page-profile');
	});

</script>
			
@stop



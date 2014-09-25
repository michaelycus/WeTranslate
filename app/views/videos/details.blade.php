@extends('layouts.default')

{{ $theme = "page-profile" }}

@section('content')

<script>
	var init_video_id = {{ $video->id }};
</script>

<div class="profile-full-name">
	<span class="text-semibold">{{ $video->title }}</span> 
</div>
	<div class="profile-row">
	<div class="left-col">
		<div class="profile-block">
			<div class="panel video-detail-image">
				{{ '<img src="' . $video->thumbnail . '" class="detail_video_img">' }}
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
						<div ng-app="commentApp" ng-controller="videoCommentController">

							<div class="comment">
								<img src="{{ Auth::user()->photo() }}" alt="" class="comment-avatar">
								<div class="comment-body">
									<form id="leave-comment-form" class="comment-text no-padding no-border" ng-init="commentData.video_id={{ $video->id }}" ng-submit="submitComment()">
										<textarea class="form-control" rows="1" ng-model="commentData.text"></textarea>
										<div class="expanding-input-hidden" style="margin-top: 10px;">
											<label class="checkbox-inline pull-left">
												<input type="checkbox" class="px">													
											</label>
											<button type="submit" class="btn btn-primary pull-right">Leave Message</button>
										</div>
									</form>
								</div> <!-- / .comment-body -->
							</div>

							<hr class="no-panel-padding-h panel-wide">

							<!-- LOADING ICON =============================================== -->
							<!-- show loading icon if the loading variable is set to true -->

							<p class="text-center" ng-show="loading"><span class="fa fa-refresh fa-5x fa-spin"></span></p>

							<div class="comment" ng-hide="loading" ng-repeat="comment in comments">
								<img src="@{{ comment.user.photo }}" alt="" class="comment-avatar">
								<div class="comment-body">
									<div class="comment-text">
										<div class="comment-heading">
											<a href="#" title="">@{{ comment.user.firstname + ' ' + comment.user.lastname  }}</a><span>@{{ comment.created_at | date:'medium' }}</span>
										</div>
										@{{ comment.text }}
									</div>
									<div class="comment-footer">										
										<a href="#" ng-show="showComment(comment, {{ Auth::id() }})" ng-click="deleteComment(comment.id)" class="text-muted"><i class="fa fa-trash-o"></i> Remove</a>
									</div>
								</div> <!-- / .comment-body -->
							</div>			

						</div>						

					</div>

					<!-- <hr class="no-panel-padding-h panel-wide"> -->

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
								{{ $task->user->firstname . ' ' . $tasks_label[$task->type] }}
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

	init.push(function () {
		$('a').tooltip();
	});

	window.PixelAdmin.start(init);

</script>
			
@stop



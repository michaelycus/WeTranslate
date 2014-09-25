@extends('layouts.default')

{{ $theme = "page-profile" }}

@section('content') 
	<div class="profile-full-name">
		<span class="text-semibold">{{ $user->firstname . ' ' . $user->lastname }}</span>'s profile
	</div>
 	<div class="profile-row">
		<div class="left-col">
			<div class="profile-block">
				<div class="panel profile-photo">
					<img src="{{ $user->photo }}" alt="">
				</div><br>				
			</div>
			
			<div class="panel panel-transparent">
				<div class="panel-heading">
					<span class="panel-title">Statistics</span>
				</div>
				<div class="panel-body">
					<div class="list-group">
						<a href="#" class="list-group-item"><strong>{{ $user->translated_videos() }}</strong> Translations</a>
						<a href="#" class="list-group-item"><strong>{{ $user->synchronized_videos() }}</strong> Synchronizations</a>
						<a href="#" class="list-group-item"><strong>{{ $user->proofreaded_videos() }}</strong> Proofreadings</a>
						<a href="#" class="list-group-item"><strong>{{ $user->suggested_videos() }}</strong> Suggestions</a>					
						<a href="#" class="list-group-item"><strong>{{ $user->worked_in_videos() }}</strong> Worked in videos</a>
						<a href="#" class="list-group-item"><strong>{{ $user->score_total() }}</strong> Points</a>					
					</div>
				</div>
			</div>
		</div>

		<div class="right-col">

			<hr class="profile-content-hr no-grid-gutter-h">
			
			<div class="profile-content">

				<ul id="profile-tabs" class="nav nav-tabs">					
					<li>
						<a href="#profile-tabs-activity" data-toggle="tab">Timeline</a>
					</li>						
				</ul>

				<div class="tab-content tab-content-bordered panel-padding">					
					<div class="tab-pane fade in active" id="profile-tabs-activity">
						<div class="timeline centered">
							<div class="tl-header now bg-primary">Now</div>

						<?php
						$tasks_label = unserialize(TASKS_TYPE_LABEL_DASHBOARD);
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
									case TASK_FINISHED_VIDEO: 
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
									@if ($task->video)
									the video <a href="{{ URL::route('videos-details', $task->video_id) }}">{{ $task->video->title }} </a> 
									@else
									an video.
									@endif 
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
</script>
			
@stop

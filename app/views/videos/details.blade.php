@extends('layouts.default')

{{ $theme = "page-profile" }}

@section('content')

<?php	
	preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#",  $video->original_link, $matches);

	$json = json_decode(file_get_contents("http://gdata.youtube.com/feeds/api/videos/$matches[0]?v=2&alt=jsonc"));
?>	

<div class="profile-full-name">
	<span class="text-semibold">{{ $json->data->title }}</span> 
</div>
	<div class="profile-row">
	<div class="left-col">
		<div class="profile-block">
			<div class="panel profile-photo">
				{{ '<img src="' . $json->data->thumbnail->hqDefault . '">' }}
			</div><br>

			<a href="#" class="btn btn-flat btn-labeled btn-success"><span class="btn-label icon fa fa-check-circle"></span>I want to help!</a>			
			
		</div>

		<div class="panel panel-transparent">
			<div class="panel-heading">
				<span class="panel-title">Who is working</span>
			</div>
			<div class="list-group">
				<a href="#" class="list-group-item"><img src="{{ URL::asset('assets/demo/avatars/1.jpg') }}" alt="" class="user-list"> Michael</a>
				<a href="#" class="list-group-item"><img src="{{ URL::asset('assets/demo/avatars/3.jpg') }}" alt="" class="user-list"> Fulana</a>
				<a href="#" class="list-group-item"><img src="{{ URL::asset('assets/demo/avatars/4.jpg') }}" alt="" class="user-list"> Siclano</a>
			</div>
		</div>

		<div class="panel panel-transparent">
			<div class="panel-heading">
				<span class="panel-title">Details</span>
			</div>
			<div class="list-group">
				<a href="#" class="list-group-item"><i class="profile-list-icon fa fa-youtube-play" style="color: #e00022"></i> Original location</a>
				<a href="#" class="list-group-item"><i class="profile-list-icon fa fa-rocket" style="color: #059418"></i> Working location</a>
				<a href="#" class="list-group-item"><i class="profile-list-icon fa fa-clock-o" style="color: #4ab6d5"></i> 00:15:26</a>
				<a href="#" class="list-group-item"><i class="profile-list-icon fa fa-calendar-o" style="color: #1a7ab9"></i> 02/06/2014</a>
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
						<img src="{{ URL::asset('assets/demo/avatars/1.jpg') }}" alt="" class="comment-avatar">
						<div class="comment-body">
							<form action="" id="leave-comment-form" class="comment-text no-padding no-border">
								<textarea class="form-control" rows="1"></textarea>
								<div class="expanding-input-hidden" style="margin-top: 10px;">
									<label class="checkbox-inline pull-left">
										<input type="checkbox" class="px">
										<span class="lbl">Private message</span>
									</label>
									<button class="btn btn-primary pull-right">Leave Message</button>
								</div>
							</form>
						</div> <!-- / .comment-body -->
					</div>

					<hr class="no-panel-padding-h panel-wide">


				</div> <!-- / .tab-pane -->

				<div class="tab-pane fade" id="profile-tabs-activity">
					<div class="timeline">
						<!-- Timeline header -->
						<div class="tl-header now">Now</div>

						<div class="tl-entry">
							<div class="tl-time">
								1h ago
							</div>
							<div class="tl-icon bg-warning"><i class="fa fa-envelope"></i></div>
							<div class="panel tl-body">
								<h4 class="text-warning">Lorem ipsum dolor sit amet</h4>
								Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
							</div> <!-- / .tl-body -->
						</div> <!-- / .tl-entry -->
					
					</div> <!-- / .timeline -->				
				
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



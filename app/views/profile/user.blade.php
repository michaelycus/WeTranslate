@extends('layouts.default')

{{ $theme = "page-profile" }}

@section('content') 
	<div class="profile-full-name">
		<span class="text-semibold">{{ Auth::user()->name }}</span>'s profile
	</div>
 	<div class="profile-row">
		<div class="left-col">
			<div class="profile-block">
				<div class="panel profile-photo">
					<img src="{{ URL::asset('assets/demo/avatars/1.jpg') }}" alt="">
				</div><br>
				<a href="#" class="btn btn-success"><i class="fa fa-check"></i>&nbsp;&nbsp;Following</a>&nbsp;&nbsp;
				<a href="#" class="btn"><i class="fa fa-comment"></i></a>
			</div>
			
			<div class="panel panel-transparent">
				<div class="panel-heading">
					<span class="panel-title">About me</span>
				</div>
				<div class="panel-body">
					Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et <a href="#">dolore magna</a> aliqua.
				</div>
			</div>

			<div class="panel panel-transparent">
				<div class="panel-heading">
					<span class="panel-title">Statistics</span>
				</div>
				<div class="list-group">
					<a href="#" class="list-group-item"><strong>126</strong> Likes</a>
					<a href="#" class="list-group-item"><strong>579</strong> Followers</a>
					<a href="#" class="list-group-item"><strong>100</strong> Following</a>
				</div>
			</div>

		</div>
		<div class="right-col">

			<hr class="profile-content-hr no-grid-gutter-h">
			
			<div class="profile-content">

				<ul id="profile-tabs" class="nav nav-tabs">
					<li class="active">
						<a href="#profile-tabs-board" data-toggle="tab">Board</a>
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

						<div class="comment">
							<img src="{{ URL::asset('assets/demo/avatars/2.jpg') }}" alt="" class="comment-avatar">
							<div class="comment-body">
								<div class="comment-text">
									<div class="comment-heading">
										<a href="#" title="">Robert Jang</a><span>14 hours ago</span>
									</div>
									Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore.
								</div>
								<div class="comment-footer">
									<a href="#"><i class="fa fa-thumbs-o-up"></i></a>&nbsp;&nbsp;
									<a href="#"><i class="fa fa-thumbs-o-down"></i></a>
									&nbsp;&nbsp;·&nbsp;&nbsp;
									<a href="#">Reply</a>
								</div>
							</div> <!-- / .comment-body -->

						</div> 

					</div> <!-- / .tab-pane -->
					<div class="tab-pane fade" id="profile-tabs-activity">
						
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

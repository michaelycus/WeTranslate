@extends('layouts.default')

@section('content')
<div id="content-wrapper">
		<div class="page-header">
			
			<div class="row">
				<!-- Page header, center on small screens -->
				<h1 class="col-xs-12 col-sm-4 text-center text-left-sm"><i class="fa fa-dashboard page-header-icon"></i>&nbsp;&nbsp;Dashboard</h1>

				<div class="col-xs-12 col-sm-8">
					<div class="row">
						<hr class="visible-xs no-grid-gutter-h">
						<!-- "Create project" button, width=auto on desktops -->
						<div class="pull-right col-xs-12 col-sm-auto"><a href="{{ URL::route('videos-suggest') }}" class="btn btn-primary btn-labeled" style="width: 100%;"><span class="btn-label icon fa fa-plus"></span>Suggest Video</a></div>

						<!-- Margin -->
						<div class="visible-xs clearfix form-group-margin"></div>
						
					</div>
				</div>
			</div>
		</div> <!-- / .page-header -->


		<div class="row">
			<div class="col-md-8">
				<div class="stat-panel">

					<div class="stat-cell col-sm-6 padding-sm-hr bordered no-border-r valign-top">
						<h4 class="padding-sm no-padding-t padding-xs-hr"><i class="fa fa-film text-primary"></i>&nbsp;&nbsp;Videos</h4>
						<ul class="list-group no-margin">
							<li class="list-group-item no-border-hr padding-xs-hr no-bg no-border-radius">
								Translation <span class="label label-pa-purple pull-right">{{ $count_videos_trans }}</span>
							</li> <!-- / .list-group-item -->
							<li class="list-group-item no-border-hr padding-xs-hr no-bg">
								Synchronizing <span class="label label-danger pull-right">{{ $count_videos_synch }}</span>
							</li> <!-- / .list-group-item -->
							<li class="list-group-item no-border-hr padding-xs-hr no-bg">
								Proofreading <span class="label label-danger pull-right">{{ $count_videos_proof }}</span>
							</li> <!-- / .list-group-item -->
							<li class="list-group-item no-border-hr no-border-b padding-xs-hr no-bg">
								Completed <span class="label label-success pull-right">{{ $count_videos_finish }}</span>
							</li>
						</ul>
					</div> <!-- /.stat-cell -->

					
					<div class="stat-cell col-sm-6 no-padding bordered valign-middle">
						
						<div class="stat-rows">
							<div class="stat-row" style="height:125px"> 
								<div class="stat-cell bg-warning valign-middle">
									<i class="fa fa-youtube-play bg-icon"></i>
									<span class="text-xlg"><strong>{{ ($count_videos_trans + $count_videos_synch + $count_videos_proof + $count_videos_finish) }}</strong></span><br>
									<span class="text-bg">Total videos</span><br>
									<span class="text-sm">Added to the system</span>
								</div>
							</div>

							<div class="stat-row" style="height:125px"> 
								<div class="stat-cell bg-warning darker valign-middle">
									<i class="fa fa-users bg-icon"></i>
									<span class="text-xlg"><strong>{{ $count_users }}</strong></span><br>
									<span class="text-bg">Users</span><br>
									<span class="text-sm">Working with translations</span>
								</div>
							</div>
						</div>
					</div>						
					
				</div>
			</div>	

			<div class="col-md-4">
				<div class="row">

					<div class="stat-panel">
						<div class="stat-row">
							<!-- Danger background, vertically centered text -->
							<div class="stat-cell bg-success valign-middle">
								<!-- Stat panel bg icon -->
								<i class="fa fa-trophy bg-icon"></i>
								<!-- Extra large text -->
								<span class="text-xlg"><span class="text-lg text-slim"></span><strong>{{ $count_user_score }}</strong></span><br>
								<!-- Big text -->
								<span class="text-bg">Points in translation</span><br>
								<!-- Small text -->
								<span class="text-sm"><a href="#">In {{ $count_user_worked }} different videos</a></span>
							</div> <!-- /.stat-cell -->
						</div> <!-- /.stat-cell -->
						<div class="stat-row">
							<!-- Bordered, without top border, horizontally centered text -->
							<div class="stat-counters bordered no-border-t text-center">
								<!-- Small padding, without horizontal padding -->
								<div class="stat-cell col-xs-4 padding-sm no-padding-hr">
									<!-- Big text -->
									<span class="text-bg"><strong>{{ $count_user_trans }}</strong></span><br>
									<!-- Extra small text -->
									<span class="text-xs text-muted">TRANSLATIONS</span>
								</div>
								<!-- Small padding, without horizontal padding -->
								<div class="stat-cell col-xs-4 padding-sm no-padding-hr">
									<!-- Big text -->
									<span class="text-bg"><strong>{{ $count_user_synch }}</strong></span><br>
									<!-- Extra small text -->
									<span class="text-xs text-muted">FOLLOWERS</span>
								</div>
								<!-- Small padding, without horizontal padding -->
								<div class="stat-cell col-xs-4 padding-sm no-padding-hr">
									<!-- Big text -->
									<span class="text-bg"><strong>{{ $count_user_proof }}</strong></span><br>
									<!-- Extra small text -->
									<span class="text-xs text-muted">PROOFREADINGS</span>
								</div>
							</div> <!-- /.stat-counters -->
						</div>
					</div>
				</div>	
			</div>	
		</div>


				<!-- Page wide horizontal line -->
		<hr class="no-grid-gutter-h grid-gutter-margin-b no-margin-t">

		<div class="row">

			<!-- Javascript -->
			<script>
				init.push(function () {
					$('#dashboard-recent .panel-body > div').slimScroll({ height: 300, alwaysVisible: true, color: '#888',allowPageScroll: true });
				})
			</script>
			<!-- / Javascript -->

			<div class="col-md-4">	
				
				<div class="panel panel-warning" id="dashboard-recent">
					<div class="panel-heading">
						<span class="panel-title"><i class="panel-title-icon fa fa-fire text-danger"></i>Last suggested videos</span>						
					</div> <!-- / .panel-heading -->
					<div class="tab-content">

						<!-- Without padding -->
						<div class="widget-threads panel-body tab-pane no-padding  fade active in">
							<div class="panel-padding no-padding-vr">

								@foreach ($last_videos as $video)
								<?php
									preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#",  $video->original_link, $matches);

									$json = json_decode(file_get_contents("http://gdata.youtube.com/feeds/api/videos/$matches[0]?v=2&alt=jsonc"));
								?>	
								<div class="thread">									
									{{ '<img src="' . $json->data->thumbnail->sqDefault . '"  alt="" class="thread-avatar">' }}
									<div class="thread-body">
										<span class="thread-time">{{ time_elapsed_string($video->created_at) }}</span>
										<a href="{{ URL::route('videos-details', $video->id) }}" class="thread-title">{{ $json->data->title }}</a>
										<div class="thread-info">suggested by <a href="" title="">{{ $video->suggestedBy()['fullname'] }}</a></div>
									</div>
								</div>
								@endforeach

							</div>
						</div> <!-- / .panel-body -->
					</div>
				</div> <!-- / .widget-threads -->
			</div>

			<div class="col-md-4">	
				
				<div class="panel panel-warning darken" id="dashboard-recent">
					<div class="panel-heading">
						<span class="panel-title"><i class="panel-title-icon fa fa-fire text-danger"></i>Last activities</span>						
					</div> <!-- / .panel-heading -->
					<div class="tab-content">

						<!-- Without padding -->
						<div class="widget-threads panel-body tab-pane no-padding  fade active in">
							<div class="panel-padding no-padding-vr">
								<div class="thread">
									<img src="assets/demo/avatars/2.jpg" alt="" class="thread-avatar">
									<div class="thread-body">
										<span class="thread-time">14h</span>
										<a href="#" class="thread-title">Lorem ipsum dolor sit amet</a>
										<div class="thread-info">started by <a href="#" title="">Robert Jang</a> in <a href="#" title="">Forum name</a></div>
									</div> <!-- / .thread-body -->
								</div> <!-- / .thread -->

								<div class="thread">
									<img src="assets/demo/avatars/3.jpg" alt="" class="thread-avatar">
									<div class="thread-body">
										<span class="thread-time">14h</span>
										<a href="#" class="thread-title">Lorem ipsum dolor sit amet</a>
										<div class="thread-info">started by <a href="#" title="">Michelle Bortz</a> in <a href="#" title="">Forum name</a></div>
									</div> <!-- / .thread-body -->
								</div> <!-- / .thread -->

								<div class="thread">
									<img src="assets/demo/avatars/4.jpg" alt="" class="thread-avatar">
									<div class="thread-body">
										<span class="thread-time">14h</span>
										<a href="#" class="thread-title">Lorem ipsum dolor sit amet</a>
										<div class="thread-info">started by <a href="#" title="">Timothy Owens</a> in <a href="#" title="">Forum name</a></div>
									</div> <!-- / .thread-body -->
								</div> <!-- / .thread -->

							</div>
						</div> <!-- / .panel-body -->
					</div>
				</div> <!-- / .widget-threads -->
			</div>


			<div class="col-md-4">	
				
				<div class="panel panel-warning darker" id="dashboard-recent">
					<div class="panel-heading">
						<span class="panel-title"><i class="panel-title-icon fa fa-fire text-danger"></i>Last user logged</span>						
					</div> <!-- / .panel-heading -->
					<div class="tab-content">

						<!-- Without padding -->
						<div class="widget-threads panel-body tab-pane no-padding  fade active in">
							<div class="panel-padding no-padding-vr">
								<div class="thread">
									<img src="assets/demo/avatars/2.jpg" alt="" class="thread-avatar">
									<div class="thread-body">
										<span class="thread-time">14h</span>
										<a href="#" class="thread-title">Lorem ipsum dolor sit amet</a>
										<div class="thread-info">started by <a href="#" title="">Robert Jang</a> in <a href="#" title="">Forum name</a></div>
									</div> <!-- / .thread-body -->
								</div> <!-- / .thread -->

							
							</div>
						</div> <!-- / .panel-body -->
					</div>
				</div> <!-- / .widget-threads -->
			</div>



		</div>



	</div>
</div>	

@stop

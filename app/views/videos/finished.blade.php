@extends('layouts.default')

@section('content')

<div class="page-header">
	<?php $status_label = unserialize (VIDEO_STATUS_LABEL); ?>
	<h1><span class="text-light-gray">Videos / </span>{{ $status_label[$status] }}</h1>
</div>

<script>
	init.push(function () {
		$('#jq-datatables-finished').dataTable();
		$('#jq-datatables-finished_wrapper .table-caption').text('Some header text');
		$('#jq-datatables-finished_wrapper .dataTables_filter input').attr('placeholder', 'Search...');
	});
</script>


<div class="col-sm-12">
	<div class="panel">
		<div class="panel-heading">
			<span class="panel-title"></span>
		</div>
		<div class="panel-body">
			<div class="table-primary">
				<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="jq-datatables-finished">
					<thead>
						<tr>							
							<th></th>
							<th>Title</th>
							<th>Duration</th>
							<th>Date</th>							
							<th>Links</th>
							<th></th>
						</tr>
					</thead>
					<tbody>

				@foreach ($videos as $video)				
					<tr data-video-id="{{ $video->id }}">
						<td class="text-center">{{ '<img src="' . $video->thumbnail . '">' }}</td>
						<td>{{ $video->title }}</td>
						<td>{{ gmdate("H:i:s", $video->duration) }}</td>
						<td>{{ date("d/m/Y", strtotime($video->created_at)) }}</td>
						<td class="text-center">
							<a href="{{ $video->original_link }}" target="_blank"><span class="fa fa-youtube-play text-danger text-lg"></span></a>&nbsp;&nbsp;
							<a href="{{ $video->working_link }}" target="_blank"><span class="fa fa-rocket text-success text-lg"></span></a>&nbsp;&nbsp;
							<a href="{{ URL::route('videos-details', $video->id) }}" target="_blank"><span class="fa  fa-info text-info text-lg"></span></a>
						</td>
						<td>
							<a class="btn btn-sm btn-primary btn-labeled btn-block confirm-return" data-video-id="{{ $video->id }}" data-status="{{ VIDEO_STATUS_PROOFREADING }}">
							<span class="btn-label"><i class="fa fa-arrow-left"></i></span>Return
							</a>
						</td>
					</tr>
				@endforeach 

				</tbody>
			</table>
		</div>
	</div>
</div>

@stop

@section('script')

<script type="text/javascript">
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
			            $("tr[data-video-id='"+video_id+"']").slideUp("slow");
				    });	
				}							
			},
			className: "bootbox-sm"
		});
	});
</script>
@stop
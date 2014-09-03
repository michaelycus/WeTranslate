@extends('layouts.default')

@section('content')

<div class="page-header">
	<h1><span class="text-light-gray">Videos / </span>for Approval</h1>
</div> <!-- / .page-header -->

<div class="row">
	<div class="col-md-12">
		<table class="table table-hover">
			<thead>
				<tr>
					<th>Original URL</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@foreach ($videos as $video)
				<tr data-row-id="{{ $video->id }}">
					<td>{{ $video->original_link }}</td>					
					<td>
						<div class="pull-right">
						<a href="{{ URL::route('videos-verify', $video->id) }}" class="btn btn-flat btn-sm btn-labeled btn-warning "><span class="btn-label icon fa fa-search"></span>Verify</a>&nbsp;&nbsp;
						<a onClick="remove_video({{ $video->id }});" class="btn btn-flat btn-sm btn-labeled btn-danger "><span class="btn-label icon fa fa-trash-o"></span>Remove</a>&nbsp;&nbsp;
						</div>							
					</td>
				</tr>
				@endforeach							
			</tbody>
		</table>
	</div>
</div>

@stop

@section('script')

<script type="text/javascript">
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
			           $("tr[data-row-id='"+video_id+"']").slideUp("slow");
				    });	
				}							
			},
			className: "bootbox-sm"
		});
	}
</script>
			
@stop
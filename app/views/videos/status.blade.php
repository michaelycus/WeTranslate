@extends('layouts.default')

@section('content')
	<!-- Javascript -->
	<script>
		init.push(function () {
			$('#jq-datatables-example').dataTable();
			$('#jq-datatables-example_wrapper .table-caption').text('');
			$('#jq-datatables-example_wrapper .dataTables_filter input').attr('placeholder', 'Search...');
		});
	</script>
	<!-- / Javascript -->

	<div class="panel">
		<div class="panel-heading">
			<span class="panel-title">Videos in translation</span>
		</div>
		<div class="panel-body">
			<div class="table-primary">
				<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="jq-datatables-example">
					<thead>
						<tr>
							<th></th>
							<th>Title</th>
                            <th>Links</th>
                            <th>Duration</th>
                            <th>Start Date</th>
                            <th>Who is working...</th>
                            <th></th>
						</tr>
					</thead>
					<tbody>
						@foreach ($videos as $video)
                        	<tr>
                        		<?php
									preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#",  $video->original_link, $matches);

									$json = json_decode(file_get_contents("http://gdata.youtube.com/feeds/api/videos/$matches[0]?v=2&alt=jsonc"));
                        		?>	

                        		<td>{{ '<img src="' . $json->data->thumbnail->sqDefault . '">' }}</td>
                        		<td>{{ $json->data->title }}</td>
                        		<td>{{ $video->original_link .' - '. $video->working_link }}</td>
                        		<td>{{ $video->duration }}</td>
                        		<td>{{ date("Y/m/d",strtotime($video->created_at)) }}</td>
                        		<td> - </td>
                        		<td>
                        			<button class="btn btn btn-xs btn-labeled btn-warning"><span class="btn-label icon fa fa-edit"></span>Edit</button> 
                        			<button class="btn btn btn-xs btn-labeled btn-danger"><span class="btn-label icon fa fa-trash-o"></span>Delete</button> </td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
@stop

				

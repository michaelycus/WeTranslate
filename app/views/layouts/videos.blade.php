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
			<span class="panel-title">Videos</span>
		</div>
		<div class="panel-body">
			<div class="table-primary">
				<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="jq-datatables-example">
					<thead>
						<tr>
							<th>Title</th>
                            <th>Links</th>
                            <th>Duration</th>
                            <th>Start Date</th>
                            <th>Who is working...</th>
                            <th>Tags</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($videos as $video)
                        	<tr>
                        		<td>{{ $video->title }}</td>
                        		<td>{{ $video->original_link .' - '. $video->working_link }}</td>
                        		<td>{{ $video->duration }}</td>
                        		<td>{{ date("Y/m/d",strtotime($video->created_at)) }}</td>
                        		<td> - </td>
                        		<td> - </td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
@stop

				

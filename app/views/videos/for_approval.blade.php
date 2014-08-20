@extends('layouts.default')

@section('content')

<div class="page-header">
	<h1><span class="text-light-gray">UI Elements / </span>Panels</h1>
</div> <!-- / .page-header -->

<div class="row">
	<div class="col-md-12">
		<table class="table">
			<thead>
				<tr>
					<th>Original</th>
					<th>Working location</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@foreach ($videos as $video)
				<tr>
					<td>{{ $video->original_link }}</td>
					<td>{{ $video->working_link }}</td>					
					<td>
						<a href="{{ URL::route('videos-verify', $video->id ) }}" class="btn btn-flat btn-sm btn-labeled btn-warning"><span class="btn-label icon fa fa-search"></span>Verify</a>						
					</td>
				</tr>
				@endforeach							
			</tbody>
		</table>
	</div>
</div>


@stop
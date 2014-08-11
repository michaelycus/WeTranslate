@extends('layouts.default')

@section('content')

	<div id="content-wrapper">

		<div class="page-header">
			<h1><span class="text-light-gray">Suggest </span>video</h1>
		</div> <!-- / .page-header -->

		<div class="row">
			<div class="col-sm-12">

				<div class="panel">
					<div class="panel-heading">
						<span class="panel-title">Inform the url of the video</span>
					</div>
					<div class="panel-body">
						<form class="form-inline" method="post" action="{{ URL::route('videos-suggest-post') }}">
							<div class="form-group">								
								<input type="text" class="form-control" id="original_link" name="original_link" placeholder="http://...">
								@if ($errors->has('original_link'))
									<p class="help-block">{{ $errors->first('original_link') }}</p>
								@endif
							</div>
							<button type="submit" class="btn btn-primary">Suggest</button>

							{{ Form::token() }}
						</form>
					</div>
				</div>

			</div>
		</div>
	</div>
@stop

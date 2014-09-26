@extends('layouts.default')

@section('content')

	<div id="content-wrapper">

		<div class="page-header">
			<h1><span class="text-light-gray">Suggest </span>video</h1>
		</div>

		<script type="text/javascript">

			var regYoutube = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
			var regVimeo = /^.*(vimeo\.com\/)((channels\/[A-z]+\/)|(groups\/[A-z]+\/videos\/))?([0-9]+)/;

			init.push(function () {		
				$.validator.addMethod(
					"valid_video_url",
					function(value, element) {
						return regYoutube.test(value) || regVimeo.test(value);
					},
					"The url informed is not valid."
				);

				// Setup validation
				$("#jq-validation-form").validate({
					ignore: '.ignore, .select2-input',
					focusInvalid: false,
					rules: {
						'original_link': {
							required: true,
							url: true,
							valid_video_url: true
						}
					}
				});
			});
		</script>

		<div class="row">
			<div class="col-md-6">				
			   <form id="jq-validation-form" class="panel form-horizontal" method="post" action="{{ URL::route('videos-suggest-post') }}">
					<div class="panel-heading">
						<span class="panel-title">Inform the url of the video</span>
					</div>

					<div class="panel-body">
						<div class="row form-group">
							<label class="col-sm-4 control-label">Video url:</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="original_link" name="original_link" placeholder="http://...">
									@if ($errors->has('original_link'))
										<p class="help-block">{{ $errors->first('original_link') }}</p>
									@endif
							</div>
						</div>
					</div>
 
					<div class="panel-footer text-right">
						<button type="submit" class="btn btn-primary">Suggest</button>
					</div>							

					{{ Form::token() }}						
					
				</form>
			</div>

			<div class="col-md-6">
				<div class="panel colourable">
					<div class="panel-heading">
						<span class="panel-title"><i class="panel-title-icon fa fa-warning"></i>Supported services</span>
					</div>
					<div class="panel-body">
						Right now, only videos from <a href="http://www.youtube.com" target="_blank">YouTube</a> and <a href="http://www.vimeo.com" target="_blank">Vimeo</a> are supported.
					</div>
					<div class="panel-footer">
						<a href="http://www.youtube.com" target="_blank"><img src="{{ URL::asset('assets/images/youtube.png') }}" class="logo_videos"></a>
						<a href="http://www.vimeo.com" target="_blank"><img src="{{ URL::asset('assets/images/vimeo.png') }}" class="logo_videos"></div></a>
				</div>
			</div>
		</div>
	</div>
@stop

@section('script')

<script type="text/javascript">
	init.push(function () {
	})
	window.PixelAdmin.start(init);
</script>

@stop

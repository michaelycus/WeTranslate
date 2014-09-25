@extends('layouts.default')

@section('content')

	<div id="content-wrapper">

		<div class="page-header">
			<h1><span class="text-light-gray">Approve </span>video</h1>
		</div> <!-- / .page-header -->

		<div class="row">
			<div class="col-md-6">

				<?php	
					if (strpos($video->original_link,'youtu') !== false) {
						preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#",  $video->original_link, $matches);

						try
						{
							$json = json_decode(file_get_contents("http://gdata.youtube.com/feeds/api/videos/$matches[0]?v=2&alt=jsonc"));
						}
						catch (Exception $e)
						{
							$json = NULL;
						}
					}
					elseif (strpos($video->original_link,'vimeo') !== false) {

						$video_id =  substr(parse_url($video->original_link, PHP_URL_PATH), 1);

						$json_data = file_get_contents("http://vimeo.com/api/v2/video/".$video_id.'.json');
						$json = json_decode($json_data);

					 //    $oembed_endpoint = 'http://vimeo.com/api/oembed';

						// // Create the URLs
						// $json_url = $oembed_endpoint . '.json?url=' . rawurlencode($video->original_link) . '&width=640';		

						// // Curl helper function
						// function curl_get($json_url) {
						//     $curl = curl_init($json_url);
						//     curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
						//     curl_setopt($curl, CURLOPT_TIMEOUT, 30);
						//     curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
						//     $return = curl_exec($curl);
						//     curl_close($curl);
						//     return $return;
						// }
						
						// $json = json_decode(curl_get($json_url));
					}					
				?>					

				@if ($json)	
					<div class="alert alert-success alert-dark">
						<button type="button" class="close" data-dismiss="alert">×</button>
						<strong>Ok!</strong> The original link is working fine.
					</div>

					<?php
		 
					// Create map with request parameters
					$params = array('video_url' => $video->original_link);
					 
					// Build Http query using params
					$query = http_build_query ($params);
					 
					// Create Http context details
					$contextData = array ( 
					                'method' => 'POST',
					                'header' => "Content-Type: application/x-www-form-urlencoded\r\n".
					                            "Content-Length: ".strlen($query)."\r\n",
					                'content'=> $query );
					 
					// Create context resource for our request
					$context = stream_context_create (array ( 'http' => $contextData ));
					 
					// Read page rendered as result of your POST request
					$result =  file_get_contents (
					                  'http://amara.org/pt/videos/create/',  // page url
					                  false,
					                  $context);		 

					preg_match('/og:url.*content="(.*)"/', $result, $matches);

					?>

				@else
					<div class="alert alert-danger alert-dark">
						<button type="button" class="close" data-dismiss="alert">×</button>
						<strong>Oh snap!</strong> There is something wrong with the original link.
					</div>
				@endif
				<form class="panel form-horizontal" method="post" action="{{ URL::route('videos-verify-post', $video->id) }}">
					<div class="panel-body">
						<div class="row form-group">
							<label class="col-sm-4 control-label">Original:</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="original_link" name="original_link" placeholder="http://..." value="{{ $video->original_link }}">
							</div>
							@if ($errors->has('original_link'))
								<p class="help-block">{{ $errors->first('original_link') }}</p>
							@endif
						</div>
						<div class="row form-group">
							<label class="col-sm-4 control-label">Working location:</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="working_link" name="working_link" placeholder="http://..." value="{{{ $matches[1] or '' }}}">
							</div>	
							@if ($errors->has('working_link'))
								<p class="help-block">{{ $errors->first('working_link') }}</p>
							@endif
						</div>
					</div>
					<div class="panel-footer text-right">
						<a href="{{ URL::route('videos-for-approval') }}" type="submit" class="btn btn-default">Cancel</a>
						@if ($json)	
							<button type="submit" class="btn btn-success">Approve</button>						
						@endif						
					</div>

					{{ Form::token() }}
				</form>
			</div>

			<div class="col-md-6">
				<div class="panel colourable">
					<div class="panel-heading">
						<span class="panel-title"><i class="panel-title-icon fa fa-warning"></i>Suggested service</span>
					</div>
					<div class="panel-body">
						We suggested to use <a href="http://www.amara.org" target="_blank">amara.org</a> to translate your videos.
					</div>
					<div class="panel-footer">
						<img src="{{ URL::asset('assets/images/amara.png') }}" width="100px"> 						
					</div>
				</div>
			</div>
		</div>
	</div>
		
@stop

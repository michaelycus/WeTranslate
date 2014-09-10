<?php

class TestController extends BaseController {


	public function getAmara()
	{
		error_reporting(E_ALL);
		 ini_set('display_errors', 1);

		$youtubeUrl = "https://vimeo.com/60342325";
		 
		// Create map with request parameters
		$params = array('video_url' => $youtubeUrl);
		 
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

		echo $matches[1];
	}


	// Route::get('/teste', function()
	// {		
	// 	$ch = curl_init();

	// 	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	// 	    'X-api-username: Michael39',
	// 	    'X-apikey: dcdda23f59b8d2cec74f4f29d18d03c403dbcf4b'
	//     ));

	// 	curl_setopt($ch, CURLOPT_URL, "https://www.universalsubtitles.org/api2/partners/videos/iv6T7WyO5Ujo/");
	// 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	// 	curl_setopt($ch, CURLOPT_HEADER, 0);
	// 	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

	// 	 curl_setopt($ch, CURLOPT_VERBOSE, 1);

	// 	$curl_response = curl_exec($ch);

	// 	$info = curl_getinfo($ch);
 
	// 	echo 'Took ' . $info['total_time'] . ' seconds for url ' . $info['url'];
	// 	echo '<br/><br/><br/>';

	// 	var_dump($info);
	// 	echo '<br/><br/><br/>';

	// 	var_dump($_SERVER);
	// 	echo '<br/><br/><br/>';

	// 	if ($curl_response === FALSE) { 
	// 	    echo "cURL Error: " . curl_error($ch);		 
	// 	}else
	// 	{
	// 		echo 'feito... <br/>';
	// 		echo (format_json($curl_response, true));
	// 	}

	// 	curl_close($ch);
	// });

	// Route::get('/teste2', function()
	// {		
	// 	$ch = curl_init();

	// 	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	// 	    'X-api-username: Michael39',
	// 	    'X-apikey: dcdda23f59b8d2cec74f4f29d18d03c403dbcf4b'
	//     ));

	// 	curl_setopt($ch, CURLOPT_URL, "https://www.universalsubtitles.org/api2/partners/videos/iv6T7WyO5Ujo/");
	// 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);		
	// 	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);		 

	// 	$curl_response = curl_exec($ch);		

	// 	if ($curl_response === FALSE) { 
	// 	    echo "cURL Error: " . curl_error($ch);		 
	// 	}else
	// 	{			
	// 		echo (format_json($curl_response, true));
	// 	}

	// 	curl_setopt($ch, CURLOPT_URL, "https://www.universalsubtitles.org/api2/partners/videos/eRXNBZ2M059G/");

	// 	$curl_response = curl_exec($ch);		

	// 	echo (format_json($curl_response, true));

	// 	curl_close($ch);
	// });

	// Route::get('/createamara', function()
	// {		
	// 	$ch = curl_init();

	// 	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	// 	    'X-api-username: Michael39',
	// 	    'X-apikey: dcdda23f59b8d2cec74f4f29d18d03c403dbcf4b',
	// 	    'Accept: application/json'
	//     ));

	// 	curl_setopt($ch, CURLOPT_URL, "https://www.amara.org/api2/partners/videos/");

	// 	$data = array('video_url' => 'http://vimeo.com/10778141');
	// 	// $data = array('video_url' => 'https://www.youtube.com/watch?v=DK61hj7F-O8', 'primary_audio_language_code ' => 'pt-br');
	// 	curl_setopt($ch, CURLOPT_POST, 1);
	// 	curl_setopt($ch, CURLOPT_POSTFIELDS,$data);

	// 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);		
	// 	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);		 


	// 	$info = curl_getinfo($ch);
	// 	var_dump($info);

	// 	$curl_response = curl_exec($ch);		

	// 	if ($curl_response === FALSE) { 
	// 	    echo "cURL Error: " . curl_error($ch);		 
	// 	}else
	// 	{			
	// 		echo (format_json($curl_response, true));
	// 	}

	// 	curl_close($ch);
	// });


	public function getTeste()
	{
		// //check if its our form
  //       if ( Session::token() !== Input::get( '_token' ) ) {
  //           return Response::json( array(
  //               'msg' => 'Unauthorized attempt to create setting'
  //           ) );
  //       }
 
  //       $setting_name = Input::get( 'setting_name' );
  //       $setting_value = Input::get( 'setting_value' );
 
  //       //.....
  //       //validate data
  //       //and then store it in DB
  //       //.....
 
  //       $response = array(
  //           'status' => 'success',
  //           'msg' => 'Setting created successfully',
  //       );
 
  //       return Response::json( $response );

		// $videos = Video::all();

		// foreach ($videos as $video) {

		// 	preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#",  $video->original_link, $matches);

		// 	$json = json_decode(file_get_contents("http://gdata.youtube.com/feeds/api/videos/$matches[0]?v=2&alt=jsonc"));

  //           $video->title 			= $json->data->title;
		// 	$video->duration 		= $json->data->duration;
		// 	$video->thumbnail 		= $json->data->thumbnail->sqDefault;				

		// 	$video->save();

		// 	echo $video->title. '<br>';
		// }

		// $vimeo = 'https://vimeo.com/29474908';

		// echo (int) substr(parse_url($vimeo, PHP_URL_PATH), 1);

		// $s = 'https://www.youtube.com/watch?v=hqmzsf-AodM';

		// if (strpos($s,'vimeo') !== false) {
		//     echo 'vimeo';
		// }
		// elseif (strpos($s,'youtu') !== false) {
		// 	echo 'youtube';
		// }

		// $imgid = 6271487;

		// $hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/$imgid.php"));

		// echo $hash[0]['thumbnail_medium']; 


  //       $value = 'https://vimeo.com/100415419d';

		// $video_id = (int) substr(parse_url($value, PHP_URL_PATH), 1);

		// try
		// {
		// 	$hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/$video_id.php"));

		// 	if ($hash)
		// 	{
		// 		echo $hash[0]['thumbnail_medium']; ;
		// 	}
		// 	else
		// 	{
		// 		echo 'erro';
		// 	}
		// }
		// catch (\Exception $e)
		// {
		// 	echo 'errou';
		// }	


		$oembed_endpoint = 'http://vimeo.com/api/oembed';

		// Grab the video url from the url, or use default
		$video_url = 'http://vimeo.com/7100569';

		// Create the URLs
		$json_url = $oembed_endpoint . '.json?url=' . rawurlencode($video_url) . '&width=640';		

		// Curl helper function
		function curl_get($json_url) {
		    $curl = curl_init($json_url);
		    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
		    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		    $return = curl_exec($curl);
		    curl_close($curl);
		    return $return;
		}

		// Load in the oEmbed XML
		$oembed = json_decode(curl_get($json_url));

		var_dump($oembed);

		



		// return 'teste fudido';
	}

}
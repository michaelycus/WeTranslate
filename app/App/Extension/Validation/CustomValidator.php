<?php namespace App\Extension\Validation;
 
class CustomValidator extends \Illuminate\Validation\Validator {

	public function validateVideoUrl($attribute, $value, $parameters)
	{
		if (strpos($value,'youtu') !== false) {
			return $this->checkYouTube($value);
		}
		elseif (strpos($value,'vimeo') !== false) {
		    return $this->checkVimeo($value);
		}

		return false;			
	}

	private function checkYouTube($value)
	{
		preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#",  $value, $matches);

		if ($matches)
		{
			try
			{
				$json = json_decode(file_get_contents("http://gdata.youtube.com/feeds/api/videos/$matches[0]?v=2&alt=jsonc"));
				return true;
			}
			catch (\Exception $e)
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}

	private function checkVimeo($value)
	{
		// $oembed_endpoint = 'http://vimeo.com/api/oembed';

		// // Create the URLs
		// $json_url = $oembed_endpoint . '.json?url=' . rawurlencode($value) . '&width=640';		

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

		// // Load in the oEmbed XML
		// $oembed = json_decode(curl_get($json_url));

		// if ($oembed)
		// {
		// 	return true;
		// }
		// else
		// {
		// 	return false;
		// }

		$video_id = (int) substr(parse_url($value, PHP_URL_PATH), 1);

		try
		{
			$hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/$video_id.php"));

			if ($hash)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		catch (\Exception $e)
		{
			return false;
		}	
	}
}

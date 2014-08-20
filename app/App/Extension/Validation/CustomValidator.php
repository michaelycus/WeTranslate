<?php namespace App\Extension\Validation;
 
class CustomValidator extends \Illuminate\Validation\Validator {
 
  // public function validateHex($attribute, $value, $parameters)
  // {
  //   // if(preg_match("/^#?([a-f0-9]{6}|[a-f0-9]{3})$/", $value))
  //   // {
  //   //   return true;
  //   // }

  //   if ($value == "teste")
  //   {
  //   	return true;
  //   }
   
  //   return false;
  // }
	public function validateVideoUrl($attribute, $value, $parameters)
	{
		preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#",  $value, $matches);

		try
		{
			json_decode(file_get_contents("http://gdata.youtube.com/feeds/api/videos/$matches[0]?v=2&alt=jsonc"));

			return true;
		}
		catch (Exception $e)
		{
			return false;
		}
	}
}
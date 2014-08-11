<?php

class VideoController extends BaseController {

	public function getTranslating()
	{
		$videos = Video::where('status', '=', VIDEO_STATUS_TRANSLATING)->get();

		 return View::make('videos.status', array('videos' => $videos));		
	}

	public function getSynchronizing()
	{
		$videos = Video::where('status', '=', VIDEO_STATUS_SYNCHRONIZING)->get();

		return View::make('videos.status', array('videos' => $videos));
	}

	public function getProofreading()
	{
		$videos = Video::where('status', '=', VIDEO_STATUS_PROOFREADING)->get();

		return View::make('videos.status', array('videos' => $videos));
	}

	public function getFinished()
	{
		$videos = Video::where('status', '=', VIDEO_STATUS_FINISHED)->get();

		return View::make('videos.status', array('videos' => $videos));
	}

	public function getSuggest()
	{
		return View::make('videos.suggest');
	}

	public function postSuggest()
	{
		$validator = Validator::make(Input::all(), 
			array(
				'original_link' 	=> 'required|url'
			)
		);

		if ($validator->fails()) {
			return Redirect::route('videos-suggest')
					->withErrors($validator)
					->withInput();
		} else {
			$original_link    = Input::get('original_link');

			$video = Video::create(array(
				'original_link'	=> $original_link,
				'status'		=> VIDEO_NEEDS_APPROVAL
			));

			if ($video){
				// Mail::send('emails.auth.activate', array('link' => URL::route('account-activate', $code) , 'name' => $name), function($message) use ($user){
 			// 		$message->to($user->email, $user->name)->subject('Activate your account');
				// });

				return Redirect::route('videos-suggest')
						->with('success', 'Your suggestion was registered! Now it needs to be approved by the team.');
			}
		}
	}
}
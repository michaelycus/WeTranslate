<?php

class VideoController extends BaseController {

	public function getTranslating()
	{
		$videos = Video::where('status', '=', VIDEO_STATUS_TRANSLATING)->get();

		foreach ($videos as $video) {
			$video->task = Task::whereRaw('video_id = '.$video->id.' and type = '. TASK_SUGGESTED_VIDEO)->first();
		}

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

	public function getForApproval()
	{
		$videos = Video::where('status', '=', VIDEO_FOR_APPROVAL)->get();

		return View::make('videos.for_approval', array('videos' => $videos));
	}	

	public function getVerify($id)
	{
		$video = Video::find($id);

		return View::make('videos.verify', array('video' => $video));
	}

	public function postVerify($id)
	{
		$validator = Validator::make(Input::all(), 
			array(
				'original_link' 	=> 'required|url|Video_url',
				'working_link'  	=> 'required|url'
			)
		);

		if ($validator->fails()) {
			return Redirect::route('videos-verify', $id)
					->withErrors($validator)
					->withInput();
		} else {
			$video = Video::find($id);

            $video->update(array(
				'original_link'	=> Input::get('original_link'),
				'working_link'	=> Input::get('working_link'),
				'status'		=> VIDEO_STATUS_TRANSLATING
			));

			// $original_link   = Input::get('original_link');
			// $working_link    = Input::get('working_link');

			// $video = Video::update(array(
			// 	'original_link'	=> $original_link,
			// 	'status'		=> VIDEO_FOR_APPROVAL
			// ));

			if ($video){
				// Mail::send('emails.auth.activate', array('link' => URL::route('account-activate', $code) , 'name' => $name), function($message) use ($user){
 			// 		$message->to($user->email, $user->name)->subject('Activate your account');
				// });

				Task::create(array(
					'type' => TASK_APPROVED_VIDEO,
					'user_id' => Auth::id(),
					'video_id' => $video->id
				));

				return Redirect::route('videos-for-approval')
						->with('success', 'The video is now opened for translations!');
			}
		}
	}

	public function getSuggest()
	{
		return View::make('videos.suggest');
	}

	public function postSuggest()
	{
		$validator = Validator::make(Input::all(), 
			array(
				'original_link' 	=> 'required|url|Video_url'
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
				'status'		=> VIDEO_FOR_APPROVAL
			));

			if ($video){
				// Mail::send('emails.auth.activate', array('link' => URL::route('account-activate', $code) , 'name' => $name), function($message) use ($user){
 			// 		$message->to($user->email, $user->name)->subject('Activate your account');
				// });

				Task::create(array(
					'type' => TASK_SUGGESTED_VIDEO,
					'user_id' => Auth::id(),
					'video_id' => $video->id
					));

				return Redirect::route('videos-suggest')
						->with('success', 'Your suggestion was registered! Now it needs to be approved by the team.');
			}
		}
	}

	public function getDetails($id)
	{
		$video = Video::find($id);

		return View::make('videos.details', array('video' => $video));
	}
}
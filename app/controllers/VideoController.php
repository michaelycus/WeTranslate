<?php

class VideoController extends BaseController {

	public function getTranslating()
	{
		$videos = Video::where('status', '=', VIDEO_STATUS_TRANSLATING)->get();

		foreach ($videos as $video) {
			//$video->task = Task::whereRaw('video_id = '.$video->id.' and type = '. TASK_SUGGESTED_VIDEO)->first();
			$video->tasks = Task::where('video_id', '=', $video->id)->orderBy('user_id')->get();


		}

		 return View::make('videos.status', array('videos' => $videos,
		 									      'status' => VIDEO_STATUS_TRANSLATING));
	}

	public function getSynchronizing()
	{
		$videos = Video::where('status', '=', VIDEO_STATUS_SYNCHRONIZING)->get();

		return View::make('videos.status', array('videos' => $videos,
		 									      'status' => VIDEO_STATUS_SYNCHRONIZING));
	}

	public function getProofreading()
	{
		$videos = Video::where('status', '=', VIDEO_STATUS_PROOFREADING)->get();

		return View::make('videos.status', array('videos' => $videos,
		 									      'status' => VIDEO_STATUS_PROOFREADING));
	}

	public function getFinished()
	{
		$videos = Video::where('status', '=', VIDEO_STATUS_FINISHED)->get();

		return View::make('videos.status', array('videos' => $videos,
		 									      'status' => VIDEO_STATUS_FINISHED));
	}

	public function getForApproval()
	{
		$videos = Video::where('status', '=', VIDEO_FOR_APPROVAL)->get();

		return View::make('videos.for_approval', array('videos' => $videos,
		 									      'status' => VIDEO_FOR_APPROVAL));
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

	public function getTasks($video_id, $status)
	{
		if (Request::ajax())
		{
		    $tasks = Task::where('video_id', '=', $video_id)->orderBy('id')->get();

			$icons = unserialize (IMG_VIDEO_STATUS);

			$currentUser = Auth::id();

			$user_task = array();
			foreach ($tasks as $task) {
				$user_task[$task->user_id][] = $task->type;
			}

			$text = '<p>';

			$is_helping = FALSE;

			foreach ($user_task as $user_id => $type) {
				$user = User::find($user_id);

				$text .= '<img src="'. $user->photo.'" alt="" class="user-list">';

				foreach ($type as $t) {
					if ($t != TASK_APPROVED_VIDEO) // Don't need to present approved icon
						$text .= ' <i class="'. $icons[$t] . ' text-primary"></i> ';

					if ($currentUser == $user_id && $t == $status) // current is participating of the task
						$is_helping = TRUE;
				}
			}

			$text .= '</p>';

			if ($is_helping)
			{
				$text .= '<button class="btn btn-flat btn-sm btn-labeled btn-danger" onclick="setStopHelp('.$video_id.','.$status.')"><span class="btn-label icon fa fa-times-circle"></span>Stop helping!</button>';
			}
			else
			{
				$text .= '<button class="btn btn-flat btn-sm btn-labeled btn-success" onclick="setHelp('.$video_id.','.$status.')"><span class="btn-label icon fa fa-check-circle"></span>I want to help!</button>';	
			}

			return $text;
		}		
	}

	public function getHelp($video_id, $status)
	{
		if (Request::ajax())
		{
			Task::create(array(
				'type' => $status,
				'user_id' => Auth::id(),
				'video_id' => $video_id
			));
		}
	}

	public function getStopHelp($video_id, $status)
	{
		if (Request::ajax())
		{
			$task = Task::whereRaw('video_id = '.$video_id.' and type = '. $status . ' and user_id = '. Auth::id() )->first();			

			$task->delete();
		}
	}

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

		return 'teste fudido';
	}
}
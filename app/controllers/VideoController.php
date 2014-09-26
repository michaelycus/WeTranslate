<?php

class VideoController extends BaseController {

	public function getTranslating()
	{
		$videos = Video::where('status', '=', VIDEO_STATUS_TRANSLATING)->get();

		foreach ($videos as $video) {
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

		return View::make('videos.finished', array('videos' => $videos,
		 									       'status' => VIDEO_STATUS_FINISHED));
	}

	public function getDetails($id)
	{
		$video = Video::find($id);
		$tasks = Task::where('video_id', '=', $id)->orderBy('id', 'desc')->get();

		return View::make('videos.details', array('video' => $video,
												  'tasks' => $tasks));
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

			$text = '<div class="text-center">';

			$is_helping = FALSE;

			foreach ($user_task as $user_id => $type) {
				$user = User::find($user_id);

				$text .= '<a href="' . URL::route('users-profile', $user->id) .'" target="_blank" data-toggle="tooltip" data-placement="top" data-original-title="  '. $user->firstname . ' ' . $user->lastname . '"><img src="'. $user->photo().'" alt="" class="user-list"></a>';
				
				foreach ($type as $t) {
					if ($t < TASK_REJECTED_VIDEO) // Don't need to present approved icon
						$text .= ' <i class="menu-icon fa '. $icons[$t] . ' text-primary"></i> ';

					if ($currentUser == $user_id && $t == $status) // current is participating of the task
						$is_helping = TRUE;
				}
			}

			$text .= '</div><br/>';

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

	public function getDetailTasks($video_id, $status)
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

			$is_helping = FALSE;

			$text = '<div class="list-group">';

			foreach ($user_task as $user_id => $type) {
				$user = User::find($user_id);

				$text .= '<a href="#" class="list-group-item">';

				$text .= '<a href=" ' . URL::route('users-profile', $user->id) .'" target="_blank" data-toggle="tooltip" data-placement="top" data-original-title="  '. $user->firstname . ' ' . $user->lastname . '"><img src="'. $user->photo().'" alt="" class="user-list"></a> ';				
				$text .= $user->name;

				$text .= ' [ ';
				foreach ($type as $t) {
					if ($t < TASK_REJECTED_VIDEO) // Don't need to present approved icon
						$text .= ' <i class="menu-icon fa '. $icons[$t] . ' text-primary"></i> ';

					if ($currentUser == $user_id && $t == $status) // current is participating of the task
						$is_helping = TRUE;
				}

				$text .= ' ]</a>';
			}

			$text .= '</div>';

			if ($status < VIDEO_STATUS_FINISHED)
			{
				if ($is_helping)
				{
					$text .= '<div class="text-center"><button class="btn btn-flat btn-sm btn-labeled btn-danger" onclick="setStopHelp('.$video_id.','.$status.')"><span class="btn-label icon fa fa-times-circle"></span>Stop helping!</button></div>';
				}
				else
				{
					$text .= '<div class="text-center"><button class="btn btn-flat btn-sm btn-labeled btn-success" onclick="setHelp('.$video_id.','.$status.')"><span class="btn-label icon fa fa-check-circle"></span>I want to help!</button></div>';	
				}
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

	public function getMoveTo($video_id, $status)
	{
		$video = Video::find($video_id);
		$video->status = $status;
		$video->save();

		switch ($status) {
			case VIDEO_STATUS_SYNCHRONIZING:
				$status = TASK_ADVANCE_TO_SYNC; break;
			case VIDEO_STATUS_PROOFREADING:
				$status = TASK_ADVANCE_TO_PROOF; break;
			case VIDEO_STATUS_FINISHED:
				$this->addScore($video_id);
				$status = TASK_FINISHED_VIDEO; break;
		}

		Task::create(array(
			'type' => $status,
			'user_id' => Auth::id(),
			'video_id' => $video_id
		));
	}

	public function getReturnTo($video_id, $status)
	{
		$video = Video::find($video_id);
		$video->status = $status;
		$video->save();

		switch ($status) {
			case TASK_IS_TRANSLATING:
				$status = TASK_BACK_TO_TRANS; break;
			case TASK_IS_SYNCHRONIZING:
				$status = TASK_BACK_TO_SYNC; break;
			case TASK_IS_PROOFREADING:
				$status = TASK_BACK_TO_PROOF; break;
		}

		Task::create(array(
			'type' => $status,
			'user_id' => Auth::id(),
			'video_id' => $video_id
		));
	}

	public function getRemove($video_id)
	{
		if (Request::ajax())
		{
			$video = Video::find($video_id);

			$video->delete();
		}
	}

	public function postSuggestion()
	{
		if (Request::ajax())
		{
			$video_url = Input::get('original_link');

			$video_title     = '';
			$video_duration  = '';
			$video_thumbnail = '';

			if (strpos($video_url,'youtu') !== false) {
				preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#",  $video_url, $matches);

				try{
					$json = json_decode(file_get_contents("http://gdata.youtube.com/feeds/api/videos/$matches[0]?v=2&alt=jsonc"));

					$video_title 			= $json->data->title;
					$video_duration 		= $json->data->duration;
					$video_thumbnail 		= $json->data->thumbnail->sqDefault;

				} catch (Exception $e){
					$json = NULL;
				}
			}
			elseif (strpos($video_url,'vimeo') !== false) {
				$video_id =  substr(parse_url($video_url, PHP_URL_PATH), 1);

				$json_data = file_get_contents("http://vimeo.com/api/v2/video/".$video_id.'.json');
				$json = json_decode($json_data);
						
				$video_title			= $json[0]->title;
				$video_duration 		= $json[0]->duration;
				$video_thumbnail		= $json[0]->thumbnail_large;
			}

			if ($json)
			{
				// Create map with request parameters
				$params = array('video_url' => $video_url);
				 
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
				
				$video = Video::create(array(
					'title' 		=> $video_title,
					'duration' 		=> $video_duration,
					'thumbnail' 	=> $video_thumbnail,
					'original_link' => $video_url,
					'working_link' 	=> $matches[1],
					'status' 		=> VIDEO_STATUS_TRANSLATING
				));

				Task::create(array(
					'type' => TASK_SUGGESTED_VIDEO,
					'user_id' => Auth::id(),
					'video_id' => $video->id
				));

			  Session::flash('success', 'Your suggestion is now avaliable for translation!');
			  	
			}
		}
		Session::flash('fail', 'Oh span! Something went wrong. Check your link and try again later!');
	}

	private function addScore($video_id)
	{
		// get tasks of the video
		$tasks = Task::where('video_id', '=', $video_id)->orderBy('id')->get();

		$user_task = array();
		foreach ($tasks as $task) {
			$user_task[$task->user_id][] = $task->type;
		}

		// count number of users in each task
		$translating_task 	= 0;
		$synchronizing_task = 0;
		$proofreading_task 	= 0;
		foreach ($user_task as $user_id => $type) {			
			foreach ($type as $t) {				
				if ($t == TASK_IS_TRANSLATING)
					$translating_task++;
				elseif ($t == TASK_IS_SYNCHRONIZING)
					$synchronizing_task++;
				elseif ($t == TASK_IS_PROOFREADING)
					$proofreading_task++;
			}
		}

		// get video info
		$video = Video::find($video_id);

		$duration_points = $video->duration / 60;

		// setting score to the user
		foreach ($user_task as $user_id => $type) {
			$user = User::find($user_id);

			$translated_videos 		= $user->translated_videos();
			$synchronized_videos 	= $user->synchronized_videos();
			$proofreaded_videos 	= $user->proofreaded_videos();
			$suggested_videos 		= $user->suggested_videos();
			$opened_videos 			= $user->opened_videos();
			$worked_in_videos 		= $user->worked_in_videos();
			$score_total 			= $user->score_total();
			
			foreach ($type as $t) {				
				if ($t == TASK_SUGGESTED_VIDEO)
				{					
					$suggested_videos++;
					$opened_videos--;
				}	
				elseif ($t == TASK_IS_TRANSLATING)
				{
					$score_total += round($duration_points * SCORE_TRANSLATED / $translating_task);
					$translated_videos++;
				}					
				elseif ($t == TASK_IS_SYNCHRONIZING)
				{
					$score_total += round($duration_points * SCORE_SYNCHRONIZED / $synchronizing_task);
					$synchronized_videos++;
				}					
				elseif ($t == TASK_IS_PROOFREADING)
				{
					$score_total += round($duration_points * SCORE_PROOFREADED / $proofreading_task);
					$proofreaded_videos++;
				}				
			}

			$worked_in_videos++;

			$user->score = $translated_videos .','. $synchronized_videos .','. $proofreaded_videos .','. 
			   			   $suggested_videos .','. $opened_videos .','. $worked_in_videos .','. $score_total;
			
			$user->save();
		}
	}

	// public function getForApproval()
	// {
	// 	$videos = Video::where('status', '=', VIDEO_FOR_APPROVAL)->get();

	// 	return View::make('videos.for_approval', array('videos' => $videos,
	// 	 									           'status' => VIDEO_FOR_APPROVAL));
	// }	
	
	// public function postForApproval()
	// {
	// }

	// public function getVerify($id)
	// {
	// 	$video = Video::find($id);

	// 	return View::make('videos.verify', array('video' => $video));
	// }

	// public function postVerify($id)
	// {
	// 	$validator = Validator::make(Input::all(), 
	// 		array(
	// 			'original_link' 	=> 'required|url|Video_url',
	// 			'working_link'  	=> 'required|url'
	// 		)
	// 	);

	// 	if ($validator->fails()) {
	// 		return Redirect::route('videos-verify', $id)
	// 				->withErrors($validator)
	// 				->withInput();
	// 	} else {
	// 		$video = Video::find($id);

	// 		if (strpos($video->original_link,'youtu') !== false) {
	// 			preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#",  Input::get('original_link'), $matches);

	// 			$json = json_decode(file_get_contents("http://gdata.youtube.com/feeds/api/videos/$matches[0]?v=2&alt=jsonc"));

	// 			$video->title 			= $json->data->title;
	// 			$video->duration 		= $json->data->duration;
	// 			$video->thumbnail 		= $json->data->thumbnail->sqDefault;
	// 		}
	// 		elseif (strpos($video->original_link,'vimeo') !== false) {	


	// 			$video_id = substr(parse_url($video->original_link, PHP_URL_PATH), 1);
 //            	$hash = json_decode(file_get_contents("http://vimeo.com/api/v2/video/{$video_id}.json"));

	// 			$video->title 			= $hash[0]->title;
	// 			$video->duration 		= $hash[0]->duration;
	// 			$video->thumbnail 		= $hash[0]->thumbnail_large;
	// 		}

	// 		$video->original_link 	= Input::get('original_link');
	// 		$video->working_link 	= Input::get('working_link');
	// 		$video->status 		= VIDEO_STATUS_TRANSLATING;			

	// 		$video->save();

	// 		if ($video){
	// 			Task::create(array(
	// 				'type' => TASK_APPROVED_VIDEO,
	// 				'user_id' => Auth::id(),
	// 				'video_id' => $video->id
	// 			));

	// 			return Redirect::route('videos-for-approval')
	// 					->with('success', 'The video is now opened for translations!');
	// 		}
	// 	}
	// }

	// public function getSuggest()
	// {
	// 	return View::make('videos.suggest');
	// }

	// public function postSuggest()
	// {
	// 	$validator = Validator::make(Input::all(), 
	// 		array(
	// 			'original_link' 	=> 'required|url|Video_url'
	// 		)
	// 	);

	// 	if ($validator->fails()) {
	// 		return Redirect::route('videos-suggest')
	// 				->withErrors($validator)
	// 				->withInput();
	// 	} else {
	// 		$original_link    = Input::get('original_link');

	// 		$video = Video::create(array(
	// 			'original_link'	=> $original_link,
	// 			'status'		=> VIDEO_FOR_APPROVAL
	// 		));

	// 		if ($video){
	// 			// Mail::send('emails.auth.activate', array('link' => URL::route('account-activate', $code) , 'name' => $name), function($message) use ($user){
 // 			// 		$message->to($user->email, $user->name)->subject('Activate your account');
	// 			// });

	// 			Task::create(array(
	// 				'type' => TASK_SUGGESTED_VIDEO,
	// 				'user_id' => Auth::id(),
	// 				'video_id' => $video->id
	// 			));

	// 			return Redirect::route('videos-suggest')
	// 					->with('success', 'Your suggestion was registered! Now it needs to be approved by the team.');
	// 		}
	// 	}
	// }	
}
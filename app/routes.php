<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', array('as' => 'home', 'uses' => 'HomeController@getIndex'));//->before('auth ');

// Route::get('translating', array('as' => 'translating', 'uses' => 'HomeController@getTranslating'));

// Route::get('/login', array('as' => 'login', 'uses' => 'AuthController@getLogin'))->before('guest');
// Route::post('login', array('uses' => 'AuthController@postLogin'))->before('csrf');

Route::get('/user/{id}', array(
	'as' => 'profile-user',
	'uses' => 'ProfileController@user'
));

/*
| Authenticated group
*/
Route::group(array('before' => 'auth'), function(){

	/*
	| CSRF protection group
	*/
	Route::group(array('before' => 'csrf'), function(){
		/*
		| Change password (POST)
		*/
		Route::post('/account/change-password', array(
			'as' => 'account-change-password-post',
			'uses' => 'AccountController@postChangePassword'
		));
	});

	/*
	| Change password (GET)
	*/
	Route::get('/account/change-password', array(
		'as' => 'account-change-password',
		'uses' => 'AccountController@getChangePassword'
	));

	/*
	| Sign out (GET)
	*/
	Route::get('/account/sign-out', array(
		'as' => 'account-sign-out',
		'uses' => 'AccountController@getSignOut'
	));

	/*
	| USERS (GET)
	*/
	Route::get('/users/manage', array(
		'as' => 'users-manage',
		'uses' => 'UserController@getUsers'
	));

	/*
	| VIDEOS
	*/
	Route::get('/videos/translating', array(
		'as' => 'videos-translating',
		'uses' => 'VideoController@getTranslating'
	));


	Route::get('/videos/teste', array(
		'as' => 'videos-teste',
		'uses' => 'VideoController@getTeste'
	));



	Route::get('/videos/tasks/{video_id}/{status}', array(
		'as' => 'videos-tasks',
		'uses' => 'VideoController@getTasks'
	));

	Route::get('/videos/detail-tasks/{video_id}/{status}', array(
		'as' => 'videos-detail-tasks',
		'uses' => 'VideoController@getDetailTasks'
	));

	Route::get('/videos/help/{id}/{status}', array(
		'as' => 'videos-help',
		'uses' => 'VideoController@getHelp'
	));

	Route::get('/videos/stophelp/{id}/{status}', array(
		'as' => 'videos-stop-help',
		'uses' => 'VideoController@getStopHelp'
	));

	Route::get('/videos/remove/{id}', array(
		'as' => 'videos-remove',
		'uses' => 'VideoController@getRemove'
	));




	Route::get('/videos/synchronizing', array(
		'as' => 'videos-synchronizing',
		'uses' => 'VideoController@getSynchronizing'
	));

	Route::get('/videos/proofreading', array(
		'as' => 'videos-proofreading',
		'uses' => 'VideoController@getProofreading'
	));

	Route::get('/videos/finished', array(
		'as' => 'videos-finished',
		'uses' => 'VideoController@getFinished'
	));

	Route::get('/videos/for-approval', array(
		'as' => 'videos-for-approval',
		'uses' => 'VideoController@getForApproval'
	));

	Route::post('/videos/for-approval', array(
		'as' => 'videos-for-approval-post',
		'uses' => 'VideoController@postForApproval'
	));

	Route::get('/videos/verify/{id}', array(
		'as' => 'videos-verify',
		'uses' => 'VideoController@getVerify'
	));

	Route::post('/videos/verify/{id}', array(
		'as' => 'videos-verify-post',
		'uses' => 'VideoController@postVerify'
	));

	Route::get('/videos/suggest', array(
		'as' => 'videos-suggest',
		'uses' => 'VideoController@getSuggest'
	));

	Route::post('/videos/suggest', array(
		'as' => 'videos-suggest-post',
		'uses' => 'VideoController@postSuggest'
	));

	Route::get('/videos/details/{id}', array(
		'as' => 'videos-details',
		'uses' => 'VideoController@getDetails'
	));

	Route::get('/videos/move-to/{id}/{status}', array(
		'as' => 'videos-move-to',
		'uses' => 'VideoController@getMoveTo'
	));

	Route::get('/videos/return-to/{id}/{status}', array(
		'as' => 'videos-return-to',
		'uses' => 'VideoController@getReturnTo'
	));



	/*
	| USERS
	*/
	Route::get('/users/{id}', array(
		'as' => 'user-profile',
		'uses' => 'UserController@getUser'
	));

	/*
	| TESTE
	*/
	Route::get('/teste', function()
	{		
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		    'X-api-username: Michael39',
		    'X-apikey: dcdda23f59b8d2cec74f4f29d18d03c403dbcf4b'
	    ));

		curl_setopt($ch, CURLOPT_URL, "https://www.universalsubtitles.org/api2/partners/videos/iv6T7WyO5Ujo/");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

		 curl_setopt($ch, CURLOPT_VERBOSE, 1);

		$curl_response = curl_exec($ch);

		$info = curl_getinfo($ch);
 
		echo 'Took ' . $info['total_time'] . ' seconds for url ' . $info['url'];
		echo '<br/><br/><br/>';

		var_dump($info);
		echo '<br/><br/><br/>';

		var_dump($_SERVER);
		echo '<br/><br/><br/>';

		if ($curl_response === FALSE) { 
		    echo "cURL Error: " . curl_error($ch);		 
		}else
		{
			echo 'feito... <br/>';
			echo (format_json($curl_response, true));
		}

		curl_close($ch);
	});

	Route::get('/teste2', function()
	{		
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		    'X-api-username: Michael39',
		    'X-apikey: dcdda23f59b8d2cec74f4f29d18d03c403dbcf4b'
	    ));

		curl_setopt($ch, CURLOPT_URL, "https://www.universalsubtitles.org/api2/partners/videos/iv6T7WyO5Ujo/");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);		
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);		 

		$curl_response = curl_exec($ch);		

		if ($curl_response === FALSE) { 
		    echo "cURL Error: " . curl_error($ch);		 
		}else
		{			
			echo (format_json($curl_response, true));
		}

		curl_setopt($ch, CURLOPT_URL, "https://www.universalsubtitles.org/api2/partners/videos/eRXNBZ2M059G/");

		$curl_response = curl_exec($ch);		

		echo (format_json($curl_response, true));

		curl_close($ch);
	});

	Route::get('/createamara', function()
	{		
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		    'X-api-username: Michael39',
		    'X-apikey: dcdda23f59b8d2cec74f4f29d18d03c403dbcf4b',
		    'Accept: application/json'
	    ));

		curl_setopt($ch, CURLOPT_URL, "https://www.amara.org/api2/partners/videos/");

		$data = array('video_url' => 'http://vimeo.com/10778141');
		// $data = array('video_url' => 'https://www.youtube.com/watch?v=DK61hj7F-O8', 'primary_audio_language_code ' => 'pt-br');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$data);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);		
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);		 


		$info = curl_getinfo($ch);
		var_dump($info);

		$curl_response = curl_exec($ch);		

		if ($curl_response === FALSE) { 
		    echo "cURL Error: " . curl_error($ch);		 
		}else
		{			
			echo (format_json($curl_response, true));
		}

		curl_close($ch);
	});

});




/*
| Unauthenticated group
*/
Route::group(array('before' => 'guest'), function(){

	/*
	| CSRF protection group
	*/	
	Route::group(array('before' => 'csrf'), function(){		
		/*
		| Create account (POST)
		*/	
		Route::post('/account/create', array(
			'as' => 'account-create-post',
			'uses' => 'HomeController@postCreate'
		));	

		/*
		| Sign in (POST)
		*/	
		Route::post('/account/sign-in', array(
			'as' => 'account-sign-in-post',
			'uses' => 'HomeController@postSignIn'
		));
	});

	/*
	| Sign in (GET)
	*/	
	Route::get('/account/sign-in', array(
		'as' => 'account-sign-in',
		'uses' => 'HomeController@getSignIn'
	));	

	/*
	| Create account (GET)
	*/	
	Route::get('/signup', array(
		'as' => 'signup',
		'uses' => 'HomeController@signup'		
	));	

	Route::get('/account/activate/{code}', array(
		'as' => 'account-activate',
		'uses' => 'HomeController@getActivate'
	));		

	Route::get('login/fb', array('as' => 'login-fb', function() {
	    $facebook = new Facebook(Config::get('facebook'));
	    $params = array(
	        'redirect_uri' => url('/login/fb/callback'),
	        'scope' => 'email',
	    );
	    return Redirect::to($facebook->getLoginUrl($params));
	}));

	Route::get('login/fb/callback', array('as' => 'login-fb-callback', function() {
	    $code = Input::get('code');
	    if (strlen($code) == 0) return Redirect::to('/')->with('message', 'There was an error communicating with Facebook');

	    $facebook = new Facebook(Config::get('facebook'));
	    $uid = $facebook->getUser();

	    if ($uid == 0) return Redirect::to('/')->with('message', 'There was an error');

	    $me = $facebook->api('/me');

	    $profile = Profile::whereUid($uid)->first();
	    if (empty($profile)) {
	        $user = new User;
	        $user->name = $me['first_name'];
	        $user->fullname = $me['first_name'].' '.$me['last_name'];
	        $user->email = $me['email'];
	        $user->photo = 'https://graph.facebook.com/'.$me['id'].'/picture?type=large';

	        $user->save();

	        $profile = new Profile();
	        $profile->uid = $uid;
	        $profile->username = $me['id'];
	        $profile = $user->profiles()->save($profile);
	    }

	    $profile->access_token = $facebook->getAccessToken();
	    $profile->save();

	    $user = $profile->user;

	    Auth::login($user);

	    return Redirect::to('/')->with('message', 'Logged in with Facebook');
	}));

});

Validator::extend('awesome', 'CustomValidation@awesome');

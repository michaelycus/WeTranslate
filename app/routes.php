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

Route::get('/', array('as' => 'home', 'uses' => 'AccountController@getIndex'));//->before('auth ');

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
	| VIDEOS - Status
	*/
	Route::get('/videos/translating', array(
		'as' => 'videos-translating',
		'uses' => 'VideoController@getTranslating'
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

	/*
	| VIDEOS - Details
	*/

	Route::get('/videos/details/{id}', array(
		'as' => 'videos-details',
		'uses' => 'VideoController@getDetails'
	));

    /*
	| VIDEOS - Ajax calls
	*/

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

	Route::get('/videos/move-to/{id}/{status}', array(
		'as' => 'videos-move-to',
		'uses' => 'VideoController@getMoveTo'
	));

	Route::get('/videos/return-to/{id}/{status}', array(
		'as' => 'videos-return-to',
		'uses' => 'VideoController@getReturnTo'
	));

 	/*
	| VIDEOS - Suggest, verify, approve
	*/	

	Route::get('/videos/suggest', array(
		'as' => 'videos-suggest',
		'uses' => 'VideoController@getSuggest'
	));

	Route::post('/videos/suggest', array(
		'as' => 'videos-suggest-post',
		'uses' => 'VideoController@postSuggest'
	));

	Route::get('/videos/verify/{id}', array(
		'as' => 'videos-verify',
		'uses' => 'VideoController@getVerify'
	));

	Route::post('/videos/verify/{id}', array(
		'as' => 'videos-verify-post',
		'uses' => 'VideoController@postVerify'
	));

	Route::get('/videos/for-approval', array(
		'as' => 'videos-for-approval',
		'uses' => 'VideoController@getForApproval'
	));

	Route::post('/videos/for-approval', array(
		'as' => 'videos-for-approval-post',
		'uses' => 'VideoController@postForApproval'
	));

	/*
	| USERS
	*/
	Route::get('/users/{id}', array(
		'as' => 'users-profile',
		'uses' => 'UserController@getUser'
	));	

	/*
	| TEST
	*/
	Route::get('/teste/teste', array(
		'as' => 'teste-teste',
		'uses' => 'TestController@getTeste'
	));

	Route::get('/teste/amara', array(
		'as' => 'teste-amara',
		'uses' => 'TestController@getAmara'
	));

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
		Route::post('/account/sign-up', array(
			'as' => 'account-sign-up-post',
			'uses' => 'AccountController@postSignUp'
		));	

		/*
		| Sign in (POST)
		*/	
		Route::post('/account/sign-in', array(
			'as' => 'account-sign-in-post',
			'uses' => 'AccountController@postSignIn'
		));
	});

	/*
	| Sign in (GET)
	*/	
	Route::get('/account/sign-in', array(
		'as' => 'account-sign-in',
		'uses' => 'AccountController@getSignIn'
	));	

	/*
	| Create account (GET)
	*/	
	Route::get('/account/sign-up', array(
		'as' => 'account-sign-up',
		'uses' => 'AccountController@getSignup'		
	));	

	Route::get('/account/activate/{code}', array(
		'as' => 'account-activate',
		'uses' => 'AccountController@getActivate'
	));

	/*
	| Login Facebook (GET)
	*/
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
	        $user->username = $me['id'];
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

	Route::get('teste', function()
	{
	    return phpinfo();
	});

});
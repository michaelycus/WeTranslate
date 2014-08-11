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
	| VIDEOS
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

	Route::get('/videos/suggest', array(
		'as' => 'videos-suggest',
		'uses' => 'VideoController@getSuggest'
	));

	Route::post('/videos/suggest', array(
		'as' => 'videos-suggest-post',
		'uses' => 'VideoController@postSuggest'
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
		$link = "https://www.youtube.com/watch?v=ifEOoEpMBxg";
		preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $link, $matches);

		$json = json_decode(file_get_contents("http://gdata.youtube.com/feeds/api/videos/$matches[0]?v=2&alt=jsonc"));
    	echo '<img src="' . $json->data->thumbnail->sqDefault . '">';
    	echo ' '. $json->data->title . ' ';


	    return 'Users! '. $matches[0];
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

});

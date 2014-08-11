<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function getIndex()
	{		
		// Mail::send('emails.auth.test', array('name' => 'Michael'), function($message){
		// 	$message->to('michaelycus@gmail.com', 'Michael')->subject('Test email');
		// });

		if (Auth::check()){
			return View::make('dashboard');
		}
		else{
			return View::make('sign.signin');
		}

		// return View::make('home');
	}

	public function getSignIn()
	{
		return View::make('sign.signin');
	}

	public function signup()
	{
		return View::make('sign.signup');
	}

	public function postSignIn()
	{
		$validator = Validator::make(Input::all(),
			array(
				'email' => 'required|email',
				'password' => 'required'
			)
		);

		if ($validator->fails()){
			return Redirect::route('account-sign-in')
				->withErrors($validator)
				->withInput();
		} else{

			$remember = (Input::has('remember')) ? true : false;

			$auth = Auth::attempt(array(
				'email' => Input::get('email'),
				'password' => Input::get('password'),
				'auth' => 1
			), $remember);

			if ($auth){
				// Redirect to the intended page
				return Redirect::intended('/');
			} else{
				return Redirect::route('account-sign-in')
						->with('global', 'Email/password wrong, or account not activated.');
			}
		}

		return Redirect::route('account-sign-in')
				->with('global', 'There was a problem signing you in.');
	}

	public function getSignOut()
	{
		Auth::logout();

		return Redirect::route('home');
	}

	public function postCreate()
	{
		$validator = Validator::make(Input::all(), 
			array(
				'email' 		 => 'required|max:50|email|unique:users',
				'name'	 		 => 'required|max:20|min:3',
				'password' 		 => 'required|min:6',
				'password_again' => 'required|same:password'
			)
		);

		if ($validator->fails()) {
			return Redirect::route('signup')
					->withErrors($validator)
					->withInput();
		} else {
			$email    = Input::get('email');
			$name     = Input::get('name');
			$password = Input::get('password');

			// Activation code
			$code = str_random(60);

			$user = User::create(array(
				'email'		=> $email,
				'name' 		=> $name,
				'password' 	=> Hash::make($password),
				'code' 		=> $code,
				'auth' 		=> 0
			));

			if ($user){
				Mail::send('emails.auth.activate', array('link' => URL::route('account-activate', $code) , 'name' => $name), function($message) use ($user){
 					$message->to($user->email, $user->name)->subject('Activate your account');
				});

				return Redirect::route('home')
						->with('account-created', 'Your account has been created! We have sent you an email to active your account.');
			}
		}
	}

	public function getActivate($code){
		$user = User::where('code', '=', $code)->where('auth', '=', 0);

		if ($user->count()){
			$user = $user->first();

			// Update user to active state	
			$user->auth = 1;
			$user->code = '';

			if ($user->save()){
				return Redirect::route('home')
						->with('account-actived', '<strong>Activated!</strong> You can now sign in!');
			}
		}

		return Redirect('home')
				->with('global', 'We could not activate your account. Try again later.');
	}

	public function getTranslating()
	{
		return View::make('translating', array('videos' => Video::all() ));
	}

}

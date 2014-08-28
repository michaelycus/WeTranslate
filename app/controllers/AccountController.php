<?php

class AccountController extends BaseController{

	public function getSignIn()
	{
		return View::make('account.signin');
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

	public function getCreate()
	{
		return View::make('account.create');
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
			return Redirect::route('account-create')
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
				'auth' 		=> USER_NOT_AUTHORIZED
			));

			if ($user){
				Mail::send('emails.auth.activate', array('link' => URL::route('account-activate', $code) , 'name' => $name), function($message) use ($user){
 					$message->to($user->email, $user->name)->subject('Activate your account');
				});

				return Redirect::route('home')
						->with('global', 'Your account has been created! We have sent you an email to active your account.');
			}

		}
	}

	public function getActivate($code){
		$user = User::where('code', '=', $code)->where('auth', '=', USER_NOT_AUTHORIZED);

		if ($user->count()){
			$user = $user->first();

			// Update user to active state	
			$user->auth = USER_AUTH_OPERATOR;
			$user->code = '';

			if ($user->save()){
				return Redirect::route('home')
						->with('global', 'Activated! You can now sign in!');
			}
		}

		return Redirect('home')
				->with('global', 'We could not activate your account. Try again later.');
	}

	public function getChangePassword()
	{
		return View::make('account.password');
	}

	public function postChangePassword()
	{
		$validator = Validator::make(Input::all(),
			array(
				'old_password' 	 => 'required',
				'password' 		 => 'required|min:6',
				'password_again' => 'required|same:password'
			)
		);

		if ($validator->fails()){
			return Redirect::route('account-change-password')
					->withErrors($validator);
		} else {
			$user = User::find(Auth::user()->id);

			$old_password = Input::get('old_password');
			$password 	  = Input::get('password');

			if (Hash::check($old_password, $user->getAuthPassword())){
				$user->password = Hash::make($password);

				if ($user->save()){
					return Redirect::route('home')
							->with('global', 'Your password has been changed.');
				}
			} else {
				return Redirect::route('account-change-password')
						->with('global', 'Your old password is incorrect.');
			}
		}

		return Redirect::route('account-change-password')
				->with('global', 'Your password could not be changed.');
	}
}
<?php

class UserController extends BaseController
{

	public function user($id)
	{
		$user = User::where('id', '=', $id);

		if ($user->count()){
			$user = $user->first();

			return View::make('profile.user')
					->with('user', $user);
		}		

		return App::abort(404);
	}

	public function getUser($id)
	{
		$user = User::where('id', '=', $id);

		if ($user->count()){
			$user = $user->first();

			return View::make('profile.user')
					->with('user', $user);
		}		

		return App::abort(404);
	}

	public function getUsers()
	{
		$users = User::all();

		return View::make('users.manage', array('users' => $users));
	}
}
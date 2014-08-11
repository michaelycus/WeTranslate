<?php

class ProfileController extends BaseController
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
}
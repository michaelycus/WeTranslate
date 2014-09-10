<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

	protected $fillable = array('email', 'firstname', 'lastname', 'password', 'password_temp', 'code', 'auth');

	public function profiles()
    {
        return $this->hasMany('Profile');
    }

    public function photo()
    {
    	return $this->photo ? $this->photo : URL::asset('assets/images/icon-user-default.png');
    }

    public function score()
    {
    	return explode(",", $this->score);
    }

    public function translated_videos()
    {
    	return (int)$this->score()[USER_SCORE_TRANSLATED];
    }

    public function synchronized_videos()
    {
    	return (int)$this->score()[USER_SCORE_SYNCHRONIZED];
    }

    public function proofreaded_videos()
    {
    	return (int)$this->score()[USER_SCORE_PROOFREADED];
    }

    public function suggested_videos()
    {
    	return (int)$this->score()[USER_SCORE_SUGGESTED];
    }

    public function opened_videos()
    {
    	return (int)$this->score()[USER_SCORE_OPENED];
    }

    public function worked_in_videos()
    {
    	return (int)$this->score()[USER_SCORE_WORKED_IN];
    }

    public function score_total()
    {
    	return (int)$this->score()[USER_SCORE_TOTAL];
    }
}

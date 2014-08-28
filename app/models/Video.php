<?php


class Video extends Eloquent{
	
	protected $fillable = array('original_link', 'status');

	public function tasks() {
		return $this->hasMany('Task');
	}

	public static function forApproval()
	{
		return Video::where('status', '=', VIDEO_FOR_APPROVAL)->count();
	}
}

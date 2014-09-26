<?php


class Video extends Eloquent{
	
	use SoftDeletingTrait;

	protected $fillable = array('title', 'duration', 'thumbnail', 'original_link', 'working_link', 'status' );

	public function tasks() {
		return $this->hasMany('Task');
	}

	// public static function forApproval()
	// {
	// 	return Video::where('status', '=', VIDEO_FOR_APPROVAL)->count();
	// }

	public function suggestedBy()
	{
		$task = $this->tasks()->where('type','=',TASK_SUGGESTED_VIDEO)->first();

		return User::find($task['user_id']);		
	}

	public function comments(){
		return $this->hasMany('Comment');
	}
}

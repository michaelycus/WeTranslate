<?php


class Task extends Eloquent{
	
	protected $fillable = array('video_id', 'user_id', 'type');

	public function tasks() {
		return $this->belongsTo('User');
	}
}

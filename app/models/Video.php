<?php


class Video extends Eloquent{
	
	protected $fillable = array('original_link', 'status');

	public function tasks() {
		return $this->hasMany('Task');
	}
}

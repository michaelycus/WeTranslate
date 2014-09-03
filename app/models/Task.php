<?php

class Task extends Eloquent{
	
	protected $fillable = array('video_id', 'user_id', 'type');


	public function user()
    {
        return $this->belongsTo('User');
    }

    public function video()
    {
    	return $this->belongsTo('Video');
    }
}

<?php

class Task extends Eloquent{
	
	protected $fillable = array('video_id', 'user_id', 'type');


	public function user()
    {
        return $this->belongsTo('User');
    }

    public function suggestedBy()
    {
    	return $this->where('type', '=', TASK_SUGGESTED_VIDEO)->first();	
    }

    public function teste()
    {
    	return 'teste';
    }
}

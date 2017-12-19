<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    public function meetingContacts()
    {
    	return $this->hasMany('App\MeetingContact');
    }

    
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserShiftSchedule extends Model
{
    protected $table='user_shift_schedule';


    public function schedule(){
    	return $this->belongsTo('App\ShiftSchedule','shift_schedule_id')->withDefault();
    }

    public function user(){
    	return $this->belongsTo('App\User')->withDefault();
    }

    public function shift(){
    	return $this->belongsTo('App\Shift','shift_id')->withDefault();
    }

}

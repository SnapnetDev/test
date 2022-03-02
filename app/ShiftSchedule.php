<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShiftSchedule extends Model
{
    protected $fillable = ['start_date','end_date'];
    public function users()
    {
         return $this->belongsToMany('App\User','user_shift_schedule','shift_schedule_id','user_id');
    }
    public function shifts()
    {
         return $this->belongsToMany('App\Shift','user_shift_schedule','shift_schedule_id','shift_id');
    }
    public function usershiftschedules(){
    	return $this->hasMany('App\UserShiftSchedule');
    }
}

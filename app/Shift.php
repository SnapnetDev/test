<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $fillable = ['type','start_time','end_time','company_id','color_code'];

    public function users()
    {
         return $this->belongsToMany('App\User','user_shift_schedule','shift_id','user_id');
    }
    public function shift_schedules()
    {
         return $this->belongsToMany('App\ShiftSchedule','user_shift_schedule','shift_id','shift_schedule_id');
    }

    public function userShift(){
    	return $this->belongsTo('App\User','user_id','shift_id')->withDefault();
    }


}

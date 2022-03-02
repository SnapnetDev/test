<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = ['emp_num','shift_id','date','user_daily_shift_id','workflow_id','workflow_details','workflow_status'];

    public function user()
    {
    	return $this->belongsTo('App\User','emp_num','emp_num');
    }
    public function attendancedetails()
    {
    	return $this->hasMany('App\AttendanceDetail','attendance_id');
    }
    public function user_daily_shift(){
        return $this->belongsTo('App\UserDailyShift');
    }
    protected $casts=[
        'workflow_details'=>'array'
    ];
    
}

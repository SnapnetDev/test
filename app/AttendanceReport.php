<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttendanceReport extends Model
{
    protected $fillable=['user_id','date','first_clockin','last_clockout','status','shift_id','attendance_id','hours_worked','expected_hours',
        'shift_start','shift_end','shift_name','overtime','amount','approved_overtime'];

    public function attendance(){
        return $this->belongsTo('App\Attendance');
    }
    public function user(){
        return $this->belongsTo('App\User');
    }

}

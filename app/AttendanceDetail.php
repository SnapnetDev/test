<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttendanceDetail extends Model
{
    protected $fillable = ['attendance_id','date','time','clock_in','clock_out',
        'clock_in_lat','clock_in_long','clock_out_lat','clock_out_long','clock_in_imei','clock_out_imei'];

    public function attendance()
    {
    	return $this->belongsTo('App\Attendance','attendance_id');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TimesheetDetail extends Model
{
   protected $table="timesheet_details";
   protected $fillable=['user_id','tdays','total_hours','weekday_hours','basic_work_hours','overtime_weekday_hours','overtime_holiday_hours','overtime_saturday_hours','overtime_sunday_hours','expected_work_hours','expected_work_days','average_first_clock_in','average_last_clock_out'];

   public function timesheets()
    {
        return $this->belongsTo('App\TimesheetDetail','timesheet_id');
    }
    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }
}

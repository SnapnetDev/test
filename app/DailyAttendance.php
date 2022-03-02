<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DailyAttendance extends Model
{
    protected $table='daily_attendances';
    public function user()
    {
        return $this->belongsTo('App\User','emp_id');
    }
    public function getStartdateAttribute($value)
    {
        return $value;
    }
}

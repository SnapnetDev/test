<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShiftSwap extends Model
{
	protected $table='shift_swaps';
	protected $fillable=['owner_id','swapper_id','approved_by','status','user_shift_schedule_id','reason','status','new_shift_id','date'];
    public function owner()
    {
        return $this->belongsTo('App\User','owner_id');
    }
    public function swapper()
    {
        return $this->belongsTo('App\User','swapper_id');
    }
    public function newShift()
    {
        return $this->belongsTo('App\Shift','new_shift_id');
    }
    public function approver()
    {
        return $this->belongsTo('App\User','approved_by');
    }
    public function userShiftSchedule()
    {
        return $this->belongsTo('App\UserShiftSchedule','user_shift_schedule_id');
    }
}

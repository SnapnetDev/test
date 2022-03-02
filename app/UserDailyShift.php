<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserDailyShift extends Model
{
    protected $table='user_daily_shifts';
    protected $fillable=['user_id','shift_id','starts','ends','sdate'];

    

    public function user(){
        return $this->belongsTo('App\User')->withDefault();
    }

    public function shift(){
        return $this->belongsTo('App\Shift','shift_id')->withDefault();
    }

}

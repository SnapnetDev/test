<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HolidayMessage extends Model
{
    protected $fillable=['holiday_id','company_id','message','a_id'];
    //

    public function holiday(){
        return $this->belongsTo('App\Holiday','holiday_id');
    }
}

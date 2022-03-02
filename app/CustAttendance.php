<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustAttendance extends Model
{
   protected $fillable = ['user_id','clocktime','datetime'];
   
   public function user(){
        return $this->belongsTo('App\User')->withDefault();
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserFingerPrint extends Model
{
    protected $fillable=['user_id', 'finger_no','size','finger_print'];

    public function user(){
        return $this->belongsTo('App\User');
    }
}

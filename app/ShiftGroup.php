<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShiftGroup extends Model
{
    protected $fillable = ['name'];
    public function users()
    {
         return $this->hasMany('App\ShiftGroup','user_id','user_id');
    }
}

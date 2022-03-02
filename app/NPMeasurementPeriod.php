<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NPMeasurementPeriod extends Model
{
    protected $fillable = ['name','from','to','start','end','status'];

    public function np_users(){
        return $this->hasMany('App\NPUser');
    }
}

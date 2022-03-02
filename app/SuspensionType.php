<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SuspensionType extends Model
{
    protected $table='suspension_types';
    protected $fillable=['name'];

    public function suspensions()
    {
    	return $this->hasMany('App\Suspension');
    }
}

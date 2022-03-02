<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SeparationType extends Model
{
    protected $table='separation_types';
    protected $fillable=['name'];

    public function seperations()
    {
    	return $this->hasMany('App\Separation');
    }
}

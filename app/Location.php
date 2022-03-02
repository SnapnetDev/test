<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = ['name', 'address'];
    public function users()
    {
        return $this->hasMany('App\User','location_id');
    }
}

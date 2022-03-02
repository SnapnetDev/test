<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable=['name','manager_id','company_id'];
    public function users()
    {
        return $this->hasManyThrough('App\User','App\Job');
    }
    public function jobs()
    {
        return $this->hasMany('App\Job');
    }
    public function manager()
    {
        return $this->belongsTo('App\User','manager_id');
    }
    public function company()
    {
        return $this->belongsTo('App\Company','company_id');
    }
}

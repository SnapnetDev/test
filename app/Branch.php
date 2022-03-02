<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
	protected $fillable=['name','email','address','manager_id','company_id'];
   public function users()
    {
        return $this->hasMany('App\User');
    }
    public function manager()
    {
        return $this->belongsTo('App\User','manager_id');
    }
}

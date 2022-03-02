<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserUnion extends Model
{
    protected $fillable=['name','dues_formula','company_id'];
    protected $table='user_unions';

    public function users()
    {
    	return $this->hasMany('App\User','union_id');
    }
    

     
}

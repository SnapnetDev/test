<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    //
    protected $fillable=['name','manages'];
    public function users()
    {
        return $this->hasMany('App\User');
    }
    public function permissions()
    {
        return $this->belongsToMany('App\Permission','permission_role','role_id','permission_id');
    }

    
}

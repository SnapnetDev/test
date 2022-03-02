<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{
    protected $fillable=['name'];
    protected $table='user_groups';

    public function users()
    {
    	return $this->belongsToMany('App\User','user_group_user','user_group_id','user_id');
    }
    public function trainings()
    {
        return $this->belongsToMany('App\UserGroup', 'training_user', 'training_id', 'group_id');
    }

     
}

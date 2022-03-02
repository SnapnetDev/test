<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    //
    protected $table='trainings';
    protected $fillable = ['training_name', 'training_mode', 'duration', 'department_id', 'remark', 'created_by'];


    public function department()
    {
    	return $this->belongsTo('App\Department', 'department_id');
    }

    public function users()
    {
    	return $this->belongsToMany('App\User', 'training_user', 'training_id', 'user_id');
    }

    public function groups()
    {
        return $this->belongsToMany('App\UserGroup', 'training_group', 'training_id', 'group_id');
    }

    public function trainees()
    {
        return $this->belongsToMany('App\User', 'training_user', 'user_id', 'training_id');
    }

    public function TrainingRecommended()
    {
        return $this->hasMany('App\TrainingRecommended', 'training_id');
    }
}

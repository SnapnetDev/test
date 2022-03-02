<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GoalCat extends Model
{
    //
    protected $table='goal_cats';

    public function goal(){
    	return $this->hasMany('App\Goal','goal_cat_id')->withDefault();
    }
}

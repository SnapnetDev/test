<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    //
    protected $fillable=[ 'emp_id', 'goal_id', 'lm_rate', 'lm_id', 'lm_comment', 'admin_id', 'admin_rate', 'admin_comment', 'quarter', 'emp_comment'];

    public function user(){
    	return $this->belongsTo('App\User','emp_id')->withDefault();
    }
    public function goal(){
    	return $this->belongsTo('App\Goal','goal_id')->withDefault();
    }
    public function linemanager(){
    	return $this->belongsTo('App\User','lm_id')->withDefault();
    }
    public function admin(){
    	return $this->belongsTo('App\User','admin_id')->withDefault();
    }

}

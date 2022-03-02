<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Confirmation extends Model
{
    protected $fillable = ['user_id','initiator_id','status','confirmation_date','workflow_id'];
    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }
    public function initiator()
    {
        return $this->belongsTo('App\User','initiator_id');
    }
    public function approvals(){
        return $this->hasMany('App\ConfirmationApproval','confirmation_id');
    }

    public function requirements()
    {
        return $this->belongsToMany('App\ConfirmationRequirement','confirmation_requirement_files')->withPivot('file')->withTimestamps();
    }
}

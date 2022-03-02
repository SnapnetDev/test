<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SeparationApproval extends Model
{
    protected $fillable=['separation_id','stage_id','approver_id','comments','status','signature'];
    public function separation()
    {
        return $this->belongsTo('App\Separation','separation_id');
    }

    public function approver()
    {
        return $this->belongsTo('App\User','approver_id');
    }
    public function stage()
    {
        return $this->belongsTo('App\Stage','stage_id');
    }
    public function lists()
    {
        return $this->belongsToMany('App\SeparationApprovalList','separation_approval_separation_approval_list');

    }
}

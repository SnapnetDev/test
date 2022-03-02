<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ManualAttendance extends Model
{
    protected $fillable=['user_id','company_id','created_by','date','time_in','time_out','status','reason','workflow_id','workflow_details'];

   public function user(){
        return $this->belongsTo('App\User');
    }
    /* public function createdby(){
        return $this->belongsTo('App\User','created_by');
    }
    public function workflow(){
        return $this->belongsTo('App\Workflow');
    }*/

    protected $casts=[
        'workflow_details'=>'array'
    ];
}

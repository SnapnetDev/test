<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QueryEscalationFlow extends Model
{
    //
    protected $fillable=['role_id','group_id','num_of_reminder','created_by','company_id'];

    public function  role(){
        return $this->belongsTo('App\Role','role_id')->withDefault();
    }
    public function group(){
        return $this->belongsTo('App\UserGroup','group_id')->withDefault();
    }
}

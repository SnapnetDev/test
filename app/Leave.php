<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    protected $fillable=['name','created_by','length','with_pay','gender','marital_status'];
    protected $table='leaves';
    public function getJdateAttribute()
    {
    	return date('m/d/Y',strtotime($this->created_at));
    }
    public function leave_requests()
    {
    	return $this->hasMany('App\LeaveRequest','leave_id');
    }

}

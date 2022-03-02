<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaveSpill extends Model
{
    protected $fillable=['user_id','from_year','to_year','days','used','valid','actual_days','modified_by','modification_reason','company_id'];
    protected $table='leave_spills';

    public function user()
    {
    	return $this->belongsTo('App\User','user_id');
    }

    public function modifier()
    {
    	return $this->belongsTo('App\User','modified_by');
    }

    

     
}

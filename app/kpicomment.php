<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class kpicomment extends Model
{
    //
    protected $fillable = ['kpi_id','lm_comment','emp_comment','emp_id','from','to'];

    public function kpi(){
    	return $this->belongsTo('App\kpi','kpi_id');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class kpiassignedto extends Model
{
    //
    protected $fillable=['kpi_id','user_id'];

    public function user(){
    	return $this->belongsTo('App\User','user_id');
    }
    public function kpi(){
    	return $this->belongsTo('App\kpi','kpi_id');
    }
}

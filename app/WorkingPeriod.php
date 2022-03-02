<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkingPeriod extends Model
{
    protected $fillable = ['company_id', 'sob','cob'];
    protected $table ='working_periods';
    public function company()
    {
    	return $this->belongsTo('App\Company');
    }
}

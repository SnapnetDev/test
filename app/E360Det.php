<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class E360Det extends Model
{
   protected $table="e360_dets";
   protected $fillable=['department_id','measurement_period_id','company_id'];

   public function measurement_period()
    {
        return $this->belongsTo('App\E360MeasurementPeriod', 'measurement_period_id');
    }
	public function department()
    {
        return $this->belongsTo('App\Department', 'department_id');
    }
	public function questions()
    {
        return $this->hasMany('App\E360DetQuestion', 'e360_det_id');
    }

    public function evaluations()
    {
        return $this->hasMany('App\E360Evaluation', 'e360_det_id');
    }
}

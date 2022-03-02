<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppraisalMeasurementPeriod extends Model
{
	protected $table="bsc_measurement_periods";
   	protected $fillable=['from','to'];

   public function appraisals()
    {
        return $this->hasMany('App\Appraisal', 'measurement_period_id');
    }
}

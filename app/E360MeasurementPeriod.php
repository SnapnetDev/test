<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class E360MeasurementPeriod extends Model
{
    protected $table="e360_measurement_periods";
   protected $fillable=['from','to','company_id'];


    public function questions()
    {
        return $this->hasMany('App\E360DetQuestion', 'mp_id');
    }

    public function evaluations()
    {
        return $this->hasMany('App\E360Evaluation', 'mp_id');
    }
}

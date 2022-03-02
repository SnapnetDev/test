<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NPDivisionalKPI extends Model
{
    protected $fillable=['division_id', 'n_p_measurement_periods', 'kpi_question','user_score','user_comment','manager_score','manager_comment','answered_by'];

    public function measurement_period(){
        return $this->belongsTo('App\NPMeasurementPeriod')->withDefault();
    }
}

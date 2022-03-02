<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NPUser extends Model
{
    protected $fillable=['user_id', 'n_p_measurement_period_id', 'score',
        'user_response','user_comment',
        'manager_response','manager_comment','manager_id',
        'sos_response','sos_comment','sos_id',
        'line_executive_response','line_executive_comment','line_executive_id',
        'status'
    ];

    public function measurement_period(){
        return $this->belongsTo('App\NPMeasurementPeriod','n_p_measurement_period_id')->withDefault();
    }
    public function user(){
        return $this->belongsTo('App\User')->withDefault();
    }

    public function individual_kpis(){
        return $this->hasMany('App\NPIndividualKPI');
    }

    public function divisional_kpis(){
        return $this->hasMany('App\NPDivisionalKPI');
    }
}

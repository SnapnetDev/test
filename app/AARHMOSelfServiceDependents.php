<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AARHMOSelfServiceDependents extends Model
{
    //
    protected $table = 'hmo_selfservice_dependents';
    protected $fillable = ['id', 'userId', 'type', 'fullname','gender', 'date_of_birth', 'primary_hospital', 'secondary_hospital', 'health_plan_type', 'occupation', 'pre_condition', 'phone', 'passport', 'email' ];


    public function FindHospital1(){
    	return $this->belongsTo(AARHMOHospitals::class, 'primary_hospital');
    }

    public function FindHospital2(){
    	return $this->belongsTo(AARHMOHospitals::class, 'secondary_hospital');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AARHMOSelfService extends Model
{
    //
    protected $table = 'hmo_selfservice';
    protected $fillable = ['id', 'userId', 'hmo', 'primary_hospital', 'secondary_hospital', 'genotype', 'bloodgroup', 'health_plan_type'];

    public function FindUser(){
    	return $this->belongsTo(User::class, 'userId');
    }

    public function FindHMO(){
    	return $this->belongsTo(AARHMO::class,'hmo');
    }

    public function FindHospital1(){
    	return $this->belongsTo(AARHMOHospitals::class, 'primary_hospital');
    }

    public function FindHospital2(){
    	return $this->belongsTo(AARHMOHospitals::class, 'secondary_hospital');
    }

    public function AttachDependant(){
    	return $this->hasMany(AARHMOSelfServiceDependents::class, 'userId','userId');
    }

    public function CountDependants(){
        return $this->hasMany(AARHMOSelfServiceDependents::class, 'userId','userId');
    }

}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AARHMO extends Model
{
    //
    protected $table = "hmo";
    protected $fillable = ['id','hmo', 'description'];

    public function CountHospital(){
    	return $this->hasMany(AARHMOHospitals::class, 'hmo');
    }


    
}

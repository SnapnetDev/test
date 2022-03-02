<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\AARHMO;

class AARHMOHospitals extends Model
{
    //
    protected $table = "hmohospitals";
    protected $fillable = ['hmo', 'hospital', 'category', 'band', 'category', 'address', 'contact'];

    public function FindHMO(){
    	return $this->belongsTo(AARHMO::class,'hmo');
    }




}


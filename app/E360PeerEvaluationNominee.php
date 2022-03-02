<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class E360PeerEvaluationNominee extends Model
{
   protected $fillable=['mp_id','nominee_id','user_id','status','company_id'];

   public function user(){
       return $this->belongsTo('App\User','user_id');
   }
    public function nominee(){
        return $this->belongsTo('App\User','nominee_id');
    }
    public function measurement_period(){
        return $this->belongsTo('App\E360MeasurementPeriod','mp_id');
    }
}

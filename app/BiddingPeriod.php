<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BiddingPeriod extends Model
{
     /**
     *  Get the goods procurement that owns the procurement item
     * */ 
    
   public function goodsProcurement() {
         return $this->belongsTo('App\ProcurementItem');
     }
}

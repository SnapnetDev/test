<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProcurementItem extends Model
{
     /**
     *  Get the goods procurement that owns the procurement item
     * */ 
    
    public function goodsProcurement() {
         return $this->belongsTo('App\GoodsProcurement');
     }

      /**
     *  Get the list of items that belongs to the procurement item
     * */ 
    
     public function itemsLists() {
         return $this->hasMany('App\ItemList');
     }

      /**
     *  Get the bidding periods of the procuremnent item
     * */ 
    
     public function biddingPeriods() {
         return $this->hasMany('App\BiddingPeriod');
     }

      /**
     *  Get the bid evaluation period of the procurement item 
     * */ 
    
     public function bidEvaluations() {
         return $this->hasMany('App\BidEvaluation');
     }

      /**
     *  Get the Governors approoval date on the procurement item
     * */ 
    
     public function approvals() {
         return $this->hasMany('App\Approval');
     }

       /**
     *  Get the contract finalization for the goods procurement item
     * */ 

     public function contractFinalization() {
         return $this->hasMany('App\ContractFinalization');
     }

}

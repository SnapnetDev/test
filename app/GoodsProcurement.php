<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GoodsProcurement extends Model
{
    /**
     *  Get the procurement items for the goods procurement plan
     * */ 
    
     public function procurementItems() {
         return $this->hasMany('App\ProcurementItem');
     }

         
}

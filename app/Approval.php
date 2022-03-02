<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
     /**
     *  Get the goods procurement that has been approved
     * */ 
    
    public function procurementItem() {
         return $this->belongsTo('App\ProcurementItem');
     }
}

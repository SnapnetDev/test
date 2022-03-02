<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    protected $fillable=['opportunity_id','staff_id','expected_commission','commission','payment_status','payment_date'];

    public function opportunity(){
        return $this->belongsTo('App\Opportunity');
    }

    public function user(){
        return $this->belongsTo('App\User','staff_id','id');
    }

}

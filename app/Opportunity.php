<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Opportunity extends Model
{
    protected $fillable=['client_id','project_name','date','project_status','payment_status','project_amount'];

    public function commissions(){
        return $this->hasMany('App\Commission');
    }

    public function client(){
        return $this->belongsTo('App\Client');
    }
}

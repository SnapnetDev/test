<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SharesAllocation extends Model
{
    protected $fillable=['user_id','no_of_shares','years_vested','start_date'];

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function shares_vested(){
        return $this->hasMany('App\SharesVested');
    }
}

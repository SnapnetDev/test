<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SharesVested extends Model
{
    protected $fillable=['shares_allocation_id','no_of_shares','date_vested','status'];

    public function shares_allocation(){
        return $this->belongsTo('App\SharesAllocation');
    }
}

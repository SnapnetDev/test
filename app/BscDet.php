<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BscDet extends Model
{
   
   protected $table="bsc_dets";
   protected $fillable=['title','company_id'];

public function details()
    {
        return $this->hasMany('App\BscDetDetail', 'bsc_det_id');
    }
}

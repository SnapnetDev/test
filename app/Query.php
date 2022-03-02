<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Query extends Model
{
    //
        protected $fillable=['title','content','created_by','company_id'];


    public function createdby(){
        return $this->belongsTo('App\User','created_by')->withDefault();
    }
}

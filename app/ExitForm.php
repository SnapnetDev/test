<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExitForm extends Model
{
    protected $fillable =['user_id','company_id','workflow_id'];

    public function answers(){
        return $this->hasMany('App\ExitFormAnswer','user_id');
    }
    public function user(){
        return $this->belongsTo('App\User','user_id');
    }

}

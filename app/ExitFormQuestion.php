<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExitFormQuestion extends Model
{
    protected $fillable=['question','type','created_by','company_id','status'];

    public function options(){
        return $this->hasMany('App\ExitFormQuestionOption','question_id');
    }
    public function answers(){
        return $this->hasMany('App\ExitFormAnswer','question_id');
    }

    public function creator(){
        return $this->belongsTo('App\User','created_by');
    }
}

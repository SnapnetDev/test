<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SeparationForm extends Model
{
    protected $fillable=['user_id','status','company_id','separation_id','handover_note'];
    public function details()
    {
        return $this->hasMany('App\SeparationFormDetail','separation_form_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }

    public function separation()
    {
        return $this->belongsTo('App\Separation','separation_id');
    }
}

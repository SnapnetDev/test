<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SeparationQuestion extends Model
{
    protected $fillable=['question','type','separation_question_category_id','status','compulsory','company_id'];

    public function options()
    {
        return $this->hasMany('App\SeparationQuestionOption','separation_question_id');
    }

    public function category()
    {
        return $this->belongsTo('App\SeparationQuestionCategory','separation_question_category_id');
    }

    public function form_details()
    {
        return $this->hasMany('App\SeparationFormDetail','separation_question_id');
    }
}

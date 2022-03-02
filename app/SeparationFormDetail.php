<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SeparationFormDetail extends Model
{
    protected $fillable=['separation_question_id','separation_form_id','answer','separation_question_option_id','type'];

    public function question()
    {
        return $this->belongsTo('App\SeparationQuestion','separation_question_id');
    }

    public function form()
    {
        return $this->belongsTo('App\SeparationForm','separation_form_id');
    }
    public function option()
    {
        return $this->belongsTo('App\SeparationQuestionOption','separation_question_option_id');
    }
    public function options()
    {
        return $this->belongsToMany('App\SeparationQuestionOption','separation_form_detail_separation_question_option');
    }
}

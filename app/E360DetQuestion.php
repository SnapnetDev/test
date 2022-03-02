<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class E360DetQuestion extends Model
{
    protected $table="e360_det_questions";
   protected $fillable=['mp_id','question','self_question','question_category_id','company_id'];

   	public function measurement_period()
    {
        return $this->belongsTo('App\E360MeasurementPeriod', 'mp_id');
    }
    public function question_category()
    {
        return $this->belongsTo('App\E360QuestionCategory', 'question_category_id');
    }
	public function options()
    {
        return $this->hasMany('App\E360DetQuestionOption', 'e360_det_question_id');
    }
}

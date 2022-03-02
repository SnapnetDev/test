<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class E360EvaluationDetail extends Model
{
    protected $table="e360_evaluation_details";
   protected $fillable=['e360_evaluation_id','e360_det_question_id','e360_det_question_option_id'];

   public function evaluation()
    {
        return $this->belongsTo('App\E360Evaluation', 'e360_evaluation_id');
    }
    public function question()
    {
        return $this->belongsTo('App\E360DetQuestion', 'e360_det_question_id');
    }
    public function option()
    {
        return $this->belongsTo('App\E360DetQuestionOption', 'e360_det_question_option_id');
    }
    
}

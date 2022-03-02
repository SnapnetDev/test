<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class E360DetQuestionOption extends Model
{
    protected $table="e360_det_question_options";
   protected $fillable=['e360_det_question_id','content','score'];

   	public function question()
    {
        return $this->belongsTo('App\E360DetQuestion', 'e360_det_question_id');
    }
	
}

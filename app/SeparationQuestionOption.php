<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SeparationQuestionOption extends Model
{
    protected $fillable=['value','separation_question_id'];
    public function question()
    {
        return $this->belongsTo('App\SeparationQuestion','separation_question_id');
    }
}

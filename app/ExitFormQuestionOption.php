<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExitFormQuestionOption extends Model
{
    protected $fillable = ['question_id','content'];

    public function question(){
        return $this->belongsTo('App\ExitFormQuestion','question_id');
    }
}

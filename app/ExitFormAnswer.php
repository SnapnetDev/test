<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExitFormAnswer extends Model
{
    protected $fillable=['exit_form_id','question_id','user_id','content'];

    public function exit_form(){
        return $this->belongsTo('App\ExitForm','exit_form_id');
    }
    public function question(){
        return $this->belonsTo('App\ExitFormQuestion','question_id');
    }
    public function user(){
        return $this->belongsTo('App\User','user_id');
    }
    public function details(){
        return $this->hasMany('App\ExitFormAnswerDetail','exit_form_answer_id');
    }
}

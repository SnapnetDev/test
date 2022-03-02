<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SeparationQuestionCategory extends Model
{
    protected $fillable=['name','company_id','created_by'];

    public function questions()
    {
        return $this->hasMany('App\SeparationQuestion','separation_question_category_id');
    }
}

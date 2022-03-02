<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class E360QuestionCategory extends Model
{
    protected $table="e360_question_categories";
   protected $fillable=['name','description','user_id','company_id'];


	public function questions()
    {
        return $this->hasMany('App\E360DetQuestion', 'question_category_id');
    }
    public function creator()
    {
        return $this->hasMany('App\User', 'user_id');
    }

}

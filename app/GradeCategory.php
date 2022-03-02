<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GradeCategory extends Model
{
   protected $fillable = ['name'];
   protected $table="grade_categories";

   public function grades()
    {
       return $this->hasMany('App\Grade','grade_category_id');
    }
}

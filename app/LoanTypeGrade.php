<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoanTypeGrade extends Model
{
    protected $with = ['grade'];
    //
    function grade(){
        return $this->belongsTo(Grade::class,'grade_id');
    }
}

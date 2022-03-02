<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalaryReview extends Model
{
    protected $fillable=['employee_id','review_month','payment_month','used','company_id','previous_gross'];

    public function employee(){
        return $this->belongsTo('App\User','employee_id');
    }
}

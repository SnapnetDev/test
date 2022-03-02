<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaveAllowancePayment extends Model
{
    protected $fillable=['user_id','year','amount','disbursed','approved','leave_request_id'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function specific_salary_component()
    {
        return $this->hasOne('App\SpecificSalaryComponent','leave_allowance_payment_id');
    }
    public function leave_request()
    {
        return $this->belongsTo('App\LeaveRequest','leave_request_id');
    }
}

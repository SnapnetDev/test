<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OffPayrollComputationDetail extends Model
{
    protected $fillable=['off_payroll_computation_id','user_id','amount'];

    public function user(){
        return $this->belongsTo('App\User','user_id');
    }

    public function computation()
    {
        return $this->belongsTo('App\OffPayrollComputation','off_payroll_computation_id');
    }
}

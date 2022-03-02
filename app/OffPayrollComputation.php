<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OffPayrollComputation extends Model
{
    protected $fillable=['off_payroll_item_id','year'];

    public function details(){
        return $this->hasMany('App\OffPayrollComputationDetail','off_payroll_computation_id');
    }

    public function item()
    {
        return $this->belongsTo('App\OffPayrollItem','off_payroll_item_id');
    }
}

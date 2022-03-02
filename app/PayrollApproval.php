<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PayrollApproval extends Model
{
    protected $fillable=['payroll_id','stage_id','approver_id','comments','status'];
    public function payroll()
    {
        return $this->belongsTo('App\Payroll','payroll_id');
    }

    public function approver()
    {
        return $this->belongsTo('App\User','approver_id');
    }
    public function stage()
    {
        return $this->belongsTo('App\Stage','stage_id');
    }
}

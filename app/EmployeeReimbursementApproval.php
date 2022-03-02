<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeReimbursementApproval extends Model
{
    protected $fillable=['employee_reimbursement_id','stage_id','approver_id','comments','status','company_id'];
    public function employee_reimbursement()
    {
        return $this->belongsTo('App\EmployeeReimbursement','employee_reimbursement_id');
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

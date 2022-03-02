<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeReimbursement extends Model
{
    protected $fillable=['title','expense_reimbursement_type_id','expense_date','amount','disbursed','disbursement_date','attachment','user_id','workflow_id','status','company_id','description'];
    // protected static function boot()
    // {
    //     parent::boot();

    //     static::addGlobalScope(new CompanyScope);
    // }

    public function employee_reimbursement_type()
    {
        return $this->belongsTo('App\EmployeeReimbursementType','expense_reimbursement_type_id');
    }
    public function employee_reimbursement_approvals()
    {
        return $this->hasMany('App\EmployeeReimbursementApproval','employee_reimbursement_id');
    }
    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }
    public function workflow()
    {
        return $this->belongsTo('App\Workflow','workflow_id');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Workflow extends Model
{
  protected $fillable=['name'];
  public function stages()
  {
    return $this->hasMany('App\Stage','workflow_id')
    ->orderBy('position', 'asc');
  }
  public function payrolls()
  {
    return $this->hasMany('App\Payroll');
  }
  public function payroll_policies()
  {
    return $this->hasMany('App\PayrollPolicy');
  }
  public function leave_policies()
  {
    return $this->hasMany('App\LeavePolicy');
  }
    public function separation_policies()
    {
        return $this->hasMany('App\SeparationPolicy');
    }
  public function loan_policies()
  {
    return $this->hasMany('App\LoanPolicy');
  }
    public function separations()
    {
        return $this->hasMany('App\Separation');
    }
    public function leave_requests()
    {
        return $this->hasMany('App\LeaveRequest');
    }
    public function loan_requests()
    {
        return $this->hasMany('App\LoanRequest');
    }
    public function document_requests()
    {
        return $this->hasMany('App\DocumentRequest');
    }
    public function employee_reimbursements()
    {
        return $this->hasMany('App\EmployeeReimbursement');
    }
    public function employee_reimbursement_types()
    {
        return $this->hasMany('App\EmployeeReimbursementType');
    }
    public function document_request_types()
    {
        return $this->hasMany('App\DocumentRequestType');
    }
  // public function audit_logs()
  // {
  //     return $this->morphMany('App\AuditLog', 'auditable');
  // }

}

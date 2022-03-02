<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Payroll extends Model
{
    use LogsActivity;
    protected static $logUnguarded = true;
    protected static $logOnlyDirty = true;
    //
    protected $fillable=['month','year','company_id','workflow_id','payslip_issued','for','user_id','approved','disbursed','section_id'];
    protected static $logAttributes = ['month','year','company.name','workflow.name','payslip_issued','for','user.name','approved','disbursed','section.name'];
    protected static $ignoreChangedAttributes = ['created_at','updated_at'];
    protected $table="payroll";

    public function company()
    {
    	return $this->belongsTo('App\Company');
    }

    public function workflow()
    {
    	return $this->belongsTo('App\Workflow');
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function payroll_details()
    {
    	return $this->hasMany('App\PayrollDetail');
    }
    public function tmsa_payroll_details()
    {
        return $this->hasMany('App\TmsaPayrollDetail');
    }
    public function specific_salary_components()
    {
        return $this->belongsToMany('App\SpecificSalaryComponent','payroll_specific_salary_component','payroll_id','specific_salary_component_id')->orderBy('type','desc');
    }
    public function loan_requests()
    {
        return $this->belongsToMany('App\LoanRequest','payroll_loan_request','payroll_id','loan_request_id');
    }
    public function salary_components()
    {
        return $this->belongsToMany('App\SalaryComponent','payroll_salary_component','payroll_id','salary_component_id')->orderBy('type','desc');
    }
    public function tmsa_components()
    {
        return $this->belongsToMany('App\TmsaComponent','payroll_tmsa_component','payroll_id','salary_component_id');
    }
    public function project_salary_components()
    {
        return $this->belongsToMany('App\PaceSalaryComponent','payroll_project_salary_component','payroll_id','salary_component_id');
    }
     public function suspension_deductions()
    {
        return $this->belongsToMany('App\SuspensionDeduction','payroll_suspension_deductions','payroll_id','suspension_deduction_id');
    }
    public function payroll_logs()
    {
        return $this->hasMany('App\PayrollLog','payroll_id');
    }
    public function payroll_approvals()
    {
        return $this->hasMany('App\PayrollApproval','payroll_id');
    }
    public function section()
    {
    	return $this->belongsTo('App\UserSection');
    }
}

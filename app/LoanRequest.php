<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\CompanyScope;


class LoanRequest extends Model
{
    protected $table='loan_requests';
    protected $fillable=['user_id','amount','netpay','monthly_deduction','deduction_count','period','months_deducted','current_rate','repayment_starts','status','approved_by','completed','workflow_id','company_id','loan_type_id'];

    public function user()
    {
    	return $this->belongsTo('App\User','user_id')->withDefault();
    }
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new CompanyScope);
    }
    public function approver()
    {
    	return $this->belongsTo('App\User','approved_by')->withDefault();
    }
    public function approval()
    {
        return $this->hasMany('App\LoanApproval','loan_request_id');
    }
    public function payrolls()
    {
        return $this->belongsToMany('App\Payroll','payroll_loan_request','loan_request_id','payroll_id');
    }

    function loan_type(){
    	return $this->belongsTo(LoanType::class,'loan_type_id');
    }


}

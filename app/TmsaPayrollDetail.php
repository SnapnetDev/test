<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TmsaPayrollDetail extends Model
{
     protected $fillable=['user_id','payroll_id','onshore_day_rate','days_worked_onshore','offshore_day_rate','days_worked_offshore','total_gross_pay','annual_gross_pay','annual_employee_pension_contribution','monthly_employee_pension_contribution','allowances','deductions','personal_allowances','personal_deductions','details','personal_details','total_relief','taxable_income','annual_paye','monthly_paye','cra','netpay','out_of_station_allowance','brt_allowance','leave_allowance'];
    protected $table='tmsa_payroll_details';

    public function payroll()
    {
    	return $this->belongsTo('App\Payroll','payroll_id');
    }
    public function user()
    {
    	return $this->belongsTo('App\User');
    }
}

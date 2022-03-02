<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PayrollDetail extends Model
{
    
    protected $fillable=['payroll_id','user_id','annual_gross_pay','gross_pay','basic_pay','deductions','allowances','working_days','worked_days','details','sc_allowances','sc_deductions','ssc_allowances','ssc_deductions','sc_details','ssc_details','is_anniversary','taxable_income','annual_paye','paye','consolidated_allowance','netpay','payroll_type','union_dues','payment_status','payment_response','name_enquiry_response'];
    protected $table='payroll_details';
    protected $casts = ['payment_response'=>'array','name_enquiry_response'=>'array'];
    public function payroll()
    {
    	return $this->belongsTo('App\Payroll','payroll_id');
    }
    public function user()
    {
    	return $this->belongsTo('App\User');
    }
}

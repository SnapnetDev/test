<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttendancePayrollDetail extends Model
{
    protected $fillable=[
        'user_id','role_id','company_id','attendance_payroll_id','days_worked','present',
        'early','absent','late','off','amount_expected','amount_received','deduction',
    ];

    public function user(){
        return $this->belongsTo('App\User');
    }
    public function role(){
        return $this->belongsTo('App\Role');
    }
    public function attendance_payroll(){
        return $this->belongsTo('App\AttendancePayroll');
    }
}

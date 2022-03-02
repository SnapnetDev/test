<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttendancePayroll extends Model
{
    protected $fillable=['day','month','year','start','end','created_by','attendance_report_id','status','pay_status'];

    public function creator(){
        return $this->belongsTo('App\User','created_by');
    }
    public function attendance_payroll_details(){
        return $this->hasMany('App\AttendancePayrollDetail');
    }
    protected $casts=[
        'pay_status'=>'array'
    ];
}

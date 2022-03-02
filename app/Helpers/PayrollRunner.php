<?php

namespace App\Helpers;

use App\Payroll;
use App\Bank;
use App\CompanyAccountDetail;
use App\PayslipDetail;
use App\PayrollPolicy;
use App\SalaryComponent;
use App\SpecificSalaryComponent;
use App\Workflow;
use App\LatenessPolicy;
use App\Setting;
use App\User;
use App\Holiday;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Company;
use Auth;
use Excel;
use App\Traits\PayrollTrait;
class PayrollRunner
{

    use PayrollTrait;

    public function runPayroll()
    {
        $users=User::has('promotionHistories.grade')->get();
        $allp=[];
        foreach ($users as $user) {
            $payroll=[];
            $payroll['user_id']=$user->id;
            $payroll['month']=$this->month;
            $payroll['year']=$this->year;

            if(date('m',strtotime($user->hiredate))==$this->month &&date('Y',strtotime($user->hiredate))==$this->year){
                $payroll['start_day']=date('d',strtotime($user->hiredate));
            }else{
                $payroll['start_day']=1;
            }

            if(date('m',strtotime($user->hiredate))==$this->month &&date('Y',strtotime($user->hiredate))!=$this->year){
                $payroll['is_anniversary']=1;
            }else{
                $payroll['is_anniversary']=0;
            }
            $payroll['working_days']=$this->getExpectedDays($this->month,$this->year);
            $payroll['days_worked']=$this->getEmployeeDays($this->month,$this->year);
            $this->calculatePAYE($payroll);
            if( $payroll['has_grade']==1){
                $payroll['serialize']['allowances'] = $payroll['allowances'];
                $payroll['serialize']['deductions'] = $payroll['deductions'];
                $payroll['serialize']['component_names'] = $payroll['component_names'];
                $payroll['serialize'] = serialize($payroll['serialize']);
            }
            $allp[]=$payroll;


        }
        return $allp;
    }

}

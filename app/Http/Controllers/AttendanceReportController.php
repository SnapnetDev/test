<?php

namespace App\Http\Controllers;

use App\AttendanceReport;
use App\LatenessPolicy;
use App\SpecificSalaryComponent;
use App\Traits\Attendance as Attendancetrait;
use App\Traits\PayrollTrait;
use App\User;
use App\WorkingPeriod;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AttendanceReportController extends Controller
{
    use attendancetrait,payrolltrait;
    public function __construct()
    {
        $this->middleware(['auth','uses_tams']);
    }

    public function lateStaff(Request $request)
    {

    }

    public function calculateLateness(){
        $company_id=companyId();
        $late_policy=LatenessPolicy::where('company_id',$company_id)->where('status','1')->count();
        if ($late_policy>0){
            $month=Carbon::today()->format('m');
            $year=Carbon::today()->format('Y');
            $this->runDeduct($month,$year,$company_id);
            return 'success';
        }
        else{
            return 'No lateness Policy Enabled';
        }
    }
}

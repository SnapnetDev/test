<?php

namespace App\Http\Controllers;

use App\AttendanceReport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Company;
use App\User;
use App\WorkingPeriod;
use App\Attendance;
use App\Leave;
use App\LeaveRequest;
use App\Job;
use App\UserTemp;
use App\EducationHistory;
use App\Dependant;
use App\NokTemp;
use App\EmploymentHistory;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company_id=companyId();
        if ($company_id>0) {
          $company=Company::find($company_id);
                 $jobs=$company->jobs()->get();
        }else{
             $jobs=Job::all();
            }
        
        $pending_leave_requests=LeaveRequest::where('status',0)->whereYear('start_date', date('Y'))->count();
        $pending_profile_requests=UserTemp::where('last_change_approved',0)->count();
        $pending_nok_requests=NokTemp::where('last_change_approved',0)->count();
        $pending_academic_history_requests=EducationHistory::where('last_change_approved',0)->count();
        $pending_employment_history_requests=EmploymentHistory::where('last_change_approved',0)->count();
        $pending_dependants_requests=Dependant::where('last_change_approved',0)->count();
       
        $users=User::where('company_id',companyId())->pluck('id')->toArray();
        $today=Carbon::today()->format('Y-m-d');
        $yesterday=Carbon::yesterday()->format('Y-m-d');

        $todays_ar=AttendanceReport::where('date',$today)->whereIn('user_id',$users)->get();
        $earlys=$todays_ar->where('status','early')->count();
        $lates=$todays_ar->where('status','late')->count();
        $usersPresent=$todays_ar->whereIn('status',['early','late'])->count();

        $yesterdays_ar=AttendanceReport::where('date',$yesterday)->whereIn('user_id',$users)->get();
        $yesterday_lates=$yesterdays_ar->where('status','late')->count();;
        $yesterday_earlys=$yesterdays_ar->where('status','early')->count();;
        $yesterday_absentees=$yesterdays_ar->where('status','absent')->count();;
        $yesterday_usersPresent =$yesterdays_ar->whereIn('status',['early','late'])->count();;

        $last_month_early_users=\App\TimesheetDetail::orderBy('average_first_clock_in','asc')->take(5)->get();
        $last_month_late_users=\App\TimesheetDetail::orderBy('average_first_clock_in','desc')->take(5)->get();

        $companies=Company::all();
        
         $date = Carbon::now();
         $monthly_logins=User::whereMonth('last_login_at', '=', $date->month)
           ->count();

                $b_users=User::whereMonth('dob', '=', $date->month)->whereYear('dob','!=',$date->year)
           ->where(function ($query) use ($date) {
               $query->whereDay('dob', '>', $date->day)
                   ->orWhereDay('dob', '<=', $date->day);
           })
           ->orderByRaw("DAYOFMONTH(dob) ASC")
           ->get();
           
           $a_users = User::whereMonth('hiredate', '=', $date->month)->whereYear('hiredate','!=',$date->year)
           ->where(function ($query) use ($date) {
              $query->whereDay('hiredate', '>', $date->day)
                   ->orWhereDay('hiredate', '<=', $date->day);
           })
          ->orderByRaw("DAYOFMONTH(hiredate) ASC")
           ->get();
        return view('demo_home',compact('pending_nok_requests','pending_employment_history_requests','pending_dependants_requests','pending_academic_history_requests','pending_profile_requests','pending_leave_requests','companies','usersPresent','yesterday_absentees', 'yesterday_usersPresent','earlys','lates', 'yesterday_earlys','yesterday_lates','last_month_early_users','last_month_late_users','pending_leave_requests','jobs','a_users','b_users'));
    }
    public function executiveView()
    {
        
        return view('executiveview.index');
    }
    public function executiveViewLeave()
    {
        
        return view('executiveview.leave');
    }
    public function executiveViewHR()
    {
        
        return view('executiveview.hr');
    }
    public function executiveViewEmployee()
    {
        
        return view('executiveview.employee');
    }
    public function executiveViewPayroll()
    {
        
        return view('executiveview.payroll');
    }
    public function executiveViewJobRole()
    {
        
        return view('executiveview.jobrole');
    }
    public function executiveViewPerformance()
    {
        
        return view('executiveview.performance');
    }
    public function time_diff($time1, $time2)
    {
        $time1 = strtotime("1/1/2018 $time1");
        $time2 = strtotime("1/1/2018 $time2");

    // if ($time2 < $time1)
    // {
    //  $time2 = $time2 + 86400;
    // }

    return ($time2 - $time1) / 3600;
    }

    public function setfy($year){
  session(['FY'=>$year]);
  return response()->json('ok',200);
  
}
public function setcpny($company_id){
  session(['company_id'=>$company_id]);
  return response()->json('ok',200);
 
  
}
public function countries(Request $request)
{
    if($request->q==""){
            return "";
        }
       else{
        $name=\App\Country::where('name','LIKE','%'.$request->q.'%')
                        ->select('id as id','name as text')
                        ->get();
            }
        
        
        return $name;
    
}
public function states(Request $request,$country_id)
{

    if($request->q==""){
        return "";
    }
    else{
        $country=\App\Country::find($country_id);
        if($country){
            $name=$country->states()->where('name','LIKE','%'.$request->q.'%')
                ->select('id as id','name as text')
                ->get();

        }else{
            $country=\App\Country::find(160);
            $name=$country->states()->where('name','LIKE','%'.$request->q.'%')
                ->select('id as id','name as text')
                ->get();
        }
    }

    return $name;
}
public function lgas(Request $request,$state_id)
{
   
    if($request->q==""){
            return "";
        }
       else{
         $state=\App\State::find($state_id);
        $name=$state->lgas()->where('name','LIKE','%'.$request->q.'%')
                        ->select('id as id','name as text')
                        ->get();
            }
        
        
        return $name;
}
}

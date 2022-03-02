<?php

namespace App\Http\Controllers;

use App\LeaveRequestAdjustment;
use App\RegistrationProgress;
use Illuminate\Http\Request;
use App\LeavePeriod;
use App\Holiday;
use App\Shift;
use App\Grade;
use App\Leave;
use App\Setting;
use App\LeavePolicy;
use App\Workflow;
use Illuminate\Support\Facades\Log;
use Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class LeaveSettingController extends Controller
{
    public function index($value='')
	{
		$company_id=companyId();
	      $lp=LeavePolicy::where('company_id',$company_id)->first();
	       $workflows=Workflow::all();


	      if (!$lp) {
	        $lp=LeavePolicy::create(['includes_weekend'=>0,'includes_holiday'=>0,'user_id'=>Auth::user()->id,'company_id'=>$company_id,'workflow_id'=>0]);
	      }
		$holidays=Holiday::where('company_id',$company_id)->get();
		$leaveperiods=LeavePeriod::all();
		$leaves=Leave::all();
		$grades=Grade::doesntHave('leaveperiod')->get();
		return view('settings.leavesettings.index',compact('holidays','leaveperiods','grades','leaves','workflows','lp'));
	}
    public function shiftindex($value='')
    {
        $company_id=companyId();
        $shifts=Shift::where('company_id',$company_id)->get();
        return view('settings.shiftsettings.index',compact('shifts'));
    }
	public function savePolicy(Request $request)
	  {

	    $company_id=companyId();
      $lp=LeavePolicy::where('company_id',$company_id)->first();
      $ih = ($request->includes_holiday==1) ? 1 : 0 ;
       $iw = ($request->includes_weekend==1) ? 1 : 0 ;
       $us = ($request->uses_spillover==1) ? 1 : 0 ;
       $ums = ($request->uses_maximum_spillover==1) ? 1 : 0 ;
          $ra = ($request->relieve_approves==1) ? 1 : 0 ;
          $pa = ($request->probationer_applies==1) ? 1 : 0 ;
          $ucl = ($request->uses_casual_leave==1) ? 1 : 0 ;
          $cra = ($request->can_request_allowance==1) ? 1 : 0 ;
 		if ($lp) {
        $lp->update(['includes_weekend'=>$iw,'includes_holiday'=>$ih,'user_id'=>Auth::user()->id,'workflow_id'=>$request->workflow_id,'default_length'=>$request->default_length,'uses_spillover'=>$us,'uses_maximum_spillover'=>$ums,'spillover_length'=>$request->spillover_length,'spillover_month'=>$request->spillover_month,'spillover_day'=>$request->spillover_day,'relieve_approves'=>$ra,'probationer_applies'=>$pa,'uses_casual_leave'=>$ucl,'casual_leave_length'=>$request->casual_leave_length,'can_request_allowance'=>$cra]);
      }else{
        LeavePolicy::create(['includes_weekend'=>$iw,'includes_holiday'=>$ih,'user_id'=>Auth::user()->id,'workflow_id'=>$request->workflow_id,'default_length'=>$request->default_length,'uses_spillover'=>$us,'uses_maximum_spillover'=>$ums,'spillover_length'=>$request->spillover_length,'spillover_month'=>$request->spillover_month,'spillover_day'=>$request->spillover_day,'company_id'=>$company_id,'relieve_approves'=>$ra,'probationer_applies'=>$pa,'uses_casual_leave'=>$ucl,'casual_leave_length'=>$request->casual_leave_length,'can_request_allowance'=>$cra]);
      }
          if($request->source=='onboarding') {
              $rp=RegistrationProgress::where('company_id',companyId())->first();
              $rp->update(['has_leave_policy'=>1]);
              $request->session()->flash('success', 'Leave Policy was saved successfully!');
          }
    return 'success';
	  }
	  public function switchLeaveIncludesWeekend(Request $request)
	  {
	    $setting=Setting::where('name','leave_includes_weekend')->first();
	    if ($setting->value==1) {
	     $setting->update(['value'=>0]);
	      return 2;
	    }elseif($setting->value==0){
	      $setting->update(['value'=>1]);
	       return 1;
	    }
	  }
	public function saveHoliday(Request $request)
	{
		$holiday = Holiday::where(['date'=>date('Y-m-d',strtotime($request->date)),'company_id'=>companyId()])->first();
       if (!is_null($holiday)){
       	$holiday->title = $request->title;
           $holiday->save();

        //     $leave_requests = \App\LeaveRequest::where('start_date', '<=', date('Y/m/d',strtotime($holiday->date)))
        //     ->where('end_date', '>=', date('Y/m/d',strtotime($holiday->date)))
        //     ->get();
        // foreach ($leave_requests as $leave_request) {
        // 	 $leaveRequest->balance = $leaveRequest->balance + 1;
        //     $leaveRequest->length = $leaveRequest->length - 1;
        //     $leaveRequest->save();
        // }




       }else{
       		$holiday=Holiday::create(['title'=>$request->title,'date'=>date('Y-m-d',strtotime($request->date)),'created_by'=>Auth::user()->id,'company_id'=>companyId(),'message'=>'message']);
       	// 	 $leave_requests = \App\LeaveRequest::where('start_date', '<=', date('Y/m/d',strtotime($holiday->date)))
        //     ->where('end_date', '>=', date('Y/m/d',strtotime($holiday->date)))
        //     ->get();
        //     foreach ($leave_requests as $leave_request) {
        // 	 $leaveRequest->balance = $leaveRequest->balance + 1;
        //     $leaveRequest->length = $leaveRequest->length - 1;
        //     $leaveRequest->save();
        // }
       }

		return  response()->json('success',200);
	}
	public function getHoliday($holiday_id)
	{
		$holiday=Holiday::find($holiday_id);
		return  response()->json($holiday,200);
	}
	public function deleteHoliday($holiday_id)
	{
		$holiday=Holiday::find($holiday_id);
		if ($holiday) {
			$holiday->delete();
		}else{
			return  response()->json(['failed'],200);
		}
		return  response()->json(['success'],200);
	}
	public function adjustHoliday($holiday_id)
	{
		$holiday=Holiday::find($holiday_id);
		$company_id=companyId();
		if ($holiday) {
			$request_dates=\App\LeaveRequestDate::where('date',date('Y-m-d',strtotime($holiday->date)))
			->whereHas('leave_request',function($query) use($company_id){
				$query->where('company_id',$company_id);
			})->get();
			foreach($request_dates as $request_date){
                $leave_request = $request_date->leave_request;

                    $selection = $leave_request->dates->pluck('date')->toArray();
                    if ($leave_request) {
                        $new_selection = array_filter($selection, function ($item, $key) use ($request_date) {
                            //print_r($key);
                            return $item > $request_date->date;
                        }, ARRAY_FILTER_USE_BOTH);
                    }
                    if($leave_request->start_date == $request_date->date && $leave_request->end_date==$request_date->date) {

                        $leave_request->delete();
                    }elseif($leave_request->end_date==$request_date->date && $leave_request->start_date!=$request_date->date){
                        $lastElement=end($new_selection);
                        $leave_request->end_date= $lastElement;
                        $leave_request->balance += 1;
                        $leave_request->length -= 1;
                        $leave_request->save();
                    }elseif($leave_request->start_date==$request_date->date&& $leave_request->end_date!=$request_date->date){

                        $firstElement = reset($new_selection);
                        $leave_request->start_date = $firstElement;
                        $leave_request->balance += 1;
                        $leave_request->length -= 1;
                        $leave_request->save();
                    }else{
                        $leave_request->balance += 1;
                        $leave_request->length -= 1;
                    }

                $request_date->delete();

                    LeaveRequestAdjustment::firstOrCreate(['leave_request_id'=>$leave_request->id,'date'=>$request_date->date],
                        ['adjuster_id'=>Auth::id(),
                        'reason'=>'Holiday adjustment']);

                }
            }

		return  response()->json('success',200);

	}
	public function saveLeave(Request $request)
	{
		Leave::updateorCreate(['id'=>$request->leave_id],['name'=>$request->name,'created_by'=>Auth::user()->id,'length'=>$request->length,'with_pay'=>$request->with_pay,'gender'=>$request->gender,'marital_status'=>$request->marital_status]);
		return  response()->json('success',200);
	}
	public function getLeave($leave_id)
	{
		$leave=Leave::find($leave_id);
		return  response()->json($leave,200);
	}
	public function deleteLeave($leave_id)
	{
		$leave=Leave::find($leave_id);
		if ($leave) {
			$leave->delete();
		}else{
			return  response()->json(['failed'],200);
		}
		return  response()->json(['success'],200);
	}


	public function saveShift(Request $request)
	{
        $company_id=companyId();
		Shift::updateOrCreate(['id'=>$request->shift_id],['type'=>$request->type,'start_time'=>$request->start_time,'color_code'=>$request->color_code,'end_time'=>$request->end_time,'company_id'=>$company_id]);
		return  response()->json('success',200);
	}
	public function getShift($shift_id)
	{
		$shift=Shift::find($shift_id);
		return  response()->json($shift,200);
	}
	public function deleteShift($shift_id)
	{
		$shift=Shift::find($shift_id);
		if ($shift) {
			$shift->delete();
		}else{
			return  response()->json(['failed'],200);
		}
		return  response()->json(['success'],200);
	}

}

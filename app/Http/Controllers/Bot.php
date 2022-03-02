<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\LeavePolicy;
use App\LeaveRequest;
use App\Holiday;
use App\Leave;
use App\AttendanceReport;


use App\Notifications\ApproveLeaveRequest;
use App\Notifications\LeaveRequestApproved;
use App\Notifications\LeaveRequestPassedStage;
use App\Notifications\LeaveRequestRejected;
use App\Notifications\RelieveColleagueOnLeave;
use App\Notifications\RelieveLeaveRequestRejection;

use App\Workflow;


class Bot extends Controller
{

	public function getMyInfo(Request $request){
		$person = trim($request->username);
		$activeUser = $this->AuthenticateUser($request->email,$request->password);
		if(!$activeUser){
			return 404;
		}

		if(strlen($person) > 0){  
			$perm='edit_user_advanced';
			$permissions=$activeUser->role->permissions;
            if($permissions->where('constant', $perm)->count() < 1 ) { //test condition
            	return "You don't have permision to access other profiles, please contact your administrator.";
            }else{
            	$activeUser = User::where('email','like','%'.$person.'%');
            }
        }

        $activeUser = $activeUser->with('branch')->with('department')->with('grade')->with('country')->with('lga')->with('state')->with('bank')->with('nok')->with('job')->with('department')->first();

        if($activeUser == null){
        	return "404";
        }

        $parseData = json_encode(array(
        	'passport' => (string)!empty(trim($activeUser->image))?asset('uploads/avatar'.$activeUser->image) : ($activeUser->sex == 'M'? asset('uploads/avatar/mholder.jpg'):asset('uploads/avatar/fholder.jpg')),
        	'name' => (string)$activeUser['name'],
        	'emp_num' => (string)$activeUser['emp_num'],
        	'sex' => (string)$activeUser['sex'],
        	'marital_status' => (string)$activeUser['marital_status'],
        	'confirmation_date' => (string)$activeUser['confirmation_date'],
        	'email' => (string)$activeUser['email'],
        	'phone' => (string)$activeUser['phone'],
        	'dob' => (string)date("F d Y", strtotime($activeUser['dob'])),
        	'address' => (string)$activeUser['address'],
        	'expat' => (string)$activeUser['expat'],
        	'payroll_type' => (string)ucwords($activeUser['payroll_type']),
        	'country' => (string)$activeUser['country']['name'],
        	'state' => (string)$activeUser['state']['name'],
        	'lga' => (string)$activeUser['lga']['name'],
        	'years_of_service' => (string)$activeUser['years_of_service'],
        	'branch' => (string)$activeUser['branch']['name'],
        	'department' => (string)$activeUser['department'],
        	'hireddate' => (string)date("F d Y", strtotime($activeUser['hiredate'])),
        	'job' => (string)$activeUser['job']['title'],
        	'employment_status' => (string)$activeUser['employment_status'],
        	'basicpayment' => (string)number_format($activeUser['grade']['basic_pay'],2),
        	'bank' => (string)$activeUser['bank']['bank_name'],
        	'bank_account_no' => (string)$activeUser['bank_account_no'],
        	'employment_status' => (string)$activeUser['employment_status'],
        	'nokn' => (string)$activeUser['nok']['name'],
        	'nokr' => (string)$activeUser['nok']['relationship'],
        	'nokp' => (string)$activeUser['nok']['phone'],
        	'noka' => (string)$activeUser['nok']['address'],
        ));

        return $parseData;
    }





    public function getCalendar(Request $request){
    $string = $request->input('query');
	$person = trim(explode(" ",explode("@", $string)[1])[0]);
    $user = User::where('email','like','%'.$person.'%')->first(['id','name']);



    $dateString = trim(explode("for", $string)[1]);
    $Calendar = AttendanceReport::where('user_id','like','%'.$user->id.'%')->where('date', date("Y-m-d", strtotime($dateString)) )->first();

    !empty($Calendar['first_clockin']) ? $clockin = gmdate("g:i A",strtotime($Calendar['first_clockin'])) : $clockin = "-";
    !empty($Calendar['last_clockout']) ? $clockout = gmdate("g:i A",strtotime($Calendar['last_clockout'])) : $clockout = "-";

        $CalendarFetch = json_encode(array(
                    'name' => $user->name,
                    'shift_name' => $Calendar['shift_name'],
                    'shift_start' => gmdate("g:i A",strtotime($Calendar['shift_start'])),
                    'shift_end' => gmdate("g:i A",strtotime($Calendar['shift_end'])),
                    'overtime' => (boolean)$Calendar['overtime'] ? 'True' : 'False',
                    'date' => gmdate("j F Y",strtotime($Calendar['date'])),
                    'clockin' => $clockin,
                    'clockout' => $clockout,
                    'status' => ucfirst($Calendar['status']),
                    'expected_hours' => ucfirst($Calendar['expected_hours']),
                    'hours_worked' => ucfirst($Calendar['hours_worked']),
                    ));

        return $Calendar['first_clockin'] != null ? $CalendarFetch : 404;
    }





public function getLeaveInfo(Request $request)
{

	$activeUser = $this->AuthenticateUser($request->email,$request->password);

	if(!$activeUser){
		return 404;
	}

	$lp = LeavePolicy::where('company_id', $activeUser->company_id)->first();
	if (!$lp) {
		return json_encode(['status' => 'Leave Policy has not been set up']);
	}

	if ($activeUser->grade) {
		if ($activeUser->grade->leave_length > 0) {
			$leavebank = $activeUser->grade->leave_length;
			$oldleavebank = 0;
		} else {
			$leavebank = $lp->default_length;
			$oldleavebank = 0;
		}

	} else {
		$leavebank = $lp->default_length;
		$oldleavebank = 0;
	}
	$leave_left = $leavebank;
	$oldleaveleft = 0;

	$leave_includes_weekend = $lp->includes_weekend;
	$leave_includes_holiday = $lp->includes_holiday;
	$holidays = \App\Holiday::whereYear('date', date('Y-m-d'))->where('company_id', companyId())->get();
	$pending_leave_requests = $activeUser->leave_requests()->where('status', 0)->whereYear('start_date', date('Y'))->get();
	$leave_requests = $activeUser->leave_requests()->whereYear('start_date', date('Y'))->get()->count();

	$leaves = \App\Leave::all();
	$leave_plans = \App\LeavePlan::where(['user_id' => $activeUser->id, 'company_id' => companyId()])->whereYear('start_date', date('Y'))->get();

	$used_leaves = $activeUser->leave_requests()->where('status', 1)->whereYear('start_date', date('Y'))->get();
	$used_days = $activeUser->leave_requests()->whereYear('start_date', date('Y'))->where('status', 1)->where('leave_id', 0)->sum('length');
	$used_days_last_year = 0;

	if (date('Y', strtotime($activeUser->hiredate)) == date('Y')) {
            //porate for staff employed this year
		$leavebank = $leavebank / 12 * (12 - intval(date('m', strtotime($activeUser->hiredate))) + 1);
		$oldleavebank = 0;
	} else {
		$leave_spill_last_year = \App\LeaveSpill::where('from_year', date('Y') - 1)->where('to_year', date('Y'))->where('user_id', $activeUser->id)->first();
		$date = date('Y-m-d', strtotime('01-' . $request->month));
		$spillover_date = date('Y-m-d', strtotime(date('Y') . '-' . $lp->spillover_month . '-' . $lp->spillover_day));
		if (date('Y-m-d') > $spillover_date) {
			if ($leave_spill_last_year) {
				$oldleaveleft = $leave_spill_last_year->days - $leave_spill_last_year->used;
			} else {
				$oldleaveleft = 0;
			}
		} else {
			$oldleaveleft = 0;
		}

	}
	$leaveleft = $leavebank - $used_days;

	$currentYear = date('Y');
	$holidaysCount = count($holidays);
		//////////////Levae start and end dates//////////
	$leave_plansList = NULL;
	$leave_plansCount = count($leave_plans);

	$myArray =  ['Name' => $activeUser->name, 'LeaveBank' => $leavebank, 'UsedLeave' => $used_days, 'LeaveBalance' => $leaveleft, 'LeaveRequest' => $leave_requests, 'Holidays' => $holidaysCount, 'LeavePlan' => $leave_plansCount ];

	return json_encode($myArray);
}






public function saveRequest(Request $request)
{


//resolve leaves dynamically
	switch($request->leave_id){
		case $request->leave_id == "Annual Leave":
		$request->leave_id = 0;
		break;
		default:
		$request->leave_id = Leave::where('name', $request->leave_id)->pluck('id')[0];
		break;
	}



	$activeUser = $this->AuthenticateUser($request->email,$request->password);

	if(!$activeUser){
		return 404;
	}

	if ($request->file('absence_doc')) {
		$mime = $request->file('absence_doc')->getClientOriginalextension();
		if (!(in_array($mime, $this->allowed))): throw new \Exception("Invalid File Type"); endif;
	}

	$balance=$this->leaveLength($request)['balance'];
	$paystatus=$this->leaveLength($request)['paystatus'];
	$length=$this->differenceBetweenDays($request->start_date,$request->end_date);


	$company_id = companyId();
	$lp = LeavePolicy::where('company_id', $activeUser->company_id)->first();
	$leave_request = LeaveRequest::create(['leave_id' => $request->leave_id, 'user_id' => $activeUser->id, 'start_date' => date('Y-m-d', strtotime($request->start_date)), 'end_date' => date('Y-m-d', strtotime($request->end_date)), 'reason' => $request->reason, 'workflow_id' => $lp->workflow_id, 'paystatus' => $paystatus, 'status' => 0, 'length' => $length, 'company_id' => $company_id, 'replacement_id' => $request->replacement, 'balance' => $balance, 'leave_bank' => $length]);
	if ($request->file('absence_doc')) {

		$path = $request->file('absence_doc')->store('leave');
		if (Str::contains($path, 'leave')) {
			$filepath = Str::replaceFirst('leave', '', $path);
		} else {
			$filepath = $path;
		}
		$leave_request->absence_doc = $filepath;
		$leave_request->save();
	}


       /*
        $leave_request->replacement->notify(new RelieveColleagueOnLeave($leave_request));
        if ($lp->relieve_approves == 1) {
            # code...
        } else {

            $stage = Workflow::find($leave_request->workflow_id)->stages->first();
            if ($stage->type == 1) {
                $leave_request->leave_approvals()->create([
                    'leave_request_id' => $request->id, 'stage_id' => $stage->id, 'comments' => '', 'status' => 0, 'approver_id' => $stage->user_id
                ]);
                if ($stage->user) {
                    $stage->user->notify(new ApproveLeaveRequest($leave_request));
                }

            } elseif ($stage->type == 2) {
                $leave_request->leave_approvals()->create([
                    'leave_request_id' => $request->id, 'stage_id' => $stage->id, 'comments' => '', 'status' => 0, 'approver_id' => 0
                ]);
                if ($stage->role->manages == 'dr') {
                    if ($leave_request->user->managers) {
                        foreach ($leave_request->user->managers as $manager) {
                            $manager->notify(new ApproveLeaveRequest($leave_request));
                        }
                    }
                } elseif ($stage->role->manage == 'all') {
                    foreach ($stage->role->users as $user) {
                        $user->notify(new ApproveLeaveRequest($leave_request));
                    }
                } elseif ($stage->role->manage == 'none') {
                    foreach ($stage->role->users as $user) {
                        $user->notify(new ApproveLeaveRequest($leave_request));
                    }
                }
            } elseif ($stage->type == 3) {
                if ($stage->group) {
                    foreach ($stage->group->users as $user) {
                        $user->notify(new ApproveLeaveRequest($leave_request));
                    }
                }

            }

        }
        */

        return 'success';
    }










    public function AuthenticateUser($email,$password){
    	$credentials=[
    		'email' =>$email,
    		'password'=>$password
    	];
    	if (Auth::attempt($credentials)){
    		$activeUser =  User::whereEmail($credentials['email'])->first();
    		return $activeUser;
    	}else{
    		return false;
    	}   
    }













    public function leaveLength(Request $request)
    {
    	if ($request->leave_id == 0) {
    		$company_id = companyId();
    		$lp = LeavePolicy::where('company_id', $company_id)->first();
    		if (Auth::user()->grade) {
    			if (Auth::user()->grade->leave_length > 0) {
    				$leavebank = Auth::user()->grade->leave_length;
    			} else {
    				$leavebank = $lp->default_length;
    			}

    		} else {
    			$leavebank = $lp->default_length;
    		}
    		$leave_left = $leavebank;
            // $leavebank=Auth::user()->promotionHistories()->latest()->first()->grade->leave_length;
    		$leave_includes_weekend = $lp->includes_weekend;
    		$leave_includes_holiday = $lp->includes_holiday;
    		$holidays = Holiday::whereYear('date', date('Y-m-d'))->get();
    		$pending_leave_requests = Auth::user()->leave_requests()->where('status', 0)->whereYear('start_date', date('Y'))->get();
    		$leave_requests = Auth::user()->leave_requests()->whereYear('start_date', date('Y'))->get();

    		$leaves = Leave::all();


    		$used_leaves = Auth::user()->leave_requests()->where(['status' => 1, 'leave_id' => 0])->whereYear('start_date', date('Y'))->get();
    		if ($used_leaves) {
    			$used_days = 0;
    			foreach ($used_leaves as $used_leave) {
    				$startdate = \Carbon\Carbon::parse($used_leave->start_date);

    				$used_days += $startdate->diffInDays($used_leave->end_date) + 1;
    				if ($leave_includes_weekend == 0) {

    					$weekends = 0;
    					$fromDate = $used_leave->start_date;
    					$toDate = $used_leave->end_date;
    					$begin = new \DateTime($used_leave->start_date);
    					$end = new \DateTime($used_leave->end_date);
    					$interval = \DateInterval::createFromDateString('1 day');
    					$period = new \DatePeriod($begin, $interval, $end);
    					foreach ($period as $dt) {
    						$day = $dt->format(" w");
    						if ($day == 0 || $day == 6) {
    							$weekends++;
    						}
    					}
                        // while (date("Y-m-d", $fromDate) != date("Y-m-d", $toDate)) {
                        //     $day = date("w", $fromDate);
                        //     if ($day == 0 || $day == 6) {
                        //         $weekends ++;
                        //     }
                        //     $fromDate = strtotime(date("Y-m-d", $fromDate) . "+1 day");
                        // }
    					$used_days = $used_days - $weekends;
    				} elseif ($leave_includes_holiday == 0) {

    					$fromDate = $used_leave->start_date;
    					$toDate = $used_leave->end_date;
    					$hols = Holiday::whereBetween('date', [$fromDate, $toDate])->count();
    					$used_days = $used_days - $hols;

    				}
    			}
    			$leaveleft = $leavebank - $used_days;
    		}
    		if(Auth::user()->status==1){
    			return ['balance' => $leaveleft, 'paystatus' => 1];
    		}else{

    		}

    	} else {
    		$leave = Leave::find($request->leave_id);
    		$used_leave_days = Auth::user()->leave_requests()->where(['status' => 1, 'leave_id' => $leave->id])->whereYear('start_date', date('Y'))->sum('length');

    		return ['balance' =>  $leave->length-$used_leave_days, 'paystatus' => $leave->with_pay];
    	}
    }



    public function differenceBetweenDays($start_date, $end_date)
    {
    	$company_id = companyId();
    	$lp = LeavePolicy::where('company_id', $company_id)->first();
    	$start = new \DateTime($start_date);
    	$end = new \DateTime($end_date);
        // otherwise the  end date is excluded (bug?)
    	$end->modify('+1 day');

    	$interval = $end->diff($start);

        // total days
    	$days = $interval->days;

        // create an iterateable period of date (P1D equates to 1 day)
    	$period = new \DatePeriod($start, new \DateInterval('P1D'), $end);

        // best stored as array, so you can add more than one
        $holidays = \App\Holiday::where(['status' => 1, 'company_id' => $company_id])->whereYear('date', date('Y'))->pluck('date');//array('2012-09-07');

        foreach ($period as $dt) {
        	$curr = $dt->format('D');

            // substract if Saturday or Sunday
        	if (($curr == 'Sat' || $curr == 'Sun') && $lp->includes_weekend == 0) {
        		$days--;
            } // (optional) for the updated question
            elseif ($holidays->count() > 0 && $lp->includes_holiday == 0) {
            	foreach ($holidays as $holiday) {
            		if ($dt->format('m/d/Y') == $holiday) {
            			$days--;
            		}
            	}


            }
        }


        return $days;
    }













}

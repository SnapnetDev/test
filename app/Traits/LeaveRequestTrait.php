<?php

namespace App\Traits;

use App\LeaveAllowancePayment;
use App\LeaveRequestRecall;
use App\Notifications\ApproveLeaveRequest;
use App\Notifications\LeaveRequestApproved;
use App\Notifications\LeaveRequestPassedStage;
use App\Notifications\LeaveRequestRejected;
use App\Notifications\RecallLeaveRequest;
use App\Notifications\RelieveColleagueOnLeave;
use App\Notifications\RelieveLeaveRequestRejection;
use App\Leave;
use App\LeaveRequest;
use App\LeaveApproval;
use App\LeavePolicy;
use App\Holiday;
use App\PaceSalaryComponent;
use App\Setting;
use App\Shift;
use App\Workflow;
use App\Stage;
use App\LeaveRequestDate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\User;
use Excel;
use Auth;

/**
 *
 */
trait LeaveTrait
{
    public $allowed = ['JPG', 'PNG', 'jpeg', 'png', 'gif', 'jpg', 'pdf', 'docx', 'doc'];

    public function processGet($route, Request $request)
    {
        switch ($route) {
            case 'myrequests':
                return $this->myRequests($request);
                break;
                case 'hrrequests':
                return $this->getLeaveRequests($request);
                break;
            case 'download':
                # code...
                return $this->download($request);
                break;
            case 'get_request':
                return $this->getRequest($request);
                break;
            case 'view_requests':
                return $this->viewRequests($request);
                break;
            case 'delete_request':
                return $this->deleteRequest($request);
                break;
            case 'show_approval':
                return $this->showApproval($request);
                break;
            case 'getdetails':
                return $this->getDetails($request);
                break;
            case 'approvals':
                return $this->approvals($request);
                break;
            case 'department_approvals':
                return $this->departmentApprovals($request);
                break;
            case 'get_leave_length':
                return $this->leaveLength($request);
                break;
            case 'get_entitled_leave_length':
                return $this->entitled_leave_days($request);
                break;
            case 'get_leave_requested_days':
                return $this->leaveDaysRequested($request);
                break;
            case 'graph_report':
                return $this->graphChart($request);
                break;
            case 'get_year_report':
                return $this->getYearReport($request);
                break;
            case 'excel_report':
                return $this->exportForExcelReport($request);
                break;
            case 'relieve_approvals':
                return $this->relieve_approvals($request);
                break;
            case 'leave_spillovers':
                return $this->leaveSpillovers($request);
                break;
            case 'cancel_leave':
                return $this->cancelLeaveRequest($request);
                break;
            case 'delete_leave_plan':
                return $this->deleteLeavePlan($request);
                break;
            case 'dept_leave_plan_calendar':
                return $this->deptLeavePlanCalendar($request);
                break;
            case 'dept_leave_plan_calendar_json':
                return $this->deptLeavePlanCalendarJson($request);
                break;
            case 'dept_leave_day_view':
                return $this->deptLeaveDayView($request);
                break;
            case 'comp_leave_plan_calendar':
                return $this->compLeavePlanCalendar($request);
                break;
            case 'comp_leave_plan_calendar_json':
                return $this->compLeavePlanCalendarJson($request);
                break;
            case 'comp_leave_day_view':
                return $this->compLeaveDayView($request);
                break;
            case 'leave_recall_view':
                return $this->recallLeave($request);
                break;
            case 'view_leave_allowances':
                return $this->view_leave_allowances($request);
                break;



            default:
                # code...
                break;
        }
    }

    public function processPost(Request $request)
    {
        switch ($request->type) {
            case 'save_request':
                return $this->saveRequest($request);
                break;
            case 'save_approval':
                return $this->saveApproval($request);
                break;
            case 'save_relieve_approval':
                return $this->save_relieve_approval($request);
                break;
            case 'save_leave_spillover_modification':
                return $this->saveLeaveSpilloverModification($request);
                break;
            case 'save_leave_plans':
                return $this->saveLeavePlan($request);
                break;
            case 'save_recall_leave':
                return $this->saveRecallLeave($request);
                break;


            default:
                # code...
                break;
        }
    }

    public function recallLeave(Request $request){
        $leave_requests= LeaveRequest::select("leave_requests.*")

            ->whereRaw('? between start_date and end_date', [date('Y-m-d')])->where(['status'=>1])

            ->get();
        if (Auth::user()->role->manages=='all'){
            $leave_requests= LeaveRequest::select("leave_requests.*")

                ->whereRaw('? between start_date and end_date', [date('Y-m-d')])->where(['status'=>1])

                ->get();
        }elseif (Auth::user()->role->manages=='dr'){
            $user=Auth::user();
            $leave_requests= LeaveRequest::select("leave_requests.*")

                ->whereRaw('? between start_date and end_date', [date('Y-m-d')])->where(['status'=>1])
                ->whereHas('user.managers',function($query) use($user){
                    $query->where('employee_manager.manager_id',$user->id);
                })
                ->get();
        }
        $leave_request_recalls=LeaveRequestRecall::where('recaller_id',Auth::user()->id)->get();
        return view('leave.recall',compact('leave_requests','leave_request_recalls'));
    }
    public function saveRecallLeave(Request $request){
        $leave_request=LeaveRequest::find($request->leave_request_id);
        if ($leave_request){
            if ($request->end_date<$leave_request->end_date){
                $new_length=$leave_request->length-($this->differenceBetweenDays($request->end_date, $leave_request->end_date)-1);
                $new_balance=$leave_request->balance+($this->differenceBetweenDays($request->end_date, $leave_request->end_date)-1);
                $leave_request_recall=LeaveRequestRecall::create(['leave_request_id'=>$leave_request->id,'old_date'=>$leave_request->end_date,'new_date'=>date('Y-m-d',strtotime($request->end_date)),'recall_reason'=>$request->recall_reason,'recaller_id'=>Auth::user()->id]);
                $leave_request->update(['end_date'=>date('Y-m-d',strtotime($request->end_date)),'length'=>$new_length,'balance'=>$new_balance]);
                $leave_request->user->notify(new RecallLeaveRequest($leave_request_recall));
                return 'success';
            }else{
                return 'error';
            }




        }else{
            return 'failed';
        }

    }

    public function leaveDaysRequested(Request $request)
    {
        if($request->type=='dates'){
            $dates_and_days=$this->LeaveDaysSelection($request->selection);
           return $length=$dates_and_days['days'];
        }


        return $this->differenceBetweenDays($request->fromdate, $request->todate);
    }

    public function viewRequests(Request $request)
    {
        # code...
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

    public function entitled_leave_days(Request $request){
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
            $used_leave_days = Auth::user()->leave_requests()->where(['status' => 1, 'leave_id' => 0])->whereYear('start_date', date('Y'))->sum('length');
            //those on probation
            // if(Auth::user()->status==0){
            //     $leave_left = 5;
            // }
             $leave_spill_last_year = \App\LeaveSpill::where('from_year', date('Y') - 1)->where('to_year', date('Y'))->where('user_id', Auth::user()->id)->first();
            $date = date('Y-m-d', strtotime('01-' . $request->month));
            $spillover_date = date('Y-m-d', strtotime(date('Y') . '-' . $lp->spillover_month . '-' . $lp->spillover_day));
            if (date('Y-m-d') < $spillover_date) {
                if ($leave_spill_last_year) {
                    $oldleaveleft = $leave_spill_last_year->days - $leave_spill_last_year->used;
                } else {
                    $oldleaveleft = 0;
                }
            } else {
                $oldleaveleft = 0;
            }
            return ['balance' =>   ($oldleaveleft+$leave_left)-$used_leave_days, 'paystatus' => 1];
        }else{
            $leave = Leave::find($request->leave_id);
            $used_leave_days = Auth::user()->leave_requests()->where(['status' => 1, 'leave_id' => $leave->id])->whereYear('start_date', date('Y'))->sum('length');

            return ['balance' =>  $leave->length-$used_leave_days, 'paystatus' => $leave->with_pay];
        }
    }

    public function myRequests(Request $request)
    {


        $company_id = companyId();
        $lp = LeavePolicy::where('company_id', $company_id)->first();
        if (!$lp) {
            return redirect()->back()->with(['status' => 'Leave Policy has not been set up']);
        }


        if (Auth::user()->grade) {
            if (Auth::user()->grade->leave_length > 0) {
                $leavebank = Auth::user()->grade->leave_length;
                $oldleavebank = 0;
            } else {
                $leavebank = $lp->default_length;
                $oldleavebank = 0;
            }

        } else {
            $leavebank = $lp->default_length;
            $oldleavebank = 0;
        }
        if(Auth::user()->status==0 && $lp->uses_casual_leave==1 ){
            $used_days = Auth::user()->leave_requests()->whereYear('start_date', date('Y'))->where('status', 1)->where('leave_id', 0)->sum('length');
            $leavebank = $lp->casual_leave_length-$used_days;
            $oldleavebank = 0;
        }
        $leave_left = $leavebank;
        $oldleaveleft = 0;

        
        $leave_includes_weekend = $lp->includes_weekend;
        $leave_includes_holiday = $lp->includes_holiday;
        $holidays = Holiday::whereYear('date', date('Y-m-d'))->where('company_id', companyId())->get();
        $pending_leave_requests = Auth::user()->leave_requests()->where('status', 0)->whereYear('start_date', date('Y'))->get();
        $leave_requests = Auth::user()->leave_requests()->whereYear('start_date', date('Y'))->get();

        $leaves = Leave::all();
        $leave_plans = \App\LeavePlan::where(['user_id' => Auth::user()->id, 'company_id' => companyId()])->whereYear('start_date', date('Y'))->get();

        $used_leaves = Auth::user()->leave_requests()->where(['status'=>1,'is_spillover'=>0])->whereYear('start_date', date('Y'))->get();
        $used_days= LeaveRequestDate::whereHas('leave_request', function($query){
            $query->where(['is_spillover'=>0,'leave_id'=>0,'status'=>1])
            ->where('leave_requests.user_id',Auth::user()->id);
        })->whereYear('date',date('Y-m-d'))->count();
        
        $used_days_last_year = 0;

        if (date('Y', strtotime(Auth::user()->hiredate)) == date('Y')) {
            //porate for staff employed this year
            $leavebank = $leavebank / 12 * (12 - intval(date('m', strtotime(Auth::user()->hiredate))) + 1);
            $oldleavebank = 0;
        } else {
            // $used_days_last_year=Auth::user()->leave_requests()->whereYear('start_date', date('Y',strtotime('-1 year')))->where('status',1)->sum('length');
            $leave_spill_last_year = \App\LeaveSpill::where('from_year', date('Y') - 1)->where('to_year', date('Y'))->where('user_id', Auth::user()->id)->first();
            $date = date('Y-m-d', strtotime('01-' . $request->month));
            $spillover_date = date('Y-m-d', strtotime(date('Y') . '-' . $lp->spillover_month . '-' . $lp->spillover_day));
            if (date('Y-m-d') < $spillover_date) {
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
        

        return view('leave.myrequests', compact('leavebank', 'holidays', 'leave_requests', 'pending_leave_requests', 'leaves', 'used_days', 'leaveleft', 'oldleaveleft', 'leave_plans','lp'));
    }

    private function download(Request $request)
    {


        $leave_request = LeaveRequest::find($request->leave_request_id);
        if ($leave_request->absence_doc != '') {
            $path = $leave_request->absence_doc;
// 		$path = \App\Document::where('id',$request->id)->value('document');
// 		$path2 = \App\Document::where('id',$request->id)->update(['last_mod_id'=>$request->user()->id]);
            //Put Condition for permission here
            return response()->download(public_path('uploads/leave' . $path));
        } else {
            redirect()->back();
        }
    }

    /**
     * @return array
     */
    public function getlength_and_balance(Request $request)
    {
        $balance=$this->leaveLength($request)['balance'];
        $paystatus=$this->leaveLength($request)['paystatus'];
        $length=$this->differenceBetweenDays($request->start_date,$request->end_date);
    }
    public function LeaveDaysRange($start_date, $end_date)
    {
        $company_id = companyId();
        $lp = LeavePolicy::where('company_id', $company_id)->first();
        $dates=[];
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

            }elseif ($holidays->count() > 0 && $lp->includes_holiday == 0) {
                foreach ($holidays as $holiday) {
                    if ($dt->format('m/d/Y') == $holiday) {
                        $days--;
                    }
                }


            }else{
                $dates[]=$dt->format('Y-m-d');
            }
        }


        return ['days'=>$days,'dates'=>$dates];
    }
    public function LeaveDaysSelection($selection){
        $company_id = companyId();
        $lp = LeavePolicy::where('company_id', $company_id)->first();
        $holidays = \App\Holiday::where(['status' => 1, 'company_id' => $company_id])->whereYear('date', date('Y'))->pluck('date');//array('2012-09-07');
        $dates=[];
        $days=0;
        $dates_array= explode(",", $selection);
        //sort
        usort($dates_array,array("ClassName","cmp"));
        // usort($dates_array,"static::cmp");

        foreach($dates_array as $date_a){
            $ld = new \DateTime($date_a);
            $curr = $ld->format('D');
            if ((($curr == 'Sat' || $curr == 'Sun') && $lp->includes_weekend == 0) 
            || (($holidays->count() > 0 && $lp->includes_holiday == 0)&& in_array($ld->format('Y-m-d'),$holidays))) {
               

            }else{
                $days++;
                $dates[]=$ld->format('Y-m-d');
            }
        }
        
        return ['days'=>$days,'dates'=>$dates];

    }
    private static function cmp($a, $b)
    {
        if ($a == $b) {
            return 0;
        }
        return ($a < $b) ? -1 : 1;
    }


    public function saveRequest(Request $request)
    {

        // $leave_workflow_id=Setting::where('name','leave_workflow')->first()->value;
        
        //check if pending or approved application with similar dates exist
        $leave_request=LeaveRequest::where(['start_date' => date('Y-m-d', strtotime($request->start_date)), 'end_date' => date('Y-m-d', strtotime($request->end_date))])->first();
        if($leave_request){
            return "failed";
        }
        if ($request->file('absence_doc')) {
            $mime = $request->file('absence_doc')->getClientOriginalextension();
            if (!(in_array($mime, $this->allowed))): throw new \Exception("Invalid File Type"); endif;
        }
        $company_id = companyId();
        $lp = LeavePolicy::where('company_id', $company_id)->first();
// 		$leave_request=LeaveRequest::create(['leave_id'=>$request->leave_id,'user_id'=>Auth::user()->id,'start_date'=>date('Y-m-d',strtotime($request->start_date)),'end_date'=>date('Y-m-d',strtotime($request->end_date)),'reason'=>$request->reason,'workflow_id'=>$leave_workflow_id,'paystatus'=>$request->paystatus,'status'=>0,'length'=>$request->leavelength,'company_id'=>$company_id,'replacement_id'=>$request->replacement,'balance'=>$request->leavelength]);
        //check days selection type
        if($request->days_selection_type=='range'){
            $start_date=date('Y-m-d', strtotime($request->start_date));
            $end_date=date('Y-m-d', strtotime($request->end_date));
            $dates_and_days=$this->LeaveDaysRange($request->start_date,$request->end_date); 
            $length=$dates_and_days['days'];

       

        //range
        }elseif($request->days_selection_type=='dates'){
            $dates_and_days=$this->LeaveDaysSelection($request->selection);
            $start_date = date('Y-m-d', strtotime($dates_and_days['dates'][0]));
            $end_date = date('Y-m-d', strtotime(end($dates_and_days['dates'])));
            $length=$dates_and_days['days'];

        //selection
        }

        $leave_request = LeaveRequest::create(['leave_id' => $request->leave_id, 'user_id' => Auth::user()->id, 'start_date' => $start_date, 'end_date' => $end_date, 'reason' => $request->reason, 'workflow_id' => $lp->workflow_id, 'paystatus' => $request->paystatus, 'status' => 0, 'length' => $length, 'company_id' => $company_id, 'replacement_id' => $request->replacement, 'balance' => $request->leaveremaining, 'leave_bank' => $request->length]);
        
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
                }elseif($stage->role->manages == 'ss') {
                    foreach ($leave_request->user->plmanager->managers as $manager) {
                        $manager->notify(new ApproveLeaveRequest($leave_request));
                    }
                }elseif ($stage->role->manages == 'all') {
                    foreach ($stage->role->users as $user) {
                        $user->notify(new ApproveLeaveRequest($leave_request));
                    }
                } elseif ($stage->role->manages == 'none') {
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


        return 'success';
    }

    public function saveLeavePlan(Request $request)
    {


        $company_id = companyId();
        $lp = LeavePolicy::where('company_id', $company_id)->first();
        if ($request->input('start_date') !== null) {
            $no_of_periods = count($request->input('start_date'));
        }
        $length = 0;
        if ($no_of_periods > 0) {
            for ($i = 0; $i < $no_of_periods; $i++) {
                $start_date = date('Y-m-d', strtotime($request->start_date[$i]));
                $end_date = date('Y-m-d', strtotime($request->end_date[$i]));
                $length = $this->differenceBetweenDays($start_date, $end_date);
                $leave_plan = \App\LeavePlan::create(['user_id' => Auth::user()->id, 'start_date' => $start_date, 'end_date' => $end_date, 'length' => $length, 'company_id' => $company_id]);
            }
        }


        return 'success';
    }

    public function deleteLeavePlan(Request $request)
    {
        $id = $request->leave_id;
        $leave_plan = \App\LeavePlan::find($id);
        if ($leave_plan) {

            $leave_plan->delete();
            return 'success';

        } else {
            return 'failed';
        }
    }

    public function updateRequest(Request $request)
    {
        $leave_workflow_id = Setting::where('name', 'leave_workflow')->first()->value;
        // $approved_leave_request_exist=LeaveRequest::find($request->leave_request_id)->approvals()->where(function ($query) {
        //               $query->where('status',1);

        //           })
        //           ->get();
        $leave_request_approval = LeaveRequest::find($request->leave_request_id)->approvals()->where('status', 0)->first();
        $leave_request_approval->status = 2;
        $leave_request_approval->save();


        $leave_request = LeaveRequest::find($request->leave_request_id)->update(['start_date' => date('Y-m-d', strtotime($request->start_date)), 'end_date' => date('Y-m-d', strtotime($request->end_date)), 'status' => 0, 'replacement_id' => $request->replacement]);

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
                }elseif($stage->role->manages == 'ss') {

                    foreach ($leave_request->user->plmanager->managers as $manager) {
                        $manager->notify(new ApproveLeaveRequest($leave_request));
                    }
                } elseif ($stage->role->manages == 'all') {
                    foreach ($stage->role->users as $user) {
                        $user->notify(new ApproveLeaveRequest($leave_request));
                    }
                } elseif ($stage->role->manages == 'none') {
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


        return 'success';



    }

    function update()
    {
        $id = $this->request->id;
        $model = LeaveRequest::find($id);

        $model->end_date = $this->request->end_date;
        $model->start_date = $this->request->start_date;

        $model->save();

        return [
            'message' => 'Leave Request saved',
            'data' => $model
        ];
    }


    public function deleteRequest(Request $request)
    {
        $lr = LeaveRequest::find($request->leave_request_id);
        if ($lr) {

            $lr->delete();
            return 'success';
        }
    }

    public function showApproval(Request $request)
    {
        $leave_request = LeaveRequest::find($request->leave_request_id);

        return view('leave.approval', ['leave_request' => $leave_request]);
    }

    public function approvals(Request $request)
    {
        $user = Auth::user();

        $user_approvals = $this->userApprovals($user);
        $dr_approvals = $this->getDRLeaveApprovals($user);
        $ss_approvals = $this->getSSLeaveApprovals($user);
        $role_approvals = $this->roleApprovals($user);
        $group_approvals = $this->groupApprovals($user);

        return view('leave.approvals', compact('user_approvals', 'role_approvals', 'group_approvals', 'dr_approvals','ss_approvals'));
    }

    public function departmentApprovals(Request $request)
    {
        $user = Auth::user();
        $dapprovals = LeaveApproval::whereHas('leave_request.user.job.department', function ($query) use ($user) {
            $query->where('leave_requests.user_id', '!=', $user->id)
                ->where('departments.manager_id', $user->id);

        })
            ->where('status', 0)->orderBy('id', 'asc')->get();
        return view('leave.department_approvals', compact('dapprovals'));
    }

    public function userApprovals(User $user)
    {
        return $las = LeaveApproval::whereHas('stage.user', function ($query) use ($user) {
            $query->where('users.id', $user->id);

        })
            ->where('status', 0)->orderBy('id', 'asc')->get();

    }

    public function getDRLeaveApprovals(User $user)
    {
        return Auth::user()->getDRLeaveApprovals();
        // 	return $las = LeaveApproval::whereHas('stage.role.users',function($query) use($user){
        // 	$query->where('users.id',$user->id);
        // })

        //  ->where('status',0)->orderBy('id','desc')->get();

    }
    public function getSSLeaveApprovals(User $user)
    {
        return Auth::user()->getSSLeaveApprovals();
        // 	return $las = LeaveApproval::whereHas('stage.role.users',function($query) use($user){
        // 	$query->where('users.id',$user->id);
        // })

        //  ->where('status',0)->orderBy('id','desc')->get();

    }

    public function roleApprovals(User $user)
    {
        return $las = LeaveApproval::whereHas('stage.role', function ($query) use ($user) {
            $query->where('manages', '!=', 'dr')
                ->where('manages', '!=', 'ss')
                ->where('roles.id', $user->role_id);
        })->where('status', 0)->orderBy('id', 'asc')->get();
    }

    public function groupApprovals(User $user)
    {
        return $las = LeaveApproval::whereHas('stage.group.users', function ($query) use ($user) {
            $query->where('users.id', $user->id);
        })
            ->where('status', 0)->orderBy('id', 'asc')->get();

    }

    public function saveApproval(Request $request)
    {
        $leave_approval = LeaveApproval::find($request->leave_approval_id);
        $leave_approval->comments = $request->comment;
        if ($request->approval == 1) {
            $leave_approval->status = 1;
            $leave_approval->approver_id = Auth::user()->id;
            $leave_approval->save();
            // $logmsg=$leave_approval->document->filename.' was approved in the '.$leave_approval->stage->name.' in the '.$leave_approval->stage->workflow->name;
            // $this->saveLog('info','App\Review',$leave_approval->id,'leave_approvals',$logmsg,Auth::user()->id);
            $newposition = $leave_approval->stage->position + 1;
            $nextstage = Stage::where(['workflow_id' => $leave_approval->stage->workflow->id, 'position' => $newposition])->first();
            // return $review->stage->position+1;
            // return $nextstage;

            if ($nextstage){

                $newleave_approval = new LeaveApproval();
                $newleave_approval->stage_id = $nextstage->id;
                $newleave_approval->leave_request_id = $leave_approval->leave_request->id;
                $newleave_approval->status = 0;
                $newleave_approval->save();
                // $logmsg='New review process started for '.$newleave_approval->document->filename.' in the '.$newleave_approval->stage->workflow->name;
                // $this->saveLog('info','App\Review',$leave_approval->id,'reviews',$logmsg,Auth::user()->id);
                if ($nextstage->type == 1) {

                    // return $nextstage->type . '-2--' . 'all';

                    $nextstage->user->notify(new ApproveLeaveRequest($newleave_approval->leave_request));
                } elseif ($nextstage->type == 2) {
                    // return $nextstage->role->manages . '1---' . 'all' . json_encode($leave_approval->leave_request->user->managers);
                    if ($nextstage->role->manages == 'dr') {

                        // return $nextstage->role->manage . '---' . json_encode($nextstage->role->users);

                        foreach ($leave_approval->leave_request->user->managers as $manager) {
                            $manager->notify(new ApproveLeaveRequest($newleave_approval->leave_request));
                        }
                        
                    }elseif($nextstage->role->manages == 'ss') {
                        $ss=$leave_approval->leave_request->user->plmanager->managers;
                        if(!$ss){
                             $leave_approval->leave_request->status = 1;
                                    $leave_approval->leave_request->save();

                                    $leave_approval->leave_request->user->notify(new LeaveRequestApproved($leave_approval->stage, $leave_approval));
                                    $this->prepareLeaveAllowance($leave_approval->leave_request->user);
                        }
                        
                        foreach ($leave_approval->leave_request->user->plmanager->managers as $manager) {
                            $manager->notify(new ApproveLeaveRequest($newleave_approval->leave_request));
                        }
                    } elseif ($nextstage->role->manages == 'all') {
                        // return 'all.';

                        // return $nextstage->role->manage . '---' . json_encode($nextstage->role->users);

                        foreach ($nextstage->role->users as $user) {
                            $user->notify(new ApproveLeaveRequest($newleave_approval->leave_request));
                        }
                    } elseif ($nextstage->role->manages == 'none') {
                        foreach ($nextstage->role->users as $user) {
                            $user->notify(new ApproveLeaveRequest($newleave_approval->leave_request));
                        }
                    }
                } elseif ($nextstage->type == 3) {
                    //1-user
                    //2-role
                    //3-groups
                    // return 'not_blank';

                    foreach ($nextstage->group->users as $user) {
                        $user->notify(new ApproveLeaveRequest($newleave_approval->leave_request));
                    }
                } else {
                    // return 'blank';
                }

                $leave_approval->leave_request->user->notify(new LeaveRequestPassedStage($leave_approval, $leave_approval->stage, $newleave_approval->stage));
            } else {
                // return 'blank2';
                $leave_approval->leave_request->status = 1;
                $leave_approval->leave_request->save();

                $leave_approval->leave_request->user->notify(new LeaveRequestApproved($leave_approval->stage, $leave_approval));
                $this->prepareLeaveAllowance($leave_approval->leave_request->user);
               //code for leave shift added to userdaily shift for leave period
                /* $shift=1;
                $leave_shift=Setting::where('name','leave_shift')->where('company_id',companyId())->first();
                if ($leave_shift){
                    $shift=$leave_shift->value;
                }
                $shift=Shift::find($shift);
                $leave_request=$leave_approval->leave_request;
                $user_id=$leave_request->user_id;
                $shift_dates=[];
                $sdate=Carbon::parse($leave_request->start_date);
                $edate=Carbon::parse($leave_request->end_date);
                $days=$sdate->diffInDays($edate);
                for ($i = 0; $i <=$days ; $i++) {
                    $form = $sdate->format('Y-m-d');
                    $sdate = $sdate->addDay();
                    $shift_dates[] = $form;
                }
                if ($shift) {
                    foreach ($shift_dates as $date){
                        if ($shift->start_time > $shift->end_time) {
                            $ends = date('Y-m-d H:i:s', strtotime($date . $shift->end_time . '+1 day'));
                        } else {
                            $ends = date('Y-m-d H:i:s', strtotime($date . $shift->end_time));
                        }
                        $starts = date('Y-m-d H:i:s', strtotime($date . $shift->start_time));
                        \App\UserDailyShift::updateOrCreate(['user_id' => $user_id, 'sdate' => $date],
                            ['user_id' => $user_id, 'shift_id' => $shift->id, 'starts' => $starts, 'ends' => $ends, 'sdate' => $date]);
                    }
                }*/
            }


        } elseif ($request->approval == 2) {
            // return 'blank3';
            $leave_approval->status = 2;
            $leave_approval->comments = $request->comment;
            $leave_approval->approver_id = Auth::user()->id;
            $leave_approval->save();
            // $logmsg=$leave_approval->document->filename.' was rejected in the '.$leave_approval->stage->name.' in the '.$leave_approval->stage->workflow->name;
            // $this->saveLog('info','App\Review',$leave_approval->id,'leave_approvals',$logmsg,Auth::user()->id);
            $leave_approval->leave_request->status = 2;
            $leave_approval->leave_request->save();
            $leave_approval->leave_request->user->notify(new LeaveRequestRejected($leave_approval->stage, $leave_approval));
            // return redirect()->route('documents.mypendingleave_approvals')->with(['success'=>'Document Reviewed Successfully']);
        }

        return 'success';


        // return redirect()->route('documents.mypendingreviews')->with(['success'=>'Leave Request Approved Successfully']);
    }

    public function getDetails(Request $request)
    {
        $leave_request = LeaveRequest::where('id', $request->leave_request_id)->get()->first();
        return view('leave.partials.leaveDetails', compact('leave_request'));
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

    public function getYearReport(Request $request)
    {

        $data = [];

        $data['labels'] = $months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        $status = ['approved', 'pending', 'rejected'];
        // $leaves=Leave::where('company_id',comapnyId())->get();
        $leaves = Leave::all();
        if ($request->filled('type')) {
            $akey = array_search($request->type, $status);
            $data['datasets'][0]['label'] = 'Annual Leave';
            foreach ($months as $key => $month) {
                $data['datasets'][0]['data'][$key] = LeaveRequest::where('company_id', companyId())->where('status', $akey)->where('leave_id', 0)->where(function ($query) use ($request, $key) {
                    $query->whereYear('start_date', $request->year)
                        ->whereMonth('start_date', '=', $key + 1);
                })
                    ->orWhere(function ($query) use ($request, $key) {
                        $query->whereYear('start_date', $request->year)
                            ->where('start_date', '=', $key + 1);
                    })->count();
            }


            foreach ($leaves as $key => $leave) {
                $data['datasets'][$leave->id]['label'] = $leave->name;
                foreach ($months as $key => $month) {
                    $data['datasets'][$leave->id]['data'][$key] = LeaveRequest::where('company_id', companyId())->where('status', $akey)->where('leave_id', $leave->id)->where(function ($query) use ($request, $key) {
                        $query->whereYear('start_date', $request->year)
                            ->whereMonth('start_date', '=', $key + 1);
                    })
                        ->orWhere(function ($query) use ($request, $key) {
                            $query->whereYear('start_date', $request->year)
                                ->where('start_date', '=', $key + 1);
                        })->count();
                }


            }
        } else {
            $data['datasets'][0]['label'] = 'Annual Leave';
            foreach ($months as $key => $month) {
                $data['datasets'][0]['data'][$key] = LeaveRequest::where('company_id', companyId())->where('leave_id', 0)->where(function ($query) use ($request, $key) {
                    $query->whereYear('start_date', $request->year)
                        ->whereMonth('start_date', '=', $key + 1);
                })
                    ->orWhere(function ($query) use ($request, $key) {
                        $query->whereYear('start_date', $request->year)
                            ->where('start_date', '=', $key + 1);
                    })->count();
            }


            foreach ($leaves as $key => $leave) {
                $data['datasets'][$leave->id]['label'] = $leave->name;
                foreach ($months as $key => $month) {
                    $data['datasets'][$leave->id]['data'][$key] = LeaveRequest::where('company_id', companyId())->where('leave_id', $leave->id)->where(function ($query) use ($request, $key) {
                        $query->whereYear('start_date', $request->year)
                            ->whereMonth('start_date', '=', $key + 1);
                    })
                        ->orWhere(function ($query) use ($request, $key) {
                            $query->whereYear('start_date', $request->year)
                                ->where('start_date', '=', $key + 1);
                        })->count();
                }


            }
        }


        return $data;
    }

    public function graphChart(Request $request)
    {
        return view('leave.graph_report');
    }

    public function exportForExcelReport(Request $request)
    {

        $data = [];

        $data['labels'] = $months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        $status = ['approved', 'pending', 'rejected'];
        // $leaves=Leave::where('company_id',comapnyId())->get();

        if ($request->filled('type')) {
            $akey = array_search($request->type, $status);
            return \Excel::create("leave request export", function ($excel) use ($months, $request, $akey) {

                foreach ($months as $key => $month) {

                    $leave_requests = LeaveRequest::where('company_id', companyId())->where('status', $akey)->where(function ($query) use ($request, $key) {
                        $query->whereYear('start_date', $request->year)
                            ->whereMonth('start_date', '=', $key + 1);
                    })
                        ->orWhere(function ($query) use ($request, $key) {
                            $query->whereYear('start_date', $request->year)
                                ->where('start_date', '=', $key + 1);
                        })->get();
                    $excel->sheet($month, function ($sheet) use ($leave_requests, $month) {

                        $sheet->loadView('leave.partials.report', compact('leave_requests', 'month'))->setOrientation('landscape');
                    });
                }
                $leave_requests = LeaveRequest::where('company_id', companyId())->where('status', $akey)->where(function ($query) use ($request, $key) {
                    $query->whereYear('start_date', $request->year);
                })
                    ->orWhere(function ($query) use ($request, $key) {
                        $query->whereYear('start_date', $request->year);
                    })->get();
                $excel->sheet($request->year, function ($sheet) use ($leave_requests, $month) {

                    $sheet->loadView('leave.partials.report', compact('leave_requests', 'month'))->setOrientation('landscape');
                });

            })->export('xlsx');
        } else {
            return \Excel::create("leave request export", function ($excel) use ($months, $request) {

                foreach ($months as $key => $month) {

                    $leave_requests = LeaveRequest::where('company_id', companyId())->where(function ($query) use ($request, $key) {
                        $query->whereYear('start_date', $request->year)
                            ->whereMonth('start_date', '=', $key + 1);
                    })
                        ->orWhere(function ($query) use ($request, $key) {
                            $query->whereYear('start_date', $request->year)
                                ->where('start_date', '=', $key + 1);
                        })->get();
                    $excel->sheet($month, function ($sheet) use ($leave_requests, $month) {

                        $sheet->loadView('leave.partials.report', compact('leave_requests', 'month'))->setOrientation('landscape');
                    });
                }
                $leave_requests = LeaveRequest::where('company_id', companyId())->where(function ($query) use ($request, $key) {
                    $query->whereYear('start_date', $request->year);
                })
                    ->orWhere(function ($query) use ($request, $key) {
                        $query->whereYear('start_date', $request->year);
                    })->get();
                $excel->sheet($request->year, function ($sheet) use ($leave_requests, $month) {

                    $sheet->loadView('leave.partials.report', compact('leave_requests', 'month'))->setOrientation('landscape');
                });

            })->export('xlsx');
        }


    }

    public function relieve_approvals(Request $request)
    {
        $leave_requests = LeaveRequest::where('replacement_id', \Auth::user()->id)->where('relieve_approved', 0)->get();
        return view('leave.relieve_approvals', compact('leave_requests'));
    }

    public function save_relieve_approval(Request $request)
    {
        $leave_request = LeaveRequest::find($request->leave_request_id);
        $leave_request->relieve_approved = $request->approval;
        $leave_request->relieve_comment = $request->comment;
        $leave_request->relieve_approved_at = date('Y-m-d H:i:s');
        $leave_request->save();
        if ($leave_request->approvals) {
                return 'success';

        } elseif ($request->approval == 2) {
            $leave_request->user->notify(new RelieveLeaveRequestRejection($leave_request));
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
                }elseif($stage->role->manages == 'ss') {

                    foreach ($leave_request->user->plmanager->managers as $manager) {
                        $manager->notify(new ApproveLeaveRequest($leave_request));
                    }
                }elseif ($stage->role->manages == 'all') {
                    foreach ($stage->role->users as $user) {
                        $user->notify(new ApproveLeaveRequest($leave_request));
                    }
                } elseif ($stage->role->manages == 'none') {
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
    }

    public function leaveSpillovers(Request $request)
    {

        $spillovers = \App\LeaveSpill::where('to_year', currentYear())->where('company_id', companyId())->get();
        return view('leave.leave_spills', compact('spillovers'));
    }

    public function saveLeaveSpilloverModification(Request $request)
    {
        $spillover = \App\LeaveSpill::find($request->leavespill_id);
        if ($spillover) {
            $spillover->update(['days' => $request->newdays, 'modified_by' => \Auth::user()->id, 'modification_reason' => $request->comment]);
        }

        return 'success';

    }

    public function cancelLeaveRequest(Request $request)
    {
        $id = $request->id;
        $leave_request = \App\LeaveRequest::find($id);
        if ($leave_request && $leave_request->status == 0) {
            if ($leave_request->leave_approvals) {
                foreach ($leave_request->leave_approvals as $approval) {
                    $approval->delete();
                }
            }
            $leave_request->delete();
            return 'Success';

        } else {
            return 'Cannot cancel leave!';
        }
    }

    public function deptLeavePlanCalendarJson(Request $request)
    {


        $user = Auth::user();
        $dispemp = [];
        $startdate = $request->start;
        $enddate = $request->end;
        $leave_plans = \App\LeavePlan::where(function ($query) use ($startdate, $enddate) {
            $query->whereBetween('start_date', [$startdate, $enddate])
                ->orWhereBetween('end_date', [$startdate, $enddate]);
        })->whereHas('user.job.department', function ($query) use ($user) {
            $query->where('departments.manager_id', $user->id);

        })->get();

        $colours = ['#67a8e4', '#f32f53', '#77c949', '#FFC1CC', '#ffbb44', '#f32f53', '#67a8e4'];
        $i = 0;
        foreach ($leave_plans as $leave_plan) {
            $begin = new \DateTime($leave_plan->start_date);
            $end = new \DateTime($leave_plan->end_date . '+1 days');
            $col = $colours[$i];
            $interval = \DateInterval::createFromDateString('1 day');
            $period = new \DatePeriod($begin, $interval, $end);

            foreach ($period as $dt) {

                $dispemp[] = [
                    'title' => $leave_plan->user->name,
                    'start' => $dt->format(" Y-m-d") . 'T' . '00:00:00',
                    'end' => $dt->format(" Y-m-d") . 'T' . '11:59:59',
                    'color' => '#67a8e4',
                    'id' => $leave_plan->id];
            }

            $i++;
        }

        if (isset($dispemp)):
            return response()->json($dispemp);
        else:
            $dispemp = ['title' => 'Nil', 'start' => date('Y-m-d')];
            return response()->json($dispemp);
        endif;


    }

    public function deptLeavePlanCalendar(Request $request)
    {
        return view('leave.dept_leave_plan_calendar');
    }

    public function deptLeaveDayView(Request $request)
    {
        $user = Auth::user();
        $leave_plans = \App\LeavePlan::whereRaw('? between start_date and end_date', [$request->date])
            ->whereHas('user.job.department', function ($query) use ($user) {
                $query->where('departments.manager_id', $user->id);

            })->get();
        return view('leave.partials.dept_leave_plan_day_list', compact('leave_plans'));
    }

    public function compLeavePlanCalendarJson(Request $request)
    {


        $user = Auth::user();
        $dispemp = [];
        $startdate = $request->start;
        $enddate = $request->end;
        $leave_plans = \App\LeavePlan::where(function ($query) use ($startdate, $enddate) {
            $query->whereBetween('start_date', [$startdate, $enddate])
                ->orWhereBetween('end_date', [$startdate, $enddate]);
        })->where(['company_id' => companyId()])->get();

        $colours = ['#67a8e4', '#f32f53', '#77c949', '#FFC1CC', '#ffbb44', '#f32f53', '#67a8e4'];
        $i = 0;
        foreach ($leave_plans as $leave_plan) {
            $begin = new \DateTime($leave_plan->start_date);
            $end = new \DateTime($leave_plan->end_date . '+1 days');
            $col = $colours[$i];
            $interval = \DateInterval::createFromDateString('1 day');
            $period = new \DatePeriod($begin, $interval, $end);

            foreach ($period as $dt) {

                $dispemp[] = [
                    'title' => $leave_plan->user->name,
                    'start' => $dt->format(" Y-m-d") . 'T' . '00:00:00',
                    'end' => $dt->format(" Y-m-d") . 'T' . '11:59:59',
                    'color' => '#67a8e4',
                    'id' => $leave_plan->id];
            }

            $i++;
        }

        if (isset($dispemp)):
            return response()->json($dispemp);
        else:
            $dispemp = ['title' => 'Nil', 'start' => date('Y-m-d')];
            return response()->json($dispemp);
        endif;


    }

    public function compLeavePlanCalendar(Request $request)
    {
        return view('leave.comp_leave_plan_calendar');
    }

    public function compLeaveDayView(Request $request)
    {
        $user = Auth::user();
        $leave_plans = \App\LeavePlan::whereRaw('? between start_date and end_date', [$request->date])
            ->where(['company_id' => companyId()])->get();
        return view('leave.partials.comp_leave_plan_day_list', compact('leave_plans'));
    }

    public function view_leave_allowances(Request $request)
    {
        if ($request->year==''){
            $year=date('Y');
            $leave_allowance_payments=LeaveAllowancePayment::where(['year'=>$year])->get();
        }else{
            $year=$request->year;
            $leave_allowance_payments=LeaveAllowancePayment::where(['year'=>$year])->get();
        }
         $years=LeaveAllowancePayment::select('year')->distinct('year')->get();
        return view('leave.leave_payment', compact('leave_allowance_payments','years','year'));

    }

    public function prepareLeaveAllowance($user)
    {
        $check_leave_payment=LeaveAllowancePayment::where(['user_id'=>$user->id,'year'=>date('Y')])->first();
        if(!$check_leave_payment){

//           $salary_category=PaceSalaryComponent::whereHas('pace_salary_category',function ($query) use($user){
//               $query->where('pace_salary_categories.id',$user->id);
//           })->where('constant','leave_allowance_payment')->first();
//           if ($salary_category){
//               $leave_payment=LeaveAllowancePayment::create(['user_id'=>$user->id,'year'=>date('Y'),'amount'=>$salary_category->amount,'disbursed'=>0,'approved'=>0]);
//           }
            if ($user->payroll_type=='project'){

                $amount=$user->project_salary_category->basic_salary*($user->project_salary_category->tax_rate);
                $leave_payment=LeaveAllowancePayment::create(['user_id'=>$user->id,'year'=>date('Y'),'amount'=>$amount,'disbursed'=>0,'approved'=>0]);
            }elseif ($user->payroll_type=='office' && $user->grade){
                $amount=$user->grade->basic_salary*0.16;
                $leave_payment=LeaveAllowancePayment::create(['user_id'=>$user->id,'year'=>date('Y'),'amount'=>$amount,'disbursed'=>0,'approved'=>0]);
            }

        }
    }
    
    public function getLeaveRequests(Request $request){
        $year=$request->year;
        if ($request->year==''){
            $year=date('Y');
        }
        $leave_requests= LeaveRequest::whereYear('created_at', $year)->get();
        $approved_requests=LeaveRequest::whereYear('created_at', $year)->where('status',1)->count();
        $pending_requests=LeaveRequest::whereYear('created_at', $year)->where('status',0)->count();
        $rejected_requests=LeaveRequest::whereYear('created_at', $year)->where('status',2)->count();
        return view('leave.hrrequests', compact('leave_requests','approved_requests','pending_requests','rejected_requests'));
        
    }


}

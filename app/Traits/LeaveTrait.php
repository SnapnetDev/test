<?php

namespace App\Traits;

use App\Company;
use App\LeaveAllowancePayment;
use App\LeaveBank;
use App\LeaveRequestRecall;
use App\Notifications\ApproveLeaveRequest;
use App\Notifications\LeaveRequestApproved;
use App\Notifications\LeaveRequestApprovedOthers;
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
use App\LeaveRequestDate;
use App\PaceSalaryComponent;
use App\Setting;
use App\Shift;
use App\SpecificSalaryComponent;
use App\SpecificSalaryComponentType;
use App\Workflow;
use App\Stage;
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

    public function adjustLeave(Request $request)
    {
        $leave_requests = LeaveRequest::select("leave_requests.*")
            ->whereRaw('? between start_date and end_date', [date('Y-m-d')])->where(['status' => 1])
            ->get();
        if (Auth::user()->role->manages == 'all') {
            $leave_requests = LeaveRequest::select("leave_requests.*")
                ->whereRaw('? between start_date and end_date', [date('Y-m-d')])->where(['status' => 1])
                ->get();
        } elseif (Auth::user()->role->manages == 'dr') {
            $user = Auth::user();
            $leave_requests = LeaveRequest::select("leave_requests.*")
                ->whereRaw('? between start_date and end_date', [date('Y-m-d')])->where(['status' => 1])
                ->whereHas('user.managers', function ($query) use ($user) {
                    $query->where('employee_manager.manager_id', $user->id);
                })
                ->get();
        }
        $leave_request_adjustments = LeaveRequestAdjustment::all();
        return view('leave.adjust', compact('leave_requests', 'leave_request_adjustments'));
    }

    public function saveAdjustLeave(Request $request)
    {
        $leave_request = LeaveRequest::find($request->leave_request_id);
        $selection = $leave_request->dates->pluck('date')->toArray();
        if ($leave_request) {
            $new_selection = array_filter($selection, function ($item, $key) use ($request) {
                //print_r($key);
                return $item <= $request->end_date;
            }, ARRAY_FILTER_USE_BOTH);
            $dates_and_days = $this->LeaveDaysSelection($new_selection);
            if ($request->end_date < $leave_request->end_date) {
                $new_length = $dates_and_days['days'];
                $new_balance = $leave_request->balance + ($leave_request->length - $new_length);
                $leave_request_recall = LeaveRequestRecall::create(['leave_request_id' => $leave_request->id, 'old_date' => $leave_request->end_date, 'new_date' => date('Y-m-d', strtotime($request->end_date)), 'recall_reason' => $request->recall_reason, 'recaller_id' => Auth::user()->id]);
                $leave_request->update(['end_date' => date('Y-m-d', strtotime($request->end_date)), 'length' => $dates_and_days['days'], 'balance' => $new_balance]);
                $leave_dates_to_be_removed = $leave_request->dates->whereNotIn('date', $new_selection);
                foreach ($leave_dates_to_be_removed as $date_removed) {
                    $date_removed->delete();
                }
                $leave_request->user->notify(new RecallLeaveRequest($leave_request_recall));
                return 'success';
            } else {
                return 'error';
            }


        } else {
            return 'failed';
        }

    }

    public function recallLeave(Request $request)
    {
        $leave_requests = LeaveRequest::select("leave_requests.*")
            ->whereRaw('? between start_date and end_date', [date('Y-m-d')])->where(['status' => 1])
            ->where('length', '!=', 1)
            ->get();
        if (Auth::user()->role->manages == 'all') {
            $leave_requests = LeaveRequest::select("leave_requests.*")
                ->whereRaw('? between start_date and end_date', [date('Y-m-d')])->where(['status' => 1])
                ->where('length', '!=', 1)
                ->get();
        } elseif (Auth::user()->role->manages == 'dr') {
            $user = Auth::user();
            $leave_requests = LeaveRequest::select("leave_requests.*")
                ->where('length', '!=', 1)
                ->whereRaw('? between start_date and end_date', [date('Y-m-d')])->where(['status' => 1])
                ->whereHas('user.managers', function ($query) use ($user) {
                    $query->where('employee_manager.manager_id', $user->id);
                })
                ->get();
        }
        $leave_request_recalls = LeaveRequestRecall::all();
        return view('leave.recall', compact('leave_requests', 'leave_request_recalls'));
    }

    public function saveRecallLeave(Request $request)
    {
        $leave_request = LeaveRequest::find($request->leave_request_id);
        $selection = $leave_request->dates->pluck('date')->toArray();
        if ($leave_request) {
            $new_selection = array_filter($selection, function ($item, $key) use ($request) {
                //print_r($key);
                return $item <= $request->end_date;
            }, ARRAY_FILTER_USE_BOTH);
            $dates_and_days = $this->LeaveDaysSelection($new_selection);
            if ($request->end_date < $leave_request->end_date) {
                $new_length = $dates_and_days['days'];
                $new_balance = $leave_request->balance + ($leave_request->length - $new_length);
                $leave_request_recall = LeaveRequestRecall::create(['leave_request_id' => $leave_request->id, 'old_date' => $leave_request->end_date, 'new_date' => date('Y-m-d', strtotime($request->end_date)), 'recall_reason' => $request->recall_reason, 'recaller_id' => Auth::user()->id]);
                $leave_request->update(['end_date' => date('Y-m-d', strtotime($request->end_date)), 'length' => $dates_and_days['days'], 'balance' => $new_balance]);
                $leave_dates_to_be_removed = $leave_request->dates->whereNotIn('date', $new_selection);
                foreach ($leave_dates_to_be_removed as $date_removed) {
                    $date_removed->delete();
                }
                $leave_request->user->notify(new RecallLeaveRequest($leave_request_recall));
                return 'success';
            } else {
                return 'error';
            }


        } else {
            return 'failed';
        }

    }

    public function leaveDaysRequested(Request $request)
    {

        if ($request->type == 'dates') {
            $dates_and_days = $this->LeaveDaysSelection($request->selection);
            return $length = $dates_and_days['days'];
        } elseif ($request->type == 'range') {
            $dates_and_days = $this->LeaveDaysRange($request->fromdate, $request->todate);
            return $length = $dates_and_days['days'];
        }

        // return $this->differenceBetweenDays($request->fromdate, $request->todate);
    }

    public function viewRequests(Request $request)
    {
        # code...
    }


    public function entitled_leave_days(Request $request)
    {
        $user = Auth::user();
        $year = $request->year;
        if ($request->year == '') {
            $year = date('Y');
        }
        $company_id = companyId();
        $lp = LeavePolicy::where('company_id', $company_id)->first();
        //check if the leave is a spillover leave
        if ($request->is_spillover == 'yes' && $request->leave_id == 0) {
            $leave_left = 0;
            //check if leave policy allows spillover leave
            if ($lp->uses_spillover) {
                $leave_spill_last_year = \App\LeaveSpill::where('from_year', $year - 1)->where('to_year', $year)->where('user_id', $user->id)->first();
                if($leave_spill_last_year){
                    $spillover_date = date('Y-m-d', strtotime($year . '-' . $lp->spillover_month . '-' . $lp->spillover_day));
                    //check if it is passed the allowed spill over usage day
                    if (date('Y-m-d') < $spillover_date) {
                        if ($leave_spill_last_year) {
                            $leave_left = $leave_spill_last_year->days - $leave_spill_last_year->used;
                        } else {
                            $leave_left = 0;
                        }
                    } else {
                        $leave_left = 0;
                    }
                }else{
                    $leave_left = 0;
                }

            } else {
                $leave_left = 0;
            }
            return ['balance' => $leave_left, 'paystatus' => 1];
        }
        // check if leave is annual leave
        if ($request->leave_id == 0) {

            //check if  employee has grade
            if (Auth::user()->user_grade) {
//                check if employee grade has a leave length set
                if (Auth::user()->user_grade->leave_length > 0) {
                    //let leave bank be set to the grade leave length
                    $leavebank = Auth::user()->user_grade->leave_length;
                } else {
                    //let the leave policy  leave length be the leave bank
                    $leavebank = $lp->default_length;
                }

            } else {
                //let the leave policy  leave length be the leave bank
                $leavebank = $lp->default_length;
            }
            if (date('Y', strtotime($user->hiredate)) == date('Y')) {
                //porate for staff employed this year
                $leavebank = $leavebank / 12 * (12 - intval(date('m', strtotime($user->hiredate))) + 1);

            }
            //get leave bank if leave policy enables casual leave and employee is on probation
            if ($lp->uses_casual_leave == 1 && $user->status == 0) {
                $leave_bank = $lp->casual_leave_length;
            }
            $leave_left = $leavebank;
            //get the used leave days for the current year
            $used_leave_days = LeaveRequestDate::whereYear('date', $year)->whereHas('leave_request', function ($query) use ($user) {
                $query->where('leave_requests.user_id', $user->id)
                    ->where('status', 1)
                    ->where('leave_id', 0)
                    ->where('is_spillover','!=', 'yes');
            })->count();


            return ['balance' => $leave_left - $used_leave_days, 'paystatus' => 1];
        } else {
            $leave = Leave::find($request->leave_id);
            $used_leave_days = LeaveRequestDate::whereYear('date', $year)->whereHas('leave_request', function ($query) use ($user, $leave) {
                $query->where('leave_requests.user_id', $user->id)
                    ->where('status', 1)
                    ->where('leave_id', $leave->id);
            })->count();

            return ['balance' => $leave->length - $used_leave_days, 'paystatus' => $leave->with_pay];
        }
    }

    public function myRequests(Request $request)
    {

        $user = Auth::user();
        $year = $request->year;
        if ($request->year == '') {
            $year = date('Y');
        }
        $company_id = companyId();
        //get leave policy
        $lp = LeavePolicy::where('company_id', $company_id)->first();
        if (!$lp) {
            //redirect back if leave policy has not been set for company
            return redirect()->back()->with(['status' => 'Leave Policy has not been set up']);
        }

        //fetch all leaves
        $leaves = Leave::all();
        //create leave in formation array
        $leave_info = [];
        //check if user is confirmed
        if ($user->status == 1 || ($user->status == 0 && $lp->uses_casual_leave == 0)) {

            foreach ($leaves as $leave) {

                $used_leave_days = LeaveRequestDate::whereYear('date', $year)->whereHas('leave_request', function ($query) use ($user, $leave) {
                    $query->where('leave_requests.user_id', $user->id)
                        ->where('status', 1)
                        ->where('leave_id', $leave->id);
                })->count();
                if ($leave->gender == 'all') {
                    $leave_info[$leave->id]['name'] = $leave->name;
                    $leave_info[$leave->id]['entitled'] = $leave->length;
                    $leave_info[$leave->id]['usage'] = $used_leave_days;
                    $leave_info[$leave->id]['balance'] = $leave->length - $used_leave_days;
                } elseif ($leave->gender == $user->sex) {
                    $leave_info[$leave->id]['name'] = $leave->name;
                    $leave_info[$leave->id]['entitled'] = $leave->length;
                    $leave_info[$leave->id]['usage'] = $used_leave_days;
                    $leave_info[$leave->id]['balance'] = $leave->length - $used_leave_days;

                }
            }
        }


        //check if user has leave bank
        $user_leave_bank = LeaveBank::where(['user_id' => $user->id, 'year' => $year])->first();
        if ($user_leave_bank) {
            $leavebank = $user_leave_bank->entitled;

        } else {
            if ($user->user_grade) {
                if ($user->user_grade->leave_length > 0) {
                    $leavebank = $user->user_grade->leave_length;
                    LeaveBank::create(['user_id' => $user->id, 'year' => $year,'entitled'=>$user->user_grade->leave_length,
                        'company_id'=>companyId(),'last_modified'=>Auth::user()->id]);

                } else {
                    $leavebank = $lp->default_length;

                }

            } else {
                $leavebank = $lp->default_length;

            }
        }


        if ($user->status == 0 && $lp->uses_casual_leave == 1) {
            $used_days = $user->leave_requests()->whereYear('start_date', $year)->where('status', 1)->where('leave_id', 0)->sum('length');
            $leavebank = $lp->casual_leave_length - $used_days;

        }
        $leave_left = $leavebank;
        $oldleaveleft = 0;



        $holidays = Holiday::whereYear('date', date('Y-m-d'))->where('company_id', companyId())->get();
        $pending_leave_requests = $user->leave_requests()->where('status', 0)->whereYear('start_date', $year)->get();
        $leave_requests = $user->leave_requests()->whereYear('start_date', $year)->get();

        $leave_plans = \App\LeavePlan::where(['user_id' => $user->id, 'company_id' => companyId()])->whereYear('start_date', $year)->get();


         $used_days = LeaveRequestDate::whereYear('date', $year)->whereHas('leave_request', function ($query) use ($user) {
            $query->where('leave_requests.user_id', $user->id)
                ->where('status', 1)
                ->where('leave_id', 0)
                ->where('is_spillover','=' ,null);
        })->count();


        if (date('Y', strtotime($user->hiredate)) == $year) {
            //porate for staff employed this year
            $leavebank = $leavebank / 12 * (12 - intval(date('m', strtotime($user->hiredate))) + 1);

        } else {

            $leave_spill_last_year = \App\LeaveSpill::where('from_year', $year - 1)->where('to_year', $year)->where('user_id', $user->id)->first();
            $date = date('Y-m-d', strtotime('01-' . $request->month));
            $spillover_date = date('Y-m-d', strtotime($year . '-' . $lp->spillover_month . '-' . $lp->spillover_day));
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


        return view('leave.myrequests', compact('leavebank', 'holidays', 'leave_requests', 'pending_leave_requests', 'leaves', 'used_days', 'leaveleft', 'oldleaveleft', 'leave_plans', 'lp','leave_info','user'));
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


    public function saveRequest(Request $request)
    {

        // $leave_workflow_id=Setting::where('name','leave_workflow')->first()->value;

        //check if pending or approved application with similar dates exist
        if ($request->days_selection_type == 'range') {
            $start_date = date('Y-m-d', strtotime($request->start_date));
            $end_date = date('Y-m-d', strtotime($request->end_date));
            $dates_and_days = $this->LeaveDaysRange($request->start_date, $request->end_date);
            $length = $dates_and_days['days'];


            //range
        } elseif ($request->days_selection_type == 'dates') {
            $dates_and_days = $this->LeaveDaysSelection($request->selection);
            $start_date = date('Y-m-d', strtotime($dates_and_days['dates'][0]));
            $end_date = date('Y-m-d', strtotime(end($dates_and_days['dates'])));
            $length = $dates_and_days['days'];

            //selection
        }
        $leave_request = LeaveRequest::where(['start_date' => $start_date, 'end_date' => $end_date, 'user_id' => Auth::user()->id])->first();
        if ($leave_request) {
            return "failed";
        }
        if ($request->file('absence_doc')) {
            $mime = $request->file('absence_doc')->getClientOriginalextension();
            if (!(in_array($mime, $this->allowed))): throw new \Exception("Invalid File Type"); endif;
        }
        $is_spillover=$request->filled('is_spillover')?$request->is_spillover:'no';
        $company_id = companyId();
        $lp = LeavePolicy::where('company_id', $company_id)->first();
        $leave_id = ($request->leave_id == '') ? 0 : $request->leave_id;
        $check_leave_payment = LeaveAllowancePayment::where(['user_id' => Auth::user()->id, 'year' => date('Y')])->first();
        $requested_allowance = 0;
        if (!$check_leave_payment && $lp->can_request_allowance == 1 && $request->request_allowance == 1) {
            $requested_allowance = 1;
        }

        $leave_request = LeaveRequest::create(['leave_id' => $leave_id, 'user_id' => Auth::user()->id, 'start_date' => $start_date,
            'end_date' => $end_date, 'reason' => $request->reason, 'workflow_id' => $lp->workflow_id,
            'paystatus' => 0, 'status' => 0, 'length' => $length, 'company_id' => $company_id, 'replacement_id' => $request->replacement,
            'balance' => $request->leaveremaining, 'requested_allowance' => $requested_allowance,'is_spillover'=>$is_spillover]);
        foreach ($dates_and_days['dates'] as $dd) {
            LeaveRequestDate::create(['leave_request_id' => $leave_request->id, 'date' => date('Y-m-d', strtotime($dd))]);
        }
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
                } elseif ($stage->role->manages == 'ss') {
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
                } elseif ($stage->role->manages == 'ss') {

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

        return view('leave.approvals', compact('user_approvals', 'role_approvals', 'group_approvals', 'dr_approvals', 'ss_approvals'));
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

            if ($nextstage) {

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

                    } elseif ($nextstage->role->manages == 'ss') {
                        $ss = $leave_approval->leave_request->user->plmanager->managers;
                        if (!$ss) {
                            $leave_approval->leave_request->status = 1;
                            $leave_approval->leave_request->save();

                            $leave_approval->leave_request->user->notify(new LeaveRequestApproved($leave_approval->stage, $leave_approval));

                            if ($leave_approval->leave_request->requested_allowance == 1) {
                                $this->prepareLeaveAllowance($leave_approval->leave_request->user, $leave_approval->leave_request);
                            }
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
//                check and notify line manager(s)
                foreach ($leave_approval->leave_request->user->managers as $manager) {
                    $manager->notify(new LeaveRequestApprovedOthers($leave_approval));
                }

                //check and notify manager of manager
                if($leave_approval->leave_request->user->plmanager){

               
                $ss = $leave_approval->leave_request->user->plmanager->plmanager;
                $company=Company::find(companyId());
                if($ss and $ss->id!=$company->manager_id){
                    $ss->notify(new LeaveRequestApprovedOthers($leave_approval));
                }
                }
                if ($leave_approval->leave_request)
                    if ($leave_approval->leave_request->requested_allowance == 1) {
                        $this->prepareLeaveAllowance($leave_approval->leave_request->user, $leave_approval->leave_request);
                    }

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
        $leave_request = LeaveRequest::where('id', $request->leave_request_id)->first();
        $previous_annual_leave_requests = LeaveRequest::where('user_id', $leave_request->user_id)->where('id', '!=', $leave_request->id)
            ->whereHas('dates', function ($query) use ($leave_request) {
                $query->whereYear('date', date('Y', strtotime($leave_request->start_date)))
                    ->orWhereYear('date', date('Y', strtotime($leave_request->end_date)));
            })->get();
        return view('leave.partials.leaveDetails', compact('leave_request', 'previous_annual_leave_requests'));
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
                } elseif ($stage->role->manages == 'ss') {

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
        if ($request->year == '') {
            $year = date('Y');
        } else {
            $year = $request->year;
        }
        $leave_allowance_payments = LeaveAllowancePayment::where(['year' => $year, 'disbursed' => 0])->get();
        foreach ($leave_allowance_payments as $payment) {
            //check if salary component is marked completed
            $salary_component = SpecificSalaryComponent::where('leave_allowance_payment_id', $payment->id)->first();
            if ($salary_component && $salary_component->completed == 1) {
                $payment->update(['disbursed' => 1]);
            }
        }
        $leave_allowance_payments = LeaveAllowancePayment::where(['year' => $year])->get();

        $years = LeaveAllowancePayment::select('year')->distinct('year')->get();

        return view('leave.leave_payment', compact('leave_allowance_payments', 'years', 'year'));

    }


    public function prepareLeaveAllowance($user, $leave_request)
    {
        $check_leave_payment = LeaveAllowancePayment::where(['user_id' => $user->id, 'year' => date('Y', strtotime($leave_request->created_at))])->first();

        if ($check_leave_payment == null) {

//           $salary_category=PaceSalaryComponent::whereHas('pace_salary_category',function ($query) use($user){
//               $query->where('pace_salary_categories.id',$user->id);
//           })->where('constant','leave_allowance_payment')->first();
//           if ($salary_category){
//               $leave_payment=LeaveAllowancePayment::create(['user_id'=>$user->id,'year'=>date('Y'),'amount'=>$salary_category->amount,'disbursed'=>0,'approved'=>0]);
//           }
            if ($user->payroll_type == 'project') {

                $amount = $user->project_salary_category->basic_salary * 12 * 0.06;
                $leave_payment = LeaveAllowancePayment::create(['user_id' => $user->id, 'year' => date('Y'), 'amount' => $amount, 'disbursed' => 0, 'approved' => 0, 'leave_request_id' => $leave_request->id]);
                $specific_salary_component_type = SpecificSalaryComponentType::firstOrCreate(['name' => 'Leave Allowance', 'company_id' => companyId()],
                    ['type' => 1, 'display' => 1])->first();
                $specific_salary_component = SpecificSalaryComponent::create([
                    'specific_salary_component_type_id' => $specific_salary_component_type->id,
                    'amount' => $amount,
                    'name' => 'Leave Allowance',
                    'type' => 1,
                    'duration' => 1,
                    'grants' => 0,
                    'completed' => 0,
                    'emp_id' => $user->id,
                    'company_id' => $user->company_id,
                    'one_off' => 1,
                    'is_relief' => 0,
                    'taxable' => 0,
                    'taxable_type' => 1,
                    'status' => 1,
                    'leave_allowance_payment_id' => $leave_payment->id
                ]);
            } elseif ($user->payroll_type == 'office' && $user->user_grade) {

                $amount = $user->user_grade->basic_pay * 12 * 0.10;

                $leave_payment = LeaveAllowancePayment::create(['user_id' => $user->id, 'year' => date('Y'), 'amount' => $amount, 'disbursed' => 0, 'approved' => 0, 'leave_request_id' => $leave_request->id]);
                $specific_salary_component_type = SpecificSalaryComponentType::firstOrCreate(['name' => 'Leave Allowance', 'company_id' => companyId()],
                    ['type' => 1, 'display' => 1])->first();
                $specific_salary_component = SpecificSalaryComponent::create([
                    'specific_salary_component_type_id' => $specific_salary_component_type->id,
                    'amount' => $amount,
                    'name' => 'Leave Allowance',
                    'type' => 1,
                    'duration' => 1,
                    'grants' => 0,
                    'completed' => 0,
                    'emp_id' => $user->id,
                    'company_id' => $user->company_id,
                    'one_off' => 1,
                    'is_relief' => 0,
                    'taxable' => 0,
                    'taxable_type' => 1,
                    'status' => 1,
                    'leave_allowance_payment_id' => $leave_payment->id
                ]);
            }

        }
    }

    public function getLeaveRequests(Request $request)
    {
        $year = $request->year;
        if ($request->year == '') {
            $year = date('Y');
        }
        $leave_requests = LeaveRequest::whereYear('created_at', $year)->get();
        $approved_requests = LeaveRequest::whereYear('created_at', $year)->where('status', 1)->count();
        $pending_requests = LeaveRequest::whereYear('created_at', $year)->where('status', 0)->count();
        $rejected_requests = LeaveRequest::whereYear('created_at', $year)->where('status', 2)->count();
        $employees_on_leave = User::whereHas('leave_request_dates', function ($query) {
            $query->whereHas('leave_request', function ($query) {
                $query->where('leave_requests.status', 1);
            })
                ->whereDate('date', date('Y-m-d', strtotime('+1 day')));
        })->with('leave_request_dates')->count();
        $month_leave_requests = LeaveRequest::whereHas('dates', function ($query) {
            $query->whereMonth('date', date('m'))
                ->whereYear('date', date('Y'));
        })->where('status', 1)->get();

        return view('leave.hrrequests', compact('leave_requests', 'approved_requests', 'pending_requests', 'rejected_requests', 'employees_on_leave', 'month_leave_requests'));

    }

    public function annual_leave_utilization()
    {

    }

    public function LeaveDaysRange($start_date, $end_date)
    {
        $company_id = companyId();
        $lp = LeavePolicy::where('company_id', $company_id)->first();
        $dates = [];
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
            $is_weekend = 0;
            $is_holiday = 0;

            // substract if Saturday or Sunday
            if (($curr == 'Sat' || $curr == 'Sun') && $lp->includes_weekend == 0) {
                $days--;
                $is_weekend = 1;

            } elseif ($holidays->count() > 0 && $lp->includes_holiday == 0) {
                foreach ($holidays as $holiday) {
                    if ($dt->format('m/d/Y') == $holiday) {
                        $days--;
                        $is_holiday = 1;
                    }
                }


            } else {

            }
            if ($is_weekend == 0 && $is_holiday == 0) {
                $dates[] = $dt->format('Y-m-d');
            }
            // $dates[]=$dt->format('Y-m-d');
        }


        return ['days' => $days, 'dates' => $dates];
    }

    public function LeaveDaysSelection($selection)
    {
        $company_id = companyId();
        $lp = LeavePolicy::where('company_id', $company_id)->first();
        $holidays = \App\Holiday::where(['status' => 1, 'company_id' => $company_id])->whereYear('date', date('Y'))->pluck('date')->toArray();//array('2012-09-07');

        $dates = [];
        $days = 0;
        if (!is_array($selection)) {
            $dates_array = explode(",", $selection);
        } else {
            $dates_array = $selection;
        }

        //sort
        // usort($dates_array,array("ClassName","cmp"));
        usort($dates_array, "static::cmp");

        foreach ($dates_array as $date_a) {
            $ld = new \DateTime($date_a);
            $curr = $ld->format('D');
            if ((($curr == 'Sat' || $curr == 'Sun') && $lp->includes_weekend == 0)
                || ((count($holidays) > 0 && $lp->includes_holiday == 0) && in_array($ld->format('m/d/Y'), $holidays))) {


            } else {
                $days++;
                $dates[] = $ld->format('Y-m-d');
            }
        }

        return ['days' => $days, 'dates' => $dates];

    }

    private static function cmp($a, $b)
    {
        if ($a == $b) {
            return 0;
        }
        return ($a < $b) ? -1 : 1;
    }


}

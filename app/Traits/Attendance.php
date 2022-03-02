<?php

namespace App\Traits;


use App\AttendancePayrollDetail;
use App\AttendanceReport;
use App\Exemption;
use App\LatenessPolicy;
use App\Mail\SendAttachMail;
use App\Notifications\AttendanceOvertimeNotify;
use App\Setting;
use App\SpecificSalaryComponent;
use App\User;
use App\Shift;
use App\UserDailyShift;
use App\Workflow;
use App\WorkingPeriod;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Mail;

/**
 *
 */
trait Attendance
{

    function seconds_to_time($seconds)
    {
        $t = round($seconds);
        return sprintf('%02d:%02d:%02d', ($t / 3600), ($t / 60 % 60), $t % 60);
    }

    function time_to_seconds($time)
    {
        $sec = 0;
        foreach (array_reverse(explode(':', $time)) as $k => $v) $sec += pow(60, $k) * $v;
        return $sec;
    }

    public function time_diff($time1, $time2)
    {
        $time1 = strtotime("1/1/2018 $time1");
        $time2 = strtotime("1/1/2018 $time2");
        return ($time2 - $time1) / 3600;
    }
    public function get_time_difference($time1, $time2)
    {
        $time1 = strtotime("1/1/2018 $time1 ");
        $time2 = strtotime("1/1/2018 $time2");

        if ($time2 < $time1) {
            $time2 = $time2 + 86400;
        }
        return ($time2 - $time1) / 3600;
    }

    public function checkStatus($clockin, $shifttime)
    {
        if ($clockin <= $shifttime) {
            return 'early';
        } else {
            return 'late';
        }
    }

    public function checkStatus2($clockin, $shifttime)
    {
        $grace_period=Setting::where('name','grace_period')->first()->value;
        $shifttime=Carbon::createFromFormat('H:i:s',$shifttime)->addMinutes($grace_period)->format('H:i:s');
        if ($clockin <= $shifttime) {
            return 'early';
        } else {
            return 'late';
        }
    }
    private function checkVerification($verify_id)
    {
        switch ($verify_id) {
            case '0' :
                return 'Password';
                break;
            case '1' :
                return 'Fingerprint';
                break;
            case '2' :
                return 'Card';
                break;
            case '9' :
                return 'Others';
                break;
        }
    }

    public function getHoursBetween($shift_in,$shift_out){
        $clock_in = Carbon::createFromFormat('H:i:s', $shift_in);
        if ($shift_out){
            $clock_out = Carbon::createFromFormat('H:i:s', $shift_out);
        }else{
            //if there is no clock out, set the clock out to be clockin
            $clock_out = Carbon::createFromFormat('H:i:s', $shift_in);
        }
        if ($clock_in>$clock_out){//it means clockout is next day
            $clock_out = $clock_out->addDay();
        }
        return $clock_in->diffInHours($clock_out);
    }

    public function getDayHoursNew($attendance)
    {   //function to calculate hours worked, overtime and work hours worked
        $wp = WorkingPeriod::all()->first();
        $total_hours = 0;
        $work_hours_worked = 0;
        $excess = 0;
        $overtime = 0;
        $work_end_time = '';
        $work_start_timeA = '';
        $shift_name='';
        $status = '';
        $amount='';
        if ($attendance) {
            $user = User::where('emp_num',$attendance->emp_num)->first();
            //$amount=$user->role->daily_pay;
            $details = $attendance->attendancedetails;
            if(isset($details->first()->clock_in)){
                $first_clock_in = $details->first()->clock_in;
            }
            else{
                //dd($attendance->id);
            }

            $last_clock_out = $details->last()->clock_out;
            //hours worked and work_hours_worked
            foreach ($details as $detail) {
                $clock_in = Carbon::createFromFormat('H:i:s', $detail->clock_in);
                if ($detail->clock_out){
                    $clock_out = Carbon::createFromFormat('H:i:s', $detail->clock_out);
                }else{
                    //if there is no clock out, set the clock out to be clockin
                    $clock_out = Carbon::createFromFormat('H:i:s', $detail->clock_in);
                }
                if ($clock_in>$clock_out){//it means clockout is next day
                    $clock_out = $clock_out->addDay();
                }

                $total_hours += $clock_in->diffInHours($clock_out);
                //$hours += $this->get_time_difference($detail->clock_in, $detail->clock_out);
            }
            //end of hours calculation
            //early or late determination
            if ($attendance->user_daily_shift_id != 0) {        //there is shift
                $shift_id = $attendance->user_daily_shift->shift->id;
                $shift = Shift::where('id', $shift_id)->first();
                $work_end_time = $shift->end_time;
                $work_start_timeA = $shift->start_time;
                $work_start_time = $shift->start_time;
                $shift_name=$shift->type;
            } else {
                $work_end_time = $wp->cob;
                $work_start_timeA = $wp->sob;
                $work_start_time = $wp->sob;
                $shift_name='Default Working Hours';
            }
            $status = $this->checkStatus2($first_clock_in, $work_start_time);//no shift, use business hours
            if ($shift_name=='Off Day'){$status='off';}
            //overtime
            if ($last_clock_out > $work_end_time) {
                $work_end = Carbon::createFromFormat('H:i:s', $work_end_time);
                $last_clock = Carbon::createFromFormat('H:i:s', $last_clock_out);
                $overtime = $last_clock->diffInHours($work_end);
                // $overtime = $this->get_time_difference($last_clock_out,$work_end_time);
            }
            if ($first_clock_in < $work_start_time) {
                $first_clock_in = Carbon::createFromFormat('H:i:s', $first_clock_in);
                $work_start_time = Carbon::createFromFormat('H:i:s', $work_start_time);
                $excess = $first_clock_in->diffInHours($work_start_time);
                $before_shift_start = Setting::where('name', 'before_shift_time')->first();
                if ($before_shift_start->value == '1') {
                    $overtime = $overtime + $excess;
                }
                $first_clock_in=$first_clock_in->format('H:i:s');
            }
            //$total_hours = $total_hours - $excess;  //remove time before shift starts to from hours worked
            //$total_hours = $total_hours - $overtime;  //remove overtime from hours worked

            if ($work_end_time>$work_start_time){   //the shift has no spillover
                //$this->info($shift_name.' - day');
            }
            else{       //the shift has spillover
                // $this->info($shift_name.' - spillover');

            }
            $expected_hours=$this->getHoursBetween($work_start_timeA,$work_end_time);
        }
        return ['hours' => $total_hours, 'status' => $status, 'overtime' => $overtime,
            'shift_start' => $work_start_timeA, 'shift_end' => $work_end_time,'shift_name'=>$shift_name,'expected_hours'=>$expected_hours,
            'first_clock_in'=>$first_clock_in, 'last_clock_out'=>$last_clock_out,'amount'=>$amount];
    }

    public function attendanceToDB($from, $to)
    {
        \App\Attendance::whereDate('date','>=', $from)->whereDate('date','<=', $to)->chunk(200, function ($not_processed_yet) {
            foreach ($not_processed_yet as $not_processed) {
                $this->attendanceReportForAttendance($not_processed);
            }
        });
    }
    public function attendanceReportForAttendance($not_processed)
    {
        //find and update user daily shift if there is any
        $user_shift = UserDailyShift::where('user_id', $not_processed->user->id)->where('sdate', $not_processed->date)->first();
        if ($user_shift) {
            \App\Attendance::where('id', $not_processed->id)->update(['user_daily_shift_id' => $user_shift->id]);
        }
        $not_processed = \App\Attendance::find($not_processed->id);
        $data = $this->getDayHoursNew($not_processed);
        if ($data['overtime']>0){
            $this->initiateOvertimeWorkflow($not_processed);
        }
        \App\AttendanceReport::updateOrCreate(
            ['user_id' => $not_processed->user->id, 'date' => $not_processed->date],
            ['attendance_id' => $not_processed->id,
                'first_clockin' => $data['first_clock_in'],
                'last_clockout' => $data['last_clock_out'],
                'status' => $data['status'],
                'hours_worked' => $data['hours'],
                'expected_hours' => $data['expected_hours'],
                'overtime' => $data['overtime'],
                'shift_name' => $data['shift_name'],
                'shift_start' => $data['shift_start'],
                'shift_end' => $data['shift_end'],
                'amount'=>$data['amount']
            ]
        );
    }

    public function getAbsentees($dates){
        foreach ($dates as $date) {$present = AttendanceReport::where('date', $date)->pluck('user_id')->toArray();
            $absentees = User::where('status','1')->whereNotIn('id', $present)->get();//for each day, fetch users that don't have attendance
            foreach ($absentees as $absentee) {
                $user_daily_shift = UserDailyShift::where('user_id', $absentee->id)->where('sdate', $date)->first();
                $status="absent";
                if ($user_daily_shift) {
                    $shift = Shift::where('id', $user_daily_shift->shift_id)->first();
                    $shift_start = $shift->start_time;
                    $shift_end = $shift->end_time;
                    $shift_name = $shift->type;
                    if ($shift_name=='Off Day'){$status='off';}
                } else {
                    $wp = WorkingPeriod::all()->first();
                    $shift_start = $wp->sob;
                    $shift_end = $wp->cob;
                    $shift_name = 'Default Working Hours';
                }
                $expected_hours=$this->getHoursBetween($shift_start,$shift_end);
                \App\AttendanceReport::updateOrCreate(
                    ['user_id' => $absentee->id, 'attendance_id' => '', 'date' => $date],
                    ['first_clockin' => '', 'last_clockout' => '',
                        'status' => $status, 'hours_worked' => '', 'overtime' => '',
                        'shift_name' => $shift_name, 'shift_start' => $shift_start, 'shift_end' => $shift_end,'expected_hours' => $expected_hours,'amount'=>'0']
                );  //add their details with null
            }
        }
    }
    public function runDeductold($month,$year,$company_id){
        $late_users = User::/*where('company_id',$company_id)->*/whereHas('attendance_reports', function (Builder $query) use($month,$year){
            $query->where('status', 'late')->whereMonth('date',$month)->whereYear('date',$year);
        })->get();

        foreach ($late_users as $late_user){
            $late_count=AttendanceReport::where('user_id',$late_user->id)
                ->whereMonth('date',$month)->whereYear('date',$year)->where('status','late')->count();
            $gross_pay=$late_user->grade->basic_pay;
            $days_in_a_month= $this->getExpectedDays($month,$year,$company_id);
            $daily_pay=$gross_pay/$days_in_a_month;
            $late_policy=LatenessPolicy::first();
            if ($late_policy->deduction_type=='1'){//percentage
                $daily_deduction=($late_policy->deduction/100)*$daily_pay;
            }
            else{
                $daily_deduction=$late_policy->deduction;
            }
            $month_deduction=$daily_deduction*$late_count;
            SpecificSalaryComponent::updateOrCreate(
                ['name' => $late_policy->policy_name,
                    'amount' => $month_deduction,
                    //'gl_code' => '',
                    //'project_code' => '',
                    'type' => 0,
                    'comment' => $late_policy->policy_name,
                    'emp_id' => $late_user->id,
                    'duration' => 1,
                    'grants' => 0,
                    'status' => 1,
                    'starts' => '',
                    'ends' => '',
                    'company_id' => $company_id,
                    'specific_salary_component_type_id' =>$late_policy->specific_salary_component_type_id,
                    'taxable' => 0,
                    'one_off' => 1
                ]);
        }
    }

    public function runDeduct($month,$year,$company_id){
        $late_users = User::where('company_id',$company_id)->whereHas('attendance_reports', function (Builder $query) use($month,$year){
            $query->where('status', 'late')->whereMonth('date',$month)->whereYear('date',$year);
        })->get();
        $days_in_a_month= $this->getExpectedDays($month,$year,$company_id);

        $late_policies=LatenessPolicy::whereIn('payroll',['normal','all'])->where('company_id',$company_id)->where('status','1')->get();
        foreach ($late_users as $late_user){
            $month_deduction=0;
            $gross_pay=$late_user->grade->basic_pay;
            $daily_pay=$gross_pay/$days_in_a_month;
            $lates=AttendanceReport::where('user_id',$late_user->id)
                ->whereMonth('date',$month)->whereYear('date',$year)->where('status','late')->get();
            foreach ($lates as $late){
                $late_with= Carbon::parse($late->shift_start)->diffInMinutes(Carbon::parse($late->first_clockin));
                $get_late_policy= $late_policies->where('late_minute','>=',$late_with)->sortBy('late_minute')->first();
                if (!$get_late_policy){
                    $get_late_policy=$late_policies->where('late_minute','<=',$late_with)->sortByDesc('late_minute')->first();
                }
                if ($get_late_policy->deduction_type=='1'){//percentage
                    $daily_deduction=($get_late_policy->deduction/100)*$daily_pay;
                }
                else{
                    $daily_deduction=$get_late_policy->deduction;
                }
                $month_deduction=$month_deduction+$daily_deduction;
            }

            SpecificSalaryComponent::create(
                ['name' => $get_late_policy->policy_name,
                    'amount' => $month_deduction,
                    //'gl_code' => '',
                    //'project_code' => '',
                    'type' => 0,
                    'comment' => $get_late_policy->policy_name,
                    'emp_id' => $late_user->id,
                    'duration' => 1,
                    'grants' => 0,
                    'status' => 1,
                    'starts' => '',
                    'ends' => '',
                    'company_id' => $company_id,
                    'specific_salary_component_type_id' =>$get_late_policy->specific_salary_component_type_id,
                    'taxable' => 0,
                    'one_off' => 1
                ]);
        }
    }

    public function staffPayslipEmail($attendance_payroll_detail){
        $attendance_payroll_detail=AttendancePayrollDetail::findOrFail($attendance_payroll_detail);
        //view to be converted to pdf
        $view = 'attendance.payroll.payslip';
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView($view, compact('attendance_payroll_detail'))->setPaper('a4', 'landscape');

        $from='info@snapnet.com.ng';
        $subject=$attendance_payroll_detail->user->name.' Payslip for '.$attendance_payroll_detail->attendance_payroll->month.' '.$attendance_payroll_detail->attendance_payroll->year;
        $data=['mail'=>'Kindly download your payslip for '.$attendance_payroll_detail->attendance_payroll->month.' '.$attendance_payroll_detail->attendance_payroll->year];
        $mail_body_view='emails.plain_body';
        $name=$attendance_payroll_detail->user->name.' Payslip. '.$attendance_payroll_detail->attendance_payroll->month.' '.$attendance_payroll_detail->attendance_payroll->year;

        Mail::to('timothy@snapnet.com.ng')->send(new SendAttachMail($from,$subject,$data,$mail_body_view,$pdf,$name));

    }

    public function initiateOvertimeWorkflow($attendance){
        $workflow_details_response=$this->getWorkflowDetails();
        $workflow_details=collect($workflow_details_response['workflow_details']);
        $first_stage_users=$workflow_details_response['first_stage_users'];
        $workflow=$workflow_details_response['workflow'];

        \App\Attendance::where('id',$attendance->id)->update(['workflow_status'=>'pending','workflow_id'=>$workflow->id,'workflow_details'=>$workflow_details]);

        $first_stage_users=User::whereIn('id',$first_stage_users)->get();
        foreach ($first_stage_users as $user) {
            $user->notify(new AttendanceOvertimeNotify($attendance));
        }
        return 'done';
    }
    private function getWorkflowDetails(){
        $workflow=1;
        $overtime_workflow=Setting::where('name','overtime_workflow')->where('company_id',companyId())->first();
        if ($overtime_workflow){
            $workflow=$overtime_workflow->value;
        }
        $workflow=Workflow::find($workflow);
        $workflow_details=[];
        $first_stage_users=[];
        foreach ($workflow->stages as $stage){
            $type=$this->stageTypeDetails($stage)['type'];
            $users=$this->stageTypeDetails($stage)['users'];
            $status='inactive';
            if ($stage->position=='0'){$status='pending'; $first_stage_users=$users;}
            $stage_details=[
                'id'=>$stage->id,
                'position'=>$stage->position,
                'type'=>$type,
                'users'=>$users,
                'status'=>$status,
                'approved_by'=>''
            ];
            $workflow_details[]=$stage_details;
        }
        $workflow_details=collect($workflow_details);
        return ['workflow'=>$workflow,'workflow_details'=>$workflow_details,'first_stage_users'=>$first_stage_users];

    }
    private function stageTypeDetails($stage){
        if ($stage->type=='1'){
            $users=[$stage->user_id];
            return ['type'=>'user','users'=>$users];
        }
        elseif ($stage->type=='2'){
            $users=User::where('role_id',$stage->role_id)->pluck('id')->toArray();
            $users=collect($users)->map(function ($user){return (int) $user;});
            return ['type'=>'role','users'=>$users];
        }
        elseif ($stage->type=='3'){
            $users=$stage->group->users->pluck('id')->toArray();
            $users=collect($users)->map(function ($user){return (int) $user;});
            return ['type'=>'group','users'=>$users];
        }
        else{
            $users=[];
            return ['type'=>'','users'=>$users];
        }
    }

}
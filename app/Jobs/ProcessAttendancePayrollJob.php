<?php

namespace App\Jobs;

use App\AttendancePayroll;
use App\AttendancePayrollDetail;
use App\AttendanceReport;
use App\LatenessPolicy;
use App\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessAttendancePayrollJob /*implements ShouldQueue*/
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $year;private $month;private $start;private $end;private $user;private $company;private $settings;
    private $users;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($year,$month,$start,$end,$user,$company,$settings,$users=null)
    {
        $this->year=$year;
        $this->month=$month;
        $this->start=$start;
        $this->end=$end;
        $this->user=$user;
        $this->company=$company;
        $this->settings=$settings;
        $this->users=$users;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $year=$this->year;
        $month=$this->month;
        $start=$this->start;
        $end=$this->end;
        $company=$this->company;
        $status=$this->settings['status'];
        $pay_full_days=$this->settings['pay_full_days'];
        $divide_by_days=$this->settings['divide_by_days'];

        $date = Carbon::create($year,$month);
        $last_report_in_period=AttendanceReport::whereBetween('date',[$start,$end])->orderBy('id','desc')->first();
        $late_policies=LatenessPolicy::whereIn('payroll',['attendance','all'])->where('company_id',$company)->where('status','1')->get();
        if ($last_report_in_period){
            $last=$last_report_in_period;
            $last_date=$last->date;
            $last_date=Carbon::parse($last_date);
            $last_day=$last_date->format('d');
            $process_users=User::where('payroll_type','attendance')->where('company_id',$company)->with('role')->with('grade');
            if ($this->users){
                $process_users=$process_users->whereIn('id',$this->users);
            }
            $payroll=AttendancePayroll::updateOrCreate(['month' => $date->format('m'), 'year' => $date->format('Y')],
                ['pay_status'=>$status,'start'=>$start,'end'=>$end, 'day' => $last_day,'created_by'=>$this->user,'attendance_report_id'=>$last->id]
            );
            $process_users->chunk(10, function ($users) use($date,$payroll,$start,$end,$status,$pay_full_days,$divide_by_days,$late_policies) {
                foreach ($users as $user) {
                    $user_att=AttendanceReport::whereBetween('date',[$start,$end])->where('user_id',$user->id)->get();

                    $present = $user_att->whereIn('status', ['late', 'early'])->count();
                    $absent=$user_att->where('status', 'absent')->count();
                    $late_count= $user_att->where('status', 'late')->count();
                    $early= $user_att->where('status', 'early')->count();
                    $off= $user_att->where('status', 'off')->count();

                    $pay_days=$user_att->whereIn('status',$status)->count();
                    $max_exp=0;

                    if ($user->grade){
                        $max_exp=$user->grade->basic_pay;
                        $daily_pay=$max_exp/$divide_by_days;
                        if ($pay_days>$pay_full_days){
                            $to_receive=$max_exp;
                        }
                        else{
                            $to_receive=$daily_pay*$pay_days;
                        }
                    }
                    //deductions for late coming
                    $deduction=0;
                    foreach ($user_att->where('status', 'late') as $late){
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
                        $deduction=$deduction+$daily_deduction;
                    }
                    $to_receive=$to_receive-$deduction;

                    AttendancePayrollDetail::updateOrCreate(['user_id' => $user->id, 'attendance_payroll_id' => $payroll->id],
                        ['role_id' => $user->role->id, 'days_worked' => $present, 'present' => $present,
                            'company_id' => $user->company_id, 'absent' => $absent,'early' => $early, 'late' =>$late_count,'off'=>$off,
                            'amount_expected' => $max_exp, 'amount_received' => $to_receive,'deduction'=>$deduction]
                    );
                }
            });
            $message='Successfully ran payroll';
        }
        else{
            $message='Error running Payroll';
        }

    }
}

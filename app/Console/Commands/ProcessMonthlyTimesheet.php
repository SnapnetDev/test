<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use App\User;
use App\DailyAttendance;
use App\Attendance;
use App\AttendanceDetail;
use App\Holiday;
use App\WorkingPeriod;
use App\Timesheet;
use App\TimesheetDetail;
use App\Traits\Attendance as TAttendance;

class ProcessMonthlyTimesheet extends Command
{
    use TAttendance;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'timesheet:month';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process Monthly Timesheet';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->getTimesheetMonth();
    }
    public function getTimesheetMonth()
    {
        $timesheet=[];
        $tdays=[];
        // $month=date('m',strtotime('first day of previous month'));
        $month='02';//date('m');
        $year='2019';date('Y');
        $users=User::all();
        $count=$users->count();
        $days=cal_days_in_month(CAL_GREGORIAN,$month,$year);
        $total_hours=0;
        $ts=Timesheet::create(['month'=>$month,'year'=>$year]);
        foreach ($users as $user) {
            $total_hours=0;
            $monthHours=$this->getMonthHours($user->emp_num,$month,$year);
            $weekdayHours=$this->getWeekdayHours($user->emp_num,$month,$year);
            $basicWorkHours=$this->getBasicWorkHours($user->emp_num,$month,$year);
            $overtimeWeekdayHours=$this->getOvertimeWeekdayHours($user->emp_num,$month,$year);
            $overtimeSaturdaysHours=$this->getOvertimeSaturdaysHours($user->emp_num,$month,$year);
            $overtimeSundaysHours=$this->getOvertimeSundaysHours($user->emp_num,$month,$year);
            $overtimeHolidaysHours=$this->getOvertimeHolidayHours($user->emp_num,$month,$year);
            $expectedworkhours=$this->getExpectedHours($user->emp_num,$month,$year);
            $expectedworkdays=$this->getExpectedDays($user->emp_num,$month,$year);
            
            // $timesheet[$user->id]['sn']=$count;
            // $timesheet[$user->id]['badge_no']=$user->emp_num;
            // $timesheet[$user->id]['name']=$user->name;
            // $timesheet[$user->id]['position']=$user->position->name;
            // $timesheet[$user->id]['staff_location']=$user->position->name;
            // $timesheet[$user->id]['category']=$user->position->name;
            // $timesheet[$user->id]['employee_type']=$user->position->name;
            // $timesheet[$user->id]['cost_center']=$user->cost_center->code;
            for ($i=1; $i <=$days ; $i++) { 

                $timesheet[$user->id][$i]=$this->getDayHours($user->emp_num,$year.'-'.$month.'-'.$i);
                $tdays[$i]=$timesheet[$user->id][$i];
                $total_hours+=$timesheet[$user->id][$i];
            }
            $ts->timesheetdetails()->create(['user_id'=>$user->id,'tdays'=>serialize($tdays),'total_hours'=>$total_hours,'weekday_hours'=>$weekdayHours,'basic_work_hours'=>$basicWorkHours,'overtime_weekday_hours'=>$overtimeWeekdayHours,'overtime_holiday_hours'=>$overtimeHolidaysHours,'overtime_saturday_hours'=>$overtimeSaturdaysHours,'overtime_sunday_hours'=>$overtimeSundaysHours,'expected_work_hours'=>$expectedworkhours,'expected_work_days'=>$expectedworkdays,'average_first_clock_in'=>$monthHours['avg_clock_in'],'average_last_clock_out'=>$monthHours['avg_clock_out']]);
            // $timesheet[$user->id]['total_hours']=$total_hours;
            // $timesheet[$user->id]['weekdayHours']=$weekdayHours;
            // $timesheet[$user->id]['basicWorkHours']=$basicWorkHours;
            // $timesheet[$user->id]['overtimeWeekdayHours']=$overtimeWeekdayHours;
            // $timesheet[$user->id]['overtimeSaturdaysHours']=$overtimeSaturdaysHours;
            // $timesheet[$user->id]['overtimeHolidaysHours']=$overtimeHolidaysHours;
            // $timesheet[$user->id]['overtimeSundaysHours']=$overtimeSundaysHours;
            // $timesheet[$user->id]['monthHours']=$monthHours;
            // $timesheet[$user->id]['expectedworkhours']=$expectedworkhours;
            // $timesheet[$user->id]['expectedworkdays']=$expectedworkdays;
            // $timesheet[$user->id]['test']=$this->testTime("$year-$month-25");
            

        }
        return $timesheet;
        
    }
    public function getDayHours($emp_num,$date)
    {
        $wp=WorkingPeriod::all()->first();
        $hours=0;
        $diff=0;
        $time='';
        // dd($date);
        $attendance=Attendance::has('attendancedetails')->whereDate('date', $date)->where('emp_num',$emp_num)->first();
        
        if ($attendance ) {
            $details = \App\AttendanceDetail::whereHas('attendance', function ($query) use ($emp_num,$date) {
                    $query->whereDate('date', $date)->where('emp_num',$emp_num);
                })->orderBy('id','asc')->get();
            // $details=$attendance->attendancedetails->orderBy('id','desc');
        // $acount=$attendance->attendancedetails->count();
            $time=$details->first()->clock_in;
            foreach ($details as $detail) {
                $hours+=$this->get_time_difference($detail->clock_in, $detail->clock_out);
            }
            $diff=$this->time_diff($time,$wp->sob);
            if ($diff>0) {
                return $hours-$diff;
            }

        }
        
            return $hours;
    }
        
    public function getMonthHours($emp_num,$month,$year)
    {
        
        $total_hours=0;
        $first_clock_in=[];
        $last_clock_out=[];
        $first_clock_in_total=0;
        $first_clock_in_count=0;
        $last_clock_out_total=0;
        $last_clock_out_count=0;
        $days=cal_days_in_month(CAL_GREGORIAN,$month,$year);
        for ($i=1; $i <=$days ; $i++) { 
                $total_hours+=$this->getDayHours($emp_num,$year.'-'.$month.'-'.$i);
                $first_clock_in[$i]=\App\Attendance::where(['emp_num'=>$emp_num,'date'=>$year.'-'.$month.'-'.$i])->first()->attendancedetails()->orderBy('clock_in', 'asc')->first()->clock_in;
            $last_clock_out[$i]=\App\Attendance::where(['emp_num'=>$emp_num,'date'=>$year.'-'.$month.'-'.$i])->first()->attendancedetails()->orderBy('clock_out', 'desc')->first()->clock_out;
            if ($first_clock_in[$i]) {
               $first_clock_in_total+= $this->time_to_seconds($first_clock_in[$i]);
                $first_clock_in_count++;
            }
            if ($last_clock_out[$i]) {
               $last_clock_out_total+= $this->time_to_seconds($last_clock_out[$i]);
                $last_clock_out_count++;
            }
            

                
            }
            return ['total_hours'=>$total_hours,'avg_clock_in'=>$this->seconds_to_time($first_clock_in_total/$first_clock_in_count),'avg_clock_out'=>$this->seconds_to_time($last_clock_out_total/$last_clock_out_count)];
    }

    public function getWeekdayHours($emp_num,$month,$year)
    {
        $total_hours=0;
        $days=cal_days_in_month(CAL_GREGORIAN,$month,$year);
        for ($i=1; $i <=$days ; $i++) { 
            if (date('N',strtotime("$year-$month-$i"))<6 && $this->checkHoliday("$year-$month-$i")==false) {
                $total_hours+=$this->getDayHours($emp_num,$year.'-'.$month.'-'.$i);
            }
                
                
        }
            return $total_hours;

    }
    public function getBasicWorkHours($emp_num,$month,$year)
    {
        $wp=WorkingPeriod::all()->first();
        $expectedworkhours=$this->get_time_difference($wp->sob, $wp->cob)-1;
        $total_hours=0;
        $days=cal_days_in_month(CAL_GREGORIAN,$month,$year);
        // return $expectedworkhours;
        for ($i=1; $i <=$days ; $i++) { 
            if (date('N',strtotime("$year-$month-$i"))<6 && $this->checkHoliday("$year-$month-$i")==false) {
                if ($this->getDayHours($emp_num,"$year-$month-$i")>=$expectedworkhours) {
                    $total_hours+=$expectedworkhours;
                }else{
                    $total_hours+=$this->getDayHours($emp_num,$year.'-'.$month.'-'.$i);
                }
            }
                
                
        }
            return $total_hours;

    }
    public function getOvertimeWeekdayHours($emp_num,$month,$year)
    {
        $wp=WorkingPeriod::all()->first();
        $expectedworkhours=$this->get_time_difference($wp->sob, $wp->cob)-1;
        $total_hours=0;
        $days=cal_days_in_month(CAL_GREGORIAN,$month,$year);
            for ($i=1; $i <=$days ; $i++) { 
                if (date('N',strtotime("$year-$month-$i"))<6 && $this->checkHoliday("$year-$month-$i")==false) {
                    if ($this->getDayHours($emp_num,"$year-$month-$i")>$expectedworkhours) {
                        $total_hours+=$this->getDayHours($emp_num,"$year-$month-$i")-$expectedworkhours;
                    }
                }
                    
                    
            }
            return $total_hours;
    }
    public function getOvertimeSaturdaysHours($emp_num,$month,$year)
    {
        $total_hours=0;
        $days=cal_days_in_month(CAL_GREGORIAN,$month,$year);
            for ($i=1; $i <=$days ; $i++) { 
                if (date('N',strtotime("$year-$month-$i"))==6 && $this->checkHoliday("$year-$month-$i")==false) {
                    $total_hours+=$this->getDayHours($emp_num,$year.'-'.$month.'-'.$i);
                }
                    
                    
            }
            return $total_hours;
    }
    public function getOvertimeSundaysHours($emp_num,$month,$year)
    {
        $total_hours=0;
        $days=cal_days_in_month(CAL_GREGORIAN,$month,$year);
            for ($i=1; $i <=$days ; $i++) { 
                if (date('N',strtotime("$year-$month-$i"))==7 && $this->checkHoliday("$year-$month-$i")==false) {
                    $total_hours+=$this->getDayHours($emp_num,$year.'-'.$month.'-'.$i);
                }
                    
                    
            }
            return $total_hours;
    }
    public function getOvertimeHolidayHours($emp_num,$month,$year)
    {
        
        $total_hours=0;
        $days=cal_days_in_month(CAL_GREGORIAN,$month,$year);
            for ($i=1; $i <=$days ; $i++) { 
                if ( $this->checkHoliday($year.'-'.$month.'-'.$i)==true) {
                    $total_hours+=$this->getDayHours($emp_num,$year.'-'.$month.'-'.$i);
                }
                    
                    
            }
            return $total_hours;
    }
    public function getExpectedHours($emp_num,$month,$year)
    {
        $wp=WorkingPeriod::all()->first();
        $expectedworkhours=$this->get_time_difference($wp->sob, $wp->cob)-1;
        $total_hours=0;
        $days=cal_days_in_month(CAL_GREGORIAN,$month,$year);
        // return $expectedworkhours;
        for ($i=1; $i <=$days ; $i++) { 
            if (date('N',strtotime("$year-$month-$i"))<6 && $this->checkHoliday("$year-$month-$i")==false) {
                
                    $total_hours+=$expectedworkhours;
                
            }
                
                
        }
            return $total_hours;
    }
    public function getExpectedDays($emp_num,$month,$year)
    {
        $wp=WorkingPeriod::all()->first();
        // $expectedworkhours=$this->get_time_difference($wp->sob, $wp->cob)-1;
        $total_days=0;
        $days=cal_days_in_month(CAL_GREGORIAN,$month,$year);
        // return $expectedworkhours;
        for ($i=1; $i <=$days ; $i++) { 
            if (date('N',strtotime("$year-$month-$i"))<6 && $this->checkHoliday("$year-$month-$i")==false) {
                
                    $total_days++;
                
            }
                
                
        }
            return $total_days;
    }
    public function checkHoliday($date)
    {
        $has_holiday=Holiday::whereDate('date', $date)->first();
        $retVal = ($has_holiday) ? true : false ;
        return $retVal;
    }
}

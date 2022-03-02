<?php

namespace App\Http\Controllers;
use App\AttendanceReport;
use App\Company;
use App\Department;
use App\LeaveRequest;
use App\Setting;
use App\UserDailyShift;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Traits\Attendance as AttendanceTrait;
use App\User;
use App\DailyAttendance;
use App\Attendance;
use App\Holiday;
use App\WorkingPeriod;
use App\Timesheet;
use App\TimesheetDetail;
use App\Shift;
use App\ShiftSchedule;
use App\UserShiftSchedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use MaddHatter\LaravelFullcalendar\Facades\Calendar;
use App\ShiftSwap;
use Auth;
use Excel;

class AttendanceController extends Controller
{ 
    use AttendanceTrait;
    public function __construct()
    {
      $this->middleware(['auth','uses_tams']);
    }
    public function dailyAttendance(Request $request)
    {
        if ($request->has('date')) {
            $date = Carbon::createFromFormat('m/d/Y', $request->date);
        } else {
            $date = Carbon::today();
        }
        $company_id = companyId();
        $users = User::where('company_id', $company_id)->get();
        if (Auth::user()->role->manages == 'dr'){
            $attendances = AttendanceReport::whereIn('user_id', $users->pluck('id')->toArray())
                ->whereDate('date', $date->format('Y-m-d'))->with('user')
                ->whereHas('user.managers',function($query){
                    $query->where('manager_id',Auth::id());})
                ->get();
        }
        elseif(Auth::user()->role->manages == 'all'){
            $attendances = AttendanceReport::whereIn('user_id', $users->pluck('id')->toArray())->whereDate('date', $date->format('Y-m-d'))->with('user')->get();
        }
        else{
            $attendances = AttendanceReport::whereIn('user_id', $users->pluck('id')->toArray())->whereDate('date', $date->format('Y-m-d'))->with('user')->get();
        }
        $earlys = $attendances->where('status', 'early')->count();
        $lates = $attendances->where('status', 'late')->count();
        $absentees = $attendances->where('status', 'absent')->count();
        $presents = $attendances->whereIn('status', ['early', 'late'])->count();
        $offs=UserDailyShift::whereIn('user_id', $users->pluck('id')->toArray())->whereDate('sdate',$date->format('Y-m-d'))->where('shift_id','7')->count();
        if ($request->type=='excel'){
            $view = 'attendance.excel.exceldailyAttendanceReport';
            $name=$date->format('d M, Y').' report';
            return \Excel::create($name, function ($excel) use ($view, $attendances, $users, $lates, $earlys, $date, $absentees,$name) {

                $excel->sheet($name, function ($sheet) use ($view, $attendances, $users, $lates, $earlys, $date, $absentees) {
                    $sheet->loadView("$view", compact('attendances', 'lates', 'earlys', 'date', 'absentees'))
                        ->setOrientation('landscape');
                });
            })->export('xlsx');
        }
        //return $attendances->first()->attendance->workflow_details;
        return view('attendance.new.dailyAttendanceReport', compact('attendances', 'users', 'lates', 'earlys', 'absentees', 'date', 'presents','offs'));
    }

    public function monthlyAttendance(Request $request)
    {
        if ($request->has('date')) {
            $date = Carbon::createFromFormat('m-Y', $request->date);
        } else {
            $date = Carbon::today();
        }
        $company_id = companyId();
        if (Auth::user()->role->manages == 'dr'){
            $users = User::where('company_id', $company_id)
                ->whereHas('user.managers',function($query){
                    $query->where('manager_id',Auth::id());})
                ->get();
        }
        elseif(Auth::user()->role->manages == 'all'){
            $users = User::where('company_id', $company_id)->get();
        }
        else{
            $users = User::where('company_id', $company_id)->get();
        }
        $calculated_days=$this->daysInMonth($date->format('Y'),$date->format('m'));
        $fridays=$calculated_days['fridays'];
        $saturdays=$calculated_days['saturdays'];
        $sundays=$calculated_days['sundays'];
        $holidays=Holiday::where('company_id',$company_id)->pluck('date')->toArray();
        $allusers=[];
        foreach ($users as $user) {
            $user_attendances = AttendanceReport::where('user_id', $user->id)->whereYear('date', $date->format('Y'))->whereMonth('date', $date->format('m'))->get();
            if (count($user_attendances)>0){
                $user['total_hours'] = $user_attendances->whereIn('status', ['late', 'early'])->sum('hours_worked');
                $user['overtime_worked'] = $user_attendances->whereIn('status', ['late', 'early'])->sum('overtime');
                $user['earlys'] = $user_attendances->where('status', 'early')->count();
                $user['lates'] = $user_attendances->where('status', 'late')->count();
                $user['offs'] = $user_attendances->where('status', 'off')->count();
                $user['absents'] = $user_attendances->where('status', 'absent')->count();

                $user['fridays'] = $user_attendances->whereIn('status', ['late', 'early'])->whereIn('date',$fridays)->count();
                $user['saturdays'] = $user_attendances->whereIn('status', ['late', 'early'])->whereIn('date',$saturdays)->count();
                $user['sundays'] = $user_attendances->whereIn('status', ['late', 'early'])->whereIn('date',$sundays)->count();
                $user['holidays'] = $user_attendances->whereIn('status', ['late', 'early'])->whereIn('date',$holidays)->count();

                $user['night_days'] = $user_attendances->whereIn('status', ['late', 'early'])->where('shift_name','Night Shift')->count();
                $present=$user['earlys']+ $user['lates'];
                $user['present'] = $present;
                $allusers[]=$user;
            }
        }
        $users=$allusers;
        if ($request->type=='excel'){
            $view = 'attendance.excel.excelmonthlyAttendanceReport';
            $name=$date->format('M, Y').' report';
            return \Excel::create($name, function ($excel) use ($view, $users, $date,$name) {

                $excel->sheet($name, function ($sheet) use ($view, $users, $date) {
                    $sheet->loadView("$view", compact('users', 'date'))
                        ->setOrientation('landscape');
                });

            })->export('xlsx');
        }
        if ($request->type=='csv'){
            $records=[];
            $ddate=$date->format('Yn');
            foreach ($users as $user){
                $work_through_hours=1;
                $weekend_public_days=$user->saturdays+$user->sundays+$user->holidays;
                $night_days=$user->night_days;
                $overtime_hours=$user->overtime_worked;
                if ($weekend_public_days>0){
                    $records[]="003;$user->emp_num;2530;$weekend_public_days;$ddate";//weekend_public days
                }
                if ($night_days>0){
                    $records[]="003;$user->emp_num;2541;$night_days;$ddate";//night days
                }
                if ($work_through_hours>0){
                    $records[]="003;$user->emp_num;2546;$work_through_hours;$ddate";//work_through
                }
                if ($overtime_hours>0){
                    $records[]="003;$user->emp_num;2546;$overtime_hours;$ddate";//Overtime
                }
            }
            $view = 'attendance.excel.excelamplitude';
            $name=$date->format('M, Y').' Amplitude export';
            return \Excel::create($name, function ($excel) use ($view,$records,$name) {

                $excel->sheet($name, function ($sheet) use ($view,$records) {
                    $sheet->loadView("$view", compact('records'))
                        ->setOrientation('landscape');
                });

            })->export('csv');
        }
        //return $users;
        return view('attendance.new.monthlyAttendanceReport', compact('users', 'date'));
    }
    private function daysInMonth($year,$month){
        $date=Carbon::create($year,$month,1);
        $daterange=Carbon::create($year,$month,1);
        $days= $date->daysInMonth;
        $saturdays=[];$sundays=[];$fridays=[];
        for ($i = 1; $i <=$days ; $i++) {
            $form = $daterange->format('Y-m-d');
            $dates[] = $form;
            if ($daterange->isSaturday()){
                $saturdays[]=$form;
            }
            if ($daterange->isSunday()){
                $sundays[]=$form;
            }
            if ($daterange->isFriday()){
                $fridays[]=$form;
            }
            $daterange = $daterange->addDay();
        }
        return ['dates'=>$dates,'sundays'=>$sundays,'saturdays'=>$saturdays,'fridays'=>$fridays];
    }

    public function latenessReport(Request $request){
        if ($request->filled('from') && $request->filled('to')) {
            $start = Carbon::createFromFormat('m/d/Y', $request->from)->subDay();
            $end = Carbon::createFromFormat('m/d/Y', $request->to);
        } else {
            $end = Carbon::today()->subDay();
            $start = Carbon::today()->subDay(7);
        }
        $days = $start->diffInDays($end);
        for ($i = 0; $i <= $days; $i++) {
            $start->addDay();
            $dates[] = $start->format('Y-m-d');
        }
        $start = $dates[0];
        $end = $dates[$days];
        $wp = WorkingPeriod::first();
        $datas = [];
        foreach ($dates as $date) {
                 if (Auth::user()->role->manages == 'dr'){
                     $ars=AttendanceReport::where('date', $date)->where('status', 'early')->whereHas('user')->with('attendance.attendancedetails')->with('user')
                         ->whereHas('user.managers',function($query){
                             $query->where('manager_id',Auth::id());})
                         ->get();
                 }
                 elseif(Auth::user()->role->manages == 'all'){
                     $ars=AttendanceReport::where('date', $date)->where('status', 'early')->whereHas('user')->with('attendance.attendancedetails')->with('user')->get();
                 }
                 else{
                     $ars=AttendanceReport::where('date', $date)->where('status', 'early')->whereHas('user')->with('attendance.attendancedetails')->with('user')->get();
                 }
            $users = $ars->where('status', 'early');
            $users2 = $ars->where('status', 'late');
            $datas[] = $users;
            $datas2[] = $users2;
        }
        return view('attendance.new.lateStaffReport', compact('datas', 'datas2', 'dates', 'start', 'end'));
    }

    public function staffAttendance($staff, Request $request)
    {
        $user = User::findorfail($staff);
        if ($request->filled('from') && $request->filled('to')) {
            $start = Carbon::parse($request->from)->subDay();
            $end = Carbon::parse( $request->to);
        } else {
            $end = Carbon::today();
            $start = Carbon::today()->subDay(7);
        }
        //return $dates[2];
        $attendances = AttendanceReport::where('user_id', $staff)->whereBetween('date', [$start->format('Y-m-d'), $end->format('Y-m-d')])->get();
        $start = $start->format('m/d/Y');
        $end = $end->format('m/d/Y');
        $staff = $user;
        if ($request->filled('type')) {
            // return $attendances;
            if ($request->type == 'excel') {
                $name = $user->name.' Report';
                $view = 'attendance.excel.excelSpecificStaffAttendance';
                $this->exportToExcel2($attendances, [], $view, $name);
            }
        }
        return view('attendance.new.specificStaffAttendance', compact('attendances', 'user', 'staff', 'start', 'end'));
    }
    public function UserMonthlyAttendance($user, $date)
    {
        $date = Carbon::createFromFormat('Y-m-d', $date);
        $details = AttendanceReport::where('user_id', $user)->whereYear('date', $date->format('Y'))->whereMonth('date', $date->format('m'))->get();
        return view('attendance.partials.monthlyDetails', compact('details'));
    }

	public function employeesSchedule(Request $request)
	{
		$date=date('Y-m-d',strtotime($request->date));
		$user_daily_shifts=\App\UserDailyShift::where('sdate',$date)->get();
		return view('attendance.partials.dayScheduleDetails', compact('user_daily_shifts','date'));
	}

    public function exportShiftSchedules(Request $request)
    {
        if ($request->filled('from') && $request->filled('to')) {
            $start = Carbon::createFromFormat('m/d/Y', $request->from)->subDay();
            $end = Carbon::createFromFormat('m/d/Y', $request->to);
        } else {
            $end = Carbon::today()->subDay();
            $start = Carbon::today()->subDay(7);
        }
        $days = $start->diffInDays($end);
        for ($i = 0; $i <= $days; $i++) {
            $start->addDay();
            $fullDays[] = $start->format('D');
            $fullWeeks[] = $start->format('W');
            $fullDates[] = $start->format('n/j');
            $dates[] = $start->format('Y-m-d');
            $datesFull[] = $start->format('D, M d, Y');
        }

        $company_id = companyId();
        if (Auth::user()->role->manages == 'dr'){
            $users = User::whereIn('status',[1,0])->where('company_id', $company_id)
                ->whereHas('user.managers',function($query){
                    $query->where('manager_id',Auth::id());});
        }
        elseif(Auth::user()->role->manages == 'all'){
            $users = User::whereIn('status',[1,0])->where('company_id', $company_id);
        }
        else{
            $users = User::whereIn('status',[1,0])->where('company_id', $company_id);
        }
        $users_shifts=UserDailyShift::whereIn('user_id',$users->pluck('id')->toArray())->whereIn('sdate',$dates)->with('shift')->get();
        $leave_requests=LeaveRequest::with('leave')->where('status','1')->get();
        if ($request->filled('department')){
            $dept=$request->department;
            $users=$users->whereHas('job.department', function ($query) use($dept){
                $query->where('departments.id',$dept);
            });
        }
        $users=$users->get();
        if ($request->filled('type') && $request->type=='excel'){
            $view = 'attendance.excel.excelShiftScheduleReport';
            $special='yes';
            $name='Shift Schedule report';
            return \Excel::create($name, function ($excel) use ($view, $users, $users_shifts,$special, $dates,$name,$leave_requests) {

                $excel->sheet($name, function ($sheet) use ($view, $users, $users_shifts,$special, $dates,$leave_requests) {
                    $sheet->loadView("$view", compact('users', 'users_shifts', 'special', 'dates','leave_requests'))
                        ->setOrientation('landscape');
                });
            })->export('xlsx');
        }
        if ($request->filled('type') && $request->type=='pdf'){
            $special='yes';
            $view = 'attendance.excel.pdfShiftScheduleReport';
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadView($view,compact('users', 'users_shifts', 'special', 'dates','fullDays','fullWeeks',
                'fullDates','leave_requests','datesFull'))->setPaper('a4', 'landscape');
            return $pdf->stream();
        }
        if ($request->filled('type') && $request->type=='html'){
            $special='yes';
            $view = 'attendance.excel.pdfShiftScheduleReport';
            return view($view,compact('users', 'users_shifts', 'special', 'dates','fullDays','fullWeeks',
                'fullDates','leave_requests','datesFull'));
        }
    }


	public function myShiftSchedule()
	{
		$user=Auth::user();
		return view('attendance.userShiftSchedule', compact('user'));
	}
	public function userShiftSchedule($user_id)
	{
		$user=User::find($user_id);
		return view('attendance.userShiftSchedule', compact('user'));
	}
	public function myAttendance($user_id)
	{
		$user=User::find($user_id);
		return view('attendance.userAttendance', compact('user'));
		
	}
    public function myAttendanceCal()
    {
        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        return view('attendance.userAttendance', compact('user'));
    }

    public function myAttendanceCalendar(Request $request, $user_id)
    {
        $user = User::find($user_id);
        $dispemp = [];
        $startdate = $request->start;
        $enddate = $request->end;
        $begin = new \DateTime($startdate);
        $end = new \DateTime($enddate . '+1 days');
        $interval = \DateInterval::createFromDateString('1 day');
        $period = new \DatePeriod($begin, $interval, $end);
        foreach ($period as $dt) {
            $attendancereport = AttendanceReport::where(['user_id' => $user->id, 'date' => $dt->format(" Y-m-d")])->first();
            $attendance = Attendance::where(['emp_num' => $user->emp_num, 'date' => $dt->format(" Y-m-d")])->first();
            if ($attendancereport) {
                if ($attendancereport->status=='early'){
                    $color='#3aac76';
                }
                elseif($attendancereport->status=='late'){
                    $color='#3aac76';
                }
                elseif($attendancereport->status=='off'){

                }
                elseif($attendancereport->status=='absent'){
                    $color='#be3030';
                }
                $dispemp[] = [
                    'title' => ucfirst($attendancereport->status),
                    'start' => $attendancereport->date . 'T' . $attendancereport->first_clockin,
                    'end' => $attendance->date . 'T' . $attendancereport->last_clockout,
                    'color' =>$color,
                    'id' => ($attendancereport->attendance_id) ? $attendancereport->attendance_id : ''
                ];
            } else {
                 $dispemp[]=[
                   	'title'=>'No Data',
                   	'start'=>$dt->format("Y-m-d"),
                   	'end'=>$dt->format(" Y-m-d"),
                   	'color' =>'#be3030',
                   	'id'=>''];
            }
        }

        if (isset($dispemp)):
            return response()->json($dispemp);
        else:
            $dispemp = ['title' => 'Nil', 'start' => '2018-09-09'];
            return response()->json($dispemp);
        endif;
    }
    public function shiftUploadedCalendar(Request $request)
    {
        $company_id=companyId();
        $shifts=Shift::where('company_id',$company_id)->pluck('id')->toArray();
        $dispemp = [];
        $startdate = $request->start;
        $enddate = $request->end;
        $begin = new \DateTime($startdate);
        $end = new \DateTime($enddate . '+1 days');
        $interval = \DateInterval::createFromDateString('1 day');
        $period = new \DatePeriod($begin, $interval, $end);
        foreach ($period as $dt) {
            $date=$dt->format("Y-m-d");
            $exists=UserDailyShift::where('sdate',$dt->format(" Y-m-d"))->whereIn('shift_id',$shifts)->first();
            $color = '#3aac76';//green
            $message = 'Shift Uploaded. Click to view';
            if (!$exists) {
                $color='#be3030';//red
                $message = 'No shift Uploaded yet';
            }
                $dispemp[] = [
                    'title' => $message,
                    'start' => $date,
                    'end' => $date,
                    'color' =>$color,
                    'id' => ''
                ];
        }
        return response()->json($dispemp);
    }
	public function userShiftScheduleCalendar(Request $request,$user_id){
        $user=User::find($user_id);
        $dispemp=[];
        $startdate=$request->start;
        $enddate=$request->end;
        $shift_schedules=UserDailyShift::where('user_id',$user->id)->whereBetween('sdate',[$startdate,$enddate])->with('shift')->get();
        $begin = new \DateTime($startdate);
        $end = new \DateTime($enddate . '+1 days');
        $interval = \DateInterval::createFromDateString('1 day');
        $period = new \DatePeriod($begin, $interval, $end);

        foreach ($period as $dt) {
            $date=$dt->format("Y-m-d");
            $shift_schedule=$shift_schedules->where('sdate',$date)->first();
            if ($shift_schedule) {
                $color = '#3aac76';//green
                $message = $shift_schedule->shift->type;
                $start=$date;
                //$start=$date.'T'.$shift_schedule->shift->start_time,;
                //$end=$date.'T'.$shift_schedule->shift->end_time;
                $end=$date;
            }
            else{
                $color='#be3030';//red
                $message = 'No Shift';
                $start=$date;
                $end=$date;
            }
            $dispemp[] = [
                'title' => $message,
                'start' => $start,
                'end' => $end,
                'color' =>$color,
                'id' => ''
            ];
        }
        return response()->json($dispemp);
    }
	public function userShiftScheduleDetails(Request $request,$id)
	{
		$user_shift_schedule=UserShiftSchedule::find($id);
		$shift_id=$user_shift_schedule->shift_id;
		$users=UserShiftSchedule::find($id)
		->schedule
		->users()
		->whereHas('usershiftschedules.shift' ,function ($query) use($shift_id) {
				    $query->where('shift_id', '!=', $shift_id);
				})
		->get();
		$shifts=Shift::where('id','!=',$shift_id)->get();
		return response()->json(['users'=>$users,'shifts'=>$shifts]);
	}
	public function swapShift(Request $request)
	{
		$userShiftSchedule=UserShiftSchedule::find($request->user_shift_schedule_id);
		$shiftSwap=ShiftSwap::where(['owner_id'=>Auth::user()->id,'user_shift_schedule_id'=>$request->user_shift_schedule_id,'date'=>$request->date])->first();
		if ($shiftSwap) {
			return  response()->json('exists',401);
		}

		ShiftSwap::create(['owner_id'=>Auth::user()->id,'swapper_id'=>$request->swapper_id,'approved_by'=>0,'user_shift_schedule_id'=>$request->user_shift_schedule_id,'status'=>0,'reason'=>$request->reason,'new_shift_id'=>$request->shift_id,'date'=>$request->date]);
		return  response()->json('success',200);
	}
	public function myShiftSwaps()
	{
		$initiatedShiftSwaps=ShiftSwap::where(['owner_id'=>Auth::user()->id])->get();
		$suggestedShiftSwaps=ShiftSwap::where(['swapper_id'=>Auth::user()->id])->get();
		return view('attendance.myShiftSwaps',compact('initiatedShiftSwaps','suggestedShiftSwaps'));
	}
	public function shiftSwaps()
	{
		$shiftswaps=ShiftSwap::all();
		return view('attendance.shiftSwaps',compact('shiftswaps'));
	}
	public function cancelShiftSwap($shiftSwap_id)
	{
		$shiftSwap=ShiftSwap::where(['owner_id'=>Auth::user()->id,'id'=>$shiftSwap_id,'status'=>0]);
		if ($shiftSwap) {
			$shift->delete();
			return  response()->json('success',200);
		}else{
			return response()->json('denied',200);
		}
	}
	public function rejectShiftSwap($shiftSwap_id)
	{
		$shiftSwap=ShiftSwap::where(['id'=>$shiftSwap_id,'status'=>0]);
		if ($shiftSwap) {
			$shift->update(['status' => 2,'approved_by'=>Auth::user()->id]);
			return  response()->json('success',200);
		}else{
			return response()->json('denied',200);
		}
	}
	public function approveShiftSwap($shiftSwap_id)
	{
		$shiftSwap=ShiftSwap::where(['id'=>$shiftSwap_id,'status'=>0]);
		if ($shiftSwap) {
            $shiftSwap->update(['status' => 1,'approved_by'=>Auth::user()->id]);
			return  response()->json('success',200);
		}else{
			return response()->json('denied',200);
		}
	}
	public function shift_schedules(){
		$shift_schedules=ShiftSchedule::all();
		return view('attendance.shiftSchedules',compact('shift_schedules'));
	}
	public function shift_schedule_details($shift_schedule_id){
		$shift_schedule=ShiftSchedule::find($shift_schedule_id);

		return view('attendance.viewShiftSchedule',compact('shift_schedule'));
	}
	public function queueTimesheet($month=0,$year=0)
	{
		// \Artisan::queue('timesheet:month',['month' => $month,'year'=>$year, '--queue' => 'default']);
		\Artisan::call('queue:work');
		return redirect('timesheets');
	}
	public function timesheetExcel($timesheet_id)
	{
		$timesheet=Timesheet::find($timesheet_id);
		$holidays=Holiday::whereMonth('date',$timesheet->month)->whereYear('date',$timesheet->year)->get();
		$view='attendance.exceltimesheet';
		$this->exportToExcel($timesheet,$holidays,$view);

	}
	private function exportToExcel($datas,$holidays,$view){

        return     \Excel::create("$view", function($excel) use ($datas,$view,$holidays) {

            $excel->sheet("$view", function($sheet) use ($datas,$view,$holidays) {
                $sheet->loadView("$view",compact("datas","holidays"))
                ->setOrientation('landscape');
            });

        })->export('xlsx');
    }


	public function timesheets()
	{
		$timesheets=Timesheet::all();
		return view('attendance.timesheet',compact('timesheets'));
	}
	public function timesheetDetail($timesheet_id)
	{
		$timesheet=Timesheet::find($timesheet_id);
		return view('attendance.timesheetdetails',compact('timesheet'));
	}
	public function userTimesheetDetail($user_id)
	{
		// $user=User::find($user_id);
		$detail=TimesheetDetail::where('user_id',$user_id)->get()->first();
		return view('attendance.partials.userTimesheetDetails',compact('detail'));
	}
	public function getDetails($attendance_id)
	{
		$attendancedetails=Attendance::find($attendance_id)->attendancedetails;
		return view('attendance.partials.attendanceDetails',compact('attendancedetails'));
	}

	public function getWorkingDays(Request $request)
	{
		$timesheet=[];
		$tdays=[];
		$users=User::all();
		$count=$users->count();
		$days=cal_days_in_month(CAL_GREGORIAN,$request->month,$request->year);
		$total_hours=0;
		foreach ($users as $user) {
			
			$monthHours=$this->getMonthHours($user->emp_num,$request->month,$request->year);
			$weekdayHours=$this->getWeekdayHours($user->emp_num,$request->month,$request->year);
			$basicWorkHours=$this->getBasicWorkHours($user->emp_num,$request->month,$request->year);
			$overtimeWeekdayHours=$this->getOvertimeWeekdayHours($user->emp_num,$request->month,$request->year);
			$overtimeSaturdaysHours=$this->getOvertimeSaturdaysHours($user->emp_num,$request->month,$request->year);
			$overtimeSundaysHours=$this->getOvertimeSundaysHours($user->emp_num,$request->month,$request->year);
			$overtimeHolidaysHours=$this->getOvertimeHolidayHours($user->emp_num,$request->month,$request->year);
			$expectedworkhours=$this->getExpectedHours($user->emp_num,$request->month,$request->year);
			$expectedworkdays=$this->getExpectedDays($user->emp_num,$request->month,$request->year);
			$timesheet[$user->id]['sn']=$count;
			$timesheet[$user->id]['badge_no']=$user->emp_num;
			$timesheet[$user->id]['name']=$user->name;
			$timesheet[$user->id]['position']=$user->position->name;
			$timesheet[$user->id]['staff_location']=$user->position->name;
			$timesheet[$user->id]['category']=$user->position->name;
			$timesheet[$user->id]['employee_type']=$user->position->name;
			// $timesheet[$user->id]['cost_center']=$user->cost_center->code;
			for ($i=1; $i <=$days ; $i++) { 

				$timesheet[$user->id][$i]=$this->getDayHours($user->emp_num,$request->year.'-'.$request->month.'-'.$i);
				$tdays[$user->id][$i]=$timesheet[$user->id][$i];
				$total_hours+=$timesheet[$user->id][$i];
			}
			$timesheet[$user->id]['total_hours']=$total_hours;
			$timesheet[$user->id]['weekdayHours']=$weekdayHours;
			$timesheet[$user->id]['basicWorkHours']=$basicWorkHours;
			$timesheet[$user->id]['overtimeWeekdayHours']=$overtimeWeekdayHours;
			$timesheet[$user->id]['overtimeSaturdaysHours']=$overtimeSaturdaysHours;
			$timesheet[$user->id]['overtimeHolidaysHours']=$overtimeHolidaysHours;
			$timesheet[$user->id]['overtimeSundaysHours']=$overtimeSundaysHours;
			$timesheet[$user->id]['monthHours']=$monthHours;
			$timesheet[$user->id]['expectedworkhours']=$expectedworkhours;
			$timesheet[$user->id]['expectedworkdays']=$expectedworkdays;
			$timesheet[$user->id]['test']=$this->testTime("$request->year-$request->month-25");
			

		}
		return $timesheet;
		// $attendances = Attendance::where('emp_num'=>$request->emp_num)
		// 			->whereMonth('created_at', '7')
		// 			 ->whereYear('created_at', '2018')
		// 			 ->get();
		//  foreach ($attendances as $attendance) {
		//  	$attendance->attendancedetails;
		//  }
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
		$days=cal_days_in_month(CAL_GREGORIAN,$month,$year);
		for ($i=1; $i <=$days ; $i++) { 
				$total_hours+=$this->getDayHours($emp_num,$year.'-'.$month.'-'.$i);
				
			}
			return $total_hours;
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
	public function testTime($date)
	{
		$wp=WorkingPeriod::all()->first();
		$hours=0;
		$diff=0;
		$time='';
		// dd($date);
		$attendance=Attendance::whereDate('date', $date)->first();
		if ($attendance) {
			$details = \App\AttendanceDetail::whereHas('attendance', function ($query) use ($date) {
				    $query->whereDate('date', $date);
				})->orderBy('id','desc')->get();
			// $details=$attendance->attendancedetails->orderBy('id','desc');
		// $acount=$attendance->attendancedetails->count();
			$time=$details->first()->clock_in;
			foreach ($details as $detail) {
				$hours+=$this->get_time_difference($detail->clock_in, $detail->clock_out);
			}
			$diff=$this->get_time_difference($time,$wp->sob);


		}
		
			return $diff;
	}
	public function viewAttendanceCalendar($value='')
	{
		return view('xtrafeature.attendance360');
	}
	public function displayCalendar(Request $request)
	{
		try {
			$attendances=DailyAttendance::whereBetween('date',[$request->start,$request->end])->get();
			
			
		$emps=\DB::table('users')
						->join('daily_attendance','users.emp_num','=','daily_attendance.emp_num')
						->select('users.name','daily_attendance.clock_in as startdate')
						->whereBetween('daily_attendance.date',[$request->start,$request->end])
						->get();
		 
			foreach($emps as $empres):
			
			$dispemp[]=['title'=>$empres->name,'start'=>$empres->startdate];
			 
			endforeach;
			if(isset($dispemp)):
			return response()->json($dispemp);
			else:
			$dispemp=['title'=>'Nil','start'=>'2016-09-09'];
			return response()->json($dispemp);
			endif;
			
		}
		
		catch(\Exception $ex){
			
			return response()->json("Error:$ex");
		}
	}

    public function employeeShiftSchedules(Request $request)
	{
        $company_id=companyId();
        $today=Carbon::today();
        $departments=Department::where('company_id',$company_id)->get();
        return view('attendance.empShiftSchedules',compact('today','departments'));
	}
    private function exportToExcel2($datas, $dates, $view, $name)
    {

        return \Excel::create("$name", function ($excel) use ($datas, $view, $dates, $name) {

            $excel->sheet("$name", function ($sheet) use ($datas, $view, $dates) {
                $sheet->loadView("$view", compact("datas", "dates"))
                    ->setOrientation('landscape');
            });

        })->export('xlsx');
    }

    public function downloadShiftUploadTemplate(Request $request)
    {
        $heading = ['Employee Name', 'Staff ID'];
        if ($request->filled('from')) {
            $date = Carbon::createFromFormat('m/d/Y', $request->from);
        } else {
            $date = Carbon::today();
        }
        for ($i = 1; $i <= 14; $i++) {
            $form = $date->format('m/d/Y');
            $date = $date->addDay();
            $heading[] = $form;
        }
        $company_id = companyId();
        $users = User::whereIn('status',[0,1])->where('company_id',$company_id);
        $dept_name='All Staff';
        if ($request->filled('department')){
            $dept_name=Department::find($request->department)->name;
            $dept=$request->department;
            $users=$users->whereHas('job.department', function ($query) use($dept){
                $query->where('departments.id',$dept);
            });
        }
        $users=$users->get();
        $users=$users->map(function ($name) {
            return ['Staff Id'=>$name->emp_num,'Employee Name'=>$name->name];
        });
        $shifts = \App\Shift::where('company_id',$company_id)->orwhere('company_id','1')->select('type as Shift Name', 'start_time as Starts', 'end_time as Ends', 'id as Shift ID')->get()->toArray();
        return $this->exportshiftexcel($dept_name.' template', ['users' => $users, 'shifts' => $shifts], $heading);
    }
    private function exportshiftexcel($worksheetname, $data, $heading)
    {
        return \Excel::create($worksheetname, function ($excel) use ($data, $heading) {
            foreach ($data as $sheetname => $realdata) {
                $excel->sheet($sheetname, function ($sheet) use ($realdata, $sheetname, $heading) {

                    if ($sheetname == 'users') {
                        $sheet->row(1, $heading);
                    }
                    $sheet->fromArray($realdata);
                    if ($sheetname == 'shifts') {
                        $sheet->_parent->addNamedRange(
                            new \PHPExcel_NamedRange(
                                'sdf', $sheet->_parent->getSheet(1), "A2:A" . $sheet->_parent->getSheet(1)->getHighestRow()
                            )
                        );
                        for ($j = 2; $j <= 200; $j++) {
                            foreach (range('C','P') as $letter){
                                $objValidation = $sheet->_parent->getSheet(0)->getCell("$letter$j")->getDataValidation();
                                $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                                $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                                $objValidation->setAllowBlank(false);
                                $objValidation->setShowInputMessage(true);
                                $objValidation->setShowErrorMessage(true);
                                $objValidation->setShowDropDown(true);
                                $objValidation->setErrorTitle('Input error');
                                $objValidation->setError('Value is not in list.');
                                $objValidation->setPromptTitle('Pick Shift ID from list');
                                $objValidation->setPrompt('Please pick a value from the drop-down list.');
                                $objValidation->setFormula1('sdf');
                            }
                        }
                    }

                });
            }
        })->download('xlsx');
    }




    public function importUserShifts(Request $request)
    {
        $company_id = companyId();
        if ($request->hasFile('template')) {
            $datas = \Excel::load($request->file('template')->getrealPath(), function ($reader) {
                $reader->noHeading();
                // $reader->formatDates(true, 'Y-m-d');
            })->get();
            $dates = [];
            $user = '';
            foreach ($datas[0] as $dkey => $data) {
                if ($dkey == 0) {
                    foreach ($data as $key => $value) {
                        if ($key != 0) {
                            $dates[$key] = $value;
                        }
                    }
                }
                else {
                    foreach ($data as $key => $value) {
                        if ($key == 0) {
                            $user = \App\User::where('emp_num', $value)->first();
                        }
                        else {
                            $shift = \App\Shift::where('type', $value)->whereIn('company_id',[$company_id,1])->first();
                            if ($shift) {
                                $sd = date('Y-m-d', strtotime($dates[$key]));
                                $sdt = date('Y-m-d H:i:s', strtotime($sd . $shift->start_time));
                                if ($shift->start_time > $shift->end_time) {
                                    $edt = date('Y-m-d H:i:s', strtotime($sd . $shift->end_time . '+1 day'));
                                } else {
                                    $edt = date('Y-m-d H:i:s', strtotime($sd . $shift->end_time));
                                }
                                if ($user) {
                                    \App\UserDailyShift::updateOrCreate(['user_id' => $user->id, 'sdate' => $sd], ['user_id' => $user->id, 'shift_id' => $shift->id, 'starts' => $sdt, 'ends' => $edt, 'sdate' => $sd]);
                                }
                            }
                        }
                    }
                }
            }
        }
        return 'success';
    }

    public function appScheduleShift(Request $request){
        $company_id=companyId();
        $date=Carbon::today();
        $daterange=Carbon::today();
        $departments=Department::where('company_id',$company_id)->get();
        $days=7;
        if (Auth::user()->role->manages == 'dr'){
            $users = User::where('company_id', $company_id)->whereIn('status',[0,1])
                ->whereHas('user.managers',function($query){
                    $query->where('manager_id',Auth::id());});
        }
        elseif(Auth::user()->role->manages == 'all'){
            $users = User::where('company_id', $company_id)->whereIn('status',[0,1]);
        }
        else{
            $users = User::where('company_id', $company_id)->whereIn('status',[0,1]);
        }

        if ($request->filled('department')){
            $dept=$request->department;
            $users=$users->whereHas('job.department', function ($query) use($dept){
                $query->where('departments.id',$dept);
            });
        }
        if ($request->filled('date')){
            $date=Carbon::parse($request->date);
            $daterange=Carbon::parse($request->date);
        }
        if ($request->filled('days')){
            $days=$request->days;
        }
        for ($i = 1; $i <=$days ; $i++) {
            $form = $daterange->format('Y-m-d');
            $daterange = $daterange->addDay();
            $dates[] = $form;
        }
        $users=$users->get();
        $users_shifts=UserDailyShift::whereIn('user_id',$users->pluck('id')->toArray())->whereIn('sdate',$dates)->with('shift')->get();
        $shifts=Shift::where('company_id',$company_id)->get();
        $leave_requests=LeaveRequest::with('leave')->where('status','1')->get();
        if ($request->filled('type')){
            $view = 'attendance.excel.excelShiftScheduleReport';
            $special='yes';
            $name='Shift Schedule report';
            return \Excel::create($name, function ($excel) use ($view, $users, $users_shifts,$special, $dates,$name,$leave_requests) {

                $excel->sheet($name, function ($sheet) use ($view, $users, $users_shifts,$special, $dates,$leave_requests) {
                    $sheet->loadView("$view", compact('users', 'users_shifts', 'special', 'dates','leave_requests'))
                        ->setOrientation('landscape');
                });
            })->export('xlsx');
        }
        return view('attendance.new.app_schedule_shift',compact('date','departments','users','dates','users_shifts','shifts','leave_requests'));
    }

    public function appScheduleShiftSubmit(Request $request){
        //return $request->all();
        $company_id=companyId();
        foreach ($request->shift as $key => $users_shifts) {
            $user_id = $key;
            $user = \App\User::where('id', $user_id)->first();
            if ($user) {
                foreach ($users_shifts as $key2 => $shift_id) {
                    if (isset($shift_id)) {
                        $date = $key2;
                        $shift = \App\Shift::where('id', $shift_id)->whereIn('company_id', [$company_id, 1])->first();
                        if ($shift) {
                            if ($shift->start_time > $shift->end_time) {
                                $ends = date('Y-m-d H:i:s', strtotime($date . $shift->end_time . '+1 day'));
                            } else {
                                $ends = date('Y-m-d H:i:s', strtotime($date . $shift->end_time));
                            }
                            $starts = date('Y-m-d H:i:s', strtotime($date . $shift->start_time));
                            \App\UserDailyShift::updateOrCreate(['user_id' => $user->id, 'sdate' => $date],
                                ['user_id' => $user->id, 'shift_id' => $shift->id, 'starts' => $starts, 'ends' => $ends, 'sdate' => $date]);
                        }
                    }
                }
            }
        }

        return 'success';
    }
}
<?php
namespace App\Traits;
use App\Project;
use App\ProjectTask;
use App\Client;
use App\ProjectTimesheetDetail;
use App\User;
use Auth;
use Illuminate\Http\Request;

/**
 *
 */
trait ProjectTrait
{
	public function processGet($route,Request $request)
	{
		switch ($route) {
			case 'get_project':
			return $this->getProject($request);
			break;
			case 'view_project':
			return $this->viewProject($request);
			break;
			case 'get_project_task':
			return $this->getProjectTask($request);
			break;
			case 'get_project_members':
			return $this->getProjectMembers($request);
			break;
			case 'project_tasks':
			return $this->projectTasks($request);
			break;
			case 'project_members':
			return $this->projectMembers($request);
			break;
			case 'project_details':
			return $this->projectDetails($request);
			break;
			case 'delete_project_task':
			return $this->deleteProjectTask($request);
			break;
			case 'delete_project':
			return $this->deleteProject($request);
			break;
			case 'change_project_status':
			return $this->changeProjectStatus($request);
			break;
			case 'change_project_task_status':
			return $this->changeProjectTaskStatus($request);
			break;
			case 'timesheet':
				return $this->projectTimesheet($request);
				break;
			case 'timesheet_calendar_json':
				return $this->timesheetCalendarJson($request);
				break;
			case 'delete_timesheet_detail':
				return $this->deleteTimesheetDetail($request);
				break;
			case 'get_month_report':

				return $this->get_month_report($request);
				break;
			case 'project_hours_report':

				return $this->project_hours_report($request);
				break;
				
					
			
			default:
				# code...
				break;
		}
	}

	public function processPost(Request $request)
	{
		switch ($request->type) {
			case 'project':
			return $this->saveProject($request);
			break;
			case 'task':
			return $this->saveTask($request);
			break;
			case 'project_member':

			return $this->saveProjectMembers($request);
			break;
			case 'timesheet':

				return $this->saveProjectTImesheet($request);
				break;
			case 'timesheet_detail':

			return $this->saveProjectTImesheetDetail($request);
			break;
			case 'multiple_days_timesheet_detail':

				return $this->saveMultipleDaysProjectTImesheetDetail($request);
				break;
				
				
			
			default:
				# code...
				break;
		}
	}

	public function getProject(Request $request)
	{
		return $project=Project::find($request->project_id);
		
	}
	public function viewProject(Request $request)
	{
		 $project=Project::find($request->project_id);
		 $clients=Client::all();
		 return view('project_management.project_details',compact('project','clients'));
		
	}
	public function getProjectTask(Request $request)
	{
		return $task=Project::find($request->project_task_id);
		
	}
	public function projectTasks(Request $request)
	{
		 $project=Project::find($request->project_id);
		return view('project_management.partials.tasks',compact('project'));
	}
	public function projectMembers(Request $request)
	{
		 $project=Project::find($request->project_id);
		return view('project_management.partials.members',compact('project'));
	}
	public function projectDetails(Request $request)
	{
		 $project=Project::find($request->project_id);
		return view('project_management.partials.details',compact('project'));
	}
	public function getProjectMembers(Request $request)
	{
		$members=Project::find($request->project_id)->project_members;
		return ['project_id'=>$request->project_id,'members'=>$members];
		
	}
	public function deleteProject(Request $request)
	{
		$project=Project::find($request->project_id);
		if ($project) {
			$project->delete();
		}
		return 'success';
	}
	public function deleteProjectTask(Request $request)
	{
		$task=ProjectTask::find($request->project_task_id);
		if ($task) {
			$task->delete();
		}
		return 'success';
	}
	public function changeProjectStatus(Request $request)
	  {
	   $project=Project::find($request->project_id);
	   if ($project) {
	     $project->update(['status'=>1]);
	   }
	   return 'success';
	  
	  }
	  public function changeProjectTaskStatus(Request $request)
	  {
	   $task=ProjectTask::find($request->project_task_id);
	   if ($task) {
	     $task->update(['status'=>1]);
	   }
	   return 'success';
	  
	  }

	  public function saveProject(Request $request)
	  {

	  	$project=Project::updateOrCreate(['id'=>$request->id],['name'=>$request->name
		  ,'project_manager_id'=>$request->project_manager_id,
		  'fund_code'=>$request->fund_code,
		  'start_date'=>$request->start_date,
		  'end_est_date'=>$request->end_est_date,
		  'actual_ending_date'=>$request->actual_ending_date,
		  'remark'=>$request->remark,
		  'description'=>$request->description,'client_name'=>$request->client_name,'created_by'=>Auth::user()->id,'status'=>0,'company_id'=>companyId()]);
	  	
        return 'success';
	  }
	  public function saveProjectTask(Request $request)
	  {
	  	$task=ProjectTask::updateOrCreate(['id'=>$request->id],['name'=>$request->name,'project_id'=>$request->project_id,'froms'=>$request->froms,'tos'=>$request->tos,'status'=>$request->status]);
	  	
        return 'success';
	  }
	  public function saveProjectMembers(Request $request)
	  {
	  	  $request->all();
	  	$project=Project::find($request->project_id);
	   $project_members_count=count($request->input('project_members'));

	  	 if($project_members_count>0){
      		$project->project_members()->detach();
              for ($i=0; $i <$project_members_count ; $i++) {
                if ($request->project_members[$i]!=0) {
                  $project->project_members()->attach($request->project_members[$i]);
                }
            
            }
        }
        return 'success';
	  }
	  public function ProjectTimesheet(Request $request)
	{
		$user = \Auth::user();
		 $projects=Project::all();
		 $holidays = \App\Holiday::where(['status' => 1, 'company_id' => companyId()])->whereYear('date', date('Y'))->whereMonth('date', date('m'))->pluck('date')->toArray();//array('2012-09-07');
		$holiday_hours=0;
		$timesheet_sum=[];
		foreach($holidays as $holiday)
		{
			$ld = new \DateTime($holiday);
            $curr = $ld->format('D');
			if($curr == 'Mon' || $curr == 'Tue'|| $curr == 'Wed'|| $curr == 'Thu'){
				$holiday_hours+=9;
			}elseif($curr == 'Fri'){
				$holiday_hours+=4;
			}
		}
		$leave_hours=0;
		$used_leave_days = \App\LeaveRequestDate::whereYear('date', date('Y'))
		->whereMonth('date', date('m'))->whereHas('leave_request', function ($query) use ($user) {
			$query->where('leave_requests.user_id', $user->id)
				->where('status', 1);
		})->pluck('date')->toArray();
		foreach($used_leave_days as $leave_day)
		{
			
			$ld = new \DateTime($leave_day);
            $curr = $ld->format('D');
			if($curr == 'Mon' || $curr == 'Tue'|| $curr == 'Wed'|| $curr == 'Thu'){
				$leave_hours+=9;
			}elseif($curr == 'Fri'){
				$leave_hours+=4;
			}
		}
		$total_hours=0;
		$details=\App\ProjectTimesheetDetail::whereYear('date', date('Y'))
		->whereMonth('date', date('m'))
		->where('user_id',$user->id)->get();
		foreach($projects as $project){
			$timesheet_sum[$project->id]['name']=$project->name;
			$timesheet_sum[$project->id]['fund_code']=$project->fund_code;
			$timesheet_sum[$project->id]['sum']=0;
		}

		foreach($details as $detail){
			$timesheet_sum[$detail->project_id]['sum']+=$detail->hours;
			$total_hours+=$detail->hours;
		
		}
		 $timesheet_sum;
		
		return view('project_management.timesheet',compact('projects','holiday_hours','leave_hours','total_hours','timesheet_sum'));
	}
	public function timesheetCalendarJson(Request $request)
    {


        $user = \Auth::user();
        $dispemp = [];
        $startdate = $request->start;
        $enddate = $request->end;
		$details=\App\ProjectTimesheetDetail::whereBetween('date', [$startdate, $enddate])
		->where('user_id',$user->id)->get();
		foreach($details as $detail){
			$dispemp[] = [
				'title' => $detail->project->name.' ('.$detail->hours.' Hours)',
				'start' => $detail->date . 'T' . '00:00:00',
				'end' => $detail->date . 'T' . '11:59:59',
				'color' => '#1A237E',
				'hours'=>$detail->hours,
				'lin_code'=>$detail->lin_code,
				'project_id'=>$detail->project_id,
				'id' => $detail->id];
		
		}
		$holidays = \App\Holiday::where([ 'company_id' => companyId()])->whereYear('date', date('Y',strtotime($startdate)))
		->whereMonth('date', date('m',strtotime($startdate)))->get();//array('2012-09-07');
		$holiday_hours=0;
		foreach($holidays as $holiday)
		{
			$hours=0;
			$ld = new \DateTime($holiday->date);
            $curr = $ld->format('D');
			if($curr == 'Mon' || $curr == 'Tue'|| $curr == 'Wed'|| $curr == 'Thu'){
				$hours+=9;
			}elseif($curr == 'Fri'){
				$hours+=4;
			}
			$dispemp[] = [
				'title' => $holiday->title.' ('.$hours.' Hours)',
				'start' => date('Y-m-d',strtotime($holiday->date)) . 'T' . '00:00:00',
				'end' =>  date('Y-m-d',strtotime($holiday->date)) . 'T' . '11:59:59',
				'color' => '#E65100',
				'id' => 0,
				'rendering'=>'background'];
		}
		$used_leave_days = \App\LeaveRequestDate::whereYear('date', date('Y',strtotime($startdate)))
		->whereMonth('date', date('m',strtotime($startdate)))->whereHas('leave_request', function ($query) use ($user) {
			$query->where('leave_requests.user_id', $user->id)
				->where('status', 1);
		})->get();
		foreach($used_leave_days as $leave_day)
		{
			$hours=0;
			$ld = new \DateTime($leave_day->date);
            $curr = $ld->format('D');
			if($curr == 'Mon' || $curr == 'Tue'|| $curr == 'Wed'|| $curr == 'Thu'){
				$hours+=9;
			}elseif($curr == 'Fri'){
				$hours+=4;
			}
			$dispemp[] = [
				'title' => $leave_day->leave_request->leave_name.' ('.$hours.' Hours)',
				'start' => $leave_day->date . 'T' . '00:00:00',
				'end' => $leave_day->date . 'T' . '11:59:59',
				'color' => '#33691E',
				'id' => 0,
				'rendering'=>'background'];
		}
       
        $colours = ['#67a8e4', '#f32f53', '#77c949', '#FFC1CC', '#ffbb44', '#f32f53', '#67a8e4'];
        

        if (isset($dispemp)):
            return response()->json($dispemp);
        else:
            $dispemp = ['title' => 'Nil', 'start' => date('Y-m-d')];
            return response()->json($dispemp);
        endif;


    }
	public function deleteTimesheetDetail(Request $request)
	{
		$detail=ProjectTimesheetDetail::find($request->detail_id);
		if($detail){
			$detail->delete();
			return 'success';
		}
		return 'failed';
		

	}
	public function saveMultipleDaysProjectTImesheetDetail(Request $request)
	{
		$user = \Auth::user();
        $company_id = companyId();
		$result=$this->LeaveDaysRange($request->start_date, $request->end_date,$user);
		foreach($result['dates'] as $date){
			$details_previous_sum=ProjectTimesheetDetail::where(['date'=>date('Y-m-d', strtotime($request->date)),'user_id'=>$user->id])
		->where('project_id','!=',$request->project_id)->sum('hours');
		$sum_of_hours=$details_previous_sum+$request->hour;
		$ld = new \DateTime($date);
                $curr = $ld->format('D');
			if ($curr == 'Mon' || $curr == 'Tue'|| $curr == 'Wed'|| $curr == 'Thu') {
				if($sum_of_hours>9){
					continue;
				}
			} elseif ($curr == 'Fri') {
				if($sum_of_hours>4){
					continue;
				}
			}
			$detail=ProjectTimesheetDetail::updateOrCreate(['project_id'=>$request->project_id,
			'lin_code'=>$request->lin_code,'date'=>date('Y-m-d', strtotime($date)),'user_id'=>$user->id],['project_id'=>$request->project_id,
			'lin_code'=>$request->lin_code,'date'=>date('Y-m-d', strtotime($date)),'user_id'=>$user->id,'hours'=>$request->hour,'company_id'=>companyId()]);
			

		}
	}
	public function saveProjectTimesheetDetail(Request $request)
	{
		$user = \Auth::user();
        $company_id = companyId();
		$details_previous_sum=ProjectTimesheetDetail::where(['date'=>date('Y-m-d', strtotime($request->date)),'user_id'=>$user->id])
		->where('project_id','!=',$request->project_id)->sum('hours');
		$sum_of_hours=$details_previous_sum+$request->hour;
		$ld = new \DateTime($request->date);
                $curr = $ld->format('D');
			if ($curr == 'Mon' || $curr == 'Tue'|| $curr == 'Wed'|| $curr == 'Thu') {
				if($sum_of_hours>9){
					return 'failed';
				}
			} elseif ($curr == 'Fri') {
				if($sum_of_hours>4){
					return 'failed';
				}
			}
			$detail=ProjectTimesheetDetail::find($request->detail_id);
			$detail->update(['project_id'=>$request->project_id,
			'lin_code'=>$request->lin_code,'hours'=>$request->hour]);
	}
	public function saveProjectTimesheet(Request $request)
    {

		$user = \Auth::user();
        $company_id = companyId();
		$no_of_periods=0;
        if ($request->input('lin_code') !== null) {
            $no_of_periods = count($request->input('lin_code'));
        }
		
		$details_previous_sum=ProjectTimesheetDetail::where(['date'=>date('Y-m-d', strtotime($request->date)),'user_id'=>$user->id])->sum('hours');
		$sum_of_hours=$details_previous_sum;
        if ($no_of_periods > 0) {
            for ($i = 0; $i < $no_of_periods; $i++) {
                $sum_of_hours+=$request->hour[$i];
                
            }
			$ld = new \DateTime($request->date);
                $curr = $ld->format('D');
			if ($curr == 'Mon' || $curr == 'Tue'|| $curr == 'Wed'|| $curr == 'Thu') {
				if($sum_of_hours>9){
					return 'failed';
				}
			} elseif ($curr == 'Fri') {
				if($sum_of_hours>4){
					return 'failed';
				}
			}
        }
        
        if ($no_of_periods > 0) {
            for ($i = 0; $i < $no_of_periods; $i++) {
				
				
				$detail=ProjectTimesheetDetail::updateOrCreate(['project_id'=>$request->project_id[$i],
				'lin_code'=>$request->lin_code[$i],'date'=>date('Y-m-d', strtotime($request->date)),'user_id'=>$user->id],['project_id'=>$request->project_id[$i],
				'lin_code'=>$request->lin_code[$i],'date'=>date('Y-m-d', strtotime($request->date)),'user_id'=>$user->id,'hours'=>$request->hour[$i],'company_id'=>companyId()]);
               }
        }


        return 'success';
    }
	public function LeaveDaysRange($start_date, $end_date,$user)
    {
        $company_id = companyId();
        
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
		$used_leave_days = \App\LeaveRequestDate::whereYear('date', date('Y',strtotime($start_date)))
		->whereMonth('date', date('m',strtotime($start_date)))->whereHas('leave_request', function ($query) use ($user) {
			$query->where('leave_requests.user_id', $user->id)
				->where('status', 1);
		})->pluck('date');

        // best stored as array, so you can add more than one
        $holidays = \App\Holiday::where(['status' => 1, 'company_id' => $company_id])->whereYear('date', date('Y',strtotime($start_date)))
		->whereMonth('date', date('m',strtotime($start_date)))->pluck('date');//array('2012-09-07');
		
        foreach ($period as $dt) {
            $curr = $dt->format('D');
            $is_weekend = 0;
            $is_holiday = 0;
			$is_leave = 0;

            // substract if Saturday or Sunday
            if (($curr == 'Sat' || $curr == 'Sun') ) {
                $is_weekend = 1;
            } elseif ($holidays->count() > 0 ) {
				if($holidays->contains('date',$dt->format('m/d/Y')))
                {
                       
                        $is_holiday = 1;
                }
            }elseif($used_leave_days->count()>0){
				if($used_leave_days->contains('date',$dt->format('Y-m-d')))
				{
					
					$is_leave = 1;
				}
			}
			 else {

            }
            if ($is_weekend == 0 && $is_holiday == 0 && $is_leave==0) {
                $dates[] = $dt->format('Y-m-d');
            }else{
				$days--;
			}
            // $dates[]=$dt->format('Y-m-d');
        }


        return ['days' => $days, 'dates' => $dates];
    }

	public function get_month_report(Request $request){
		
		$projects=Project::all();
		if ($request->filled('month')) {
            $date = date('Y-m-d', strtotime('01-' . $request->month));
        } else {
            $date = date('Y-m-d');
        }
		$month=date('m',strtotime($date));
		$year=date('Y',strtotime($date));
		$users=User::whereHas('project_timesheet_details',function($query) use($month,$year){
			$query->whereMonth('date',$month)
			->whereYear('date',$year);
		})->get();
		$report=[];
        foreach ($users as $user) {
            $report[$user->id]['employee_id']=$user->emp_num;
            $report[$user->id]['name']=$user->name;
            $report[$user->id]['department_code']=$user->job?$user->job->department->name:'160';
            $report[$user->id]['office_code']=$user->branch?$user->branch->name:mt_rand(100,120);
            
            $total_hours=0;
            $details=\App\ProjectTimesheetDetail::whereYear('date', date('Y'))
        ->whereMonth('date', date('m'))
        ->where('user_id', $user->id)->get();
		$report[$user->id]['lin_code']=$details->first()->lin_code;
            foreach ($projects as $project) {
                $timesheet_sum[$project->id]['name']=$project->name;
                $timesheet_sum[$project->id]['sum']=0;
                $report[$user->id]['projects'][$project->id]['name']=$project->name;
                $report[$user->id]['projects'][$project->id]['fund_code']=$project->fund_code;
                $report[$user->id]['projects'][$project->id]['sum']=0;
            }

            foreach ($details as $detail) {
                $report[$user->id]['projects'][$detail->project_id]['sum']+=$detail->hours;
                $total_hours+=$detail->hours;
            }
            $report[$user->id]['total_hours']=$total_hours;
			$holidays = \App\Holiday::where(['status' => 1, 'company_id' => companyId()])->whereYear('date', date('Y'))->whereMonth('date', date('m'))->pluck('date')->toArray();//array('2012-09-07');
		$holiday_hours=0;
		
		foreach($holidays as $holiday)
		{
			$ld = new \DateTime($holiday);
            $curr = $ld->format('D');
			if($curr == 'Mon' || $curr == 'Tue'|| $curr == 'Wed'|| $curr == 'Thu'){
				$holiday_hours+=9;
			}elseif($curr == 'Fri'){
				$holiday_hours+=4;
			}
		}
		$report[$user->id]['holiday_hours']=$holiday_hours;
		$leave_hours=0;
		$used_leave_days = \App\LeaveRequestDate::whereYear('date', date('Y'))
		->whereMonth('date', date('m'))->whereHas('leave_request', function ($query) use ($user) {
			$query->where('leave_requests.user_id', $user->id)
				->where('status', 1);
		})->pluck('date')->toArray();
		foreach($used_leave_days as $leave_day)
		{
			
			$ld = new \DateTime($leave_day);
            $curr = $ld->format('D');
			if($curr == 'Mon' || $curr == 'Tue'|| $curr == 'Wed'|| $curr == 'Thu'){
				$leave_hours+=9;
			}elseif($curr == 'Fri'){
				$leave_hours+=4;
			}
		}
		$report[$user->id]['leave_hours']=$leave_hours;
        }
		
		return view('project_management.timesheet_report',compact('report','date'));
	}
	public function project_hours_report(Request $request)
	{
		if ($request->filled('month')) {
            $date = date('Y-m-d', strtotime('01-' . $request->month));
        } else {
            $date = date('Y-m-d');
        }
		$month=date('m',strtotime($date));
		$project=Project::find($request->project_id);
		$users=User::whereHas('project_timesheet_details',function($query) use($month,$project){
			$query->whereMonth('date',$month)
			->whereYear('date',date('Y'))
			->where('project_id',$project->id);
		})->get();
		$report=[];
        foreach ($users as $user) {
            $report[$user->id]['employee_id']=$user->emp_num;
            $report[$user->id]['name']=$user->name;
            $report[$user->id]['department_code']=$user->job?$user->job->department->name:'160';
            $report[$user->id]['office_code']=$user->branch?$user->branch->name:mt_rand(100, 120);
            
            $total_hours=0;
            $details=\App\ProjectTimesheetDetail::whereYear('date', date('Y'))
        ->whereMonth('date', date('m'))
        ->where('user_id', $user->id)->where('project_id',$project->id)->get();
            $report[$user->id]['lin_code']=$details->first()->lin_code;
			$report[$user->id]['sum']=0;

            foreach ($details as $detail) {
                $report[$user->id]['sum']+=$detail->hours;
                $total_hours+=$detail->hours;
            }
            $report[$user->id]['total_hours']=$total_hours;
        }
		return view('project_management.project_timesheet_report',compact('report','project','date'));

	}


}
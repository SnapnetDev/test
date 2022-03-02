<?php
namespace App\Http\Controllers;

use App\BiometricDevice;
use App\LeavePolicy;
use App\Setting;
use App\Shift;
use App\Workflow;
use Illuminate\Http\Request;
use App\WorkingPeriod;
use App\Company;
use App\Position;
class AttendanceSettingController extends Controller
{

	public function index($type='')
	{
		$companies=Company::all();
		foreach ($companies as $company) {
			if(!$company->workingperiod){
                $workingperiod=new WorkingPeriod();
                $workingperiod->sob='08:00';
                $workingperiod->cob='17:00';
                $workingperiod->company_id=$company->id;
                $workingperiod->save();
			}
		}
        $company_id=companyId();
		$before_shift_time=$this->prepareSetting('before_shift_time',1);
		$grace_period=$this->prepareSetting('grace_period',30);
        $uses_tams=$this->prepareSetting('uses_tams',1);
        $daily_attendance_report=$this->prepareSetting('daily_attendance_report',1);
        $attendance_workflow=$this->prepareSetting('attendance_workflow',1);
        $overtime_workflow=$this->prepareSetting('overtime_workflow',1);
        $company_long=$this->prepareSetting('company_long','3.4686459');
        $company_lat=$this->prepareSetting('company_lat','6.4414438');
        $distance=$this->prepareSetting('distance','2');
        $enforce_geofence=$this->prepareSetting('enforce_geofence',1);
        $leave_shift=$this->prepareSetting('leave_shift',1);

        $attendance_settings=[
            'before_shift_time'=>$before_shift_time->value,
            'grace_period'=>$grace_period->value,
            'uses_tams'=>$uses_tams->value,
            'daily_attendance_report'=>$daily_attendance_report->value,
            'attendance_workflow'=>$attendance_workflow->value,
            'overtime_workflow'=>$overtime_workflow->value,
            'company_long'=>$company_long->value,
            'company_lat'=>$company_lat->value,
            'distance'=>$distance->value,
            'enforce_geofence'=>$enforce_geofence->value,
            'leave_shift'=>$leave_shift->value
        ];
        //$workflows=Workflow::where('company_id',companyId())->get();
        $workflows=Workflow::all();
        $shifts=Shift::where('company_id',$company_id)->get();
        $biometric_devices=BiometricDevice::where('company_id',$company_id)->get();
        $company=Company::find($company_id);
		return view('settings.attendancesettings.index',compact('companies','attendance_settings','workflows','shifts','biometric_devices','company'));
	}
	private function prepareSetting($variable,$value){
        $company_id=companyId();
        $variable_value=Setting::where('name',$variable)->where('company_id',$company_id)->first();
        if (!$variable_value){
            $variable_value=Setting::create(['name'=>$variable,'value'=>$value,'company_id'=>$company_id]);
        }
        return $variable_value;
    }
    public function saveAttendanceSettings(Request $request){
	    $company_id=companyId();
        $before_shift_time = ($request->before_shift_time==1) ? 1 : 0 ;
        $uses_tams = ($request->uses_tams==1) ? 1 : 0 ;
        $daily_attendance_report = ($request->daily_attendance_report==1) ? 1 : 0 ;
        $enforce_geofence = ($request->enforce_geofence==1) ? 1 : 0 ;
        $grace_period = $request->grace_period;
        $attendance_workflow = $request->attendance_workflow;
        $overtime_workflow = $request->overtime_workflow;
        $company_long = $request->company_long;
        $company_lat = $request->company_lat;
        $distance = $request->distance;
        $leave_shift = $request->leave_shift;

        Setting::where('company_id',$company_id)->where('name','before_shift_time')->update(['value'=>$before_shift_time]);
        Setting::where('company_id',$company_id)->where('name','grace_period')->update(['value'=>$grace_period]);
        Setting::where('company_id',$company_id)->where('name','uses_tams')->update(['value'=>$uses_tams]);
        Setting::where('company_id',$company_id)->where('name','daily_attendance_report')->update(['value'=>$daily_attendance_report]);
        Setting::where('company_id',$company_id)->where('name','attendance_workflow')->update(['value'=>$attendance_workflow]);
        Setting::where('company_id',$company_id)->where('name','overtime_workflow')->update(['value'=>$overtime_workflow]);
        Setting::where('company_id',$company_id)->where('name','company_long')->update(['value'=>$company_long]);
        Setting::where('company_id',$company_id)->where('name','company_lat')->update(['value'=>$company_lat]);
        Setting::where('company_id',$company_id)->where('name','distance')->update(['value'=>$distance]);
        Setting::where('company_id',$company_id)->where('name','enforce_geofence')->update(['value'=>$enforce_geofence]);
        Setting::where('company_id',$company_id)->where('name','leave_shift')->update(['value'=>$leave_shift]);

        return  response()->json('success',200);
    }
	public function listWorkingPeriod()
	{
		
	}
	public function saveWorkingPeriod(Request $request)
	{
		WorkingPeriod::updateorCreate(['id'=>$request->working_period_id],['sob'=>$request->sob,'cob'=>$request->cob]);
		return  response()->json('success',200);
	}
	public function getWorkingPeriod($workingperiod_id)
	{
		$workingperiod=WorkingPeriod::find($workingperiod_id);
		return  response()->json($workingperiod,200);
	}
	public function deleteWorkingPeriod($workingperiod_id)
	{
		$workingperiod=WorkingPeriod::find($workingperiod_id);
		if ($workingperiod) {
			$workingperiod->delete();
		}else{
			return  response()->json(['failed'],200);
		}
		return  response()->json(['success'],200);
	}
	
	
	public function listProject()
	{
		$projects=Project::all();
		return view('settings.attendancesettings.position',compact('positions'));
	}

	public function saveProject(Request $request)
	{
		Project::updateOrCreate(['id'=>$request->project_id],['name'=>$request->name,'lga'=>$request->lga,'state'=>$request->state,'country'=>$request->country]);
		return  response()->json('success',200);
	}
	public function getProject($project_id)
	{
		$project=Project::find($project_id);
		return  response()->json($project,200);
	}
	public function deleteProject($project_id)
	{
		$project=Project::find($project_id);
		if ($project) {
			$project->delete();
		}else{
			return  response()->json(['failed'],200);
		}
		return  response()->json(['success'],200);
	}
	
	public function listEmployeeType()
	{
		$employeetypes=EmployeeType::all();
		return view('settings.attendancesettings.employeetype',compact('employeetypes'));
	}
	public function saveEmployeeType(Request $request)
	{
		EmployeeType::updateOrCreate(['id'=>$request->employeetype_id],['type'=>$request->type]);
		return  response()->json('success',200);
	}
	public function getEmployeeType($employeetype_id)
	{
		$employeetype=EmployeeType::find($employeetype_id);
		return  response()->json($employeetype,200);
	}
	public function deleteEmployeeType($employeetype_id)
	{
		$employeetype=EmployeeType::find($employeetype_id);
		if ($employeetype) {
			$employeetype->delete();
		}else{
			return  response()->json(['failed'],200);
		}
		return  response()->json(['success'],200);
	}
	public function listCostCenter()
	{
		$costcenters=CostCenter::all();
		return view('settings.attendancesettings.costcenter',compact('costcenters'));
	}
	public function saveCostCenter(Request $request)
	{
		CostCenter::updateOrCreate(['id'=>$request->costcenter_id],['code'=>$request->code]);
		return  response()->json('success',200);
	}
	public function getCostCenter($costcenter_id)
	{
		$costcenter=CostCenter::find($costcenter_id);
		return  response()->json($costcenter,200);
	}
	public function deleteCostCenter($costcenter_id)
	{
		$costcenter=CostCenter::find($costcenter_id);
		if ($costcenter) {
			$costcenter->delete();
		}else{
			return  response()->json(['failed'],200);
		}
		return  response()->json(['success'],200);
	}
	public function listAllowance()
	{
		$allowances=Allowance::all();
		return view('settings.attendancesettings.allowance',compact('allowances'));
	}
	public function saveAllowance(Request $request)
	{
		Allowance::updateOrCreate(['id'=>$request->allowance_id],['name'=>$request->name,'location_id'=>$request->location_id]);
		return  response()->json('success',200);
	}
	public function getAllowance($allowance_id)
	{
		$allowance=Allowance::find($allowance_id);
		return  response()->json($allowance,200);
	}
	public function deleteAllowance($allowance_id)
	{
		$allowance=Allowance::find($allowance_id);
		if ($allowance) {
			$allowance->delete();
		}else{
			return  response()->json(['failed'],200);
		}
		return  response()->json(['success'],200);
	}
	public function listshift()
	{
		$shifts=Shift::all();
		return view('settings.attendancesettings.shift',compact('shifts'));
	}
	public function saveShift(Request $request)
	{
		Shift::updateOrCreate(['id'=>$request->shift_id],['type'=>$request->type,'start_time'=>$request->start_time,'color_code'=>$request->color_code,'end_time'=>$request->end_time]);
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
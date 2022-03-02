<?php

function userCompanyName(){
	if (session()->has('company_id')) {
		$company=\App\Company::where('id',session('company_id'))->get()->first();
		return $company->name;
	}else{
		if(\Auth::user()->company){
		$company=\App\Company::where('id',\Auth::user()->company_id)->get()->first();
		session(['company_id'=>$company->id]);
		return $company->name;


	}else{

		$company=\App\Company::where('is_parent',1)->get()->first();

		session(['company_id'=>$company->id]);
		return $company->name;
	}
	}
}

	function companyInfo(){
	if (session()->has('company_id')) {
		$company=\App\Company::where('id',session('company_id'))->get()->first();
		return $company;
	}else{
		if(\Auth::user()->company){
		$company=\App\Company::where('id',\Auth::user()->company_id)->get()->first();
		session(['company_id'=>$company->id]);
		return $company;


	}else{

		$company=\App\Company::where('is_parent',1)->get()->first();

		session(['company_id'=>$company->id]);
		return $company;
	}
	}



}
function systemInfo()
{
	$name=\App\Setting::where('name','sys_name')->first();
		$logo=\App\Setting::where('name','sys_logo')->first();
		if (!$name) {
			$name=\App\Setting::create(['name'=>'sys_name','value'=>'HCMatrix']);
			$name=$name;
		}
		if (!$logo) {
			$logo=\App\Setting::create(['name'=>'sys_logo','value'=>'']);
			$logo=$logo;
		}
		return['name'=>$name->value,'logo'=>$logo->value];
}
function companyId(){
    //	    i modified this code
    if(!isset(\Auth::user()->company)){
        return '';
    }
	if (session()->has('company_id')) {

		return session('company_id');
	}else{

		if (\Auth::user()->company) {

		$company=\App\Company::where('id',\Auth::user()->company_id)->get()->first();
		session(['company_id'=>$company->id]);

		return $company->id;

	}else{

		$company=\App\Company::where('is_parent',1)->get()->first();

		session(['company_id'=>$company->id]);
		return $company->id;
	}
	}
}
function companies(){
	return \App\Company::all();
}

function punctualityStatus($time){
	$wp=\App\WorkingPeriod::all()->first();
	$time1 = strtotime("1/1/2018 $time1");
		$time2 = strtotime("1/1/2018 $time2");

	return ($time2 - $time1) / 3600;
}
function employeeFirstClockin($emp_num,$date){
	return $ci=\App\Attendance::where(['emp_num'=>$emp_num,'date'=>$date])->first()->attendancedetails()->orderBy('clock_in', 'asc')->first()->clock_in;
}
function employeeLastClockout($emp_num,$date){
	return $co=\App\Attendance::where(['emp_num'=>$emp_num,'date'=>$date])->first()->attendancedetails()->orderBy('clock_out', 'desc')->first()->clock_out;
}
function userAttendanceId($emp_num,$date){
	return $id=\App\Attendance::where(['emp_num'=>$emp_num,'date'=>$date])->first()->id;
}
function time_to_seconds($time) {
    $sec = 0;
    foreach (array_reverse(explode(':', $time)) as $k => $v) $sec += pow(60, $k) * $v;
    return $sec;
}

function bscweight($department_id,$performance_category_id,$metric_id){
	$weight=\App\BscWeight::where(['department_id'=>$department_id,'performance_category_id'=>$performance_category_id,'metric_id'=>$metric_id])->first();
	if ($weight) {
		return $weight;
	}elseif(!$weight){
		return $weight=\App\BscWeight::create(['department_id'=>$department_id,'performance_category_id'=>$performance_category_id,'metric_id'=>$metric_id,'percentage'=>25]);
	}
}
	function currentYear(){
		return date('Y');
	}
	function getBscUserEvaluation($user_id,$mp_id)
	{
		$user=\App\User::find($user_id);
		$mp=\App\BscMeasurementPeriod::find($mp_id);
		$operation='evaluate';
		if ($user->grade&&$user->job) {
			if ($user->job) {
				if (!$user->grade->performance_category) {
				return ['bsc_score'=>'0.00','behavioral_score'=>'0.00','average_score'=>'0.00'];
			}else{
						$evaluation=\App\BscEvaluation::where(['user_id'=>$user->id,'bsc_measurement_period_id'=>$mp->id])->first();
					if($evaluation){
						$metrics=App\BscMetric::all();
						 $average=($evaluation->score+$evaluation->behavioral_score)/2;
						return ['bsc_score'=>($evaluation->score>1)?$evaluation->score:"1.00",'behavioral_score'=>($evaluation->behavioral_score>1)?$evaluation->behavioral_score:"1.00",'average_score'=>($average>1)?round($average,1):'1.00'];


					}else{


						return ['bsc_score'=>'0.00','behavioral_score'=>'0.0','average_score'=>'0.0'];



					}
			}
			}else{
				return ['bsc_score'=>'0.00','behavioral_score'=>'0.0','average_score'=>'0.0'];
			}



		}elseif(!$user->grade){

				return ['bsc_score'=>'0.00','behavioral_score'=>'0.0','average_score'=>'0.0'];
		}else{
			return ['bsc_score'=>'0.00','behavioral_score'=>'0.0','average_score'=>'0.0'];

		}
	}

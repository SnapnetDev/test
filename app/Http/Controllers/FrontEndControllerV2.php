<?php

namespace App\Http\Controllers;

use App\Classes\GEvent;
use App\Exports\KpiExport;
use App\JobAppliedFor;
use App\JobAva;
use App\JobDep;
use App\KpiAgreement;
use App\KpiData;
use App\KpiInterval;
use App\KpiSession;
use App\KpiUserScore;
use App\KpiYear;
use App\Notifications\SendKpiNotCompletedNotification;
use App\Notifications\SendKpiReportNotification;
use App\Traits\FrontEndTrait;
use App\User;
use Auth;
use File;
use Illuminate\Http\Request;

class FrontEndControllerV2 extends Controller
{
    //
	use FrontEndTrait;


	function process($cmd){
		return $this->execCommand($cmd);
	}

	function test(){
		return 'Calling test ... ';
	}



	function fetchKpiYears(){
		$data = [];
		$data['list'] = (new KpiYear)->fetch([])->get();
		$data['current_interval'] = (new KpiSession)->getCurrentInterval();
		return view('kpi.year.index',$data);
	}

	function fetchKpiIntervals(){
        $data = [];
        $data['year'] = KpiYear::find(request()->get('kpi_year_id'));
        $data['current_interval'] = (new KpiSession)->getCurrentInterval();
        $data['list'] = (new KpiInterval)->fetch([
        	'kpi_year_id'=>request()->get('kpi_year_id')
        ])->get();
        return view('kpi.interval.index',$data);
	}

	function fetchKpiData(){

       $data = [];
       $data['interval'] = KpiInterval::find(request()->get('kpi_interval_id'));
		$data['current_interval'] = (new KpiSession)->getCurrentInterval();
		$data['list'] = (new KpiData)->fetch([])->get();
//       $data['jobs'] = JobDep::all();
		$data['jobs'] = JobAva::all();
       return view('kpi.data.index',$data);

	}

	function makeCurrent(){
		$response = (new KpiSession)->makeCurrent();
		return redirect()->back()->with($response);
	}


	function fetchKpiUsers(){
		$data = [];

		$jobs = [];

		

		$jobs = JobAva::all();
		$data['users'] = (new User)->fetch([])->paginate(20);

		if (Auth::user()->isHr()){
			$jobs = JobAva::all();
			$data['users'] = (new User)->fetch([])->where('locked',0)->paginate(20);
		}else if (Auth::user()->isLineManager()){
			$jobs = JobAva::where('id',Auth::user()->job_id)->get();
			$data['users'] = (new User)->fetch([
				'job_id'=>Auth::user()->job_id
			])->orWhere('linemanager_id',Auth::user()->id)->where('locked',0)->paginate(20);
		}else{
			$data['users'] = [];
//			$data['users'] = (new User)->fetch([])->where('locked',0)->paginate(20);
		}

		$data['jobs'] = $jobs;
		if (request()->has('workdept_id')){
			$data['workdept_id'] = request()->get('workdept_id');
		}else{
			$data['workdept_id'] = Auth::user()->job_id;
		}

		return view('kpi.userlist.index',$data);
	}

	function fetchIndividualKpi(){
		$data = [];
		$data['kpis'] = (new KpiData)->fetch([
			'kpi_user_score_user_id'=>request()->get('user_id'),
			'scope'=>'private'
		])->get();
		$data['user'] = User::find(request()->get('user_id'));
		$data['user_id'] = request()->get('user_id');
		$data['type'] = request()->get('type');
		$data['workdept_id'] = request()->get('workdept_id');

		return view('kpi.individual_kpi.index',$data);
	}

	function downloadIndividualKpiData(){
		$r = (new KpiData)->fetch([
			'kpi_user_score_user_id'=>request()->get('user_id'),
			'scope'=>'private'
		])->get();
		$rr = [];

		$user = User::find(request()->get('user_id'));
		$type = request()->get('type');
		$file = $user->name . '_' . $type;

		foreach ($r as $item){
			$rr[] = [
				'requirement'=>$item->requirement,
				'percentage'=>$item->percentage
			];
		}
		return (new KpiExport)->download($file,$rr);
	}


	private function fetchUserKpiEvaluationComponent($user_id,$role=''){
		$data = [];
//		$user_id = request()->get('user_id');
		$kpi_interval_id = (new KpiSession)->getCurrentIntervalId();
		$data['hasInterval'] = (new KpiSession)->hasInterval();
		$data['kpi_interval_id'] = $kpi_interval_id;
		$userObj = User::find($user_id);
		$data['user'] = $userObj;
		$data['user_id'] = $user_id;
		$workdept_id = $userObj->job_id;

		$intervals = (new KpiSession)->getIntervalCollection();
		$data['intervals'] = $intervals;

		if (!empty($role)){
			$data['override_role'] = true;
		}

		if ((new KpiInterval)->intervalNotExpired()){
//			dd('ne');
			$data['expired'] = false;
		}else{
//			dd('e');
			$data['expired'] = true;
		}

		$data['department_list'] = (new KpiData)->fetch([
			'type'=>'dep',
			'kpi_interval_id'=>$kpi_interval_id,
			'dep_id'=>$workdept_id,
			'scope'=>'public'
		])->get();

		$data['individual_department_list'] = (new KpiData)->fetch([
			'type'=>'dep',
			'kpi_interval_id'=>$kpi_interval_id,
			'dep_id'=>$workdept_id,
			'kpi_user_score_user_id'=>$user_id,
			'scope'=>'private'
		])->get();
		//kpi_user_score_user_id

//		dd(9);
		$data['organisation_list'] = (new KpiData)->fetch([
			'type'=>'org',
			'kpi_interval_id'=>$kpi_interval_id,
			'dep_id'=>$workdept_id,
			'scope'=>'public'
		])->get();

		$data['individual_organisation_list'] = (new KpiData)->fetch([
			'type'=>'org',
			'kpi_interval_id'=>$kpi_interval_id,
			'dep_id'=>$workdept_id,
			'kpi_user_score_user_id'=>$user_id,
			'scope'=>'private'
		])->get();


		//'user_id','kpi_interval_id','agreement'
		$data['hasAgreed'] = (new User)->fetch([
			'user_id'=>$user_id,
			'kpi_interval_id'=>$kpi_interval_id,
			'agreement'=>true
		])->exists();

		$data['currentInterval'] = (new KpiSession)->getCurrentInterval();

//		dd($data);

//		return $data;
		return view('kpi.user_score.index',$data);

	}

	function fetchUserKpiEvaluation(){
	  $user_id = request()->get('user_id');
	  return $this->fetchUserKpiEvaluationComponent($user_id);
	}

	function evaluateSelf(){
		$user_id = Auth::user()->id;
		return $this->fetchUserKpiEvaluationComponent($user_id,'me');
	}


	function kpiGetUserScore(){
		$user_id = request()->get('user_id');
		$kpi_data_id = request()->get('kpi_data_id');
		$userScore = (new KpiUserScore)->fetch([
			'user_id'=>$user_id,
			'kpi_data_id'=>$kpi_data_id
		])->first();

		return [
			'data'=>$userScore
		];

	}

	function sendKpiNotification(){
		$response = (new KpiUserScore)->sendGlobalNotification();
		return redirect()->back()->with($response);
	}

	function kpiUserReport(){

		$data = [];
		$data['user'] = (new User)->find(request()->get('user_id'));

		return view('kpi.report.index',$data);
	}

	function kpiGetUserScoreReport(){
		$data = [];
		//'kpi_year_id','user_id'
		//'kpi_year_id'=>request()->get('kpi_year_id'),
		$obj = (new KpiUserScore)->fetch([
			'user_id'=>request()->get('user_id')
		]);

		$data['hr'] = $obj->sum('hr_score') * 25/100;
		$data['manager'] = $obj->sum('manager_score') * 50/100;
		$data['personal'] = $obj->sum('user_score') * 25/100;
		$data['avg'] =  round(($data['hr'] + $data['manager'] + $data['personal'])/3 , 2);

		return $data;

	}


	function sendGeneralNotification(){
       return  view('kpi.messaging.index');
	}

	function acceptKpiAgreement(){
		$response = (new KpiAgreement)->acceptAgreement();
		return redirect()->back()->with($response);
	}

	function denyKpiAgreement(){
		$response = (new KpiAgreement)->denyAgreement();
		return redirect()->back()->with($response);
	}

	function myUserReport(){
	    return $this->userReport(Auth::user()->id);
    }


    private function getMailCcs($userId){
	    //linemanager_id
        $user = User::find($userId);
        $linemanager_id = $user->linemanager_id;
        $lineManager = User::find($linemanager_id);
        //$this->role == 3
        //'locked',0
        $admins = User::where('role',3)->where('locked',0)->get();

        $emails = [];
        $emails[] = $lineManager->email;
        foreach ($admins as $item){
            $emails[] = $item->email;
        }

        return $emails;
    }

	function userReport(){
	   $userId =  request('id');
       $obj = new KpiData;
       $interval = (new KpiSession)->getCurrentIntervalObject();
       $interval_id = $interval->kpi_interval_id;
       $dataReport = $obj->getUserReport($userId,$interval_id);

//       dd($dataReport);


       $report = view('kpi.user_score.report',$dataReport)->render();

       try{
           User::find($userId)->notify(new SendKpiReportNotification($report,$this->getMailCcs($userId)));
           $response = [
               'message'=>'Report sent successfully',
               'error'=>false
           ];

       }catch (\Exception $exception){
           $response = [
               'message'=>'Please check your SMTP - Server!(' . $exception->getMessage() . ')',
               'error'=>true
           ];

       }

       if (request()->exists('or')){
	       return $report;
       }else{
	       return redirect()->back()->with($response);
       }


    }


    function cronCheckCompletedKpi(){
	    if ((new KpiSession)->intervalIsAboutToClose()){

            $kpiSession = new \App\KpiSession;

            $user = new \App\User;
            $list = $user->getUsersWhoCompetedEvaluation($kpiSession->getCurrentIntervalObject()->kpi_interval_id);

            //dd($list);

            foreach ($list['not_completed']  as $item){
               $item->notify(new SendKpiNotCompletedNotification($item));
            }

            return [
                'message'=>'Interval is about to close',
                'data'=>$list,
                'error'=>false
            ];

        }else{
	        return [
	            'message'=>'Interval is still sufficient',
                'error'=>false
            ];
        }
    }



    function inlineKpiReport(){

		$passedValidation = GEvent::raise('PassedValidation', []);

		if (!$passedValidation){
			return '<h1>' . GEvent::raise('GetValidationMessage', []) . '</h1>';
		}

		$reportData = GEvent::raise('GetReport', []);

		$view = GEvent::raise('GetView', $reportData);

		return $view;

	}
	

	function downloadExport($json,$name){

		$data = json_encode($json);
		$fileName = time() . '_' . $name . '.json';
		File::put(public_path('/uploads/json/'.$fileName),$data);
		return response()->download(public_path('/uploads/json/'.$fileName));
		
	}


	function downloadUserExport(){

		$this->downloadExport(User::all(),'user');

		$this->downloadExport(KpiYear::all(),'kpi_year');
		$this->downloadExport(KpiSession::all(),'kpi_session');
		$this->downloadExport(KpiInterval::all(),'kpi_interval');
		$this->downloadExport(KpiData::all(),'kpi_data');
		$this->downloadExport(JobAva::all(),'job_ava');
		$this->downloadExport(KpiUserScore::all(),'kpi_user_score');
		$this->downloadExport(KpiAgreement::all(),'kpi_agreement');


	}




}

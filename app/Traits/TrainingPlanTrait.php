<?php
/**
 * Created by PhpStorm.
 * User: NnamdiAlexanderAkamu
 * Date: 4/15/2020
 * Time: 11:09 PM
 */

namespace App\Traits;


use App\Department;
use App\Notifications\TrainingPlanApprovalNotification;
use App\Notifications\TrainingPlanCreationNotification;
use App\Notifications\TrainingPlanRejectionNotification;
use App\Notifications\TrainingPlanUpdateNotification;
use App\Role;
use App\Tr_OfflineTraining;
use App\Tr_OfflineTrainingGroup;
use App\Traits\ModelTraits\UACTrait;
use App\User;
use App\UserGroup;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Builder;
use Auth;
use App\BscMeasurementPeriod;

trait TrainingPlanTrait
{

	use UACTrait;



	function getYearFromMeasurementPeriod($measurementPeriod){
      $obj = BscMeasurementPeriod::find($measurementPeriod);
      // dd($obj);
      $year = date('Y',strtotime(@$obj->to));
      return $year;
	}


	private function getTrainingDict(){
		return [
			'status'=>[
				0=>'Pending',
				1=>'Approved',
				2=>'Rejected'
			]
		];
	}

	function fetchTrainingPlan_(){

	  $this->syncOnlineTrainingFromMoodle();

	  $object = new Tr_OfflineTraining;
	  return [
	  	'list'=>$object->fetchRequestTrainingPlan([
		    'line_manager_id'=>Auth::user()->id
	    ])->paginate(20),
		'dict'=>$this->getTrainingDict()
	  ];

	}




	function fetchTrainingPlanApproved_(){
		$object = new Tr_OfflineTraining;

		$year = $this->getYearFromMeasurementPeriod(request()->get('mp'));

		$filters = ['status'=>'1'];
		$filters['year'] = $year;

		return [
			'list'=>$object->fetchRequestTrainingPlan($filters)->get()
		];

	}

	function fetchTrainingPlanCounts_(){
		$object = new Tr_OfflineTraining;

		$year = $this->getYearFromMeasurementPeriod(request()->get('mp'));

        $filters = [];
        $filters['year'] = $year;
        $filters['status'] = 1;
        $filters['user_id'] = 0;

		$allTrainingCount = $object->fetchRequestTrainingPlan($filters)->count();


        //webrdp.

		$filters = [];
		$filters['year'] = $year;
		$filters['enroll_status'] = 1;
		$filters['user_id'] = request()->get('user_id');

		$enrolledTrainingCount = $object->fetchRequestTrainingPlan($filters)->count();

		$filters = [];
		$filters['year'] = $year;
		$filters['enroll_status'] = 1;
		$filters['completed'] = 1;
		$filters['user_id'] = request()->get('user_id');

		$completedTrainingCount = $object->fetchRequestTrainingPlan($filters)->count();



		$filters = [];
		$filters['year'] = $year;
		$filters['enroll_status'] = 1;
		$filters['completed'] = 0;
		$filters['user_id'] = request()->get('user_id');

		$inSessionTrainingCount = $object->fetchRequestTrainingPlan($filters)->count();

		return [
			'all'=>$allTrainingCount,
			'enrolled'=>$enrolledTrainingCount,
			'completed'=>$completedTrainingCount,
			'insession'=>$inSessionTrainingCount
		];

//		[
//			'user_id'=>request()->get('user_id'),
//			'enroll_status'=>1
//		]

	}


	function fetchTrainingPlanForApproval_(){
		$object = new Tr_OfflineTraining;
		return [
			'list'=>$object->fetchRequestTrainingPlan([
				'line_manager_id'=>Auth::user()->id,
//				'status'=>0
			])->paginate(20),
			'dict'=>$this->getTrainingDict()
		];

	}

	function getVars(){
		$roles = Role::all();
		$departments = Department::where('company_id',companyId())->get();
		$groups = UserGroup::where('company_id',companyId())->get();
		return [
			'roles'=>$roles,
			'departments'=>$departments,
			'groups'=>$groups
		];
	}


	function createTrainingPlan_(){
       $newRecord = new Tr_OfflineTraining;
       //groupId
       $newRecord = $newRecord->entityCreate(function($record){

       	 $record->name = request()->get('name');
       	 $record->cost_per_head  = request()->get('cost_per_head');
       	 $record->number_of_enrollees = request()->get('number_of_enrollees');
       	 $record->grand_total = request()->get('cost_per_head') * request()->get('number_of_enrollees');
	     $record->line_manager_id = Auth::user()->id;
	     $record->status = 0; //disabled by default
//	     $record->approved_by = null;
	     $record->train_start = request()->get('train_start');
	     $record->train_stop = request()->get('train_stop');
	     $record->year_of_training =  date('Y'); //request()->get('year_of_training');

	     $record->dep_id = request()->get('dep_id');
	     $record->role_id = request()->get('role_id');

	       return $record;

       });

       $newID = $newRecord->id;
//       dd($newRecord);
       if (request()->filled('groupId')){
       	 $groups = request()->get('groupId');
       	 foreach ($groups as $group){
       	 	$obj = new Tr_OfflineTrainingGroup;
       	 	$obj->training_plan_id = $newID;
       	 	$obj->group_id = $group;
       	 	$obj->save();
         }
       }





       //send notification
//	  dd(User::where('role_id',2)->get());
	   $this->notifyHr(new TrainingPlanCreationNotification);

       return [
	      'message'=>'New training plan uploaded, waiting for Hr-approval',
	       'data'=>null,
	       'error'=>false
       ];
	}

	function respondToTrainingPlan_(){
		$messages = [];
		$messages[1] = 'Training plan successfully approved.';
		$messages[2] = 'Training plan successfully denied.';
		return $this->updateTrainingPlan_([
			'message'=>$messages[request()->get('status')]
		]);
	}


	function denyTrainingPlan_(){
		return $this->updateTrainingPlan_([
			'status'=>2,
			'message'=>'Training plan successfully denied.'
		]);
	}


	function deleteTrainingPlan_(){
		$deleteRecord = new Tr_OfflineTraining;
		$id = request()->get('id');
		$deleteRecord->entityDelete($id);

		return [
			'message'=>'Training plan removed.',
			'data'=>null,
			'error'=>false
		];

	}

	function updateTrainingPlan_($config=[]){
      $updateRecord = new Tr_OfflineTraining;
      $id = request()->id;
      $updateRecord->entityUpdate($id, function($record) use ($config){

	      $record->name = request()->get('name');

	      if ($record->status == 0 || $record->status == 2){
		      $record->cost_per_head  = request()->get('cost_per_head');
		      $record->number_of_enrollees = request()->get('number_of_enrollees');
		      $record->grand_total = request()->get('cost_per_head') * request()->get('number_of_enrollees');
		      $record->train_start = request()->get('train_start');
		      $record->train_stop = request()->get('train_stop');

		      $record->dep_id = request()->get('dep_id');
		      $record->role_id = request()->get('role_id');

//		      $record->year_of_training = request()->get('year_of_training');
	      }



	      if (request()->filled('status')){
	      	 $record->status = request()->get('status');//$config['status'];
	      	 $record->approved_by = Auth::user()->id;
		     $record->last_modified_by = Auth::user()->id;
	      	 if (request()->get('status') == 2)$record->reason = request()->get('reason');

	      	 if (request()->get('status') == 1){//approval
	      	 	$notifyUser = User::find($record->line_manager_id);
	      	 	$notifyUser->notify(new TrainingPlanApprovalNotification);
	         }else if (request()->get('status') == 2){ //rejection
		         $notifyUser = User::find($record->line_manager_id);
		         $notifyUser->notify(new TrainingPlanRejectionNotification);
	         }
	      }else{
	      	$this->notifyHr(new TrainingPlanUpdateNotification);
	      }

//	      $record->line_manager_id = Auth::user()->id;
//	      $record->status = 0; //disabled by default
//	     $record->approved_by = null;

	      return $record;


      });



//		$newID = $id;
		$garbageList = Tr_OfflineTrainingGroup::where('training_plan_id',$id)->get();
		foreach ($garbageList as $garbage){
			$garbage->delete();
		}
//       dd($newRecord);
		if (request()->filled('groupId')){
			$groups = request()->get('groupId');
			foreach ($groups as $group){
				$obj = new Tr_OfflineTrainingGroup;
				$obj->training_plan_id = $id;
				$obj->group_id = $group;
				$obj->save();
			}
		}


		return [
			'message'=>(isset($config['message']))? $config['message'] : 'Training plan updated.',
			'data'=>null,
			'error'=>false
		];

	}


	private function notifyHr($notifiable){
//		Auth::user()->notify($notifiable);
		$users = $this->getUsersByPermission_('approve_training_plan');
		foreach ($users as $user){
			$user->notify($notifiable);
		}
	}



	//http://elearning.thehcmatrix.com/hcm_onboard/?username=1015&courseId=5&endpoint=manual_enroll_user
	//http://elearning.thehcmatrix.com/hcm_onboard/?endpoint=training


	function syncOnlineTrainingFromMoodle(){ //run at boot time for training plan.
       $client = new Client;
       $result = $client->get('http://elearning.thehcmatrix.com/hcm_onboard/',[
       	'query'=>[
       		'endpoint'=>'training'
        ]
       ])->getBody()->getContents();

       $record = json_decode($result);

       foreach ($record->data as $k=>$v){
       	   $data = $v;
           if (!$this->checkDuplicate($v->id)){
	           (new Tr_OfflineTraining)->entityCreate(function($record) use ($data){

		           $record->name = $data->shortname;
		           $record->cost_per_head  = 0;
		           $record->number_of_enrollees = 0;
		           $record->grand_total = 0;
		           $record->line_manager_id = Auth::user()->id;
		           $record->status = 1; //disabled by default
//	     $record->approved_by = null;
		           $record->train_start = date('Y-m-d',$data->startdate);
		           $record->train_stop = date('Y-m-d',$data->enddate);
		           $record->year_of_training =  date('Y',$data->startdate); //request()->get('year_of_training');

		           $record->dep_id = 0;
		           $record->role_id = 0;

		           $record->type = 'online';
		           $record->remote_id = $data->id;

//		           dd($record);

		           return $record;

	           });
           }
       }
//       dd($record);

	}

	private function checkDuplicate($remote_id){
		$check = Tr_OfflineTraining::where('remote_id',$remote_id)->count();
		return ($check > 0);
	}




}

<?php
/**
 * Created by PhpStorm.
 * User: NnamdiAlexanderAkamu
 * Date: 4/20/2020
 * Time: 2:19 AM
 */

namespace App\Traits;


use App\Tr_OfflineTraining;
use App\Tr_TrainingBudget;
use App\Tr_UserOfflineTraining;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Builder;

trait TrainingUserPlanTrait
{


	function userIsEligible_($userId,$trainingPlanId){
		$allocated = $this->getUserBudgetAllocationByGrade_($userId);
		$used = $this->getUserUsedBudgetAllocation_($userId);
		$trainingPlanObject = Tr_OfflineTraining::find($trainingPlanId);
//		echo $allocated;
//		echo '<br />' . $used;
//		echo '<br />' . ($allocated - $used);
//		dd(($allocated - $used) >= $trainingPlanObject->cost_per_head * 1);
//		dd($trainingPlanObject->cost_per_head);

		return (($allocated - $used) >= $trainingPlanObject->cost_per_head * 1);
	}

	function getUserIsEligible_($userId,$trainingPlanId){
		if ($this->userIsEligible_($userId, $trainingPlanId)){
			return [
				'message'=>'Yes.',
				'error'=>false
			];
		}else{
			return [
				'message'=>'No.',
				'error'=>true
			];
		}
	}

	function getUserBudgetAllocationByGrade_($userId){

	  $user = User::find($userId);

	  if (is_null($user->grade)){

	  	return 0;

	  }else{

	  	$gradeId = $user->grade->id;
	  	$query = new Tr_TrainingBudget;
	  	$sum = $query->fetchRequestTrainingBudget_([
           'grade_id'=>$gradeId,
		   'year'=>date('Y')
	    ])->sum('allocation_total');

	  	return $sum;

	  }

	}

	function getUserUsedBudgetAllocation_($userId){
		$query = new Tr_OfflineTraining;
		$sum = $query->fetchRequestTrainingPlan([
			'user_id'=>$userId,
			'year'=>date('Y'),
			'enroll_status'=>1
		])->sum('cost_per_head');

//		echo $query->fetchRequestTrainingPlan([
//			'user_id'=>$userId,
//			'year'=>date('Y')
//		])->toSql();

		return $sum;
	}

	function inlineMoodleEnroll($emp_num,$remote_id){
		$client = new Client;
		$result = $client->get('http://elearning.thehcmatrix.com/hcm_onboard/',[
			'query'=>[
				'endpoint'=>'manual_enroll_user',
				'username'=>$emp_num,
				'courseId'=>$remote_id
			]
		])->getBody()->getContents();

//		dd($result);
		//http://elearning.thehcmatrix.com/hcm_onboard/?username=1015&courseId=5&endpoint=manual_enroll_user
	}

	function createUserTrainingPlan_(){

		$user_id = request()->get('user_id');
		$training_plan_id = request()->get('training_plan_id');

		$objTrainingPlan = Tr_OfflineTraining::find($training_plan_id);
		$ref = $this;

		if ($this->userIsEligible_($user_id, $training_plan_id)){
			if (!$this->userTrainingPlanExists_($user_id, $training_plan_id)){

				$newRecord = (new Tr_UserOfflineTraining)->entityCreate(function($record) use ($training_plan_id,$user_id,$ref){

					$record->training_plan_id = $training_plan_id;
					$record->user_id = $user_id;
					$record->status = 1;
					$record->completed  = 0;

					return $record;

				});

				//handle moodle enrollment here.
				$msg = '';
				if ($objTrainingPlan->type == 'online'){
					$user = User::find($user_id);
					$ref->inlineMoodleEnroll($user->emp_num,$objTrainingPlan->remote_id);
					$msg = ' (Moodle)';
				}

				return [
					'message'=>'User successfully enrolled' . $msg,
					'data'=>$newRecord,
					'error'=>false
				];

			}else{


				$record = $this->getUserTraining_($user_id, $training_plan_id);
				$record->status = 1;
				$record->save();

				return [
					'message'=>'User enrollement activated',
					'error'=>true
				];
			}
		}else{
			return [
				'message'=>'User not eligible (Yearly budget allocation exceeded)!',
				'error'=>true
			];
		}



//		if ($objTrainingPlan->type == 'online'){
//			return [
//				'message'=>'User enrolled to moodle',
//				'error'=>true
//			];
//		}else{ //offline
//		}

	}

	function userTrainingIsEnrolled_(){

		$userId = request()->get('user_id');
		$trainingPlanId = request()->get('training_plan_id');

		if ($this->userTrainingPlanExists_($userId, $trainingPlanId)){

			$record = $this->getUserTraining_($userId, $trainingPlanId);

			if ($record->status == 1){
				return [
					'message'=>'Enrolled'
				];
			}else{
				return [
					'message'=>'Enrollement Paused.'
				];
			}

		}else{
			return [
				'message'=>'Not-Enrolled'
			];

		}

	}

	function getUserTrainingMetta_(){

		$userId = request()->get('user_id');
		$trainingPlanId = request()->get('training_plan_id');

		$record = (new Tr_UserOfflineTraining)->fetchUserTrainingPlanRequestQuery([
				'user_id'=>$userId,
				'training_plan_id'=>$trainingPlanId
			])->first();

		$response = [];
		if (!is_null($record)){
			if ($record->completed == 0){
				$response['completed'] = 'In-Session';
			}else if ($record->completed == 1){
				$response['completed'] = 'Completed';
			}
		}else{
			$response['completed'] = 'Pending';
		}

		$response['data'] = $record;

		return $response;

	}

	private function userTrainingPlanExists_($userId,$trainingPlanId){
		$query = (new Tr_UserOfflineTraining)->fetchUserTrainingPlanRequestQuery([
			'user_id'=>$userId,
			'training_plan_id'=>$trainingPlanId
		]);
		return ($query->count() >= 1);
	}

	private function getUserTraining_($userId,$trainingPlanId){
		$query = (new Tr_UserOfflineTraining)->fetchUserTrainingPlanRequestQuery([
			'user_id'=>$userId,
			'training_plan_id'=>$trainingPlanId
		]);
        return $query->first();
	}



	function removeUserTrainingPlan_(){

		$userId = request()->get('user_id');
		$trainingPlanId = request()->get('training_plan_id');

		if ($this->userTrainingPlanExists_($userId, $trainingPlanId)){
			$record = (new Tr_UserOfflineTraining)->fetchUserTrainingPlanRequestQuery([
				'user_id'=>$userId,
				'training_plan_id'=>$trainingPlanId
			])->first();
			$record->status = 0;
//			$record->completed
			$record->save();

			return [
				'message'=>'User unenrolled',
				'error'=>false

			];

		}else{
			return [
				'message'=>'User not yet enrolled',
				'error'=>true
			];

		}
	}


	function getUserTrainingPlan_($userId){

		$record = (new Tr_UserOfflineTraining)->fetchUserTrainingPlanRequestQuery([
			'user_id'=>$userId
		])->paginate(20);

		return [
			'list'=>$record
		];

	}


	function updateUserTrainingPlan_(){

		$id = request()->get('id');
		$updateRecord = new Tr_UserOfflineTraining;
		$updateRecord = $updateRecord->entityUpdate($id, function(Tr_UserOfflineTraining $record){

			$record->rating = request()->get('rating');
			$record->feedback = request()->get('feedback');
			$record->completed = (request()->filled('completed'))? 1 : 0;

			$record = $record->upload('upload1', 'training_assets');

			return $record;

		});

		return [
			'message'=>'Training Status Updated',
			'error'=>false,
			'data'=>$updateRecord
		];

	}



}
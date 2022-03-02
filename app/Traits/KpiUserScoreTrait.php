<?php
/**
 * Created by PhpStorm.
 * User: NnamdiAlexanderAkamu
 * Date: 5/17/2020
 * Time: 7:19 PM
 */

namespace App\Traits;


use App\KpiData;
use App\KpiSession;
use App\KpiUserScore;
use App\Notifications\ThreePartiesNotification;
use App\User;
use Excel;
use Auth;

trait KpiUserScoreTrait
{

	function createUserScore(){

		$obj = new KpiUserScore;

		$kpi_data_id = request()->get('kpi_data_id');
		$user_id = request()->get('user_id');

		$check = KpiUserScore::where('user_id',$user_id)->where('kpi_data_id',$kpi_data_id)->first();

		if (is_null($check)){
			$this->updateUserScore($obj,$user_id,$kpi_data_id);
			$obj->save();
			return [
				'message'=>'Score added',
				'error'=>false,
				'data'=>$obj
			];
		}else{
			$this->updateUserScore($check,$user_id,$kpi_data_id);
			return [
				'message'=>'Score updated',
				'error'=>false,
				'data'=>$check
			];
		}

	}

	function sendNotificationToHr($hrs,$cause,$role){
      foreach ($hrs as $hr){
      	$hr->notify(new ThreePartiesNotification($hr, $cause, $role));
      }
	}

	function sendNotificationToManager($manager,$cause,$role){
//	  $manager = User::where('emp_num',76)->first();
//	  dd($manager);
      $manager->notify(new ThreePartiesNotification($manager, $cause, $role));
	}

	function sendNotificationToUser($user,$cause,$role){
      $user->notify(new ThreePartiesNotification($user, $cause, $role));
	}

	function sendGlobalNotification(){

		try{
			$user_id = request()->get('user_id');
			$user = User::find($user_id);
			$cause = Auth::user();
			$manager = User::find($user->linemanager_id);
			$hrs = User::where('role',3)->get();
			$role = request()->get('role');

			if ($role == 'manager'){
				$this->sendNotificationToHr($hrs,Auth::user(),'hr');

				return [
					'message'=>'Notification sent to HR',
					'error'=>false
				];

			}else if ($role == 'user'){
				$this->sendNotificationToManager($manager,Auth::user(), 'manager');

				return [
					'message'=>'Notification sent to user',
					'error'=>false
				];

			}else if ($role == 'hr'){
				$this->sendNotificationToUser($user, Auth::user(), 'user');

				return [
					'message'=>'Notification sent to user',
					'error'=>false
				];

			}
		}catch(\Exception $ex){
			return [
				'message'=>$ex->getMessage(),
				'error'=>true
			];
		}


	}

	function updateUserScore($record,$user_id,$kpi_data_id){

		$user = User::find($user_id);
		$manager = User::find($user->linemanager_id);
		$hrs = User::where('role',3)->get();

		//check for who-is identity (tag)
        if (request()->has('send_notification')){

//        	$send_notification = request()->get('send_notification');
//        	$role = request()->get('role');
//        	if ($role == 'manager'){
//		        $this->sendNotificationToHr($hrs,Auth::user(),$role);
//	        }else if ($role == 'user'){
//                $this->sendNotificationToManager($manager,Auth::user(), $role);
//	        }else if ($role == 'hr'){
//                $this->sendNotificationToUser($user, Auth::user(), $role);
//	        }
        }

        $record->user_id = $user_id;
        $record->kpi_data_id = $kpi_data_id;

        if (request()->has('manager_score')){
	        $record->manager_score = request()->get('manager_score');
        }

        if (request()->has('manager_comment')){
	        $record->manager_comment = request()->get('manager_comment');
        }

        if (request()->has('user_score')){
	        $record->user_score = request()->get('user_score');
        }

        if (request()->has('user_comment')){
	        $record->user_comment = request()->get('user_comment');
        }

        if (request()->has('hr_score')){
	        $record->hr_score = request()->get('hr_score');
        }


        if (request()->has('hr_comment')){
	        $record->hr_comment = request()->get('hr_comment');
        }

		$record->save();

	}


	function createIndividualKpi(){

		if (request()->file('excel_file')) {

			$path = request()->file('excel_file')->getRealPath();
			$data = Excel::load($path)->get();
			$ref = $this;

//			dd($data->toArray());

			if ($data->count() > 0) {

				foreach ($data->toArray() as $k => $v) {

					$response = (new KpiData)->createPrivateKpiData($v);
					$obj = new KpiUserScore;
					$obj->user_id = request()->get('user_id');
					$obj->kpi_data_id = $response['data']->id;
					$obj->manager_score = '';
					$obj->manager_comment = '';
					$obj->user_score = '';
					$obj->user_comment = '';
					$obj->hr_score = '';
					$obj->hr_comment = '';
					$obj->save();

				}

				return [
					'message'=>'Bulk Individual KPI uploaded',
					'error'=>false
				];

			}else{
				return [
					'message'=>'Invalid Excel File!',
					'error'=>true
				];
			}

		}else{
			$response = (new KpiData)->createPrivateKpiData();
			$obj = new KpiUserScore;
			$obj->user_id = request()->get('user_id');
			$obj->kpi_data_id = $response['data']->id;
			$obj->manager_score = '';
			$obj->manager_comment = '';
			$obj->user_score = '';
			$obj->user_comment = '';
			$obj->hr_score = '';
			$obj->hr_comment = '';
			$obj->save();
			return [
				'message'=>'Individual KPI Added',
				'error'=>false
			];
		}



	}

	function updateIndividualKpi(){

	}

	function removeIndividualKpi(){
		$id = request()->get('id');
		$remove2 = KpiUserScore::where('kpi_data_id',$id);
		$remove1 = KpiData::find($id);
		$remove1->delete();
		$remove2->delete();

		return [
			'message'=>'Individual KPI Removed',
			'error'=>false
		];
	}

}
<?php
/**
 * Created by PhpStorm.
 * User: NnamdiAlexanderAkamu
 * Date: 10/17/2020
 * Time: 12:32 AM
 */

namespace App\Services;


use App\Stage;
use App\Traits\WithWorkflowStage;
use App\TrOfflineTrainingApproval;
use App\User;
use Auth;

class TrOfflineTrainingService
{

	use WithWorkflowStage;


	function onEnableApprovalStage(Stage $stage, $objApproval)
	{
		// TODO: Implement onEnableApprovalStage() method.
		$objApproval->status = 1;
		$objApproval->comment = request('comment');
		$objApproval->approver_id = Auth::user()->id;
		$objApproval->save();
		//tr_user_offline_training_id
	}

	function onEnableFinalApprovalStage(Stage $stage, $objApproval)
	{
		// TODO: Implement onEnableFinalApprovalStage() method.
		$objApproval->status = 1;
		$objApproval->comment = request('comment');
		$objApproval->approver_id = Auth::user()->id;
		$objApproval->save();

		$objApproval->training->status = 1; //approve main training.
		$objApproval->training->save();

		//tr_user_offline_training_id

	}

	function onRejectApprovalStage(Stage $stage, $objApproval)
	{
		// TODO: Implement onRejectApprovalStage() method.
		$objApproval->status = 0;
		$objApproval->comment = request('comment');
		$objApproval->approver_id = Auth::user()->id;
		$objApproval->save();

	}

	function onCreateApprovalStage(Stage $stage, $objApproval_)
	{
		// TODO: Implement onCreateApprovalStage() method.
		$objApproval = new TrOfflineTrainingApproval;
		$objApproval->tr_offline_training_id = is_object($objApproval_)? $objApproval_->tr_offline_training_id : $objApproval_;
		$objApproval->status = 0;
		$objApproval->stage_id = $stage->id;
//		$objApproval->comment = request('comment');
//		$objApproval->approver_id = Auth::user()->id;
		$objApproval->save();

	}

	function onNotifyUserApprove(User $user, Stage $stage, $objApproval)
	{
		// TODO: Implement onNotifyUserApprove() method.
	}

	function onNotifyUserReject(User $user, Stage $stage, $objApproval)
	{
		// TODO: Implement onNotifyUserReject() method.
//		$this->startStage($workFlowId, $objApproval, $user)
	}

	function initStage($workFlowId,$tr_offline_training_id,$user){
		$this->startStage($workFlowId, $tr_offline_training_id, $user);
	}


}
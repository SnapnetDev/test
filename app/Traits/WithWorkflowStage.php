<?php
/**
 * Created by PhpStorm.
 * User: NnamdiAlexanderAkamu
 * Date: 9/28/2020
 * Time: 11:27 AM
 */

namespace App\Traits;


use App\Stage;
use App\User;
use App\Workflow;

trait WithWorkflowStage
{

	abstract function onEnableApprovalStage(Stage $stage,$objApproval);
	abstract function onEnableFinalApprovalStage(Stage $stage,$objApproval);
	abstract function onRejectApprovalStage(Stage $stage,$objApproval);
	abstract function onCreateApprovalStage(Stage $stage,$objApproval);
	abstract function onNotifyUserApprove(User $user,Stage $stage,$objApproval);
	abstract function onNotifyUserReject(User $user,Stage $stage,$objApproval);


//	abstract function onFirstStage();
//	abstract function onLastStage();
//	abstract function onNotifyApproval();
//	abstract function onNotifyRejection();


	function startStage($workFlowId,$objApproval,$user){
      $stage = Workflow::find($workFlowId)->stages->first();
      $this->onCreateApprovalStage($stage,$objApproval);
      $this->stageNotificationreUse($stage,$objApproval,$user);
	}


	function approveStage($objApproval,User $user){

		$stage = $objApproval->stage; //index objApproval to stage
		$newposition = $stage->position + 1;
		$nextstage = Stage::where(['workflow_id' => $stage->workflow->id, 'position' => $newposition])->first();

		$this->onEnableApprovalStage($stage,$objApproval);

		if ($nextstage){

			$this->onCreateApprovalStage($nextstage,$objApproval);

			$this->stageNotificationreUse($nextstage,$objApproval,$user);

			return;
		}

		//approval final section.
		$this->onEnableFinalApprovalStage($stage, $objApproval);

	}

	private function stageNotificationreUse($stage,$objApproval,$user){
        if ($stage->type == 1) {

            $this->onNotifyUserApprove($stage->user,$stage,$objApproval);

        }

        if ($stage->type == 2) {


            if ($stage->role->manages == 'dr') {

                // return $nextstage->role->manage . '---' . json_encode($nextstage->role->users);

                foreach ($user->managers as $manager) {
                    $this->onNotifyUserApprove($manager,$stage,$objApproval);
//						$manager->notify(new ApproveLeaveRequest($newleave_approval->leave_request));
                }

            }elseif($stage->role->manages == 'ss') {

                foreach ($user->plmanager->managers as $manager) {
                    $this->onNotifyUserApprove($manager,$stage,$objApproval);
//						$manager->notify(new ApproveLeaveRequest($newleave_approval->leave_request));
                }
            } elseif ($stage->role->manages == 'all') {
                // return 'all.';

                // return $nextstage->role->manage . '---' . json_encode($nextstage->role->users);

                foreach ($stage->role->users as $user) {
                    $this->onNotifyUserApprove($user,$stage,$objApproval);
//						$user->notify(new ApproveLeaveRequest($newleave_approval->leave_request));
                }
            } elseif ($stage->role->manages == 'none') {
                foreach ($stage->role->users as $user) {
                    $this->onNotifyUserApprove($user,$stage,$objApproval);
                }
            }


        }



        if ($stage->type == 3) {
            //1-user
            //2-role
            //3-groups
            // return 'not_blank';

            foreach ($stage->group->users as $user) {
                $this->onNotifyUserApprove($user,$stage,$objApproval);
//					$user->notify(new ApproveLeaveRequest($newleave_approval->leave_request));
            }
        }

    }


	function rejectStage($objApproval,User $user){
		$stage = $objApproval->stage;
		$this->onRejectApprovalStage($stage, $objApproval);
		$this->onNotifyUserReject($user,$stage, $objApproval);

	}


}
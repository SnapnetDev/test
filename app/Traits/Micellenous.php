<?php
namespace App\Traits;
use App\Notifications\UpdateKpiInfo;

use App\Notifications\NotifyHrSavePerformanceDiscussion;
/**
 *
 */
trait Micellenous
{
 
 public function fiscalYear(){


 }

 public function pilotGoals(){

   $goals = \App\Goal::whereHas('goalcat',function($query){
   						$query->where('category','Pilot');
   					})->get();
      
    return $goals;
}


 public function goals($column,$emp_id,$quarter,$date){

   $goals = \App\Goal::where('goal_cat_id',$column) 
   					->where(['assigned_to'=>$emp_id,'quarter'=>$quarter])
   					->whereYear('created_at',session('FY'))
   					->get();
   			 
    return $goals;
}



public function notifyUserKpiChange($user_id,$evaluation_id){
	$user=\App\User::where('id',$user_id)->first(); 
	$user->notify((new UpdateKpiInfo("bsc/get_my_evaluation?evaluation=$evaluation_id")));
	 
}

public function nofityHrAdminSaveDiscussion($evaluation){
 
	$Hrusers=\App\User::whereHas('role',function($query){
			$query->where('manages','all');
	} )->where('company_id',companyId())->get();
	foreach($Hrusers as $hr){   
		$hr->notify((new NotifyHrSavePerformanceDiscussion("bsc/get_hr_evaluation?employee={$evaluation->bscevaluation->user->id}&mp=$evaluation->evaluation_id",$evaluation->bscevaluation->user->name)));

	}
}



}

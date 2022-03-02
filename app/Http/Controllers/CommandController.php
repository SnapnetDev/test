<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\CommandTrait;
use App\Traits\MoodleTrait;
use App\Traits\TrainingBudgetTrait;
use App\Traits\TrainingPlanTrait;
use App\Traits\TrainingUserPlanTrait;
use Illuminate\Http\Request;

class CommandController extends Controller
{
    //


	use CommandTrait;
	use MoodleTrait;
	use TrainingPlanTrait;
	use TrainingBudgetTrait;
	use TrainingUserPlanTrait;

	function processAjaxAction(Request $request,$cmd){
		return $this->runAjaxCommand($cmd, $request);
	}

	function processAction(Request $request,$cmd){
		return $this->runActionCommand($cmd, $request);
	}


	function testCourses(){
		//process-ajax-command
		dd($this->fetchCourses());
	}




	function enrollUserAction($response,Request $request){

//		echo $request->get('userId');
//		dd($response);

		return redirect()->back()->with('a','b')->with('c','d')->with($response);

	}

	function getTrackedCourse_(Request $request){
		return $this->trackCourse($request);
	}

	function createTrainingPlan()
	{
		$response = $this->createTrainingPlan_();
		return redirect()->back()->with($response);

	}

	function updateTrainingPlan()
	{
		$response = $this->updateTrainingPlan_([]);
		return redirect()->back()->with($response);
	}

	function deleteTrainingPlan()
	{
		$response = $this->deleteTrainingPlan_();
		return redirect()->back()->with($response);
	}

	function respondToTrainingPlan()
	{
		$response = $this->respondToTrainingPlan_();
		return redirect()->back()->with($response);

	}

	function createTrainingBudget()
	{
		$response = $this->createTrainingBudget_();
		return redirect()->back()->with($response);

	}

	function updateTrainingBudget()
	{
		$response = $this->updateTrainingBudget_();
		return redirect()->back()->with($response);

	}

	function deleteTrainingBudget()
	{
		$response = $this->deleteTrainingBudget_();
		return redirect()->back()->with($response);

	}

	function updateUserTrainingPlan()
	{
		$response = $this->updateUserTrainingPlan_();
		return redirect()->back()->with($response);

	}



//	function approveTrainingPlan()
//	{
//		$response = $this->approveTrainingPlan_();
//		return redirect()->back()->with($response);
//	}
//
//	function denyTrainingPlan()
//	{
//		$response = $this->denyTrainingPlan_();
//		return redirect()->back()->with($response);
//	}

}

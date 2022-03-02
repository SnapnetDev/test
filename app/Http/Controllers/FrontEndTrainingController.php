<?php

namespace App\Http\Controllers;

use App\Traits\CommandTrait;
use App\Traits\MoodleTrait;
use App\Traits\TrainingBudgetTrait;
use App\Traits\TrainingPlanTrait;
use App\Traits\TrainingUserPlanTrait;
use App\Traits\ViewTraits\CourseViewTrait;
use App\User;
use Illuminate\Http\Request;
use Auth;

class FrontEndController extends Controller
{
    //

	use CommandTrait;
	use MoodleTrait;
	use TrainingPlanTrait;
	use TrainingBudgetTrait;
	use TrainingUserPlanTrait;

//	use CourseViewTrait;

	function processGet($cmd,Request $request){
		return $this->runViewCommand($cmd, $request);
	}

	function hello(){
		return 'Hello world....';
	}

	function getCourseCategories(){
		return $this->fetchCourses();
	}

	function getCourseListPartial(){
		$data = $this->fetchCourses();
		return view('performance.scriptss.course_list_partial',$data);
	}



	function courseTraining(Request $request){
		$data = [];
		$id = $request->get('id');
		$data['employeeId'] = $id;
		$user = User::find($id);
		$data['employee'] = $user;
		$data['courses'] = $this->fetchCourses();
		$ref = $this;
		$data['courseTrack'] = function($courseId) use ($ref,$user){
			return $ref->trackCourseExt($user->emp_num, $courseId);
		};
		return view('training_new.index',$data);
	}



	function fetchTrainingPlan()
	{

		$data = $this->fetchTrainingPlan_();
		$data['vars'] = $this->getVars();

		return view('training_new.plan',$data);

	}

	function fetchTrainingPlanForApproval(){

		$data = $this->fetchTrainingPlanForApproval_();

		$data['mode'] = 'hr';
		$data['disable_create'] = true;
		$data['vars'] = $this->getVars();


		return view('training_new.plan_approvals',$data);

	}

	function fetchMyTrainingBudget()
	{
		//createTrainingBudget

		$defaultYear = date('Y');
		if (request()->filled('year')){
			$defaultYear = request()->get('year');
		}
		$previousYear = $defaultYear - 1;
//		$data = [];

		$data = $this->fetchMyTrainingBudget_();

		$data['prevYear'] = [
			'label'=>'Allocation For ' . $previousYear . ':',
			'data'=>$this->getTrainingBudgetTotalByYear_([
				'year'=>$previousYear
			])
		];

		$data['currentYear'] = [
			'label'=>'Allocation For ' . $defaultYear . ':',
			'data'=>$this->getTrainingBudgetTotalByYear_([
				'year'=>$defaultYear
			])
		];

		return view('training_new.budget',$data);

	}

	function fetchTrainingPlanApproved()
	{
		return $this->fetchTrainingPlanApproved_();
	}


	function createUserTrainingPlan()
	{
		return $this->createUserTrainingPlan_();
	}

	function userTrainingIsEnrolled()
	{
		return $this->userTrainingIsEnrolled_();
	}

	function removeUserTrainingPlan()
	{
		return $this->removeUserTrainingPlan_();
	}

	function getUserTrainingMetta()
	{
		return $this->getUserTrainingMetta_();
	}

	function getUserIsEligible()
	{
		$userId = request()->get('userId');
		$trainingPlanId = request()->get('trainingPlanId');
		return $this->getUserIsEligible_($userId, $trainingPlanId);
	}

	function getUsersByPermission()
	{

	  $users = $this->getUsersByPermission_('approve_training_plan');
//      dd($users);
		return $users;
		//$auth->role->permissions->contains('constant', 'group_access')

	}

	function getOfflineTraining(){
		$userId = Auth::user()->id;
		$data = $this->getUserTrainingPlan_($userId);

		return view('training_new.my_training_plan',$data);

	}



	function fetchTrainingPlanCounts()
	{
		return $this->fetchTrainingPlanCounts_();
	}


	function getAjax(){
		return [
			[
				'name'=>'AKL'
			],
			[
				'name'=>'AKL2'
			],
			[
				'name'=>'AKL3'
			]

		];
	}
//
//	function getAjax2(){
//		return [
//			'a'=>request()->get('a') . uniqid()
//		];
//	}


}

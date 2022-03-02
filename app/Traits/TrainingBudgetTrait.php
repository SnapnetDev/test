<?php
/**
 * Created by PhpStorm.
 * User: NnamdiAlexanderAkamu
 * Date: 4/17/2020
 * Time: 2:02 AM
 */

namespace App\Traits;


use App\Grade;
use App\Tr_TrainingBudget;

use Auth;
use Excel;

trait TrainingBudgetTrait
{


	function fetchMyTrainingBudget_($config=[]){
		return [
			'list'=>(new Tr_TrainingBudget)->fetchRequestTrainingBudget_($config)->paginate(20),
			'grades'=>Grade::where('company_id',companyId())->get()
		];
	}
	
	function getTrainingBudgetTotalByYear_($config=[]){
		$query = new Tr_TrainingBudget;
		$sum = $query->fetchRequestTrainingBudget_($config)->sum('allocation_total');
		return [
			'sum'=>$sum
		];
	}

	private function checkBulkUpload(){

	}

	private function getGradeIdFromLevel($level){
		$obj = Grade::where('level',$level)->first();
		if (!is_null($obj)){
			return $obj->id;
		}else{
			return '';
		}
	}

	function createTrainingBudget_(){

		if (request()->file('excel_file')){

			$path = request()->file('excel_file')->getRealPath();
			$data = Excel::load($path)->get();
			$ref = $this;

//			dd($data->toArray());

			if ($data->count() > 0){

				foreach ($data->toArray() as $k=>$v){

					$newRecord = (new Tr_TrainingBudget)->entityCreate(function($record) use ($ref,$v){

						$record->hr_id = Auth::user()->id;
						$record->grade_id = $ref->getGradeIdFromLevel($v['grade']);
						$record->training_budget_name = $v['name'];
						$record->allocation_total = $v['allocation_total'];
						$record->year_of_allocation = date('Y');

						return $record;
					});


				}


				return [
					'message'=>'New Training Budget Uploaded',
					'data'=>[],
					'error'=>false
				];


			}

//			dd('bulk detected');

		}else{
			$newRecord = (new Tr_TrainingBudget)->entityCreate(function($record){

				$record->hr_id = Auth::user()->id;
				$record->grade_id = request()->get('grade_id');
				$record->training_budget_name = request()->get('training_budget_name');
				$record->allocation_total = request()->get('allocation_total');
				$record->year_of_allocation = date('Y');

				return $record;
			});

			return [
				'message'=>'New Training Budget Created',
				'data'=>$newRecord,
				'error'=>false
			];

		}


	}

	function updateTrainingBudget_(){

		$updatedRecord = (new Tr_TrainingBudget)->entityUpdate(request()->get('id'),function($record){


//			$record->hr_id = Auth::user()->id;
			$record->grade_id = request()->get('grade_id');
			$record->training_budget_name = request()->get('training_budget_name');
			$record->allocation_total = request()->get('allocation_total');
//			$record->year_of_allocation = date('Y');


       	return $record;

       });


		return [
			'message'=>'Training Budget Updated',
			'data'=>$updatedRecord,
			'error'=>false
		];

	}

	function deleteTrainingBudget_(){

		$deletedRecord = (new Tr_TrainingBudget)->entityDelete(request()->get('id'));

		return [
			'message'=>'Training Budget Removed',
			'data'=>$deletedRecord,
			'error'=>false
		];

	}

}
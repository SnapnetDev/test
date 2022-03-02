<?php
/**
 * Created by PhpStorm.
 * User: NnamdiAlexanderAkamu
 * Date: 4/17/2020
 * Time: 2:03 AM
 */

namespace App\Traits\ModelTraits;


use App\Tr_TrainingBudget;
use Illuminate\Database\Eloquent\Builder;

trait TrainingBudgetModelTrait
{





	function fetchRequestTrainingBudget_($config=[]){

		if (request()->filled('year')){
			$config['year'] = request()->get('year');
		}

		if (request()->filled('grade_id')){
			$config['grade_id'] = request()->get('grade_id');
		}

		return $this->fetchTrainingBudgetQuery_($config);

	}


	function fetchTrainingBudgetQuery_($config=[]) : Builder{


		$query = new Tr_TrainingBudget;
		$query = $query->entityFetch(function(Builder $builder) use ($config,$query){

             if (isset($config['grade_id'])){
             	$builder = $query->filterByGradeId($builder, $config['grade_id']);
             }

             if (isset($config['year'])){
               $builder = $query->filterByYearOfAllocation($builder, $config['year']);
             }


			 return $builder;

		});

		return $query;
	}

}
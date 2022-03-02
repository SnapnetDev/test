<?php
/**
 * Created by PhpStorm.
 * User: NnamdiAlexanderAkamu
 * Date: 4/20/2020
 * Time: 2:03 AM
 */

namespace App\Traits\ModelTraits;


use App\Tr_UserOfflineTraining;
use Illuminate\Database\Eloquent\Builder;

trait UserTrainingPlanModelTrait
{
	function fetchUserTrainingPlanRequestQuery($config=[]) : Builder{

        $all = request()->all();
        foreach ($all as $k=>$v){
        	$config[$k] = $v;
        }

		return $this->fetchUserTrainingPlanQuery($config);
	}

	function fetchUserTrainingPlanQuery($config=[]) : Builder{
		$query = new Tr_UserOfflineTraining;
		$query = $query->entityFetch(function(Builder $builder) use ($config,$query){

			 if (isset($config['training_plan_id'])){
			 	$builder = $query->filterByTrainingPlanId($builder, $config['training_plan_id']);
			 }

			 if (isset($config['user_id'])){
               $builder = $query->filterByUserId($builder, $config['user_id']);
			 }
			 
			 if (isset($config['completed'])){
			 	$builder = $query->filterByCompleted($builder, $config['completed']);
			 }

			 if (isset($config['status'])){
			 	$builder = $query->filterByLineManager($builder, $config['status']);
			 }

			 if (isset($config['rating'])){
			 	$builder = $query->filterByRating($builder, $config['rating']);
			 }

			 return $builder;

		});

		return $query;
	}

}
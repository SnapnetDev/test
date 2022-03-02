<?php
/**
 * Created by PhpStorm.
 * User: NnamdiAlexanderAkamu
 * Date: 4/15/2020
 * Time: 10:31 AM
 */

namespace App\Traits;


use App\Tr_OfflineTraining;
use Illuminate\Database\Eloquent\Builder;

trait TrainingTrait
{


	function fetchTraining_(){

	}

	private function fetchRequestTrainingQuery_($config=[]){

	}

	private function fetchTrainingQuery_($config=[]){
	  $query = new Tr_OfflineTraining;
	  return $query->entityFetch(function(Builder $builder) use ($config,$query){

	  	if (isset($config['line_manager_id'])){

	    }

	  	return $builder;
	  });
	}

	function createTraining_(){

	}

	function updateTraining_(){

	}


}
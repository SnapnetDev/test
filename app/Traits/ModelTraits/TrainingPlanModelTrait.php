<?php
/**
 * Created by PhpStorm.
 * User: NnamdiAlexanderAkamu
 * Date: 4/15/2020
 * Time: 11:36 PM
 */

namespace App\Traits\ModelTraits;


use App\Tr_OfflineTraining;
use Illuminate\Database\Eloquent\Builder;

trait TrainingPlanModelTrait
{


	function fetchRequestTrainingPlan($config){

		if (request()->filled('year')){
			$config['year'] = request()->get('year');
		}

		if (request()->filled('nameSearch')){
			$config['nameSearch'] = request()->get('nameSearch');
		}

		if (request()->filled('line_manager_id')){
			$config['line_manager_id'] = request()->get('line_manager_id');
		}

		if (request()->filled('status')){
			$config['status'] = request()->get('status');
		}

		if (request()->filled('training_date')){
			$config['training_date'] = request()->get('training_date');
		}

		if (request()->filled('type')){
			$config['type'] = request()->get('type');
		}


//	    if (!isset($config['user_id'])){
		    if (request()->filled('user_id')){
			  if (isset($config['user_id'])){
			  	  //
			  }else{
				  $config['user_id'] = request()->get('user_id');
			  }
		    }
//	    }

		if (request()->filled('completed')){
			$config['completed'] = request()->get('completed');
		}

		if (request()->filled('enroll_status')){
			$config['enroll_status'] = request()->get('enroll_status');
		}



		if (request()->filled('filterDepartmentUserId')){
			$config['filterDepartmentUserId'] = request()->get('filterDepartmentUserId');
		}

		if (request()->filled('filterRoleUserId')){
			$config['filterRoleUserId'] = request()->get('filterRoleUserId');
		}

		if (request()->filled('filterGroupUserId')){
			$config['filterGroupUserId'] = request()->get('filterGroupUserId');
		}

		return $this->fetchTrainingPlanQuery($config);

	}


	function fetchTrainingPlanQuery($config){

//		dd($config);

		$query = new Tr_OfflineTraining;
		$query = $query->entityFetch(function(Builder $builder) use ($config,$query){

			if (isset($config['nameSearch'])){
				$builder = $query->filterByNameSearch($builder, 'name', $config['nameSearch']);
			}
			
			if (isset($config['year'])){
				$builder = $query->filterByYearOfTraining($builder, $config['year']);
			}

			if (isset($config['line_manager_id'])){
				$builder = $query->filterByLineManager($builder, $config['line_manager_id']);
			}

			if (isset($config['status'])){
				$builder = $query->filterByStatus($builder, $config['status']);
			}

			if (isset($config['training_date'])){
				$builder = $query->filterByTrainingInterval($builder, $config['training_date']);
			}

			if (isset($config['type'])){
				$builder = $query->filterByType($builder, $config['type']);
			}

			//inner config
			$smallConfig = [];

			if (isset($config['user_id']) && $config['user_id'] != 0) {
				$smallConfig['user_id'] = $config['user_id'];
			}

			if (isset($config['completed'])) {
				$smallConfig['completed'] = $config['completed'];
			}

			if (isset($config['enroll_status'])){
				$smallConfig['status'] = $config['enroll_status'];
			}

			if (!empty($smallConfig)){
				$builder = $query->filterByTrainingUsersRelation($builder,$smallConfig);
//				dd($builder->toSql());
			}

			if (isset($config['filterDepartmentUserId']) && !empty($config['filterDepartmentUserId'])){
				$builder = $query->filterByOfflineTrainingUserDepartment($builder, $config['filterDepartmentUserId']);
			}

			if (isset($config['filterRoleUserId']) && !empty($config['filterRoleUserId'])){
				$builder = $query->filterByOfflineTrainingUserRole($builder, $config['filterRoleUserId']);
			}

			if (isset($config['filterGroupUserId']) && !empty($config['filterGroupUserId'])){
				$builder = $query->filterByOfflineTrainingUserGroups($builder, $config['filterGroupUserId']);
			}


			$builder = $builder->with(['department','role','training_groups']); //group


//			dd($smallConfig);

			return $builder;

		});

		return $query;

	}


}
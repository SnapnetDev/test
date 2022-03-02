<?php
/**
 * Created by PhpStorm.
 * User: NnamdiAlexanderAkamu
 * Date: 4/15/2020
 * Time: 10:29 AM
 */

namespace App\Traits\ModelTraits;


use Illuminate\Database\Eloquent\Builder;

trait FilterTrait
{

	function filterByLineManager(Builder $builder,$line_manager_id){
		return $builder->where('line_manager_id',$line_manager_id);
	}

	function filterByApprovedBy(Builder $builder,$approved_by){
		return $builder->where('approved_by',$approved_by);
	}

	function filterByStatus(Builder $builder,$status){
		return $builder->where('status',$status);
	}

	function filterByType(Builder $builder,$type){
		return $builder->where('type',$type);
	}


	function filterByTrainingInterval(Builder $builder,$date){
		return $builder->where('train_start','>=', $date)->where('train_stop','<=',$date);
	}

//	function filterByTrainingStopInterval(Builder $builder,$start,$stop){
//		return $builder->where('train_stop','>=', $start)->where('train_stop','<=',$stop);
//	}

	function filterByYearOfTraining(Builder $builder,$year){
		return $builder->where('year_of_training',$year);
	}

	function filterByHrId(Builder $builder,$hrId){
		return $builder->where('hr_id',$hrId);
	}

	function filterByGradeId(Builder $builder,$gradeId){
		return $builder->where('grade_id',$gradeId);
		//->where('year_of_allocation',$year);
	}

	function filterByYearOfAllocation(Builder $builder,$year){
		return $builder->where('year_of_allocation',$year);
	}


	function filterByTrainingUsers(Builder $builder,$userId){
		return $builder->whereHas('training_users',function(Builder $builder) use ($userId){
			$builder = $builder->where('user_id',$userId);
			return $builder;
		});
	}

	function filterByTrainingUsersHistory(Builder $builder,$userId,$year){
		return $builder->where('year_of_training',$year)->whereHas('training_users',function(Builder $builder) use ($userId){

			return $builder->where('user_id',$userId)->where('status',1);

		});
	}

	function filterByTrainingPlanId(Builder $builder,$trainingPlanId){
		return $builder->where('training_plan_id',$trainingPlanId);
	}

	function filterByUserId(Builder $builder,$userId){
		return $builder->where('user_id',$userId);
	}

	function filterByCompleted(Builder $builder,$completed){
		return $builder->where('completed',$completed);
	}

	function filterByRating(Builder $builder,$rating){
		return $builder->where('rating',$rating);
	}

	function filterByTrainingUsersRelation(Builder $builder,$config=[]){
		return $builder->whereHas('training_users',function(Builder $builder) use ($config){

			if (isset($config['user_id'])){
                $builder->where('user_id',$config['user_id']);
			}
			if (isset($config['completed'])){
				$builder = $builder->where('completed',$config['completed']);
			}
			if (isset($config['status'])){
				$builder = $builder->where('status',$config['status']);
			}

			return $builder;
		});
	}

	function filterByNameSearch(Builder $builder,$nameKey,$nameSearch){
		return $builder->where($nameKey,'like' , '%' . $nameSearch . '%');
	}



//	function filterByUserTrainingAllocationByGrade(Builder $builder,$gradeId){
//	   //grade
//	   return $builder->whereHas('grade',function(Builder $builder){
//	   	  return $builder->whereHas('',function(){});
//	   });
//	}


   function filterByOfflineTrainingUserRole(Builder $builder,$userIdRole){
		return $builder->whereHas('role',function(Builder $builder) use ($userIdRole){
			return $builder->whereHas('users',function(Builder $builder) use ($userIdRole){
				return $builder->where('users.id',$userIdRole);
			});
		});
   }

   function filterByOfflineTrainingUserDepartment(Builder $builder,$userIdDepartment){
		return $builder->whereHas('department',function(Builder $builder) use ($userIdDepartment){
			return $builder->whereHas('jobs',function(Builder $builder) use ($userIdDepartment){
				return $builder->whereHas('users',function(Builder $builder) use ($userIdDepartment){
					return $builder->where('users.id',$userIdDepartment);
				});
			});
		});
   }


   function filterByOfflineTrainingUserGroups(Builder $builder,$userIdGroup){
		return $builder->whereHas('training_groups',function(Builder $builder) use ($userIdGroup){
			return $builder->whereHas('group',function(Builder $builder) use ($userIdGroup){
				return $builder->whereHas('users',function(Builder $builder) use ($userIdGroup){
					return $builder->where('users.id',$userIdGroup);
				});
			});
		});
   }









}
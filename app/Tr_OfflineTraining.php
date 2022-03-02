<?php

namespace App;

use App\Traits\ModelTraits\CrudTrait;
use App\Traits\ModelTraits\FilterTrait;
use App\Traits\ModelTraits\TrainingPlanModelTrait;
use App\Traits\TrainingPlanTrait;
use Illuminate\Database\Eloquent\Model;

class Tr_OfflineTraining extends Model
{

	use CrudTrait;
	use FilterTrait;
	use TrainingPlanModelTrait;

	protected $with = ['training_groups','department','role'];

	//
	protected $table = 'tr_offline_trainings';

	function training_users(){
		return $this->hasMany(Tr_UserOfflineTraining::class,'training_plan_id');
	}

	function line_manager(){
		return $this->belongsTo(User::class,'line_manager_id');
	}

	function department(){
		return $this->belongsTo(Department::class,'dep_id');
	}

	function role(){
	   return $this->belongsTo(Role::class,'role_id');
	}

	function training_groups(){
		return $this->hasMany(Tr_OfflineTrainingGroup::class,'training_plan_id');
	}


}

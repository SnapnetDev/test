<?php

namespace App;

use App\Traits\ModelTraits\CrudTrait;
use App\Traits\ModelTraits\FilterTrait;
use App\Traits\ModelTraits\MicroUploadTrait;
use App\Traits\ModelTraits\UserTrainingPlanModelTrait;
use App\Traits\ModelTraits\UserTrainingPlanTrait;
use Illuminate\Database\Eloquent\Model;

class Tr_UserOfflineTraining extends Model
{
	use CrudTrait;
	use FilterTrait;
	use UserTrainingPlanModelTrait;
	use MicroUploadTrait;

    //
	protected $table = 'tr_user_offline_trainings';
	protected $with = ['training','user'];

	function user(){
		return $this->belongsTo(User::class,'user_id');
	}

	function training(){
		return $this->belongsTo(Tr_OfflineTraining::class,'training_plan_id');
	}


}

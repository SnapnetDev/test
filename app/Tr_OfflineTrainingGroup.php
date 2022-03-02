<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tr_OfflineTrainingGroup extends Model
{
    //
	protected $table = 'tr_offline_training_group';
	protected $with = ['group'];

	function group(){
//		return $this->belongsToMany(UserGroup::class,'group_id');
		return $this->belongsTo(UserGroup::class,'group_id');
	}

	function offline_training(){
		return $this->belongsTo(Tr_OfflineTraining::class,'training_plan_id');
	}

}

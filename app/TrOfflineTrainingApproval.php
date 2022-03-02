<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TrOfflineTrainingApproval extends Model
{
    //
	protected $with = ['stage','approver'];

	function tr_user_offline_training(){
		return $this->belongsTo(Tr_OfflineTraining::class,'tr_user_offline_training_id');
	}

	function approver(){
		return $this->belongsTo(User::class,'approver_id');
	}

	function stage(){
		return $this->belongsTo(Stage::class,'stage_id');
	}

	function training(){
		return $this->belongsTo(Tr_OfflineTraining::class,'tr_offline_training_id');
	}


    function canApprove(User $user){

        //user_groups
        $id = $this->id;
        $query = (new TrOfflineTrainingApproval)->newQuery();
        $stage = $this->stage;
        $query = $query->where('id',$id)->whereHas('stage',function(Builder $builder) use ($user,$stage){

            if ($stage->type == 1){ //user
                $builder = $builder->where('user_id',$user->id);
            }

            if ($stage->type == 2){ //role
                $builder = $builder->whereHas('role',function(Builder $builder) use ($user){
                    //users
                    $builder = $builder->whereHas('users',function(Builder $builder) use ($user){
                        return $builder->where('id',$user->id);
                    });
                    return $builder;
                });
            }

            if ($stage->type == 3){//group
                $builder = $builder->whereHas('group',function(Builder $builder) use ($user){
                    $builder = $builder->whereHas('users',function(Builder $builder) use ($user){
                        return $builder->where('id',$user->id);
                    });
                    return $builder;
                });
            }

            return $builder;

        });

        return $query->exists();

    }


}

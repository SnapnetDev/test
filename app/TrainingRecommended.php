<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrainingRecommended extends Model
{
    //
    protected $table='training_recommended';
    protected $fillable = ['training_id', 'trainee_id', 'budget_id', 'training_mode', 'duration', 'proposed_start_date', 'proposed_end_date', 'status_id', 'approval_id', 'remark', 'created_by'];


    public function trainings()
    {
        return $this->belongsTo('App\Training', 'training_id');
    }

    public function trainees()
    {
    	return $this->belongsToMany('App\User','rec_training_trainee', 'rec_training_id','trainee_id');
    }

    public function status()
    {
    	return $this->belongsTo('App\StatusAndStage', 'status_id');
    }

    public function approval()
    {
    	return $this->belongsTo('App\StatusAndStage', 'approval_id');
    }
}

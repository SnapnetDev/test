<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrainingBudget extends Model
{
    //
    protected $table='training_budget';
    protected $fillable = ['trainee_id', 'purpose', 'amount', 'status_id', 'created_by'];


    public function trainee()
    {
    	return $this->belongsTo('App\User', 'trainee_id');
    }

    public function status()
    {
    	return $this->belongsTo('App\StatusAndStage', 'status_id');
    }
}

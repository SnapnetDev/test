<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatusAndStage extends Model
{
    //
    protected $table='status_and_stages';
    protected $fillable = ['type', 'name', 'created_by'];


    public function TrainingRecommended()
    {
        return $this->hasMany('App\TrainingRecommended', 'status_id', 'approver_id');
    }


    public function TrainingBudget()
    {
        return $this->hasMany('App\TrainingBudget', 'status_id');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BscEvaluationDetail extends Model
{
    protected $table="bsc_evaluation_details";
    public $appends= ['modified_date'];
   protected $fillable=['bsc_evaluation_id','metric_id','focus','objective','key_deliverable','measure_of_success','means_of_verification','evaluator_id','target','achievement','manager_of_manager_assessment','weight',
       'self_assessment','manager_assessment','justification_of_rating','is_penalty','head_of_hr_comment','head_of_strategy_comment','accept_reject'];
   public function metric()
    {
        return $this->belongsTo('App\BscMetric', 'metric_id');
    }
    public function evaluation()
    {
        return $this->belongsTo('App\BscEvaluation', 'bsc_evaluation_id');
    }

    public function getModifiedDateAttribute(){
        return date('Y-m-d',strtotime($this->updated_at));
    }
}

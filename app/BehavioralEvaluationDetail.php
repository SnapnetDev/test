<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BehavioralEvaluationDetail extends Model
{
   protected $table="behavioral_evaluation_details";
    public $appends= ['modified_date','objective','measure','weighting'];
   protected $fillable=['bsc_evaluation_id','behavioral_sub_metric_id','actual','comment','self_assessment','manager_assessment',
       'appraisee_approved',
'manager_of_manager_assessment', 'head_of_strategy','head_of_hr' ,'accept_reject' ];
   public function behavioral_sub_metric()
    {
        return $this->belongsTo('App\BehavioralSubMetric', 'behavioral_sub_metric_id');
    }
    public function evaluation()
    {
        return $this->belongsTo('App\BscEvaluation', 'bsc_evaluation_id');
    }

    public function getModifiedDateAttribute(){
        return date('Y-m-d',strtotime($this->updated_at));
    }

    public function getObjectiveAttribute(){

        return $this->behavioral_sub_metric->objective;
    }
    public function getMeasureAttribute(){
        return $this->behavioral_sub_metric->measure;
    }
    public function getWeightingAttribute(){
        return $this->behavioral_sub_metric->weighting;
    }

}

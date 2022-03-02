<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppraisalSubMetric extends Model
{
    protected $table="appraisal_sub_metrics";
   	protected $fillable=['name','description','editable','has_target','employee_id','weight','target','created_by','updated_by','company_id'];

   	public function appraisal_metric()
   	{
   		return $this->belongsTo('App\AppraisalMetric','appraisal_metric_id');
   	}
   	public function employee()
   	{
   		return $this->belongsTo('App\User','employee_id');
   	}
   	public function appraisal_assessments()
   	{
   		return $this->hasMany('App\AppraisalAssessment','appraisal_sub_metric_id');
   	}
      public function author()
       {
         return $this->belongsTo('App\User','created_by');
       }

       public function modifier()
       {
         return $this->belongsTo('App\User','updated_by');
       }
   	
}

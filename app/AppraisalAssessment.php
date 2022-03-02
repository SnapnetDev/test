<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppraisalAssessment extends Model
{
    protected $fillable=['appraisal_id','employee_score','manager_score','appraisal_sub_metric_id','target_acheived','manager_id','manager_comment','employee_comment','created_by','updated_by','company_id'];

    public function appraisal()
    {
    	return $this->belongsTo('App\Appraisal');
    }
    public function manager($value='')
    {
    	return $this->belongsTo('App\User','manager_id');
    }
    public function appraisal_sub_metric($value='')
    {
    	return $this->belongsTo('App\AppraisalSubMetric','appraisal_sub_metric_id');
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

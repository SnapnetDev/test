<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppraisalMetric extends Model
{
    protected $table="appraisal_metrics";
   	protected $fillable=['name','description','fillable','manager_appraises','employee_appraises','created_by','updated_by','company_id','status'];

   	public function appraisal_sub_metrics()
   	{
   		return $this->hasMany('App\AppraisalSubMetric','appraisal_metric_id');
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

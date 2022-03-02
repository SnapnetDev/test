<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Appraisal extends Model
{
    protected $table="appraisals";
   	protected $fillable=['measurement_period','employee_id','manager_id','manager_approved','employee_approved','manager_approved_date','employee_approved_date','created_by','updated_by','company_id'];

   	public function appraisal_assessments()
   	{
   		return $this->hasMany('App\AppraisalAssessment','appraisal_id');
   	}
   	public function employee()
   	{
   		return $this->belongsTo('App\User','employee_id');
   	}
   	public function manager()
   	{
   		return $this->belongsTo('App\User','manager_id');
   	}
   	public function measurement_period()
    {
        return $this->belongsTo('App\AppraisalMeasurementPeriod', 'measurement_period_id');
    }
    public function appraisal_comments()
    {
      return $this->hasMany('App\AppraisalComment','appraisal_id');
    }
}

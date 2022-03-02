<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppraisalComment extends Model
{
    protected $table="appraisal_comments";
   	protected $fillable=['name','description','status','manager_employee_use','created_by','updated_by','company_id'];

   	public function appraisal_assessment_comments()
   	{
   		return $this->hasMany('App\AppraisalAssessmentComment','appraisal_comment_id');
   	}
   	public function employee()
   	{
   		return $this->belongsTo('App\User','employee_id');
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

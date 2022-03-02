<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppraisalAssessmentComment extends Model
{
    protected $fillable=['appraisal_id','appraisal_comment_id','comment','created_by','updated_by','company_id'];

    public function appraisal()
    {
    	return $this->belongsTo('App\Appraisal');
    }
    public function appraisal_comment()
    {
    	return $this->belongsTo('App\AppraisalComment');
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

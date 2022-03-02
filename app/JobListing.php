<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobListing extends Model
{
    protected $table="joblistings";
    protected $fillable=['job_id','salary_from','salary_to','expires','status','level','employee_class_id','experience_from','experience_to','requirements','type'];

    public function job()
    {
    	return $this->belongsTo('App\Job','job_id');
    }
    public function jobapplications()
    {
    	return $this->hasMany('App\JobApplication');
    }
     public function jobfavorites()
    {
        return $this->hasMany('App\JobFavorite');
    }
}

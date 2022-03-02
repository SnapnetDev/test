<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
	protected $table="jobroles";
    protected $fillable=['title','department_id','parent_id','description','personnel','qualification_id'];
    //
    public function users()
    {
        return $this->belongsToMany('App\User','employee_job','job_id','user_id')->withPivot('started', 'ended')->withTimestamps();
    }
    public function department()
    {
        return $this->belongsTo('App\Department');
    }
    public function qualification()
    {
        return $this->belongsTo('App\Qualification');
    }

    public function skills()
    {
        return $this->belongsToMany('App\Skill')->using('App\JobSkillCompetency')->withTimestamps()->withPivot('competency_id');
    }

    public function parent()
    {
       return $this->belongsTo('App\Job','parent_id');
    }

    public function children()
    {
       return $this->hasMany('App\Job','parent_id');
    }
    public function listings()
    {
       return $this->hasMany('App\JobListing','job_id');
    }
}

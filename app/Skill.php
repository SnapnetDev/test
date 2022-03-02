<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Skill extends Model
{
	protected $table="skills";
	protected $fillable=['name'];

    public function users()
    {
        return $this->belongsToMany('App\User')->using('App\UserSkillCompetency')->withTimestamps()->withPivot('competency_id');
    }
    public function jobs()
    {
        return $this->belongsToMany('App\Job')->using('App\JobSkillCompetency')->withTimestamps()->withPivot('competency_id');
    }
    
}

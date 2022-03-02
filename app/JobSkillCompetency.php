<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class JobSkillCompetency extends Pivot
{
    protected $table='job_skill';

    public function competency()
    {
    	return $this->belongsTo('App\Competency','competency_id');
    }

    public function skill()
    {
    	return $this->belongsTo('App\Skill','skill_id');
    }
    public function job()
    {
    	return $this->belongsTo('App\User','job_id');
    }
}

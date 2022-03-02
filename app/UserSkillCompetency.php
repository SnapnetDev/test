<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserSkillCompetency extends Pivot
{
    protected $table='skill_user';

    public function competency()
    {
    	return $this->belongsTo('App\Competency','competency_id');
    }

    public function skill()
    {
    	return $this->belongsTo('App\Skill','skill_id');
    }
    public function user()
    {
    	return $this->belongsTo('App\User','user_id');
    }
}

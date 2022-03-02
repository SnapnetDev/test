<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Competency extends Model
{
   protected $table="job_skill_comps";
   protected $fillable=['proficiency'];
}
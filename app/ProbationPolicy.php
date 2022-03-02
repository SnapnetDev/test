<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProbationPolicy extends Model
{
    //
    protected $fillable=['probation_period','probation_reminder','automatic_probation','created_by','notify_roles','company_id'];
}

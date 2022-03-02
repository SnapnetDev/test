<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SeparationPolicy extends Model
{
    protected $fillable=['employee_fills_form','use_approval_process','prorate_salary','notify_on_staff_exit','workflow_id','company_id'];

}

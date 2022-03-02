<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class RegistrationProgress extends Model
{
    protected $fillable=['has_users','has_grades','has_leave_policy','has_payroll_policy','has_departments','has_branches','has_job_roles','company_id','completed'];
    //
}

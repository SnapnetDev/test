<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalaryReviewInjectionComponent extends Model
{
    protected $fillable=['from_component_type','injection_type','company_id','status','from_salary_component_constant','from_payroll_component_constant','to_component_type','to_salary_component_constant','to_payroll_component_constant','percentage'];




}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class ChartOfAccount extends Model
{
	protected $fillable=['name','code','description','type','display','position','salary_component_constant','specific_salary_component_type_id','nationality_display','other_constant','uses_group','group_id','source','formula','status','salary_charge','non_payroll_provision','company_id'];
    public function ssc_type()
    {
    	 return $this->belongsTo('App\SpecificSalaryComponentType','specific_salary_component_type_id');
    }

    public function account_extra_components()
    {
        return $this->hasMany('App\ChartOfAccountComponent','chart_of_account_id');
    }
    // public function salary_component()
    // {
    // 	 return $this->belongs('App\SalaryComponent','constant','salary_component_constant');
    // }

    public function group()
    {
         return $this->belongsTo('App\UserGroup','group_id');
    }

}

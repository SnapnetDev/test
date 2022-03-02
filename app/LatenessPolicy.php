<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\CompanyScope;

class LatenessPolicy extends Model
{
   protected $table="lateness_policies";
   protected $fillable=['late_minute','deduction_type','deduction','status','policy_name','company_id','specific_salary_component_type_id','payroll'];

  public function grades()
  {
  	return $this->hasMany('App\Grade','grade_id');
  }

  public function specific_salary_component_type(){
      return $this->belongsTo('App\SpecificSalaryComponentType');
  }
   protected static function boot()
    {
        parent::boot();
      
        static::addGlobalScope(new CompanyScope);
    }
}

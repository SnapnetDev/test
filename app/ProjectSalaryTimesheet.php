<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectSalaryTimesheet extends Model
{
   protected $fillable=['pace_salary_component_id','user_id','days','for'];
   protected $table='project_salary_timesheets';

   public function project_salary_component()
   {
   	return $this->belongsTo('App\PaceSalaryComponent','pace_salary_component_id');
   }
   public function user()
   {
   	return $this->belongsTo('App\User','user_id');
   }

}

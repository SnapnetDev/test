<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaceSpecificSalaryComponent extends Model
{
    protected $table = 'pace_specific_salary_components';
    protected $fillable = ['id','name','amount','type','gl_code','project_code','comment','duration','grants','status','taxable','company_id'];

    public function paceSalaryCategory()
    {
    	return $this->belongsTo('App\PaceSalaryCategory','pace_salary_category_id');
    }
}

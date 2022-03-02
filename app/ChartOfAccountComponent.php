<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChartOfAccountComponent extends Model
{
    protected $fillable = ['salary_component_constant','specific_salary_component_type_id','payroll_constant','chart_of_account_id','source','operator','amount','percentage'];
    public function account()
    {
        return $this->belongsTo('App\ChartOfAccount','chart_of_account_id');
    }


}

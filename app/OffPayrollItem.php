<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OffPayrollItem extends Model
{
    protected $fillable=['off_payroll_type_id','name','source','salary_component_constant','payroll_constant','amount','is_prorated','proration_type','percentage'];

    public function item_components(){
        return $this->hasMany('App\OffPayrollItemComponent','off_payroll_item_id');
    }
    
    public function type()
    {
        return $this->belongsTo('App\OffPayrollType','off_payroll_type_id');
    }
}


<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OffPayrollItemComponent extends Model
{
    protected $fillable=['off_payroll_item_id','name','source','salary_component_constant','payroll_constant','amount','percentage'];

    public function item(){
        return $this->belongsTo('App\OffPayrollItem','off_payroll_item_id');
    }
}

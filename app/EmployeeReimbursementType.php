<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeReimbursementType extends Model
{
    protected $fillable=['name','created_by','workflow_id','company_id'];
    public function employee_reimbursements()
    {
        return $this->hasMany('App\EmployeeReimbursement','employee_reimbursement_type_id');
    }
    public function workflow()
    {
        return $this->belongsTo('App\Workflow','workflow_id');
    }
    public function user()
    {
        return $this->belongsTo('App\User','created_by');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\CompanyScope;

class PayrollPolicy extends Model
{
    protected $table='payroll_policies';
    protected $fillable=['payroll_runs','basic_pay_percentage','user_id','workflow_id','company_id','use_lateness','use_office','use_tmsa','use_project','show_all_gross','display_lsa_on_nav_export','display_lsa_on_payroll_export','tax_preference','uses_approval'];

    public function editor()
    {
    	return $this->belongsTo('App\User','user_id');
    }
    public function workflow()
    {
    	return $this->belongsTo('App\Workflow','workflow_id');
    }
     protected static function boot()
    {
        parent::boot();
      
        static::addGlobalScope(new CompanyScope);
    }
}

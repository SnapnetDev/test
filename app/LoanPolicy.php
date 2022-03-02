<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\CompanyScope;

class LoanPolicy extends Model
{
    protected $table='loan_policies';
    protected $fillable=['annual_interest','maximum_allowed','user_id','workflow_id','company_id'];

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

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\CompanyScope;

class LeavePolicy extends Model
{
    protected $table='leave_policies';
     protected $fillable=['includes_weekend','includes_holiday','user_id','workflow_id','company_id','default_length','uses_spillover','uses_maximum_spillover','spillover_length','spillover_month','spillover_day','relieve_approves','probationer_applies','uses_casual_leave','casual_leave_length','adjustment_workflow_id','can_request_allowance'];

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

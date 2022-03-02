<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\CompanyScope;

class TmsaPolicy extends Model
{
    protected $table='tmsa_policies';
    protected $fillable=['company_id','onshore_day_rate','brt_percentage','workflow_id','out_of_station'];

    public function company()
    {
    	return $this->belongsTo('App\Company','company_id');
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

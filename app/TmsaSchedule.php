<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\CompanyScope;

class TmsaSchedule extends Model
{
    protected $table='tmsa_schedules';
    protected $fillable=['for','user_id','day_rate_onshore','day_rate_offshore','paid_off_time_rate','days_worked_offshore','days_worked_onshore','paid_off_day','travel_day','living_allowance','transport_allowance','extra_addition','brt_days','extra_deduction','days_out_of_station','company_id','created_at','updated_at'];

    public function user()
    {
    	return $this->belongsTo('App\User','user_id');
    }
     protected static function boot()
    {
        parent::boot();
      
        static::addGlobalScope(new CompanyScope);
    }
}

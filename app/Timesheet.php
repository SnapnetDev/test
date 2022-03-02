<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\CompanyScope;

class Timesheet extends Model
{
    protected $table="timesheets";
    protected $fillable=['month','year','company_id'];

    public function timesheetdetails()
    {
        return $this->hasMany('App\TimesheetDetail','timesheet_id');
    }
    protected static function boot()
    {
        parent::boot();
      
        static::addGlobalScope(new CompanyScope);
    }
}

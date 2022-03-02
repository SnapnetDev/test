<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;

class PaceSalaryCategory extends Model
{
    use LogsActivity;
    use CausesActivity;


    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    //    protected static $recordEvents = ['updated'];
    protected static $logName = 'paceSalary';
    protected $table = 'pace_salary_categories';
    protected $fillable = ['id', 'name', 'unionized', 'uses_timesheet', 'basic_salary', 'relief', 'company_id', 'uses_tax',
                           'uses_daily_net', 'tax_rate', 'uses_direct_tax'];

    public function paceSalaryComponents()
    {
        return $this->hasMany('App\PaceSalaryComponent', 'pace_salary_category_id');
    }

    public function company()
    {
        return $this->belongsTo('App\Company', 'company_id');
    }

    public function users()
    {
        return $this->hasMany('App\User', 'project_salary_category_id');
    }


}
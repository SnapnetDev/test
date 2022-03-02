<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\CompanyScope;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;


class SpecificSalaryComponentType extends Model
{
    use LogsActivity;
    use CausesActivity;

    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    protected static $logName = 'spsc';
    protected $table = 'specific_salary_component_types';
    protected $fillable = ['name', 'display', 'company_id'];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CompanyScope);
    }

    public function specific_salary_components()
    {
        return $this->hasMany('App\SpecificSalaryComponent', 'specific_salary_component_type_id');
    }
}
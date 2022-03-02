<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\CompanyScope;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;


class SpecificSalaryComponent extends Model
{
    use LogsActivity;
    use CausesActivity;

    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    protected static $logName = 'spsc';
    protected $table = 'specific_salary_components';
    protected $fillable = ['name', 'gl_code', 'project_code', 'type', 'comment', 'emp_id', 'duration', 'grants', 'status', 'starts', 'ends', 'company_id', 'amount', 'taxable', 'taxable_type', 'specific_salary_component_type_id', 'completed', 'one_off', 'loan_id', 'leave_allowance_payment_id'];


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new CompanyScope);
    }
    public function user()
    {
        return $this->belongsTo('App\User', 'emp_id');
    }

    public function ssc_type()
    {
        return $this->belongsTo('App\SpecificSalaryComponentType', 'specific_salary_component_type_id');
    }

    public function payrolls()
    {
        return $this->belongsToMany('App\Payroll', 'payroll_specific_salary_component', 'specific_salary_component_id', 'payroll_id');
    }
}

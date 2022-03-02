<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;

class BscEvaluation extends Model
{
  
         protected $fillable=['bsc_measurement_period_id','performance_metric_description','user_id',
        'department_id','performance_category_id','scorecard_score','scorecard_self_score','scorecard_percentage',
             'behavioral_score','behavioral_self_score','behavioral_percentage','penalty_score','weight_sum',
        'manager_id','employee_strength','employee_developmental_area','special_achievement',
        'manager_approval_comment','manager_approval_date','manager_approval_approved','appraisal_accepted_comment','manager_of_manager_id','manager_of_manager_approved','manager_of_manager_approval_comment',
        'manager_of_manager_approval_date','kpi_submitted','kpi_submitted_date','kpi_accepted','kpi_accepted_date','appraisal_accepted',
             'appraisal_accepted_date',
        'company_id','head_of_strategy_id','head_of_strategy_approved','head_of_hr_id','head_of_hr_approved',
        'head_of_strategy_approved_date','head_of_hr_approved_date','approval_status'];
    use LogsActivity;
    use CausesActivity;


    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    protected static $recordEvents = ['created', 'updated'];
    protected static $logName = 'bscEvaluation';
    protected $table = "bsc_evaluations";
    //   protected $fillable=['kpi_accepted','date_kpi_accepted','user_id','bsc_measurement_period_id','department_id','performance_category_id','comment','score','manager_approved','evaluator_id','manager_approved','employee_approved','date_employee_approved','date_manager_approved','behavioral_score','company_id'];
    

    public function measurement_period()
    {
        return $this->belongsTo('App\BscMeasurementPeriod', 'bsc_measurement_period_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function manager()
    {
        return $this->belongsTo('App\User', 'manager_id');
    }

    public function department()
    {
        return $this->belongsTo('App\Department', 'department_id');
    }

    public function performance_category()
    {
        return $this->belongsTo('App\BscGradePerformanceCategory', 'performance_category_id');
    }

    public function evaluation_details()
    {
        return $this->hasMany('App\BscEvaluationDetail', 'bsc_evaluation_id');
    }

    public function behavioral_evaluation_details()
    {
        return $this->hasMany('App\BehavioralEvaluationDetail', 'bsc_evaluation_id');
    }

    public function getStatusTextAttribute()
    {
        if ($this->kpi_accepted == 1) {
            return "Accepted @ {$this->date_kpi_accepted}";
        }
        elseif ($this->kpi_accepted == 2) {
            return "Rejected @ {$this->date_kpi_accepted}";
        }
        else {
            return "Pending";
        }

    }

    public function getStatusColorAttribute()
    {
        if ($this->kpi_accepted == 1) {
            return "success";
        }
        elseif ($this->kpi_accepted == 2) {
            return "danger";
        }
        else {
            return "warning";
        }
    }
}
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\CompanyScope;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;


class LeaveRequest extends Model
{
    use LogsActivity;
    use CausesActivity;


    use SoftDeletes;

    protected static $logAttributes = ['start_date', 'end_date', 'status', 'paystatus', 'reason', 'dates.date',
                                       'relieve_approved', 'relieve_comment', 'relieve_approved_at', 'updated_at','is_spillover'];
    protected static $logOnlyDirty = true;
    // protected static function boot()
    // {
    //     parent::boot();

    //     static::addGlobalScope(new CompanyScope);
    // }
    protected static $logName = 'leaveRequest';
    protected $dates = ['deleted_at'];
    protected $fillable = ['leave_id', 'start_date', 'end_date', 'paystatus', 'length', 'reason', 'absence_doc', 'user_id', 'workflow_id',
                           'status', 'company_id', 'replacement_id', 'balance', 'requested_allowance','is_spillover',
                           'relieve_approved', 'relieve_comment', 'relieve_approved_at'];

    //    custom description
    /*public function getDescriptionForEvent(string $eventName): string
    {
        return " Leave Request has been {$eventName}";
    }*/

    public function leave()
    {
        return $this->belongsTo('App\Leave', 'leave_id')->withDefault();
    }

    public function leave_approvals()
    {
        return $this->hasMany('App\LeaveApproval', 'leave_request_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id')->withoutGlobalScopes();
    }

    public function workflow()
    {
        return $this->belongsTo('App\Workflow', 'workflow_id');
    }

    public function replacement()
    {
        return $this->belongsTo('App\User', 'replacement_id')->withoutGlobalScopes();
    }

    public function getLeaveNameAttribute()
    {
        if ($this->leave_id == 0) {
            return "Annual Leave";
        }
        else {
            return $this->leave->name;
        }
    }

    public function dates()
    {
        return $this->hasMany("App\LeaveRequestDate", 'leave_request_id');
    }

    public function recalls()
    {
        return $this->hasMany("App\LeaveRequestRecall", 'leave_request_id');
    }

    public function adjustments()
    {
        return $this->hasMany("App\LeaveRequestAdjustment", 'leave_request_id');
    }

}
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;

class LeaveApproval extends Model
{
    use LogsActivity;
    use CausesActivity;


    protected static $logAttributes = ['status', 'created_at'];
    protected static $logOnlyDirty = true;
    protected static $logName = 'leaveRequest';

    protected $fillable = ['leave_request_id', 'stage_id', 'approver_id', 'comments', 'status'];

    public function leave_request()
    {
        return $this->belongsTo('App\LeaveRequest', 'leave_request_id');
    }

    public function approver()
    {
        return $this->belongsTo('App\User', 'approver_id');
    }

    public function stage()
    {
        return $this->belongsTo('App\Stage', 'stage_id');
    }


}
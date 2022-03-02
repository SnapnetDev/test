<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;

class LeaveRequestAdjustment extends Model
{
    use LogsActivity;
    use CausesActivity;


    protected static $logAttributes = ['adjustment_reason', 'date', 'updated_at'];
    protected static $logOnlyDirty = true;
    protected static $logName = 'leaveRequestAdjustment';

    protected $fillable = ['leave_request_id', 'adjuster_id', 'adjustment_reason', 'date'];

    public function leave_request()
    {
        return $this->belongsTo('App\LeaveRequest', 'leave_request_id');
    }

    public function adjuster()
    {
        return $this->belongsTo('App\User', 'adjuster_id');
    }
}
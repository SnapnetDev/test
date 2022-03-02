<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;

class LeaveRequestRecall extends Model
{
    use LogsActivity;
    use CausesActivity;


    protected static $logAttributes = ['old_date', 'new_date', 'created_at'];
    protected static $logOnlyDirty = true;
    protected static $logName = 'leaveRequestRecall';

    protected $fillable = ['leave_request_id', 'recaller_id', 'recall_reason', 'old_date', 'new_date'];

    public function leave_request()
    {
        return $this->belongsTo('App\LeaveRequest', 'leave_request_id');
    }

    public function recaller()
    {
        return $this->belongsTo('App\User', 'recaller_id');
    }
}
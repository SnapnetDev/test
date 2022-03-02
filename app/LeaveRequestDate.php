<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveRequestDate extends Model
{
    use SoftDeletes;

    use LogsActivity;
    use CausesActivity;


    protected static $logAttributes = ['date', 'created_at'];
    protected static $logOnlyDirty = true;
    protected static $logName = 'leaveRequestDates';

    protected $dates = ['deleted_at'];
    protected $fillable = ['leave_request_id', 'date'];

    public function leave_request()
    {
        return $this->belongsTo("App\LeaveRequest", 'leave_request_id');
    }
}
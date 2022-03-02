<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class EmploymentHistory extends Model
{
    use LogsActivity;
    protected $table="emp_histories";
    protected $fillable=['user_id','organization','position','start_date','end_date','last_change_approved','last_change_approved_by','last_change_approved_on','company_id'];
    protected static $logUnguarded = true;
    protected static $logOnlyDirty = true;

    protected static $logAttributes =['user_id','organization','position','start_date','end_date','company_id'];
    protected static $ignoreChangedAttributes=['created_at','updated_at','last_change_approved','last_change_approved_by','last_change_approved_on'];
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function approver()
    {
        return $this->belongsTo('App\User','approved_by');
    }
}

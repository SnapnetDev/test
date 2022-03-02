<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class EducationHistory extends Model
{
    use LogsActivity;
    protected $table='emp_academics';
    protected $fillable=['title','qualification_id','year','institution','grade','course','emp_id','company_id','last_change_approved','last_change_approved_by','last_change_approved_on'];

    protected static $logUnguarded = true;
    protected static $logOnlyDirty = true;

    protected static $logAttributes =['title','qualification.name','year','institution','grade','course','emp_id','company_id'];
    protected static $ignoreChangedAttributes=['created_at','updated_at','last_change_approved','last_change_approved_by','last_change_approved_on'];

    public function user()
    {
        return $this->belongsTo('App\User','emp_id');
    }
    public function qualification()
    {
        return $this->belongsTo('App\Qualification','qualification_id');
    }

    public function approver()
    {
        return $this->belongsTo('App\User','approved_by');
    }

}

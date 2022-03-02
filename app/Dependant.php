<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Dependant extends Model
{
    use LogsActivity;
    protected $table='emp_dependants';
    protected $fillable=['name','dob','email','phone','relationship','user_id'];

    protected static $logUnguarded = true;
    protected static $logOnlyDirty = true;

    protected static $logAttributes =['name','dob','email','phone','relationship','user_id','company_id'];
    protected static $ignoreChangedAttributes=['created_at','updated_at','last_change_approved','last_change_approved_by','last_change_approved_on'];

    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }
    public function getDobAttribute($value)
    {
        return  date('m/d/Y',strtotime($value));
    }
}

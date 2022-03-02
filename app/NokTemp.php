<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class NokTemp extends Model
{
    use LogsActivity;

    protected $table="nok_temps";
    protected static $logUnguarded = true;
    protected static $logOnlyDirty = true;
    protected $fillable=['name','relationship','phone','address','user_id','last_change_approved_on','last_change_approved','last_change_approved_by'];
    protected static $logAttributes =['name','relationship','phone','address','user_id'];
        protected static $ignoreChangedAttributes=['created_at','updated_at','last_change_approved_on','last_change_approved','last_change_approved_by'];



    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }
}

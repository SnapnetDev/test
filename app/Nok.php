<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Nok extends Model
{
    use LogsActivity;
    protected $table="noks";
    protected $fillable=['name','relationship','phone','address','user_id'];

    protected static $logUnguarded = true;
    protected static $logOnlyDirty = true;

    protected static $logAttributes =['name','relationship','phone','address','user_id'];
    protected static $ignoreChangedAttributes=['created_at','updated_at'];
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}

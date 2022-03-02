<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $fillable = ['title','message', 'date','created_by','company_id','adjustment_approved','adjustment_workflow_id'];
    public function user()
    {
        return $this->belongsTo('App\User','created_by');
    }
    public function getDateAttribute($value)
    {
    	return date('m/d/Y',strtotime($value));
    }

    public function holidaymessage(){
            return $this->hasOne('App\HolidayMessage','holiday_id')->where('company_id',companyId())->withDefault();

    }
    public function holidaymessagesconsole(){
        return $this->hasMany('App\HolidayMessage','holiday_id');

    }

}

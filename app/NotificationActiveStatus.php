<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificationActiveStatus extends Model
{
    //
    protected $fillable=['notification_type_id','company_id','status','a_id','reminder_before'];

    public function notificationtype(){
        return $this->belongsTo('App\NotificationType','notification_type_id')->withDefault();
    }
}

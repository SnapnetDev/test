<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificationMessage extends Model
{
    //
    protected  $fillable=['message','notification_type_id','a_id','company_id'];


    public function notificationtype(){
        return $this->belongsTo('App\NotificationType','notification_type_id')->withDefault();
    }
}

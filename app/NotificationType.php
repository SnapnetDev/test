<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificationType extends Model
{
    //
    protected $fillable=['name'];


    public function notificationstatus(){
        return $this->hasOne('App\NotificationActiveStatus','notification_type_id')->where('company_id',companyId())->withDefault();
    }

    public function notificationmessage(){
        return $this->hasOne('App\NotificationMessage','notification_type_id')->where('company_id',companyId())->withDefault();

    }
}

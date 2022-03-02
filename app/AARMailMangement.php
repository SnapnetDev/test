<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AARMailMangement extends Model
{
    //
    protected $table = 'mail_management';
    protected $fillable = ['id', 'userId', 'sender', 'receiver', 'subject', 'email', 'phone', 'direction', 'status', 'comments'];

    public function resolveName(){
    	return $this->belongsTo(User::class, 'receiver', 'id');
    }
   
}



<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    protected $fillable=['user_id','name','description','end_date','status','type','results','roles','groups','departments'];

    public function user(){
        return $this->belongsTo('App\User');
    }
    public function questions(){
        return $this->hasMany('App\PollQuestion');
    }
    public function responses(){
        return $this->hasMany('App\PollResponse');
    }
    protected $casts=[
        'results'=>'array',
        'roles'=>'array',
        'groups'=>'array',
        'departments'=>'array',
    ];
}

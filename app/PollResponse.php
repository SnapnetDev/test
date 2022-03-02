<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PollResponse extends Model
{
    protected $fillable=['user_id','poll_id','responses'];

    public function user(){
        return $this->belongsTo('App\User');
    }
    public function poll(){
        return $this->belongsTo('App\Poll');
    }

    protected $casts=[
        'responses'=>'array'
    ];
}

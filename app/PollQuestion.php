<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PollQuestion extends Model
{
    protected $fillable=['poll_id','question','options'];

    public function poll(){
        return $this->belongsTo('App\Poll');
    }
    protected $casts=[
        'options'=>'array'
    ];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Biometric extends Model
{
    protected $fillable=['data','url','headers'];

    protected $casts=[
        'data'=>'array',
        'headers'=>'array',
    ];
}

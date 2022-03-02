<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    //
    protected $fillable=['user_id','title','cert_type','year_received'];
}


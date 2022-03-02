<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BiometricDevice extends Model
{
    protected $fillable=['company_id','name','biometric_serial'];
}

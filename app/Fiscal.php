<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fiscal extends Model
{
    protected $fillable=['start_month','end_month','grace'];
}

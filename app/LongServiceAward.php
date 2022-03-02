<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LongServiceAward extends Model
{
    protected $fillable=['max_year','amount','difference','company_id'];

}

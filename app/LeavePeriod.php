<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeavePeriod extends Model
{
    protected $fillable = ['grade_id', 'length'];
    public function grade()
    {
        return $this->belongsTo('App\Grade','grade_id');
    }
}

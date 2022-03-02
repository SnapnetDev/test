<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaveBank extends Model
{
    protected $fillable=['user_id','year','entitled','used','last_modified_by','company_id'];

    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }

    public function modifier()
    {
        return $this->belongsTo('App\User','last_modified_by');
    }
}

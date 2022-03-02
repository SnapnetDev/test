<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeavePlan extends Model
{
    protected $fillable=['user_id','start_date','end_date','length','company_id'];

    public function user()
    {
    	return $this->belongsTo('App\User','user_id');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectTimesheetDetail extends Model
{
    protected $fillable = ['lin_code', 'project_id','date','hours','user_id','company_id'];
    public function project()
    {
        return  $this->belongsTo('App\Project','project_id');
    }
    public function user()
    {
        return  $this->belongsTo('App\User','user_id');
    }
}

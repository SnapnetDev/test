<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectTask extends Model
{
    protected $fillable=['name','project_id','froms','tos','status'];

    public function project()
    {
    	return $this->belongsTo('App\Project');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobListingExternal extends Model
{
    protected $table="joblistings_external";
    protected $fillable=['job_ref','title','level','state_id','country_id','salary_from','salary_to','experience_from','experience_to','description','experience','skills','expires','user_id','status'];

    public function state()
    {
    	$this->belongsTo('App\State');
    }
    public function country()
    {
    	$this->belongsTo('App\Country');
    }
    public function lister()
    {
        $this->belongsTo('App\User','user_id');
    }
}

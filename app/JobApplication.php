<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    protected $table='job_applications';
    protected $fillable=['applicable_id','applicable_type','job_listing_id','resume','cover_letter'];
    

    public function applicable()
    {
        return $this->morphTo();
    }
    public function joblisting()
    {
    	return $this->belongsTo('App\JobListing');
    }

}

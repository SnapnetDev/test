<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobFavorite extends Model
{
    protected $table='job_favorites';
    protected $fillable=['favorable_id','favorable_type','job_listing_id'];
    

    public function favourable()
    {
        return $this->morphTo();
    }
    public function joblisting()
    {
    	return $this->belongsTo('App\JobListing');
    }

}

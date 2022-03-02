<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyOrganogramPosition extends Model
{
   protected $fillable=['company_organogram_id','company_organogram_level_id','user_id','name','p_id','sp_id','updated_by','company_id'];
    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }
    public function organogram()
    {
        return $this->belongsTo('App\CompanyOrganogram','company_organogram_id');
    }
    public function level()
    {
        return $this->belongsTo('App\CompanyOrganogramLevel','company_organogram_level_id');
    }

    public function children()
    {
        return $this->hasMany('App\CompanyOrganogramPosition','p_id');
   }
    public function second_children()
    {
        return $this->hasMany('App\CompanyOrganogramPosition','sp_id');
    }
    public function parent()
    {
        return $this->belongsTo('App\CompanyOrganogramPosition','p_id');
    }

    public function second_parent()
    {
        return $this->belongsTo('App\CompanyOrganogramPosition','sp_id');
    }
    public function updater()
    {
        return $this->belongsTo('App\User','updated_by');
    }

}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyOrganogramLevel extends Model
{
    protected $fillable = ['name','company_id','updated_by'];

    public function company()
    {
        return $this->belongsTo('App\Company','company_id');
    }
    public function updater()
    {
        return $this->belongsTo('App\User','updated_by');
    }
}

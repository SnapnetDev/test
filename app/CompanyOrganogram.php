<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyOrganogram extends Model
{
    protected $fillable=['company_id','manager_id','name','updated_by'];

    public function manager()
    {
        return $this->belongsTo('App\User','manager_id');
    }
    public function updater()
    {
        return $this->belongsTo('App\User','updated_by');
    }
}

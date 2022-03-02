<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyDocument extends Model
{
    protected $fillable = ['title','description','file','created_by','company_id'];
    public function user()
    {
        return $this->belongsTo('App\User','created_by');
    }

}

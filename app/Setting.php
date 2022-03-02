<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\CompanyScope;

class Setting extends Model
{
    protected $fillable=['name','value','company_id'];
    //  protected static function boot()
    // {
    //     parent::boot();
      
    //     static::addGlobalScope(new CompanyScope);
    // }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\CompanyScope;
 
class PerformanceSeason extends Model
{
    //
    protected $fillable=['reviewFreq','reminderMessage','reviewStart','company_id'];

  protected static function boot()
    {
        parent::boot();
      
        static::addGlobalScope(new CompanyScope);
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\CompanyScope;

class PayslipDetail extends Model
{
    protected $table='payslip_details';
    protected $fillable=['watermark_text','logo','company_id'];
     protected static function boot()
    {
        parent::boot();
      
        static::addGlobalScope(new CompanyScope);
    }
}

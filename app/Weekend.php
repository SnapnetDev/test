<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyWeekend extends Model
{
	 protected $fillable = ['company_id', 'monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
    public function company()
    {
        return $this->belongsTo('App\Company');
    }
}

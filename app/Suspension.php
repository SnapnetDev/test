<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\CompanyScope;

class Suspension extends Model
{
    protected $table='suspensions';
    protected $fillable=['user_id','suspension_type_id','start_date','end_date','length','comment','company_id','created_by'];

    public function suspension_type()
    {
    	return $this->belongsTo('App\SuspensionType');
    }
    public function user()
    {
    	return $this->belongsTo('App\User');
    }
    public function approver()
    {
        return $this->belongsTo('App\User','created_by');
    }
    public function suspension_deductions()
    {
       return $this->hasMany('App\SuspensionDeduction');
    }
    protected static function boot()
    {
        parent::boot();
      
        static::addGlobalScope(new CompanyScope);
    }


}

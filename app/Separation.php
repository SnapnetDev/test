<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\CompanyScope;

class Separation extends Model
{
    protected $table='separations';
    protected $fillable=['user_id','separation_type_id','date_of_separation','days_of_employment','exit_interview_form','exit_checkout_form','hiredate','comment','company_id','status','workflow_id','initiator_id'];

    public function separation_type()
    {
    	return $this->belongsTo('App\SeparationType');
    }
    public function separation_form()
    {
        return $this->hasOne('App\SeparationForm');
    }
    public function user()
    {
    	return $this->belongsTo('App\User');
    }
    public function initiator()
    {
        return $this->belongsTo('App\User','initiator_id');
    }
    public function separation_approvals()
    {
        return $this->hasMany('App\SeparationApproval');
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new CompanyScope);
    }


}

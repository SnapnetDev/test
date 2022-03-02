<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\CompanyScope;

class LoanApproval extends Model
{
    protected $table='loan_approvals';
    protected $fillable=['loan_request_id','stage_id','approver_id','status','comments','company_id'];
    protected $with = ['stage','approver'];

    public function approver()
    {
    	return $this->belongsTo('App\User','approver_id');
    }
    public function stage()
    {
    	return $this->belongsTo('App\Stage','stage_id');
    }
    public function loan_request()
    {
    	return $this->belongsTo('App\LoanRequest','loan_request_id');
    }
     protected static function boot()
    {
        parent::boot();
      
//        static::addGlobalScope(new CompanyScope);
    }
}

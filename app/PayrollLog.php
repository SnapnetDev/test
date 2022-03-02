<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PayrollLog extends Model
{
    
    protected $fillable=['payroll_id','for','details','user_id','created_by','created_at','updated_by','payroll_type','status','issue'];
    protected $table='payroll_logs';
    protected $casts = [
        'details' => 'array',
    ];
    public function payroll()
    {
        return $this->belongsTo('App\Payroll');
    }
    public function author()
    {
    	return $this->belongsTo('App\User','created_by');
    }
    public function user()
    {
    	return $this->belongsTo('App\User');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SuspensionDeduction extends Model
{
    protected $table='suspension_deductions';
    protected $fillable=['date','days','suspension_id','deducted'];
    
    public function suspension()
    {
    	return $this->belongsTo('App\Suspension');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BscDetDetail extends Model
{
   protected $table="bsc_det_details";
   protected $fillable=['bsc_det_id','metric_id','business_goal','is_penalty','performance_metric_description','target','weight','company_id'];
   public function metric()
    {
        return $this->belongsTo('App\BscMetric', 'metric_id');
    }
    public function det()
    {
        return $this->belongsTo('App\BscDet', 'bsc_det_id');
    }
}

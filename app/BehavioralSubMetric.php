<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BehavioralSubMetric extends Model
{
    protected $table="behavioral_submetrics";

   protected $fillable=['objective','measure','weighting','status','company_id'];
   public function morphable()
    {
        return $this->morphTo();
    }
    public function metric()
    {
        return $this->belongsTo('App\BscMetric', 'bsc_metric_id');
    }
    public function behavioral_eveluation_details()
    {
        return $this->hasMany('App\BehavioralEvaluationDetail', 'measurement_period_id');
    }
}

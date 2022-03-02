<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;

class BscSubMetric extends Model
{
    use LogsActivity;
    use CausesActivity;


    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    protected static $logName = 'bsc';
    protected $table = "bsc_submetrics";
    protected $fillable = ['business_goal', 'measure', 'morphable_id', 'morphable_type', 'bsc_metric_id', 'bsc_measurement_period_id',
                           'low_target', 'mid_target', 'upper_target'];

    public function morphable()
    {
        return $this->morphTo();
    }

    public function metric()
    {
        return $this->belongsTo('App\BscMetric', 'bsc_metric_id');
    }

    public function measurement_period()
    {
        return $this->belongsTo('App\BscMeasurementPeriod', 'measurement_period_id');
    }
}
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;

class BscMeasurementPeriod extends Model
{

   protected $fillable=['from','to','status','head_of_hr_id','head_of_strategy_id','scorecard_percentage','behavioral_percentage'];
    use LogsActivity;
    use CausesActivity;


    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    protected static $logName = 'bsc';
    protected $table = "bsc_measurement_periods";


    public function evaluations()
    {
        return $this->hasMany('App\BscEvaluation', 'bsc_measurement_period_id');
    }
    public function head_of_strategy()
    {
        return $this->belongsTo('App\User', 'head_of_strategy_id');
    }
    public function head_of_hr()
    {
        return $this->belongsTo('App\User', 'head_of_hr_id');
    }

}

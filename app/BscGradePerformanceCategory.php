<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;

class BscGradePerformanceCategory extends Model
{
    use LogsActivity;
    use CausesActivity;


    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    protected static $logName = 'bsc';
    protected $fillable = ['name'];
    protected $table = "bsc_grade_performance_categories";

    public function grades()
    {
        return $this->hasMany('App\Grade', 'bsc_grade_performance_category_id');
    }
}
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BscWeight extends Model
{
    protected $table="bsc_weights";
   protected $fillable=['department_id','performance_category_id','metric_id','percentage'];

   public function department()
    {
        return $this->belongsTo('App\Department', 'department_id');
    }
    public function metric()
    {
        return $this->belongsTo('App\BscMetric', 'metric_id');
    }
    public function performance_category()
    {
        return $this->belongsTo('App\BscGradePerformanceCategory', 'performance_category_id');
    }

}

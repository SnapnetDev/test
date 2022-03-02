<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PerformanceDiscussionDetail extends Model
{
    protected $fillable = ['performance_discussion_id','evaluation_detail_id','action_update','challenges','comment'];

    public function performance_discussion(){
        return $this->belongsTo('App\PerformanceDiscussion','performance_discussion_id')->withDefault();
    }
    public function evaluation_detail(){
        return $this->belongsTo('App\BscEvaluationDetail','evaluation_detail_id')->withDefault();
    }
}

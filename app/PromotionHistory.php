<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PromotionHistory extends Model
{
    //
    protected $table='emp_promotion_histories';
    protected $fillable=['user_id','approved_by','approved_on','old_grade_id','grade_id'];
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function grade()
    {
        return $this->belongsTo('App\Grade','grade_id');
    }

    public function oldgrade()
    {
        return $this->belongsTo('App\Grade','old_grade_id');
    }
    public function approver()
    {
        return $this->belongsTo('App\User','approved_by');
    }

    public function getOnlygradeAttribute()
    {
        $gc = explode("-", $this->grade->level);
        return $gc[0];
        
    }
    public function getOnlyoldgradeAttribute()
    {
        $gc = explode("-", $this->oldgrade->level);
        return $gc[0];

    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Self_;

class Grade extends Model
{

    protected $fillable = ['level','basic_pay','leave_length','lateness_policy_id','company_id','grade_category_id','bsc_grade_performance_category_id','pos','description'];

    public function leaveperiod()
    {
        return $this->hasOne('App\LeavePeriod','grade_id');
    }

    public function lateness_policy()
    {
    	return $this->belongsTo('App\LatenessPolicy','lateness_policy_id');
    }

    public function grade_category()
    {
    	return $this->belongsTo('App\GradeCategory','grade_category_id');
    }

    public function users($value='')
    {
    	return $this->hasMany('App\User','grade_id');
    }

    public function performance_category()
    {
        return $this->belongsTo('App\BscGradePerformanceCategory','bsc_grade_performance_category_id');
    }

    static function fetch(){
        return (new self)->newQuery();
    }

    static function getByUser($userId){
        return self::fetch()->whereHas('users',function(Builder $builder) use ($userId){
           return $builder->where('id',$userId);
        });
    }

    static function userHasHigherGrade($userId1,$userId2){

        if (!self::getByUser($userId1)->exists() || !self::getByUser($userId2)->exists()){
            throw new \Exception("Neither of you nor your guarantor has a grade!");
        }

        $grade1 = self::getByUser($userId1)->first()->pos;
        $grade2 = self::getByUser($userId2)->first()->pos;

        return $grade1 > $grade2;

    }







}

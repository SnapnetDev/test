<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 22/06/2020
 * Time: 02:11
 */

namespace App\Traits;


use App\KpiData;
use App\User;
use Illuminate\Database\Eloquent\Builder;

trait KpiInspectTrait
{

    function userHasCompletedEvaluation($kpi_interval_id){
       $workdept_id = $this->job_id;
//       dd($workdept_id);
       $userId = $this->id;

       $query = (new KpiData)->newQuery();


        $countRequired = $query->where('dep_id',$workdept_id)
            ->where('kpi_interval_id',$kpi_interval_id)
            ->where('scope','public')
            ->count();



       //user_score
        $query = (new KpiData)->newQuery();

        $countPersonal = $query->where('dep_id',$workdept_id)
            ->where('kpi_interval_id',$kpi_interval_id)
            ->where('scope','private')
            ->whereHas('user_score',function(Builder $builder) use ($userId){
              return $builder->where('user_id',$userId);
            })->count();



        $query = (new KpiData)->newQuery();
        $countCompletedByUser = $query->where('dep_id',$workdept_id)
            ->where('kpi_interval_id',$kpi_interval_id)
            ->whereHas('user_score',function(Builder $builder) use ($userId){
                return $builder->where('user_id',$userId);
            })->count();

//        if ($userId == 155)
//        dd($countPersonal,$countRequired,$countCompletedByUser);

        return ($countRequired + $countPersonal == $countCompletedByUser && $countCompletedByUser > 0);

//        $query = $query->whereHas();

    }


    function getUsersWhoCompetedEvaluation($kpi_interval_id){
        $collection = User::where('locked',0)->get();
        $completed = [];
        $not_completed = [];
        foreach ($collection as $item){
            if ($item->userHasCompletedEvaluation($kpi_interval_id)){
                $completed[] = $item;
            }else{
                $not_completed[] = $item;
            }
        }

        $result = [
            'completed'=>$completed,
            'not_completed'=>$not_completed
        ];

//        dd($result);

        return $result;

    }



}
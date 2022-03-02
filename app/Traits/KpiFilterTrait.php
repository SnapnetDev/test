<?php
/**
 * Created by PhpStorm.
 * User: NnamdiAlexanderAkamu
 * Date: 5/17/2020
 * Time: 7:20 PM
 */

namespace App\Traits;




use Illuminate\Database\Eloquent\Builder;

trait KpiFilterTrait
{

	function filterByYear(Builder $builder,$filter){
		return $builder->where('year',$filter['year']);
	}

	function filterByKpiYearId(Builder $builder,$filter){
		return $builder->where('kpi_year_id',$filter['kpi_year_id']);
	}

	function filterByKpiIntervalId(Builder $builder,$filter){
		return $builder->where('kpi_interval_id',$filter['kpi_interval_id']);
	}

	function filterByType(Builder $builder,$filter){
		return $builder->where('type',$filter['type']);
	}

	function filterByScope(Builder $builder,$filter){
		return $builder->where('scope',$filter['scope']);
	}

	function filterByDepId(Builder $builder,$filter){
		return $builder->where('dep_id',$filter['dep_id']);
	}

	function filterByUserDepartmentId(Builder $builder,$filter){
		return $builder->where('job_id',$filter['workdept_id'])->where('locked',0);
	}



	function filterByUserIdFromKpiUserScoreRelation(Builder $builder,$filter){
        return $builder->whereHas('user_score', function (Builder $builder) use ($filter) {
//        	dd($filter['kpi_user_score_user_id']);
            return $builder->where('user_id',$filter['kpi_user_score_user_id']);
        });
	}

	function filterByUserId(Builder $builder,$filter){

		return $builder->where('user_id',$filter['user_id']);

	}

	function filterByKpiDataId(Builder $builder,$filter){
		return $builder->where('kpi_data_id',$filter['kpi_data_id']);
	}

	function filterByKpiInterval(Builder $builder,$filter){
		return $builder->where('interval_start','<=',$filter['interval_check'])
			->where('interval_stop','>=',$filter['interval_check']);
 	}


}
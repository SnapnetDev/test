<?php
/**
 * Created by PhpStorm.
 * User: NnamdiAlexanderAkamu
 * Date: 5/17/2020
 * Time: 7:18 PM
 */

namespace App\Traits;


use App\KpiInterval;

trait KpiIntervalTrait
{

	function createKpiInterval(){
		$obj = new KpiInterval;

		$obj->kpi_year_id = request()->get('kpi_year_id'); //get request
		$obj->interval_start = request()->get('interval_start');
		$obj->interval_stop = request()->get('interval_stop');
		$obj->name = request()->get('name');

		$obj->save();
		return [
			'message'=>'New Interval Added',
			'error'=>false
		];
	}

	function updateKpiInterval(){
		$obj = KpiInterval::find(request()->get('id'));

//		$obj->kpi_year_id = request()->get('kpi_year_id'); //get request
		$obj->interval_start = request()->get('interval_start');
		$obj->interval_stop = request()->get('interval_stop');
		$obj->name = request()->get('name');

		$obj->save();
		return [
			'message'=>'Interval Saved',
			'error'=>false
		];

	}

	function removeKpiInterval(){
		$obj = KpiInterval::find(request()->get('id'));

//		$obj->kpi_year_id = request()->get('kpi_year_id'); //get request
		$obj->delete();
		return [
			'message'=>'Interval Removed',
			'error'=>false
		];
	}

}
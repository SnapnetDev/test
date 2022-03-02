<?php
/**
 * Created by PhpStorm.
 * User: NnamdiAlexanderAkamu
 * Date: 5/17/2020
 * Time: 7:18 PM
 */

namespace App\Traits;


use App\KpiYear;

trait KpiYearTrait
{

	function createKpiYear(){

       $obj = new KpiYear;

       $obj->year = request()->get('year');

       $obj->save();

       return [
       	'message'=>'New Year added',
	    'error'=>false
       ];
	}

	function updateKpiYear(){

		$obj = KpiYear::find(request()->get('id'));

		$obj->year = request()->get('year');

		$obj->save();

		return [
			'message'=>'Year updated',
			'error'=>false
		];

	}

	function removeKpiYear(){

		$obj = KpiYear::find(request()->get('id'));
		$obj->delete();

		return [
			'message'=>'Year removed.',
			'error'=>false
		];

	}

}
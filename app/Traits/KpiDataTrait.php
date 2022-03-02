<?php
/**
 * Created by PhpStorm.
 * User: NnamdiAlexanderAkamu
 * Date: 5/17/2020
 * Time: 7:17 PM
 */

namespace App\Traits;


use App\KpiData;
use App\KpiSession;
use Excel;

trait KpiDataTrait
{

	function createPrivateKpiData($data = []){
		$obj = new KpiData;

		$obj->kpi_interval_id = (new KpiSession)->getCurrentIntervalId(); //request()->get('kpi_interval_id');
		$obj->type = request()->get('type');
		$obj->scope = 'private';//request()->get('scope');
		$obj->dep_id = request()->get('dep_id');
		$obj->requirement = (isset($data['requirement']))? $data['requirement'] : request()->get('requirement');
		$obj->percentage = (isset($data['percentage']))? $data['percentage'] : request()->get('percentage');

		$obj->save();
		return [
			'message'=>'New Individual Kpi-data added',
			'error'=>false,
			'data'=>$obj
		];
	}

	function createKpiData(){

		if (request()->file('excel_file')) {

			$path = request()->file('excel_file')->getRealPath();
			$data = Excel::load($path)->get();
			$ref = $this;

//			dd($data->toArray());

			if ($data->count() > 0) {

				foreach ($data->toArray() as $k => $v) {

					$obj = new KpiData;

					$obj->kpi_interval_id = request()->get('kpi_interval_id');
					$obj->type = request()->get('type');
					$obj->scope = request()->get('scope');
					$obj->dep_id = request()->get('dep_id');
					$obj->requirement = $v['requirement'];
					$obj->percentage = $v['percentage'];

					$obj->save();

				}

				return [
					'message'=>'Bulk upload successful',
					'error'=>false
				];

			}
		}else{
			$obj = new KpiData;

			$obj->kpi_interval_id = request()->get('kpi_interval_id');
			$obj->type = request()->get('type');
			$obj->scope = request()->get('scope');
			$obj->dep_id = request()->get('dep_id');
			$obj->requirement = request()->get('requirement');
			$obj->percentage = request()->get('percentage');

			$obj->save();
			return [
				'message'=>'New Kpi-data added',
				'error'=>false
			];
		}
	}

	function updateKpiData(){
		$obj = KpiData::find(request()->get('id'));

//		$obj->kpi_interval_id = request()->get('kpi_interval_id');
//		$obj->type = request()->get('type');
//		$obj->scope = request()->get('scope');
		$obj->requirement = request()->get('requirement');
		$obj->percentage = request()->get('percentage');

		$obj->save();
		return [
			'message'=>'Kpi-data updated',
			'error'=>false
		];

	}

	function removeKpiData(){
		$obj = KpiData::find(request()->get('id'));

		$obj->delete();
		return [
			'message'=>'Kpi-data removed',
			'error'=>false
		];
	}

}
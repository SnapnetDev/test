<?php

namespace App;

use App\Traits\FilterHelperTrait;
use App\Traits\JSONImport;
use App\Traits\KpiDataReportTrait;
use App\Traits\KpiDataTrait;
use App\Traits\KpiFilterTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class KpiData extends Model
{
    use KpiDataReportTrait;
	use KpiFilterTrait;
	use FilterHelperTrait;
	use KpiDataTrait;

	use JSONImport;

    //
	protected $table = 'kpi_data';
	protected $with = ['user_score'];

	function interval(){
		return $this->belongsTo(KpiInterval::class,'kpi_interval_id');
	}

	function user_score(){
		return $this->hasMany(KpiUserScore::class,'kpi_data_id');
	}


	public function loadFilters()
	{
		// TODO: Implement loadFilters() method.
		$ref = $this;

		$this->addFilter(['type'], function(Builder $builder,$filter) use ($ref){
//			dd('called.');
			return $ref->filterByType($builder, $filter);
		});

		$this->addFilter(['scope'], function(Builder $builder,$filter) use ($ref){
			return $ref->filterByScope($builder, $filter);
		});

		$this->addFilter(['kpi_interval_id'], function(Builder $builder,$filter) use ($ref){
            return $ref->filterByKpiIntervalId($builder, $filter);
		});

		$this->addFilter(['dep_id'], function(Builder $builder,$filter) use ($ref){
			return $ref->filterByDepId($builder, $filter);
		});

		$this->addFilter(['kpi_user_score_user_id'], function(Builder $builder,$filter) use ($ref){
//			dd($filter);
			return $ref->filterByUserIdFromKpiUserScoreRelation($builder, $filter);
		});


	}




	function importFromJSON($jsonResource){
		$dups = 0;

		$this->importJSONArray($jsonResource, function($k,$v) use (&$dups){

		   $skip = ['user_score'];

			$check = KpiData::where([
				'kpi_interval_id'=>$v['kpi_interval_id'],
				'dep_id'=>$v['dep_id'],
				'type'=>$v['type'],
				'scope'=>$v['scope'],
				'requirement'=>$v['requirement']
			])->exists();

			if (!$check){


				$new = new KpiData;

				foreach ($v as $field=>$value){

					if (!in_array($field, $skip)){
						$new->$field = $value;
					}

				}


				$new->save();

				return;

			}

			$dups = $dups + 1;


		});

		return redirect()->back()->with([
			'message'=>'Kpi data imported ( ' . $dups . ' duplicate(s) - Found. )'
		]);

	}





}

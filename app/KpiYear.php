<?php

namespace App;

use App\Traits\FilterHelperTrait;
use App\Traits\JSONImport;
use App\Traits\KpiFilterTrait;
use App\Traits\KpiYearTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;


class KpiYear extends Model
{
    //
	protected $table = 'kpi_year';

	use FilterHelperTrait;
	use KpiFilterTrait;
	use KpiYearTrait;

	use JSONImport;


	function intervals(){
		return $this->hasMany(KpiInterval::class,'kpi_year_id');
	}


	public function loadFilters()
	{
		$ref = $this;
//		$this->addFilter([], function(Builder $builder,$filter) use ($ref){
//
//		});

	}



	function importFromJSON($jsonResource){
		$dups = 0;

		$this->importJSONArray($jsonResource, function($k,$v) use (&$dups){

			$skip = [''];

			$check = KpiYear::where([
				'year'=>$v['year']
			])->exists();

			if (!$check){


				$new = new KpiYear;

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
			'message'=>'Kpi year imported ( ' . $dups . ' duplicate(s) - Found. )'
		]);

	}

}

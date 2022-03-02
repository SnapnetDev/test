<?php

namespace App;

use App\Traits\FilterHelperTrait;
use App\Traits\JSONImport;
use App\Traits\KpiFilterTrait;
use App\Traits\KpiUserScoreTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class KpiUserScore extends Model
{

	use KpiUserScoreTrait;
	use KpiFilterTrait;
	use FilterHelperTrait;

	use JSONImport;

    //
	protected  $table = 'kpi_user_score';

	function user(){
		return $this->belongsTo(User::class,'user_id');
	}

	function kpi_data(){
		return $this->belongsTo(KpiData::class,'kpi_data_id');
	}


	public function loadFilters()
    {
        // TODO: Implement loadFilters() method.
        $ref = $this;
        $this->addFilter(['user_id'], function (Builder $builder, $filter) use ($ref) {
            return $ref->filterByUserId($builder, $filter);
        });

        $this->addFilter(['kpi_data_id'], function (Builder $builder, $filter) use ($ref) {
            return $ref->filterByKpiDataId($builder, $filter);
        });


        $this->addFilter(['kpi_year_id', 'user_id'], function (Builder $builder, $filter) {

            return $builder->whereHas('kpi_data', function (Builder $builder) use ($filter) {

                return $builder->whereHas('interval', function (Builder $builder) use ($filter) {

                    return $builder->whereHas('year', function (Builder $builder) use ($filter) {

                        return $builder->where('id', $filter['kpi_year_id']);

                    });

                });

            })->whereHas('user', function (Builder $builder) use ($filter) {

                return $builder->where('id', $filter['user_id']);

            });


        });


        $this->addFilter(['kpi_year_id', 'user_id', 'kpi_interval_id'], function (Builder $builder, $filter) {

            return $builder->whereHas('kpi_data', function (Builder $builder) use ($filter) {

                return $builder->whereHas('interval', function (Builder $builder) use ($filter) {

                    return $builder->where('id', $filter['kpi_interval_id'])->whereHas('year', function (Builder $builder) use ($filter) {

                        return $builder->where('id', $filter['kpi_year_id']);

                    });

                });

            })->whereHas('user', function (Builder $builder) use ($filter) {

                return $builder->where('id', $filter['user_id']);

            });


        });

    }


	//createIndividualKpi


	function importFromJSON($jsonResource){
		$dups = 0;

		$this->importJSONArray($jsonResource, function($k,$v) use (&$dups){

			$skip = [''];

			$check = KpiUserScore::where([
				'user_id'=>$v['user_id'],
				'kpi_data_id'=>$v['kpi_data_id']
			])->exists();

			if (!$check){


				$new = new KpiUserScore;

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
			'message'=>'Kpi user-score imported ( ' . $dups . ' duplicate(s) - Found. )'
		]);

	}




}

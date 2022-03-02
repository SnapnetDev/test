<?php

namespace App;

use App\Traits\JSONImport;
use App\Traits\KpiSessionTrait;
use Illuminate\Database\Eloquent\Model;

class KpiSession extends Model
{
	use KpiSessionTrait;

	use JSONImport;
    //
	protected $table = 'kpi_session';

	function interval(){
		return $this->belongsTo(KpiInterval::class,'kpi_interval_id');
	}



	function importFromJSON($jsonResource){
		$dups = 0;

		$this->importJSONArray($jsonResource, function($k,$v) use (&$dups){

			$skip = [''];

			$check = KpiSession::where([
				'year'=>$v['year'],
				'kpi_interval_id'=>$v['kpi_interval_id']
			])->exists();

			if (!$check){


				$new = new KpiSession;

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
			'message'=>'Kpi session imported ( ' . $dups . ' duplicate(s) - Found. )'
		]);

	}


}

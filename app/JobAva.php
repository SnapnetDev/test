<?php

namespace App;

use App\Traits\JSONImport;
use Illuminate\Database\Eloquent\Model;

class JobAva extends Model
{

	use JSONImport;
	//
	protected $table = 'job_ava';


	function importFromJSON($jsonResource){
		$dups = 0;

		$this->importJSONArray($jsonResource, function($k,$v) use (&$dups){



			$check = JobAva::where('title',$v['title'])->exists();

			if (!$check){



				$new = new JobAva;

				foreach ($v as $field=>$value){

						$new->$field = $value;

				}


				$new->save();

				return;


			}

			$dups = $dups + 1;


		});

		return redirect()->back()->with([
			'message'=>'JobAvas imported ( ' . $dups . ' duplicate(s) - Found. )'
		]);

	}



}

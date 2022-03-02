<?php
/**
 * Created by PhpStorm.
 * User: NnamdiAlexanderAkamu
 * Date: 4/16/2020
 * Time: 11:15 AM
 */

namespace App\Traits\ModelTraits;


trait UserHelperTrait
{

	function getDepartmentName_(){
		if (!is_null($this->job)){
			if (!is_null($this->job->department)){
				return $this->job->department->name;
			}else{
				return 'N/A';
			}
		}else{
			return 'N/A';
		}
	}

}
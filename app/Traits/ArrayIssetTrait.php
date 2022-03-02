<?php
/**
 * Created by PhpStorm.
 * User: NnamdiAlexanderAkamu
 * Date: 5/17/2020
 * Time: 8:58 PM
 */

namespace App\Traits;


trait ArrayIssetTrait
{


	function arrayIsset($inputArray,$arrCheck,callable $callback){
		$track = 0;
		foreach ($arrCheck as $k=>$v){
			if (isset($inputArray[$v]) && !empty($inputArray[$v])){
				$track+=1;
			}
		}

		if ($track == sizeof($arrCheck)){
		  return $callback;
		}else{
			return null;
		}
	}


}
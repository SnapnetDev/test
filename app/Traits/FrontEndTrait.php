<?php
/**
 * Created by PhpStorm.
 * User: NnamdiAlexanderAkamu
 * Date: 5/17/2020
 * Time: 8:12 PM
 */

namespace App\Traits;


trait FrontEndTrait
{


	function execCommand($cmd){
		$cmd = strtolower($cmd);
		$r = explode('-', $cmd);
		$r = array_map(function ($v) {
			return ucfirst($v);
		}, $r);

		$r[0] = strtolower($r[0]);

		$method = implode('', $r);
		if (method_exists($this, $method)){
			return call_user_func_array([$this,$method], [request()->all()]);
		}else{
			return ['message'=>'Method does not exist!'];
		}
	}

}
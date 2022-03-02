<?php
/**
 * Created by PhpStorm.
 * User: NnamdiAlexanderAkamu
 * Date: 3/29/2020
 * Time: 12:28 AM
 */

namespace App\Traits;




use Illuminate\Http\Request;

trait CommandTrait
{

	function decodeCommand($cmd){


		$r = explode('-',$cmd);
		$r  = array_map(function($v){
			return ucfirst($v);
		},$r);
		$rr = [];
		foreach ($r as $k=>$v){
			if ($k == 0){
				$rr[] = strtolower($v);
			}else{
				$rr[] = $v;
			}
		}

		return implode('',$rr);

	}

	function runAjaxCommand($cmd, Request $request){
        $cmd = $this->decodeCommand($cmd);
		if (method_exists($this, $cmd)){
			$result = call_user_func_array([$this,$cmd], [$request]);
			return $result;
		}else{
			return [
				'message'=>'Can"t decode command',
				'error'=>true
			];
		}

	}

	function runViewCommand($cmd , Request $request){
		$cmd = $this->decodeCommand($cmd);
		if (method_exists($this, $cmd)){
			$result = call_user_func_array([$this,$cmd], [$request]);
			return $result;
		}else{
			return '404';
		}
	}



	function runActionCommand($cmd, Request $request){

		$cmd = $this->decodeCommand($cmd);
		$cmdAction = $cmd . 'Action';

		if (method_exists($this, $cmd)){
			$result = call_user_func_array([$this,$cmd], [$request]);
			if (method_exists($this, $cmdAction)){
			   return call_user_func_array([$this,$cmdAction], [$result,$request]);
			}else{
				return $result;
			}
		}else{
			return redirect()->back();
		}

	}


}
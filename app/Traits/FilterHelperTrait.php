<?php
/**
 * Created by PhpStorm.
 * User: NnamdiAlexanderAkamu
 * Date: 5/17/2020
 * Time: 7:35 PM
 */

namespace App\Traits;


use Illuminate\Database\Eloquent\Builder;

trait FilterHelperTrait
{

	use ArrayIssetTrait;

	private $filters = [];
	private $callbacks = [];


	function addFilter($keys,callable $callback){
		$this->filters[] = $keys;
		$this->callbacks[] = $callback;
	}

	public abstract function loadFilters();


	function fetch($filters=[]){

		$this->filters = [];
		$this->callbacks = [];

		$this->loadFilters();

		$r = request()->all();

		foreach ($filters as $k=>$v){
			$r[$k] = $v;
		}

		$query = $this->newQuery();

		foreach ($this->filters as $k=>$v){
			$cb = $this->arrayIsset($r, $v, $this->callbacks[$k]);
			if (!is_null($cb)){
				$query = $cb($query,$r);
			}
		}

//		foreach ($r as $k=>$v){
//			if (isset($this->filters[$k])){
//				$query = $this->filters[$k]($query,$r);
//			}
//		}

		return $query;

	}


}
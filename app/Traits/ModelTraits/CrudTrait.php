<?php
/**
 * Created by PhpStorm.
 * User: NnamdiAlexanderAkamu
 * Date: 4/15/2020
 * Time: 10:29 AM
 */

namespace App\Traits\ModelTraits;


use Illuminate\Database\Eloquent\Builder;

trait CrudTrait
{

	function entityCreate(callable $callback){
		$obj = new self;
		$obj = $callback($obj);
		$obj->save();
		return $obj;
	}



	function entityUpdate($id,callable $callback){
		$obj = $this->find($id);
		$obj = $callback($obj);
		$obj->save();
		return $obj;
	}


	function entityFetch(callable $callback): Builder{
		$obj = new self;
		$query = $callback($obj::query());
		return $query;
	}

	function entityDelete($id){
		$obj = $this->find($id);
		$obj->delete();
		return $obj;
	}


}
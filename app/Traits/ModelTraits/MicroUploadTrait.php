<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 03/04/2020
 * Time: 23:31
 */

namespace App\Traits\ModelTraits;


use Illuminate\Http\Request;

trait MicroUploadTrait
{

	function upload($fileKey,$path)
	{



		if (request()->file($fileKey)){
			$image = request()->file($fileKey)->store($path,['disk'=>'uploads']);
			$this->$fileKey = $image;
//            $callback($image);
		}

		return $this;

	}


}
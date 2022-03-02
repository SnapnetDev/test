<?php
/**
 * Created by PhpStorm.
 * User: NnamdiAlexanderAkamu
 * Date: 5/18/2020
 * Time: 8:21 PM
 */

namespace App\Traits;


use App\JobAva;

trait UserRoleTrait
{

	function isAdmin(){
		return true; // ($this->role == 3);
	}

	function isHr(){
		return ($this->isAdmin());
	}

	function isLineManager(){
		return true; // ($this->role == 2 || $this->id == 7);
	}

	function isDefaultStaff(){
		return true; // ($this->role == 1 || $this->role == 0);
	}

	function getDepartmentName(){
		//department
		$obj  = JobAva::find($this->job_id);
//		$r = $this->department;
//		dd($r);
		if (!is_null($obj)){
			return $obj->title;
		}else{
			return 'Not-Assigned';
		}
	}

}
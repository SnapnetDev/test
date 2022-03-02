<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class LoanType extends Model
{
    //
	protected $with = ['pace_salary_component','open_to_grade','loan_type_grade'];

	function pace_salary_component(){
		return $this->belongsTo(PaceSalaryComponent::class,'pace_salary_component_id');
	}

	function open_to_grade(){
		return $this->belongsTo(Grade::class,'open_to_grade_id');
	}

	function loan_type_grade(){
	    return $this->hasMany(LoanTypeGrade::class,'loan_type_id');
    }

    function getRosolvedTypesList(callable  $callbackFilter){
		$query = (new LoanType)->newQuery();
		$collection = $query->get();
		$resolved = [];
		foreach ($collection as $item){
			if ($callbackFilter($item,Auth::user())){
				$resolved[] = $item;
			}
		}
		return $resolved;
    }

    function specific_salary_component_type(){
		return $this->belongsTo(SpecificSalaryComponentType::class,'specific_salary_component_type_id');
    }





}

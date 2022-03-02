<?php

namespace App;

use App\Traits\ModelTraits\CrudTrait;
use App\Traits\ModelTraits\FilterTrait;
use App\Traits\ModelTraits\TrainingBudgetModelTrait;
use Illuminate\Database\Eloquent\Model;

class Tr_TrainingBudget extends Model
{
	use CrudTrait;
	use FilterTrait;
	use TrainingBudgetModelTrait;

    //
	protected $table = 'tr_training_budget';

	protected $with = ['department'];

	function grade(){
	  return $this->belongsTo(Grade::class,'grade_id');
	}

    function department(){
        return $this->belongsTo(Department::class,'dep_id');
    }


}

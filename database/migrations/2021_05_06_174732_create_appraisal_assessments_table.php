<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppraisalAssessmentsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('appraisal_assessments', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('appraisal_id');
				$table->integer('employee_score');
				$table->integer('manager_score');
				$table->integer('appraisal_sub_metric_id');
				$table->decimal('target_acheived', 10, 2)->nullable();
				$table->integer('manager_id');
				$table->text('manager_comment');
				$table->integer('employee_comment');
				$table->integer('created_by');
				$table->integer('updated_by');
				$table->integer('company_id');
 				$table->timestamps();
			});
		}

		/**
 * Reverse the migrations.
 *
 * @return void
 */
		public function down()
		{
    //
		}
};
		
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppraisalsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('appraisals', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('measurement_period_id');
				$table->integer('department_id');
				$table->integer('employee_id');
				$table->integer('manager_id');
				$table->integer('employee_approved');
				$table->integer('manager_approved');
				$table->string('employee_approved_date');
				$table->string('manager_approved_date');
				$table->integer('score')->default('0');
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
		
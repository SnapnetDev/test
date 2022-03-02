<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeparationPoliciesTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('separation_policies', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('employee_fills_form')->default('0');
				$table->integer('use_approval_process')->default('0');
				$table->integer('prorate_salary')->default('0');
				$table->integer('notify_staff_on_exit')->default('0');
				$table->integer('workflow_id')->default('0');
				$table->integer('company_id')->default('0');
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
		
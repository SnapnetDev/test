<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistrationProgressesTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('registration_progresses', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('has_users')->default('0');
				$table->integer('has_grades')->default('0');
				$table->integer('has_leave_policy')->default('0');
				$table->integer('has_payroll_policy')->default('0');
				$table->integer('has_branches')->default('0');
				$table->integer('has_departments')->default('0');
				$table->integer('has_job_roles')->default('0');
				$table->integer('company_id')->default('0');
				$table->integer('completed')->default('0');
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
		
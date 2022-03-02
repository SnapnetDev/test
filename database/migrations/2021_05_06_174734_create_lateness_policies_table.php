<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLatenessPoliciesTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('lateness_policies', function(Blueprint $table){
			$table->increments('id');	
				$table->string('policy_name')->nullable();
				$table->integer('late_minute')->nullable();
				$table->integer('deduction_type')->nullable();
				$table->string('deduction')->nullable();
				$table->integer('company_id')->default('0');
				$table->integer('status')->nullable()->default('0');
				$table->string('specific_salary_component_type_id')->default('1');
				$table->string('payroll')->default('all');
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
		
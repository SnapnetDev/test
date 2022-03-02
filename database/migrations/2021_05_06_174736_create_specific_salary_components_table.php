<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecificSalaryComponentsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('specific_salary_components', function(Blueprint $table){
			$table->increments('id');	
				$table->string('name');
				$table->string('type');
				$table->integer('specific_salary_component_type_id');
				$table->string('amount');
				$table->string('gl_code')->nullable();
				$table->string('project_code')->nullable();
				$table->string('comment')->nullable();
				$table->integer('duration')->nullable();
				$table->integer('grants')->nullable();
				$table->string('status')->nullable();
				$table->integer('taxable')->default('0');
				$table->integer('completed')->default('0');
				$table->string('starts')->nullable();
				$table->string('ends')->nullable();
				$table->integer('emp_id');
				$table->integer('company_id')->default('0');
				$table->integer('one_off')->default('0');
				$table->integer('is_relief')->default('0');
				$table->integer('taxable_type')->default('1');
				$table->integer('loan_id')->nullable();
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
		
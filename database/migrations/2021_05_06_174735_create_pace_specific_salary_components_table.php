<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaceSpecificSalaryComponentsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('pace_specific_salary_components', function(Blueprint $table){
			$table->increments('id');	
				$table->string('name');
				$table->string('type');
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
		
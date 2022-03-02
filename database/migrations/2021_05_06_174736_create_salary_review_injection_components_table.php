<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalaryReviewInjectionComponentsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('salary_review_injection_components', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('from_component_type');
				$table->integer('injection_type');
				$table->integer('company_id');
				$table->integer('status');
				$table->string('from_salary_component_constant')->nullable();
				$table->string('from_payroll_component_constant')->nullable();
				$table->string('to_salary_component_constant')->nullable();
				$table->string('to_payroll_component_constant')->nullable();
				$table->integer('to_component_type')->nullable();
				$table->decimal('percentage', 10, 2)->nullable();
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
		
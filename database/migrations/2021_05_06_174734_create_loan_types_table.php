<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanTypesTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('loan_types', function(Blueprint $table){
			$table->increments('id');	
				$table->string('name')->nullable();
				$table->integer('required_duration_in_months')->nullable();
				$table->string('duration_comparator')->nullable();
				$table->integer('requires_confirmation')->nullable();
				$table->integer('multiplier_index')->nullable();
				$table->integer('pace_salary_component_id')->nullable();
				$table->integer('repayment_period')->nullable();
				$table->integer('interest_rate')->nullable();
				$table->integer('open_to_grade_id')->nullable();
				$table->integer('specific_salary_component_type_id')->nullable();
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
		
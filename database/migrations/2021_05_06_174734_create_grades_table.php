<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGradesTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('grades', function(Blueprint $table){
			$table->increments('id');	
				$table->string('level');
				$table->integer('grade_category_id')->default('0');
				$table->integer('bsc_grade_performance_category_id')->default('0');
				$table->decimal('basic_pay', 10, 2)->nullable();
				$table->integer('leave_length')->nullable();
				$table->integer('payroll_policy_id')->nullable();
				$table->integer('company_id')->nullable();
				$table->text('description')->nullable();
				$table->integer('pos')->nullable();
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
		
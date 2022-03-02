<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBscEvaluationsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('bsc_evaluations', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('user_id');
				$table->integer('bsc_measurement_period_id');
				$table->integer('department_id');
				$table->integer('performance_category_id');
				$table->integer('evaluator_id')->default('0');
				$table->integer('manager_approved')->default('0');
				$table->integer('employee_approved')->default('0');
				$table->text('comment')->nullable();
				$table->decimal('score', 10, 2)->nullable();
				$table->decimal('behavioral_score', 10, 2)->nullable()->default('0.00');
				$table->string('date_employee_approved')->nullable();
				$table->string('date_manager_approved')->nullable();
				$table->integer('kpi_accepted')->nullable()->default('0');
				$table->string('date_kpi_accepted')->nullable();
				$table->integer('submitted_for_review')->nullable()->default('0');
				$table->string('date_submitted_for_review')->nullable();
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
		
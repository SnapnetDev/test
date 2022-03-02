<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppraisalMetricsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('appraisal_metrics', function(Blueprint $table){
			$table->increments('id');	
				$table->string('name');
				$table->text('description')->nullable();
				$table->integer('fillable')->default('0');
				$table->integer('manager_appraises')->default('1');
				$table->integer('employee_appraises')->default('0');
				$table->decimal('percentile', 10, 2)->default('0.00');
				$table->integer('status')->default('1');
				$table->integer('created_by');
				$table->integer('updated_by');
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
		
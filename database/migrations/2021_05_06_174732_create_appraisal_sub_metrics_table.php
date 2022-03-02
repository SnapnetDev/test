<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppraisalSubMetricsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('appraisal_sub_metrics', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('metric_id');
				$table->string('name');
				$table->text('description')->nullable();
				$table->integer('editable')->default('0');
				$table->integer('has_target')->nullable();
				$table->integer('employee_id')->nullable();
				$table->integer('weight')->default('0');
				$table->decimal('target', 10, 2)->nullable();
				$table->integer('created_by');
				$table->integer('updated_by');
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
		
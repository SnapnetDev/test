<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBscSubMetricsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('bsc_sub_metrics', function(Blueprint $table){
			$table->increments('id');	
				$table->string('business_goal');
				$table->text('measure')->nullable();
				$table->integer('morphable_id');
				$table->string('morphable_type');
				$table->integer('bsc_metric_id');
				$table->integer('bsc_measurement_period_id');
				$table->decimal('low_target', 10, 2);
				$table->decimal('mid_target', 10, 2);
				$table->decimal('upper_target', 10, 2);
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
		
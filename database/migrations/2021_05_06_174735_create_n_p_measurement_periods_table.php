<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNPMeasurementPeriodsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('n_p_measurement_periods', function(Blueprint $table){
			$table->increments('id');	
				$table->string('name')->nullable();
				$table->string('from');
				$table->string('to');
				$table->string('start')->nullable();
				$table->string('end')->nullable();
				$table->string('status')->default('pending');
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
		
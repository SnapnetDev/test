<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateE360MeasurementPeriodsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('e360_measurement_periods', function(Blueprint $table){
			$table->increments('id');	
				$table->string('from');
				$table->string('to');
				$table->integer('company_id')->default('0');
				$table->integer('user_id')->default('0');
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
		
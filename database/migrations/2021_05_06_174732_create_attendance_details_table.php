<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceDetailsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('attendance_details', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('attendance_id');
				$table->string('clock_in')->nullable();
				$table->string('clock_out')->nullable();
				$table->string('date')->nullable();
				$table->string('clock_in_lat')->nullable();
				$table->string('clock_in_long')->nullable();
				$table->string('clock_out_lat')->nullable();
				$table->string('clock_out_long')->nullable();
				$table->string('clock_in_imei')->nullable();
				$table->string('clock_out_imei')->nullable();
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
		
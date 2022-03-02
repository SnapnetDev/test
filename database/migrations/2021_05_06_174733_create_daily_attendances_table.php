<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyAttendancesTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('daily_attendances', function(Blueprint $table){
			$table->increments('id');	
				$table->string('emp_id');
				$table->string('emp_num')->nullable();
				$table->string('date')->nullable();
				$table->string('clock_in')->nullable()->default('current_timestamp()');
				$table->string('clock_out')->nullable();
				$table->string('flag')->nullable();
				$table->string('daily_deduction_percentage')->nullable();
				$table->string('late_time')->nullable();
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
		
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceReportsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('attendance_reports', function(Blueprint $table){
			$table->increments('id');	
				$table->string('user_id');
				$table->string('attendance_id')->nullable();
				$table->string('date')->nullable();
				$table->string('first_clockin')->nullable()->default('0');
				$table->string('last_clockout')->nullable();
				$table->string('status')->nullable();
				$table->string('expected_hours')->default('0');
				$table->string('hours_worked')->nullable();
				$table->string('overtime')->nullable();
				$table->string('shift_start')->nullable();
				$table->string('shift_end')->nullable();
				$table->string('shift_name')->nullable();
				$table->string('amount')->default('0');
				$table->string('approved_overtime')->default('0');
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
		
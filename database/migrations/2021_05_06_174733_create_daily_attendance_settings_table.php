<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyAttendanceSettingsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('daily_attendance_settings', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('company_id');
				$table->integer('grade_id');
				$table->integer('late_minute');
				$table->decimal('late_percentage', 10, 2);
				$table->string('status');
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
		
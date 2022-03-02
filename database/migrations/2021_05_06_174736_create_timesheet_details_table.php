<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimesheetDetailsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('timesheet_details', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('timesheet_id');
				$table->integer('user_id');
				$table->text('tdays');
				$table->integer('total_hours')->default('0');
				$table->integer('weekday_hours')->default('0');
				$table->integer('basic_work_hours')->default('0');
				$table->integer('overtime_weekday_hours')->default('0');
				$table->integer('overtime_holiday_hours')->default('0');
				$table->integer('overtime_saturday_hours')->default('0');
				$table->integer('overtime_sunday_hours')->default('0');
				$table->integer('expected_work_hours')->default('0');
				$table->integer('expected_work_days')->nullable()->default('0');
				$table->string('average_first_clock_in')->nullable();
				$table->string('average_last_clock_out')->nullable();
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
		
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancePayrollsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('attendance_payrolls', function(Blueprint $table){
			$table->increments('id');	
				$table->string('day');
				$table->string('month');
				$table->string('year');
				$table->string('start')->nullable();
				$table->string('end')->nullable();
				$table->string('created_by');
				$table->string('attendance_report_id');
				$table->string('status')->default('open');
				$table->string('pay_status')->nullable();
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
		
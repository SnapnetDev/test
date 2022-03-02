<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancePayrollDetailsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('attendance_payroll_details', function(Blueprint $table){
			$table->increments('id');	
				$table->string('user_id');
				$table->string('role_id');
				$table->string('company_id');
				$table->string('attendance_payroll_id');
				$table->string('days_worked')->default('0');
				$table->string('present')->default('0');
				$table->string('early')->default('0');
				$table->string('absent')->default('0');
				$table->string('late')->default('0');
				$table->string('off')->default('0');
				$table->string('amount_expected')->default('0');
				$table->string('deduction')->default('0');
				$table->string('amount_received')->default('0');
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
		
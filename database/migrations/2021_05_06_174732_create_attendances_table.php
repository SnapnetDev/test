<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('attendances', function(Blueprint $table){
			$table->increments('id');	
				$table->string('emp_num');
				$table->string('date')->nullable();
				$table->integer('shift_id');
				$table->integer('company_id')->nullable();
				$table->string('user_daily_shift_id')->default('0');
				$table->string('workflow_status')->nullable();
				$table->string('workflow_id')->default('1');
				$table->text('workflow_details')->nullable();
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
		
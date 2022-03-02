<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDailyShiftsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('user_daily_shifts', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('user_id')->default('0');
				$table->integer('shift_id')->nullable()->default('0');
				$table->string('sdate')->nullable();
				$table->string('starts')->nullable();
				$table->string('ends')->nullable();
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
		
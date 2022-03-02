<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTmsaSchedulesTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('tmsa_schedules', function(Blueprint $table){
			$table->increments('id');	
				$table->string('for')->nullable();
				$table->integer('user_id');
				$table->decimal('day_rate_onshore', 10, 2)->nullable();
				$table->decimal('day_rate_offshore', 10, 2)->nullable();
				$table->decimal('paid_off_time_rate', 10, 2)->nullable();
				$table->decimal('days_worked_offshore', 10, 2)->nullable();
				$table->decimal('days_worked_onshore', 10, 2)->nullable();
				$table->decimal('paid_off_day', 10, 2)->nullable();
				$table->decimal('brt_days', 10, 2)->nullable();
				$table->decimal('living_allowance', 10, 2)->nullable();
				$table->decimal('transport_allowance', 10, 2)->nullable();
				$table->decimal('extra_addition', 10, 2)->nullable();
				$table->decimal('extra_deduction', 10, 2)->nullable();
				$table->decimal('days_out_of_station', 10, 2)->nullable();
				$table->integer('company_id')->default('0');
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
		
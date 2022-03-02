<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptimizedDailySalariesTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('optimized_daily_salaries', function(Blueprint $table){
			$table->increments('id');	
				$table->decimal('daily_gross', 10, 2);
				$table->decimal('days', 10, 2);
				$table->decimal('monthly_gross', 10, 2);
				$table->decimal('annual_gross', 10, 2);
				$table->decimal('daily_net', 10, 2);
				$table->decimal('monthly_net', 10, 2);
				$table->decimal('annual_net', 10, 2);
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
		
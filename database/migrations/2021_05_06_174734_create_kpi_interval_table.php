<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKpiIntervalTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('kpi_interval', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('kpi_year_id')->nullable();
				$table->string('interval_start')->nullable();
				$table->string('interval_stop')->nullable();
				$table->string('name')->nullable();
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
		
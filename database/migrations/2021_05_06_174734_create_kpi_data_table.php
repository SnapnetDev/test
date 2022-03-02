<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKpiDataTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('kpi_data', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('kpi_interval_id')->nullable();
				$table->integer('dep_id')->nullable();
				$table->string('type')->nullable();
				$table->string('scope')->nullable();
				$table->text('requirement')->nullable();
				$table->string('percentage')->nullable();
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
		
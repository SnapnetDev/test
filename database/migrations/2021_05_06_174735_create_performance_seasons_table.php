<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerformanceSeasonsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('performance_seasons', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('reviewFreq')->nullable();
				$table->string('reminderMessage')->nullable();
				$table->integer('reviewStart')->nullable();
				$table->integer('company_id')->nullable();
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
		